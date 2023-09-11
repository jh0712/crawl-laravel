<?php

namespace Modules\Document\Contracts;

use App\Contracts\CrudContract;
use App\Contracts\SlugContract;

interface DocumentContract extends CrudContract, SlugContract
{
//    public function upload($model, $data, $status);

    public function getDocumentTypePath($type);

//    public function update($document_id, $data, $status);

}
