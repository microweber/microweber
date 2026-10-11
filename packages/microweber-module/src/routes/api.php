<?php

use Illuminate\Support\Facades\Cookie;
use  \Illuminate\Support\Facades\Route;

// task-2026-06-06-adminapisession: the `admin` middleware authorises via
// is_admin() / Auth::check() (session-based). These route groups ran in the
// stateless `api` group (or with only `['admin']`), which never starts a
// session — so the logged-in admin's session cookie was ignored and every
// request returned 401 "Please as admin login to continue". In Live Edit this
// broke the "+ ADD" module picker (api/module/list, getSkins, layout-preview):
// the 401 surfaced to the user as a false "Your session has expired" banner
// even though the admin was logged in. Start a session before `admin` runs so
// it can see the authenticated admin. Order matters: EncryptCookies (decrypt
// the incoming cookie) → AddQueuedCookies → StartSession → then `admin`.
$adminSessionStack = [
    \Illuminate\Cookie\Middleware\EncryptCookies::class,
    \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
    \Illuminate\Session\Middleware\StartSession::class,
];

Route::name('api.module.')
    ->prefix('api/module')
    ->middleware(array_merge(['api'], $adminSessionStack, ['admin']))
    ->group(function () {
// modules/list

            Route::get('list', \MicroweberPackages\Module\Http\Controllers\Api\ModulesApiLiveEdit::class . '@index')->name('list');  //api.module.list
            Route::get('getSkins', \MicroweberPackages\Module\Http\Controllers\Api\ModulesApiLiveEdit::class . '@getSkins')->name('getSkins'); //api.module.getSkins
            Route::get('layout-preview', \MicroweberPackages\Module\Http\Controllers\Api\ModulesApiLiveEdit::class . '@layoutPreview')->name('layout-preview'); //api.module.layout-preview

    // if (config('modules.restore.allow_php_files_upload')) {
//            Route::namespace('MicroweberPackages\Module\Http\Controllers\Api')->group(function () {
//                Route::post('upload', 'ModuleUploadController@upload')->name('upload');
//            });
//        }
    });


// NOTE: this group uses only ['admin'] (no 'api' group). Such routes already
// receive a session — it's specifically opting INTO the stateless `api` group
// that drops it (see the api/module group above). So no session stack is
// needed here; adding one would double-apply StartSession.
Route::name('api.')
    ->prefix('api/')
    ->middleware(['admin'])
    ->group(function () {

        Route::any('clearcache', function () {
            return clearcache();
        });

        Route::any('mw_post_update', function () {
            $status = mw_post_update();

            $cookie = Cookie::forget('XSRF-TOKEN');

            $response = response()->make('updated', 200)->withCookie($cookie);
            return $response;
        });

        Route::any('mw_reload_modules', function () {
            return mw_reload_modules();
        });

        // Formerly api_expose_admin('save_module_as_template') / delete_module_as_template
        Route::any('save_module_as_template', \MicroweberPackages\Module\Http\Controllers\Api\ModuleTemplateApiController::class . '@save')
            ->name('save_module_as_template');
        Route::any('delete_module_as_template', \MicroweberPackages\Module\Http\Controllers\Api\ModuleTemplateApiController::class . '@delete')
            ->name('delete_module_as_template');
    });
