<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use Modules\Crawl\Http\Controllers\CrawlController;

Route::group(['middleware' => ['web', 'auth']], function () {
    Route::group([
        'prefix' => 'crawl-management',
        'as'     => 'crawl-management.',
    ], function () {
        // crawl-management/crawl
        // crawl-management.index
        Route::get('/crawl', [CrawlController::class, 'index'])->name('index');
        // crawl-management/create
        // crawl-management.create
        Route::get('/create', [CrawlController::class, 'create'])->name('create');
        // crawl-management/store
        // crawl-management.store
        Route::post('/store', [CrawlController::class, 'store'])->name('store');
        Route::group([
            'prefix' => '{crawled_result_id}',
            'as'     => 'crawled_result_id.',
        ], function () {
            // crawl-management/crawled_result_id/success
            // crawl-management.crawled_result_id.success
            Route::get('/success', [CrawlController::class, 'success'])->name('success');
            // crawl-management/crawled_result_id
            // crawl-management.crawled_result_id.edit
            Route::get('/', [CrawlController::class, 'edit'])->name('edit');
            // crawl-management/crawled_result_id/update
            // crawl-management.crawled_result_id.update
            Route::put('/update', [CrawlController::class, 'update'])->name('update');
        });
    });
});
