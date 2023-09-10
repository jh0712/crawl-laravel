<?php

namespace Modules\Crawl\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Crawl\Entities\CrawledResult;

class CrawledResultFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CrawledResult::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title'       => fake()->sentence,
            'url'         => fake()->url,
            'description' => fake()->paragraph,
            'body'        => fake()->text,
        ];
    }
}
