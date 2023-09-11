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
use Modules\Document\Http\Controllers\DocumentController;
Route::group(['middleware' => ['web', 'auth']], function () {
    Route::group([
        'prefix' => 'document-management',
        'as'     => 'document-management.',
    ], function () {
        Route::group([
            'prefix' => '{document_id}',
            'as'     => 'document_id.',
        ], function () {
            // document-management/document_id/
            // document-management.document_id.index
            Route::get('/', [DocumentController::class, 'index'])->name('index');
        });
    });
});
