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

Route::any('/tus/{any?}', function () {
    return app('tus-server')->send();
})->where('any', '.*')->name('tus.url');
