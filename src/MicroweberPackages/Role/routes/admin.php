<?php
/*
Route::prefix(config('admin.router.prefix', 'admin'))->middleware('web')->group(function ($router) {
    $router->get('login', config('admin.auth.login.controller').'@showLoginForm')->name('admin.login');
    $router->post('login', config('admin.auth.login.controller').'@login')->middleware('throttle:'.config('admin.auth.login.throttle'))->middleware(\Tanwencn\Admin\Http\Middleware\HttpLog::class)->name('admin.login');
    $router->get('logout', config('admin.auth.login.controller').'@logout')->name('admin.logout');
});*/

Route::group(['namespace' => '\MicroweberPackages\Role\Http\Controllers'], function ($router) {

    $router->resource('admin/api/users', 'UserController')->names('users');
    $router->resource('admin/api/roles', 'RoleController')->names('roles');
    $router->resource('admin/api/permissions', 'PermissionController')->names('permissions');

});
