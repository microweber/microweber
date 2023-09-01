<?php

namespace MicroweberPackages\Modules\Admin\Modules\TemplatesSettings\Providers;

use Illuminate\Support\Facades\View;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use MicroweberPackages\Modules\TemplateSettings\Http\Livewire\TemplatesSettingsSettingsComponent;

class TemplatesSettingsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-admin-module-templates-settings');
    }

    public function register(): void
    {

        parent::register();

        View::addNamespace('microweber-module-admin-module-templates-settings', normalize_path((dirname(__DIR__)) . '/resources/views'));

    }

}
