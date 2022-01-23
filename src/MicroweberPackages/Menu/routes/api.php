<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 11/26/2020
 * Time: 10:58 AM
 */


Route::name('api.menu.')
    ->prefix('api/menu')
    ->middleware(['admin','api'])
    ->namespace('\MicroweberPackages\Content\Http\Controllers\Api')
    ->group(function () {

        Route::name('add.content')->post('add/content', function (\Illuminate\Http\Request $request) {
            return mw()->content_manager->helpers->add_content_to_menu($request->all());
        });

        Route::name('delete')->post('delete', function (\Illuminate\Http\Request $request) {
            return mw()->menu_manager->menu_delete($request->all());
        });

        Route::name('create')->post('create', function (\Illuminate\Http\Request $request) {
            return  mw()->menu_manager->menu_create($request->all());
        });

        Route::name('item.save')->post('item/save', function (\Illuminate\Http\Request $request) {
            return mw()->menu_manager->menu_item_save($request->all());
        });

        Route::name('item.edit')->post('item/edit', function (\Illuminate\Http\Request $request) {
            return  mw()->menu_manager->menu_item_save($request->all());
        });

        Route::name('item.delete')->post('item/delete/{id}', function ($id) {
            return  mw()->menu_manager->menu_item_delete($id);
        });

        Route::name('item.reorder')->post('item/reorder', function (\Illuminate\Http\Request $request) {
            return mw()->menu_manager->menu_items_reorder($request->all());
        });
    });

Route::name('api.v2.')
    ->prefix('api/v2')
    ->middleware(['api','admin'])
    ->namespace('\MicroweberPackages\Menu\Http\Controllers\Api')
    ->group(function () {

        Route::apiResource('menu', 'MenuApiController');

    });
