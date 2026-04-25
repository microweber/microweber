<?php

use  \Illuminate\Support\Facades\Route;





/*
|--------------------------------------------------------------------------
| Headless Module API (shipping)
|--------------------------------------------------------------------------
|
| Migrated from the global routes/module-api.php  loop. Reads
| are public (rate-limited); writes require a Passport admin-scoped
| token. Same controller as the legacy /api/shipping/* surface above so
| both clients keep working through one implementation.
|
*/

\MicroweberPackages\Module\Routing\ModuleApiRoutes::register(
    'shipping',
    \Modules\Shipping\Http\Controllers\Api\ShippingApiController::class,
    'shipping'
);
