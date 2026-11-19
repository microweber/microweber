<?php




\Illuminate\Support\Facades\Route::get('/api/image-generate-tn-request/{cache_id}', function ($mediaId) {

    $mediaId = str_replace('..', '', $mediaId);
    $check = \MicroweberPackages\Media\Models\MediaThumbnail::where('uuid', $mediaId)->first();

    if ($check) {
        $opts = $check->image_options;
        $opts = app()->url_manager->replace_site_url_back($opts);
        if (!is_array($opts)) {
            $opts = [];
        }

        // The thumbnail path was stored by MediaManager::thumbnail(); it is still validated
        // by thumbnail_img() to stay inside the thumbnails cache folder.
        $tn = mw()->media_manager->thumbnail_img($opts, [
            'cache_path_relative' => $opts['cache_path_relative'] ?? null,
        ]);
        return $tn;
    }


    return mw()->media_manager->pixum_img();
})->name('api.image-generate-tn-request');

\Illuminate\Support\Facades\Route::post('/api/save_media', function (\Illuminate\Http\Request $request) {

    return save_media($request->all());


})->middleware(['api', 'admin','xss'])->name('api.save_media');

