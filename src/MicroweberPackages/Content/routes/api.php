<?php

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
|
*/

Route::name('api.')
    ->prefix('api')
    ->middleware(['api', 'admin'])
    ->namespace('\MicroweberPackages\Content\Http\Controllers\Api')
    ->group(function () {
        Route::get('content/get_admin_js_tree_json', function (\Illuminate\Http\Request $request) {
            return mw()->category_manager->get_admin_js_tree_json($request->all());
        })->name('content.get_admin_js_tree_json');
    });

Route::name('api.')
    ->prefix('api')
    ->middleware(['api','admin'])
    ->namespace('\MicroweberPackages\Content\Http\Controllers\Api')
    ->group(function () {

        Route::apiResource('content', 'ContentApiController')->only([
            'store', // 'update','destroy','index','show'
        ]);

        Route::post('save_edit', function (\Illuminate\Http\Request $request) {
            return save_edit($request->all());
        })->name('content.save_edit');



        Route::post('content/set_published', function (\Illuminate\Http\Request $request) {
            return mw()->content_manager->set_published($request->all());
        })->name('content.set_published');

        Route::post('content/set_unpublished', function (\Illuminate\Http\Request $request) {
            return mw()->content_manager->set_unpublished($request->all());
        })->name('content.set_unpublished');

        Route::post('content/reorder', function (\Illuminate\Http\Request $request) {
            return mw()->content_manager->reorder($request->all());
        })->name('content.reorder');

        Route::post('content/reset_edit', function (\Illuminate\Http\Request $request) {
            return mw()->content_manager->helpers->reset_edit_field($request->all());
        })->name('content.reset_edit');

        Route::post('content/reset_modules_settings', function (\Illuminate\Http\Request $request) {
            return mw()->content_manager->helpers->reset_modules_settings($request->all());
        })->name('content.reset_modules_settings');

        Route::post('content/bulk_assign', function (\Illuminate\Http\Request $request) {
            return mw()->content_manager->helpers->bulk_assign($request->all());
        })->name('content.bulk_assign');
        Route::post('content/copy', function (\Illuminate\Http\Request $request) {
            return mw()->content_manager->helpers->copy($request->all());
        })->name('content.copy');


        Route::post('content/related_content/add', function (\Illuminate\Http\Request $request) {
            return mw()->content_manager->helpers->related_content_add($request->all());
        })->name('content.related.add');

        Route::post('content/related_content/remove', function (\Illuminate\Http\Request $request) {
            return mw()->content_manager->helpers->related_content_remove($request->all());
        })->name('content.related.remove');

        Route::post('content/related_content/reorder', function (\Illuminate\Http\Request $request) {
            return mw()->content_manager->helpers->related_content_reorder($request->all());
        })->name('content.related.reorder');

        Route::any('content/redirect_to_content', function (\Illuminate\Http\Request $request) {

            if (isset($request['id'])) {
                $id = intval($request['id']);
                $url = content_link($id);
                if (!$url) {
                    $url = site_url();
                }
                return redirect($url);
            }
        })->name('content.redirect_to_content');


        Route::post('content/delete', function (\Illuminate\Http\Request $request) {
            return mw()->content_manager->helpers->delete($request->all());
        })->name('content.delete');

        Route::get('content/get_link_admin', function (\Illuminate\Http\Request $request) {

            if (!isset($request['id'])) {
                return false;
            }

            $content = mw()->content_manager->get_by_id($request['id']);
            if (!$content) {
                return;
            }

            $segments = mw()->permalink_manager->link($content['id'], 'content', true);
            $admin_url = route('admin.content.edit', $content['id']);

            if(isset($content['content_type']) and $content['content_type']){
                if (\Route::has('admin.'.$content['content_type'].'.edit')) {
                    $admin_url = route('admin.' . $content['content_type'] . '.edit', $content['id']);
                }
            }
            if ($segments) {
                return [
                    'url' => $segments['url'],
                    'slug_prefix' => $segments['slug_prefix'],
                    'slug_prefix_url' => $segments['slug_prefix_url'],
                    'slug' => $segments['slug'],
                    'admin_url' => $admin_url ,
                    'site_url' => site_url()
                ];
            }

            return false;
        })->name('content.get_link_admin');

    });
