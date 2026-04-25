<?php

declare(strict_types=1);

use MicroweberPackages\Module\Routing\ModuleApiRoutes;
use MicroweberPackages\User\Http\Controllers\Api\UsersApiController;

/*
|--------------------------------------------------------------------------
| Headless Module API Routes (residual)
|--------------------------------------------------------------------------
|
| Per-module REST blocks have been migrated into each module's own
| `routes/api.php`, loaded by the module's service provider via
| `$this->loadRoutesFrom(...)`. The migrated blocks call
| `ModuleApiRoutes::register('<slug>', <controller>, '<binding>')`
| to keep the standardised `/api/module/{slug}/*` shape while moving
| the registration into the owning module.
|
| Adding a new module here is a code smell — add it to the new
| module's `routes/api.php` instead.
|
| What's left here:
|   * `users` — the User package
|     (`MicroweberPackages\User`) is not a Module, so its
|     `/api/module/users/*` routes stay in the global file. When
|     the package gains its own routes loader this block can move
|     too.
|
*/

ModuleApiRoutes::register('users', UsersApiController::class, 'user');
