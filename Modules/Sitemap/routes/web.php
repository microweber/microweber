<?php

use Illuminate\Support\Facades\Route;
use Modules\Sitemap\Http\Controllers\SitemapController;

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


Route::get('sitemap.xml', SitemapController::class . '@index')->name('sitemap.index');
Route::get('sitemap.xml/categories', SitemapController::class . '@categories')->name('sitemap.categories');
Route::get('sitemap.xml/tags', SitemapController::class . '@tags')->name('sitemap.tags');
Route::get('sitemap.xml/products', SitemapController::class . '@products')->name('sitemap.products');
Route::get('sitemap.xml/posts', SitemapController::class . '@posts')->name('sitemap.posts');
Route::get('sitemap.xml/pages', SitemapController::class . '@pages')->name('sitemap.pages');
