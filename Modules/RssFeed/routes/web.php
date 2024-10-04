<?php

use Illuminate\Support\Facades\Route;

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


Route::get('rss', \Modules\RssFeed\Http\Controllers\RssController::class . '@index')->name('module.rss.index');
Route::get('rss/posts', \Modules\RssFeed\Http\Controllers\RssController::class . '@posts')->name('module.rss.posts');
Route::get('rss/products', \Modules\RssFeed\Http\Controllers\RssController::class . '@products')->name('module.rss.products');

