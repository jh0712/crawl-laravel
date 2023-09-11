<?php

namespace Modules\Crawl\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Modules\Crawl\Contracts\CrawlContract;
use mysql_xdevapi\Exception;

class CrawlController extends Controller
{
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
        return Inertia::render('Crawl/Index', []);
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
            if(!$result['status']){
                DB::rollback();
                return redirect()->back()->with('error_message', $result['message']);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::info($e);
            return redirect()->back()->with('error_message', 'failed crawl please try again');
        }
        return redirect()->back()->with('success_message', 'successfully created');
//        return Redirect::route('crawl-management.create');
    }
    public function success($id){
        $crawledResult = $this->crawlRepo->find($id,['documents']);
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
        return Inertia::render('Crawl/Edit', []);
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
    }
}
