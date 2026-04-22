<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Comments\Http\Controllers\Api\CommentsApiController;
use Modules\Content\Http\Controllers\Api\ContentApiController;
use Modules\Media\Http\Controllers\Api\MediaApiController;
use Modules\Menu\Http\Controllers\Api\MenusApiController;
use Modules\Page\Http\Controllers\Api\PageApiController;
use Modules\Post\Http\Controllers\Api\PostApiController;
use Modules\Tag\Http\Controllers\Api\TagApiController;

/*
|--------------------------------------------------------------------------
| Headless Module API Routes
|--------------------------------------------------------------------------
|
| Unified `/api/module/{module}/*` namespace authenticated via Passport
| personal-access tokens issued from /admin/api-applications.
|
| Reads are public (rate-limited by IP), writes require a Passport token
| belonging to a user with is_admin = 1. Each controller performs its own
| admin check against $request->user() so the same controller can be
| reused across authenticated and unauthenticated surfaces.
|
*/

$modules = [
    'content' => [ContentApiController::class, 'content'],
    'pages' => [PageApiController::class, 'page'],
    'posts' => [PostApiController::class, 'post'],
    'tags' => [TagApiController::class, 'tag'],
    'comments' => [CommentsApiController::class, 'comment'],
    'menus' => [MenusApiController::class, 'menu'],
    'media' => [MediaApiController::class, 'media'],
];

foreach ($modules as $slug => [$controller, $binding]) {
    Route::prefix("api/module/{$slug}")
        ->middleware(['api', 'throttle:public'])
        ->name("api.module.{$slug}.")
        ->group(function () use ($controller, $binding) {
            Route::get('/', [$controller, 'index'])->name('index');
            Route::get('/{' . $binding . '}', [$controller, 'show'])->name('show');
        });

    Route::prefix("api/module/{$slug}")
        ->middleware(['api', 'auth:api', 'throttle:api'])
        ->name("api.module.{$slug}.")
        ->group(function () use ($controller, $binding) {
            Route::post('/', [$controller, 'store'])->name('store');
            Route::put('/{' . $binding . '}', [$controller, 'update'])->name('update');
            Route::patch('/{' . $binding . '}', [$controller, 'update'])->name('update.partial');
            Route::delete('/{' . $binding . '}', [$controller, 'destroy'])->name('destroy');
        });
}
