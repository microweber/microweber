<?php


namespace MicroweberPackages\Modules\Settings\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use MicroweberPackages\Admin\Events\ServingAdmin;
use MicroweberPackages\Admin\Facades\AdminManager;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class SettingsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-settings');
        $package->hasRoutes('admin');
        $package->hasViews('microweber-module-settings');

    }

    public function registerMenu()
    {


        AdminManager::getMenuInstance('left_menu_top')->addChild('Settings', [
            'uri' => route('admin.settings.index'),

            'attributes'=>[
                'route' => 'admin.settings.index',
                'icon'=>' <svg fill="currentColor"class="me-3" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="m370 976-16-128q-13-5-24.5-12T307 821l-119 50L78 681l103-78q-1-7-1-13.5v-27q0-6.5 1-13.5L78 471l110-190 119 50q11-8 23-15t24-12l16-128h220l16 128q13 5 24.5 12t22.5 15l119-50 110 190-103 78q1 7 1 13.5v27q0 6.5-2 13.5l103 78-110 190-118-50q-11 8-23 15t-24 12l-16 128H370Zm112-260q58 0 99-41t41-99q0-58-41-99t-99-41q-59 0-99.5 41T342 576q0 58 40.5 99t99.5 41Zm0-80q-25 0-42.5-17.5T422 576q0-25 17.5-42.5T482 516q25 0 42.5 17.5T542 576q0 25-17.5 42.5T482 636Zm-2-60Zm-40 320h79l14-106q31-8 57.5-23.5T639 729l99 41 39-68-86-65q5-14 7-29.5t2-31.5q0-16-2-31.5t-7-29.5l86-65-39-68-99 42q-22-23-48.5-38.5T533 362l-13-106h-79l-14 106q-31 8-57.5 23.5T321 423l-99-41-39 68 86 64q-5 15-7 30t-2 32q0 16 2 31t7 30l-86 65 39 68 99-42q22 23 48.5 38.5T427 790l13 106Z"/></svg>'
            ]
        ]);

        AdminManager::getMenuInstance('left_menu_top')
            ->menuItems
            ->getChild('Settings')
            ->setExtra('orderNumber', 5);
    }

    public function register(): void
    {
        parent::register();

        $this->loadRoutesFrom(__DIR__ . '/../routes/admin.php');
        View::addNamespace('modules.settings', __DIR__ . '/resources/views');

    }

    public function boot(): void
    {
        parent::boot();

        Event::listen(ServingAdmin::class, [$this, 'registerMenu']);

    }

}
