<?php

namespace Modules\Crawl\Repositories;

use App\Models\User;
use App\Repositories\Repository;
use App\Traits\HasCrud;
use App\Traits\HasSlug;
use Modules\Crawl\Contracts\CrawlContract;
use Modules\Crawl\Entities\CrawledResult;

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
        User $user
    ) {
        $this->model = $model;
        $this->user  = $user;
        parent::__construct($model);
    }

    public function getData($user_id = null)
    {
        $query = $this->model->select('')->where('');
        return $query;
    }

    public function crawlData($array, $user_id = null)
    {
        if (!$user_id) {
            $user = auth()->user();
            $user_id = auth()->user()->id;
        }else{
            $user = $this->user->find($user_id);
        }


        $data = [
            'user_id'     => $user_id,
            'title'       => $array['title'],
            'url'         => $array['url'],
            'description' => $array['description'],
            'body'        => $array['body'],
        ];
        return $this->model->create($data);
    }

}
