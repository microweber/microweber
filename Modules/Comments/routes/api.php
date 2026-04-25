<?php

use Illuminate\Support\Facades\Route;

/*
 *--------------------------------------------------------------------------
 * API Routes
 *--------------------------------------------------------------------------
 *
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 *
*/


/*
|--------------------------------------------------------------------------
| Headless Module API (comments)
|--------------------------------------------------------------------------
|
| Migrated from the global routes/module-api.php  loop. Reads
| are public (rate-limited); writes require a Passport admin-scoped
| token. Same controller as the legacy /api/comments/* surface above so
| both clients keep working through one implementation.
|
*/

\MicroweberPackages\Module\Routing\ModuleApiRoutes::register(
    'comments',
    \Modules\Comments\Http\Controllers\Api\CommentsApiController::class,
    'comment'
);
