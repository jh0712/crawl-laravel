<?php

namespace Modules\Crawl\Repositories;

use App\Models\User;
use App\Repositories\Repository;
use App\Traits\HasCrud;
use App\Traits\HasSlug;
use Goutte\Client;
use Modules\Crawl\Contracts\CrawlContract;
use Modules\Crawl\Entities\CrawledResult;
use Modules\Document\Contracts\DocumentContract;
use Modules\Document\Entities\Document;
use Modules\Document\Entities\DocumentType;
use Spatie\Browsershot\Browsershot;

class CrawlRepository extends Repository implements CrawlContract
{
    use HasCrud, HasSlug;

    /**
     * The model.
     *
     * @var $model
     */
    protected $model;

    /**
     * Class constructor.
     *
     * @param CrawledResult  $model
     */
    public function __construct(
        CrawledResult $model,
        User $user,
        DocumentContract $documentRepo,
        DocumentType $documentType
    ) {
        $this->model        = $model;
        $this->user         = $user;
        $this->documentRepo = $documentRepo;
        $this->documentType = $documentType;
        parent::__construct($model);
    }

    public function getData($user_id = null)
    {
        $query = $this->model->select('')->where('');
        return $query;
    }

    public function crawlByUrl($url_path, $user_id = null)
    {
        if (!$user_id) {
            $user    = auth()->user();
            $user_id = $user->id;
        } else {
            $user = $this->user->find($user_id);
        }
        // get data
        $crawl_result = $this->getCreateCrawlData($url_path);
        $data         = [
            'user_id'     => $user_id,
            'title'       => $crawl_result['title'],
            'url'         => $url_path,
            'description' => $crawl_result['description'],
            'body'        => $crawl_result['body'],
        ];
        // create data
        $crawled_data = $this->model->create($data);

        // document save screenshot
        $screenShotResult = $this->crawlScreenShot($url_path);
        if (!$screenShotResult['status']) {
            return ['status' => false, 'message' => 'screen shot fail'];
        }
        // screenshot success
        // create documents
        $documentData = [
            'filename'          => $screenShotResult['file_name'],
            'application_type'  => CrawledResult::class,
            'original_filename' => $crawl_result['title'],
            'application_id'    => $crawled_data->id,
            'document_type_id'  => $this->documentType->where('name', DocumentType::CREWLED_RESULT)->first()->id,
            'user_id'           => $user_id,
            'created_by'        => $user_id
        ];
        $this->documentRepo->createData($documentData);

        return ['status' => true, 'message' => 'crawl success'];
    }

    public function getCreateCrawlData($url)
    {
        $client  = new Client();
        $crawler = $client->request('GET', $url);

        $title       = $crawler->filter('title');
        $title_text  = $title->count()?$title->text():null;
        $description = $crawler->filter('meta[name="description"]');

        $description_text = $description->count()?$description->attr('content'):null;
        $body             = $crawler->filter('body');
        $body_text        = $body->count()?$body->text():null;
        return [
            'title'       => $title_text,
            'description' => $description_text,
            'body'        => $body_text,
        ];
    }

    public function crawlScreenShot($url)
    {
        $document_path = $this->documentRepo->getDocumentTypePath(DocumentType::CREWLED_RESULT);
        $file_name     = $this->documentRepo->generateRandomFilename($document_path);
        $screen_path   = storage_path($document_path . $file_name);
        $this->documentRepo->hasOrCreate($document_path);
        Browsershot::url($url)
            ->setScreenshotType('jpeg', 100)
            ->fullPage()
            ->noSandbox()
            ->save($screen_path);
        if (file_exists($screen_path)) {
            return ['status' => true, 'file_name' => $file_name];
        }
        return ['status' => false, 'file_name' => $file_name];
    }
}
