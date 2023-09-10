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
use Modules\Crawl\Http\Controllers\CrawlTestController;
//Route::prefix('crawl')->group(function() {
//    Route::get('/', 'CrawlController@index');
//});
Route::group(['middleware' => ['web', 'auth']], function () {
    // Payment Notify
    Route::group([
        'prefix' => 'crawl-management',
        'as' => 'crawl-management.',
    ], function () {
        // crawl-management/crawl
        // crawl-management.index
        Route::get('/crawl', [CrawlTestController::class, 'index'])->name('index');
    });
});
