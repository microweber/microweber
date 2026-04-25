<?php

declare(strict_types=1);

use MicroweberPackages\Module\Routing\ModuleApiRoutes;
use Modules\Customer\Http\Controllers\Api\CustomersApiController;

/*
|--------------------------------------------------------------------------
| Customer Module API Routes
|--------------------------------------------------------------------------
|
| Migrated from routes/module-api.php's global $modules loop.
|
*/

ModuleApiRoutes::register('customers', CustomersApiController::class, 'customer');
