<?php

namespace Modules\Crawl\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Crawl\Database\factories\CrawledResultFactory;
use Modules\Document\Entities\Document;

class CrawledResult extends Model
{
    use HasFactory;
    protected $table = 'crawled_results';

    protected $fillable = [
        'user_id',
        'title',
        'url',
        'description',
        'body',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function documents()
    {
        return $this->morphOne(Document::class, 'application');
    }

    protected static function newFactory()
    {
        return CrawledResultFactory::new();
    }
}
