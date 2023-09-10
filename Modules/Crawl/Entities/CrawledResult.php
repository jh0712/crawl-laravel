<?php

namespace Modules\Crawl\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Crawl\Database\factories\CrawledResultFactory;

class CrawledResult extends Model
{
    use HasFactory;
    protected $table = 'crawled_results';

    protected $fillable = [
        'title',
        'url',
        'description',
        'body',
    ];

    protected static function newFactory()
    {
        return CrawledResultFactory::new();
    }
}
