<?php

namespace Modules\Document\Repositories;

use App\Repositories\Repository;
use App\Traits\HasCrud;
use App\Traits\HasSlug;
use Modules\Document\Entities\Document;
use Modules\Document\Contracts\DocumentContract;

class DocumentRepository extends Repository implements DocumentContract
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
     * @param Document  $model
     */
    public function __construct(Document $model)
    {
        $this->model = $model;
        parent::__construct($model);
    }
}
