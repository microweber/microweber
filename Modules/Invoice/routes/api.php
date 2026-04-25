<?php

declare(strict_types=1);

use MicroweberPackages\Module\Routing\ModuleApiRoutes;
use Modules\Invoice\Http\Controllers\Api\InvoicesApiController;

/*
|--------------------------------------------------------------------------
| Invoice Module API Routes
|--------------------------------------------------------------------------
|
| Migrated from routes/module-api.php's global $modules loop.
|
*/

ModuleApiRoutes::register('invoices', InvoicesApiController::class, 'invoice');
