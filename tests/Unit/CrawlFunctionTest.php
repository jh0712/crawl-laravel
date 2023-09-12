<?php

namespace Tests\Unit;

use App\Models\User;
use Modules\Crawl\Contracts\CrawlContract;
use Modules\Crawl\Entities\CrawledResult;
use Modules\Document\Entities\Document;
use Tests\TestCase;

class CrawlFunctionTest extends TestCase
{
    /**
     * Crawl function test
     * php artisan test  --filter=CrawlFunctionTest
     */

    /**
     *@test crawl url content
     */
    public function crawl_url_content(): void
    {
        $url       = 'https://google.com';
        $crawlRepo = app(CrawlContract::class);
        $result    = $crawlRepo->getCreateCrawlData($url);

        $this->assertEquals(true, $result['status']);
    }

    /**
     *@test crawl screenshot
     */
    public function crawl_screenshot(): void
    {
        $url       = 'https://google.com';
        $crawlRepo = app(CrawlContract::class);
        $result    = $crawlRepo->crawlScreenShot($url);

        $this->assertEquals(true, $result['status']);
    }

    /**
     *@test edit crawled result data
     */
    public function edit_crawled_result_data(): void
    {
        $crawl_data = CrawledResult::factory()->create();
        $crawlRepo  = app(CrawlContract::class);
        $result     = $crawlRepo->updateData($crawl_data->id, [
            'title'       => 'test data',
            'description' => 'test description'
        ]);
        // check result
        $this->assertInstanceOf(CrawledResult::class, $result);
        // check data after update
        $new_data = CrawledResult::find($crawl_data->id);
        $this->assertEquals('test data', $new_data->title);
        $this->assertEquals('test description', $new_data->description);
    }

    /**
     *@test test the main function
     */
    public function test_the_main_function(): void
    {
        $this->login();
        $url       = 'https://google.com';
        $crawlRepo = app(CrawlContract::class);
        $result    = $crawlRepo->crawlByUrl($url);
        $this->assertEquals(true, $result['status']);
        $result_crawl_data = $result['crawled_data'];
        $this->assertInstanceOf(Document::class, $result_crawl_data->documents);
    }

    public function login()
    {
        // use login
        $user = User::find(1);
        $this->actingAs($user);
    }
}
