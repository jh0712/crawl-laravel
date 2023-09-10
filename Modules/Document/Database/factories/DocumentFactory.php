<?php

namespace Modules\Document\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Crawl\Entities\CrawledResult;
use Modules\Document\Entities\Document;

class DocumentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Document::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $crawled_result = CrawledResult::factory()->create();
        $imageURL       = fake()->image();
        // 從 URL 中取得檔名部分
        $filename = basename($imageURL);
        return [
            'filename'          => $filename,
            'original_filename' => $filename,
            'user_id'           => 1,
            'application_id'    => $crawled_result->id,
            'application_type'  => CrawledResult::class,
            'document_type_id'  => 1,
            'created_by'        => 1,
        ];
    }
}
