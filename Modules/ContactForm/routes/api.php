<?php

declare(strict_types=1);

use MicroweberPackages\Module\Routing\ModuleApiRoutes;
use Modules\ContactForm\Http\Controllers\Api\FormsApiController;

/*
|--------------------------------------------------------------------------
| ContactForm (Forms) Module API Routes
|--------------------------------------------------------------------------
|
| Migrated from routes/module-api.php's global $modules loop. `forms` and
| `contact-form` are aliases onto the same Form resource so clients can
| use either the legacy slug (`contact-form`) or the plural (`forms`),
| and both reuse the canonical `forms:write` scope.
|
*/

ModuleApiRoutes::register('forms', FormsApiController::class, 'form');
ModuleApiRoutes::register('contact-form', FormsApiController::class, 'form', 'forms');
