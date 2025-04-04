<?php


/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
|
*/

use  \Illuminate\Support\Facades\Route;

Route::name('api.')
    ->prefix('api')
    ->middleware(['api', 'admin'])
    ->group(function () {
        Route::get('content/get_admin_js_tree_json', function (\Illuminate\Http\Request $request) {
            return mw()->category_manager->get_admin_js_tree_json($request->all());
        })->name('content.get_admin_js_tree_json');

        Route::apiResource('content', \Modules\Content\Http\Controllers\Api\ContentApiController::class)->only([
            'update', 'destroy', 'index', 'show', 'store'
        ]);
    });

Route::name('api.')
    ->prefix('api')
    ->middleware(['web', 'api', 'admin'])
    ->namespace('\Modules\Content\Http\Controllers\Api')
    ->group(function () {


        Route::post('save_edit', function (\Illuminate\Http\Request $request) {
            return save_edit($request->all());
        })->name('content.save_edit');

        Route::any('get_content_admin', function (\Illuminate\Http\Request $request) {
            return get_content_admin($request->all());
        })->name('content.get_content_admin');


        Route::post('content/set_published', function (\Illuminate\Http\Request $request) {
            return app()->content_manager->set_published($request->all());
        })->name('content.set_published');

        Route::post('content/set_unpublished', function (\Illuminate\Http\Request $request) {
            return app()->content_manager->set_unpublished($request->all());
        })->name('content.set_unpublished');

        Route::post('content/reorder', function (\Illuminate\Http\Request $request) {
            return app()->content_manager->reorder($request->all());
        })->name('content.reorder');

        Route::post('content/reset_edit', function (\Illuminate\Http\Request $request) {
            return app()->content_manager->helpers->reset_edit_field($request->all());
        })->name('content.reset_edit');

        Route::post('content/reset_modules_settings', function (\Illuminate\Http\Request $request) {
            return app()->content_manager->helpers->reset_modules_settings($request->all());
        })->name('content.reset_modules_settings');

        Route::post('content/bulk_assign', function (\Illuminate\Http\Request $request) {
            return app()->content_manager->helpers->bulk_assign($request->all());
        })->name('content.bulk_assign');
        Route::post('content/copy', function (\Illuminate\Http\Request $request) {
            return app()->content_manager->helpers->copy($request->all());
        })->name('content.copy');


        Route::post('content/related_content/add', function (\Illuminate\Http\Request $request) {
            return app()->content_manager->helpers->related_content_add($request->all());
        })->name('content.related.add');

        Route::post('content/related_content/remove', function (\Illuminate\Http\Request $request) {
            return app()->content_manager->helpers->related_content_remove($request->all());
        })->name('content.related.remove');

        Route::post('content/related_content/reorder', function (\Illuminate\Http\Request $request) {
            return app()->content_manager->helpers->related_content_reorder($request->all());
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
            return app()->content_manager->helpers->delete($request->all());
        })->name('content.delete');

        Route::get('content/get_link_admin', function (\Illuminate\Http\Request $request) {

            if (!isset($request['id'])) {
                return false;
            }

            $content = app()->content_manager->get_by_id($request['id']);
            if (!$content) {
                return;
            }

            $segments = mw()->permalink_manager->link($content['id'], 'content', true);
            $admin_url = route('admin.content.edit', $content['id']);

            if (isset($content['content_type']) and $content['content_type']) {
                if (Route::has('admin.' . $content['content_type'] . '.edit')) {
                    $admin_url = route('admin.' . $content['content_type'] . '.edit', $content['id']);
                }
            }

            $liveEditUrl = admin_url() . 'live-edit';
            $liveEditUrl = $liveEditUrl .= '?url=' . content_link($request['id']);

            if ($segments) {
                return [
                    'url' => $segments['url'],
                    'slug_prefix' => $segments['slug_prefix'],
                    'slug_prefix_url' => $segments['slug_prefix_url'],
                    'slug' => $segments['slug'],
                    'admin_url' => $admin_url,
                    'live_edit_url' => $liveEditUrl,
                    'site_url' => site_url()
                ];
            }

            return false;
        })->name('content.get_link_admin');


        Route::any('get_content', function (\Illuminate\Http\Request $request) {
            return get_content($request->all());
        })->name('content.get_content');

        Route::any('get_posts', function (\Illuminate\Http\Request $request) {
            return get_posts($request->all());
        })->name('content.get_posts');

        Route::any('content_title', function (\Illuminate\Http\Request $request) {
            return content_title($request->all());
        })->name('content.content_title');

        Route::any('content_title', function (\Illuminate\Http\Request $request) {
            return content_title($request->all());
        })->name('content.content_title');

        Route::any('get_pages', function (\Illuminate\Http\Request $request) {
            return content_title($request->all());
        })->name('content.get_pages');

        Route::any('content_link', function (\Illuminate\Http\Request $request) {
            return content_link($request->all());
        })->name('content.content_link');

        Route::any('get_content_by_id', function (\Illuminate\Http\Request $request) {
            return get_content_by_id($request->all());
        })->name('content.get_content_by_id');

        Route::any('get_products', function (\Illuminate\Http\Request $request) {
            return get_products($request->all());
        })->name('content.get_products');

        Route::any('delete_content', function (\Illuminate\Http\Request $request) {
            return delete_content($request->all());
        })->name('content.delete_content');


        Route::any('content_parents', function (\Illuminate\Http\Request $request) {
            return content_parents($request->all());
        })->name('content.content_parents');
        Route::any('get_content_children', function (\Illuminate\Http\Request $request) {
            return get_content_children($request->all());
        })->name('content.get_content_children');
        Route::any('page_link', function (\Illuminate\Http\Request $request) {
            return page_link($request->all());
        })->name('content.page_link');
        Route::any('post_link', function (\Illuminate\Http\Request $request) {
            return post_link($request->all());
        })->name('content.post_link');
        Route::any('pages_tree', function (\Illuminate\Http\Request $request) {
            return pages_tree($request->all());
        })->name('content.pages_tree');
        Route::any('save_content', function (\Illuminate\Http\Request $request) {
            return save_content($request->all());
        })->name('content.save_content');
        Route::any('get_content_field_draft', function (\Illuminate\Http\Request $request) {
            return get_content_field_draft($request->all());
        })->name('content.get_content_field_draft');

        Route::any('get_content_field', function (\Illuminate\Http\Request $request) {
            return get_content_field($request->all());
        })->name('content.get_content_field');


    });
