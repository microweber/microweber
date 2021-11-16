<?php

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
|
*/

Route::name('api.')
    ->prefix('api')
    ->middleware(['api','admin'])
    ->namespace('\MicroweberPackages\Content\Http\Controllers\Api')
    ->group(function () {

        Route::post('save_edit', function (\Illuminate\Http\Request $request) {
            return save_edit($request->all());
        })->name('content.save_edit');

        Route::get('content/get_admin_js_tree_json', function(\Illuminate\Http\Request $request){
            return mw()->category_manager->get_admin_js_tree_json($request->all());
        });

        Route::post('content/set_published', function (\Illuminate\Http\Request $request) {
            return mw()->content_manager->set_published($request->all());
        });

        Route::post('content/set_unpublished', function (\Illuminate\Http\Request $request) {
            return mw()->content_manager->set_unpublished($request->all());
        });
        Route::post('content/reorder', function (\Illuminate\Http\Request $request) {
            return mw()->content_manager->reorder($request->all());
        });

        Route::post('content/reset_edit', function (\Illuminate\Http\Request $request) {
            return mw()->content_manager->helpers->reset_edit_field($request->all());
        });

        Route::post('content/reset_modules_settings', function (\Illuminate\Http\Request $request) {
            return mw()->content_manager->helpers->reset_modules_settings($request->all());
        });

        Route::post('content/bulk_assign', function (\Illuminate\Http\Request $request) {
            return mw()->content_manager->helpers->bulk_assign($request->all());
        });
        Route::post('content/copy', function (\Illuminate\Http\Request $request) {
            return mw()->content_manager->helpers->copy($request->all());
        });


        Route::post('content/related_content/add', function (\Illuminate\Http\Request $request) {
            return mw()->content_manager->helpers->related_content_add($request->all());
        });

        Route::post('content/related_content/remove', function (\Illuminate\Http\Request $request) {
            return mw()->content_manager->helpers->related_content_remove($request->all());
        });

        Route::post('content/related_content/reorder', function (\Illuminate\Http\Request $request) {
            return mw()->content_manager->helpers->related_content_reorder($request->all());
        });

        Route::any('content/redirect_to_content', function (\Illuminate\Http\Request $request) {

            if (isset($request['id'])) {
                $id = intval($request['id']);
                $url = content_link($id);
                if (!$url) {
                    $url = site_url();
                }
                return redirect($url);
            }
        });


        Route::post('content/delete', function (\Illuminate\Http\Request $request) {
            return mw()->content_manager->helpers->delete($request->all());
        });

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
            if(isset($content['content_type'])){
                if($content['content_type'] == 'page'){
                    $admin_url = route('admin.page.edit', $content['id']);
                } else if($content['content_type'] == 'post'){
                    $admin_url = route('admin.post.edit', $content['id']);
                } else if($content['content_type'] == 'product'){
                    $admin_url = route('admin.product.edit', $content['id']);
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
        });

        Route::apiResource('content', 'ContentApiController');

    });
