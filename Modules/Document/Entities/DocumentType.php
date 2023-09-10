<?php

namespace Modules\Document\Entities;

use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    const CREWLED_RESULT = 'crawled_result';
    protected $fillable  = [
        'name',
        'document_path',
        'is_active'
    ];
}
