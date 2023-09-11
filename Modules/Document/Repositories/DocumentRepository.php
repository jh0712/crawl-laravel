<?php

namespace Modules\Document\Repositories;

use App\Repositories\Repository;
use App\Traits\HasCrud;
use App\Traits\HasSlug;
use Illuminate\Support\Facades\Storage;
use Modules\Document\Contracts\DocumentContract;
use Modules\Document\Entities\Document;
use Modules\Document\Entities\DocumentType;

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
    public function __construct(
        Document $model,
        DocumentType $documentType
    ) {
        $this->model        = $model;
        $this->documentType = $documentType;
        parent::__construct($model);
    }

    public function getDocumentTypePath($type)
    {
        $document_type = $this->documentType->where('name', $type)->first();
        if ($document_type) {
            return   $document_type->document_path;
        }
    }

    public function generateRandomFilename($document_path, $filename = null)
    {
        // If no filename is specified, we generate a random filename, else we delete the existing files
        if ($filename == null) {
            do {
                $filename = bin2hex(openssl_random_pseudo_bytes(32)) . '.jpeg';
            } while (Storage::exists("{$document_path}{$filename}"));
        } else {
            if (file_exists($document_path . "{$filename}")) {
                Storage::delete($document_path . "{$filename}");
            }
        }

        return $filename;
    }

    public function createData($array)
    {
        return $this->add($array);
    }

    public function hasOrCreate($folder_path)
    {
        $folderPath = storage_path($folder_path); // 更改為您希望創建或檢查的資料夾路徑
        if (!file_exists($folderPath)) {
            // 如果資料夾不存在，則創建它
            mkdir($folderPath, 0777, true);
        }
        return true;
    }
}
