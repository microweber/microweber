<?php

declare(strict_types=1);

use MicroweberPackages\Module\Routing\ModuleApiRoutes;
use Modules\Tag\Http\Controllers\Api\TagApiController;

/*
|--------------------------------------------------------------------------
| Tag Module API Routes
|--------------------------------------------------------------------------
|
| Migrated from routes/module-api.php's global $modules loop. Reads are
| public (rate-limited); writes require a Passport admin-scoped token.
|
*/

ModuleApiRoutes::register('tags', TagApiController::class, 'tag');
