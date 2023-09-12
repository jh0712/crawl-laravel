<?php

namespace Modules\Crawl\Http\Controllers;

use App\Traits\FilterDropdown;
use App\Traits\UnionCombineQuery;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Modules\Crawl\Contracts\CrawlContract;

class CrawlController extends Controller
{
    use UnionCombineQuery;
    use FilterDropdown;

    public function __construct(
        CrawlContract $crawlRepo
    ) {
        $this->crawlRepo = $crawlRepo;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        // datatable page
        return Inertia::render('Crawl/Index', [
            'datatable_url' => route('crawl-management.getDatatable')
        ]);
    }

    public function getDatatable(Request $request)
    {
        // user deposit index for admin
        $query      = $this->crawlRepo->getAllData(auth()->user()->id);
        $allResults = $this->datatableAdvanceFilterSearch(
            $request,
            $this->getAdvanceCombineQueryData($query)
        );
        $orderByData = $this->getQueryOrderBy($allResults);

        $dataTable = datatables()->of($allResults)
        ->editColumn('action', function ($result) {
            return "<a href='" . route('crawl-management.crawled_result_id.edit', $result->id) . "' class='button'>
                        Edit
                    </a>";
        });

        $dataTable->with($orderByData);

        return $dataTable->rawColumns(['action'])->make(true);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        // create page
        return Inertia::render('Crawl/Create', [
            'error_message'   => session('error_message') ?? null,
            'success_message' => session('success_message') ?? null
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        // store action
        $request->validate([
            'url_path' => ['required', 'active_url'],
        ]);
        DB::beginTransaction();
        try {
            $result = $this->crawlRepo->crawlByUrl($request->url_path);
            if (!$result['status']) {
                DB::rollback();
                return redirect()->back()->with('error_message', $result['message']);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::info($e);
            return redirect()->back()->with('error_message', 'failed crawl please try again');
        }
        //return redirect()->back()->with('success_message', 'successfully created');
        return redirect()->route('crawl-management.crawled_result_id.success', $result['crawled_data']->id);
    }

    public function success($id)
    {
        $crawledResult = $this->crawlRepo->getModel()
            ->where('id', $id)->where('user_id', auth()->user()->id)->with('documents')->first();
        if (!$crawledResult) {
            return redirect()->back()->with('error_message', 'no record found');
        }
        return Inertia::render('Crawl/Success', [
            'crawledResult' => $crawledResult // 傳遞CrawledResult模型的資料給Inertia頁面
        ]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        // show data information can not edit
        return view('crawl::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        // show data information can edit
        $crawledResult = $this->crawlRepo->getModel()
            ->where('id', $id)->where('user_id', auth()->user()->id)->first();
        if (!$crawledResult) {
            return redirect()->back()->with('error_message', 'no record found');
        }
        return Inertia::render('Crawl/Edit', [
            'crawledResult'   => $crawledResult,
            'error_message'   => session('error_message') ?? null,
            'success_message' => session('success_message') ?? null
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        // update action
        $crawledResult = $this->crawlRepo->getModel()
            ->where('id', $id)->where('user_id', auth()->user()->id)->first();
        if (!$crawledResult) {
            return redirect()->back()->with('error_message', 'no record found');
        }
        DB::beginTransaction();
        try {
            $result = $this->crawlRepo->updateData($id, $request->only(['title', 'description', 'body']));
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::info($e);
            return redirect()->back()->with('error_message', 'failed updated please try again');
        }
        return redirect()->back()->with('success_message', 'successfully updated');
    }
}
