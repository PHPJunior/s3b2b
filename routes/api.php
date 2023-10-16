<?php

use App\Http\Controllers\BucketController;
use App\Http\Controllers\FileManagerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group([
    'prefix' => 'buckets'
], function () {
    Route::apiResource('', BucketController::class, [
        'names' => [
            'index' => 'buckets.index',
            'store' => 'buckets.store',
            'update' => 'buckets.update',
            'destroy' => 'buckets.destroy',
        ],
        'except' => [
            'show'
        ]
    ]);

    Route::get('{bucket}/files', [FileManagerController::class, 'getFiles'])->name('buckets.files');
    Route::post('{bucket}/folders', [FileManagerController::class, 'createFolder'])->name('buckets.folders.create');

    Route::post('{bucket}/visibility', [FileManagerController::class, 'setVisibility'])->name('buckets.visibility');
    Route::get('{bucket}/download', [FileManagerController::class, 'download'])->name('buckets.download');

    Route::post('{bucket}/files/upload', [FileManagerController::class, 'uploadFile'])->name('buckets.files.upload');
    Route::delete('{bucket}/files/delete', [FileManagerController::class, 'deleteFile'])->name('buckets.files.delete');
    Route::delete('{bucket}/folders/delete', [FileManagerController::class, 'deleteFolder'])->name('buckets.folders.delete');

    Route::post('{bucket}/rename', [FileManagerController::class, 'rename'])->name('buckets.rename');
    Route::post('files/move', [FileManagerController::class, 'moveFile'])->name('buckets.files.move');
});
