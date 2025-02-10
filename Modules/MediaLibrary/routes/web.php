<?php

use Illuminate\Support\Facades\Route;
use Modules\MediaLibrary\Http\Controllers\MediaLibraryController;

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

/*return Route::get('/api/media_library/browse', \Modules\MediaLibrary\Filament\Admin\Pages\MediaLibraryPage::class)
    ->name('media_library.browse');*/

//Route::get('/api/media_library/browse', function (\Illuminate\Http\Request $request) {
//    return view('modules.media_library::browse');
//});


Route::get('/api/media_library/search', function (\Illuminate\Http\Request $request) {
    $data = $request->all();
    $search = array();
    $unsplash = new \Modules\MediaLibrary\Support\Unsplash();

    $page = 1;

    if (isset($data['page'])) {
        $page = $data['page'];
    }

    if (isset($data['keyword'])) {
        $search = $unsplash->search($data['keyword'], $page);
    }

    $response = response($search);
    $response->header('Content-Type', 'text/json');

    return $response;
})->middleware(['api', 'admin', 'xss'])->name('api.media_library.search');


Route::any('/api/media_library/download', function (\Illuminate\Http\Request $request) {
    $data = $request->all();
    $unsplash = new \Modules\MediaLibrary\Support\Unsplash();
    if (isset($data['photo_id'])) {
        $image = $unsplash->download($data['photo_id']);
    }
    return $image;
})->middleware(['api', 'admin', 'xss'])->name('api.media_library.download');
