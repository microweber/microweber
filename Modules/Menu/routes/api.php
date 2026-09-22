<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 11/26/2020
 * Time: 10:58 AM
 */


use Illuminate\Support\Facades\Route;
use Modules\Menu\Http\Controllers\Api\MenusApiController;

Route::name('api.menu.')
    ->prefix('api/menu')
    ->middleware(['admin', 'api'])

    ->group(function () {

        Route::name('add.content')->post('add/content', function (\Illuminate\Http\Request $request) {
            return app()->content_manager->helpers->add_content_to_menu($request->all());
        });

        Route::name('delete')->post('delete', function (\Illuminate\Http\Request $request) {
            return app()->menu_manager->menu_delete($request->all());
        });

        Route::name('create')->post('create', function (\Illuminate\Http\Request $request) {
            return app()->menu_manager->menu_create($request->all());
        });

        Route::name('item.save')->post('item/save', function (\Illuminate\Http\Request $request) {
            return app()->menu_manager->menu_item_save($request->all());
        });

        Route::name('item.edit')->post('item/edit', function (\Illuminate\Http\Request $request) {
            return app()->menu_manager->menu_item_save($request->all());
        });

        Route::name('item.delete')->post('item/delete/{id}', function ($id) {
            return app()->menu_manager->menu_item_delete($id);
        });

        Route::name('item.reorder')->post('item/reorder', function (\Illuminate\Http\Request $request) {
            return app()->menu_manager->menu_items_reorder($request->all());
        });

        // task-2026-09-22 — reads for the Live Edit Menu quick-settings panel.
        // list = menu containers (id + pretty label); items = the shaped links
        // of one menu with a resolved label + a Page/Post/Product/Category/Link
        // type badge (the raw menus row keeps title null for content links).
        Route::name('list')->get('list', function () {
            $rows = \Modules\Menu\Models\Menu::where('item_type', 'menu')
                ->orderBy('position')->orderBy('id')->get(['id', 'title']);
            $items = $rows->map(function ($m) {
                return [
                    'id' => (int) $m->id,
                    'title' => $m->title,
                    'label' => ucfirst(trim(str_replace('_', ' ', (string) $m->title))),
                ];
            });
            return response()->json(['success' => true, 'items' => $items]);
        });

        Route::name('items')->get('items', function (\Illuminate\Http\Request $request) {
            $menuId = (int) $request->get('menu_id');
            if (!$menuId) {
                return response()->json(['success' => true, 'items' => []]);
            }
            $rows = \Modules\Menu\Models\Menu::where('parent_id', $menuId)
                ->where('item_type', '!=', 'menu')
                ->orderBy('position')->orderBy('id')->get();
            $items = [];
            foreach ($rows as $m) {
                $label = $m->title;
                $type = 'Link';
                if ($m->content_id) {
                    $c = \Modules\Content\Models\Content::find($m->content_id);
                    if ($c) {
                        if (!$label) { $label = $c->title; }
                        $ct = $c->content_type;
                        $type = $ct === 'post' ? 'Post' : ($ct === 'product' ? 'Product' : 'Page');
                    } else {
                        $type = 'Page';
                    }
                } elseif ($m->categories_id) {
                    $type = 'Category';
                    if (!$label) {
                        $cat = \Modules\Category\Models\Category::find($m->categories_id);
                        if ($cat) { $label = $cat->title; }
                    }
                } elseif ($m->url) {
                    $type = 'Link';
                    if (!$label) { $label = $m->url; }
                }
                $items[] = [
                    'id' => (int) $m->id,
                    'label' => $label ?: 'Item',
                    'type' => $type,
                    'url' => $m->url,
                    'content_id' => $m->content_id ? (int) $m->content_id : null,
                    'position' => $m->position,
                ];
            }
            return response()->json(['success' => true, 'items' => $items]);
        });
    });



/*
|--------------------------------------------------------------------------
| Headless Module API (menus)
|--------------------------------------------------------------------------
|
| Migrated from the global routes/module-api.php  loop. Reads
| are public (rate-limited); writes require a Passport admin-scoped
| token. Same controller as the legacy /api/menus/* surface above so
| both clients keep working through one implementation.
|
*/

Route::prefix('api/module/menus')
    ->middleware(['api', 'throttle:public'])
    ->name('api.module.menus.')
    ->group(function () {
        Route::get('/', [MenusApiController::class, 'index'])->name('index');
        Route::get('/{menu}', [MenusApiController::class, 'show'])->name('show');
    });

Route::prefix('api/module/menus')
    ->middleware(['api', 'auth:api', 'throttle:api', 'throttle:token', 'token.audit', 'scope:menus:write'])
    ->name('api.module.menus.')
    ->group(function () {
        Route::post('/', [MenusApiController::class, 'store'])->name('store');
        Route::put('/{menu}', [MenusApiController::class, 'update'])->name('update');
        Route::patch('/{menu}', [MenusApiController::class, 'update'])->name('update.partial');
        Route::delete('/{menu}', [MenusApiController::class, 'destroy'])->name('destroy');
    });

