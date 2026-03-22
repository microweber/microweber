<?php
/*
* This file is part of the Microweber framework.
*
* (c) Microweber CMS LTD
*
* For full license information see
* https://github.com/microweber/microweber/blob/master/LICENSE
*/

namespace MicroweberPackages\Role;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use MicroweberPackages\Core\Providers\Concerns\MergesConfig;
use MicroweberPackages\Role\Filament\Resources\RoleResource;
use MicroweberPackages\Role\Filament\Resources\PermissionResource;
use MicroweberPackages\Role\Services\ResourcePermissionService;

class RoleServiceProvider extends ServiceProvider
{
    use MergesConfig;

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/permission.php', 'permission');

        // Register the resource permission service
        $this->app->singleton(ResourcePermissionService::class, function ($app) {
            return new ResourcePermissionService();
        });
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/admin.php');

        View::addNamespace('role', __DIR__ . '/resources/views');

        $this->loadMigrationsFrom(__DIR__ . '/database/migrations/');
    }

    /**
     * Get the Filament resources for this package
     */
    public static function getFilamentResources(): array
    {
        return [
            RoleResource::class,
            PermissionResource::class,
        ];
    }
}
