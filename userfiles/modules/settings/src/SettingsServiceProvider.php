<?php


namespace MicroweberPackages\Modules\Settings;

use Illuminate\Support\Facades\View;
use MicroweberPackages\Modules\Settings\Http\Livewire\ButtonSettingsComponent;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class SettingsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-settings');
    }

    public function register(): void
    {
        parent::register();

        $this->loadRoutesFrom(__DIR__ . '/routes/admin.php');
        View::addNamespace('modules.settings', __DIR__ . '/resources/views');
    }

}
