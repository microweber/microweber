<?php

use \Illuminate\Support\Facades\Route;



Route::post('media/upload', function (\Illuminate\Http\Request $request) {

    return mw()->media_manager->upload($_POST);


})->middleware(['api', 'admin', 'xss'])->name('api.media_upload');

Route::get('pixum_img', function (\Illuminate\Http\Request $request) {

    return pixum_img();


})->middleware('web')->name('api.pixum_img');

Route::get('thumbnail_img', function (\Illuminate\Http\Request $request) {

    return thumbnail_img($request->all());


})->middleware('web')->name('api.pixum_img');







Route::any('/api/media_library/search', function (\Illuminate\Http\Request $request) {
    $data = $request->all();
    $search = array();
    $unsplash = new \Modules\Media\Support\Unsplash();

    $page = 1;

    if (isset($data['page'])) {
        $page = $data['page'];
    }

    if (isset($data['keyword'])) {
        $search = $unsplash->search($data['keyword'], $page);
    }

    $response = \Illuminate\Http\Response::make($search);
    $response->header('Content-Type', 'text/json');

    return $response;
})->middleware(['api', 'admin', 'xss'])->name('api.media_library.search');

Route::post('/api/media_library/download', function (\Illuminate\Http\Request $request) {
    $data = $request->all();
    $unsplash = new \Modules\Media\Support\Unsplash();
    if (isset($data['photo_id'])) {
        $image = $unsplash->download($data['photo_id']);
    }
    return $image;
})->middleware(['api', 'admin', 'xss'])->name('api.media_library.download');

Route::get('/api/image-generate-tn-request/{cache_id}', function ($mediaId) {

    $mediaId = str_replace('..', '', $mediaId);
    $check = \Modules\Media\Models\MediaThumbnail::where('uuid', $mediaId)->first();

    if ($check) {
        $opts = $check->image_options;
        $opts = app()->url_manager->replace_site_url_back($opts);
        $cache_id_data_json = $opts;
        $cache_id_data_json['cache_id'] = $check->rel_id ?? $mediaId;

        $tn = mw()->media_manager->thumbnail_img($cache_id_data_json);

        return $tn;
    }


    return mw()->media_manager->pixum_img();
})->name('api.image-generate-tn-request');

Route::post('/api/save_media', function (\Illuminate\Http\Request $request) {

    return save_media($request->all());


})->middleware(['api', 'admin', 'xss'])->name('api.save_media');

