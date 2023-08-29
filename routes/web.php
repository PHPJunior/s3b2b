<?php

use App\Http\Controllers\BucketController;
use App\Http\Controllers\FileManagerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::inertia('/', 'App/Index');

Route::group(['prefix' => 'api'], function () {
    Route::apiResource('buckets', BucketController::class);

    Route::get('buckets/{bucket}/files', [FileManagerController::class, 'getFiles'])->name('buckets.files');

    Route::post('buckets/{bucket}/files/upload', [FileManagerController::class, 'uploadFile'])->name('buckets.files.upload');
    Route::delete('buckets/{bucket}/files/delete', [FileManagerController::class, 'deleteFile'])->name('buckets.files.delete');
    Route::delete('buckets/{bucket}/folders/delete', [FileManagerController::class, 'deleteFolder'])->name('buckets.folders.delete');

    Route::post('buckets/{bucket}/rename', [FileManagerController::class, 'rename'])->name('buckets.rename');

    Route::post('buckets/files/move', [FileManagerController::class, 'moveFile'])->name('buckets.files.move');
});
