<?php

namespace Modules\Document\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Document\Database\factories\DocumentFactory;
use Modules\Document\Entities\DocumentType;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'filename',
        'original_filename',
        'application_id',
        'application_type',
        'document_type_id',
        'created_by',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function document_type()
    {
        return $this->belongsTo(DocumentType::class);
    }

    protected static function newFactory()
    {
        return DocumentFactory::new();
    }
}
