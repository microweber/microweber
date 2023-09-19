<?php


namespace MicroweberPackages\Modules\Btn\Providers;


use Livewire\Livewire;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use MicroweberPackages\Modules\Btn\Http\Livewire\ButtonSettingsBootstrapTemplateComponent;
use MicroweberPackages\Modules\Btn\Http\Livewire\ButtonSettingsComponent;
use MicroweberPackages\Modules\Btn\Http\Livewire\ButtonSettingsDefaultTemplateComponent;
use MicroweberPackages\Modules\Btn\Http\Livewire\ButtonSettingsDesignFormComponent;
use MicroweberPackages\Modules\Btn\Http\Livewire\ButtonSettingsFormComponent;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;


class BtnServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-btn');
        $package->hasViews('microweber-module-btn');
    }

    public function register(): void
    {
        parent::register();

        Livewire::component('microweber-module-btn::settings', ButtonSettingsComponent::class);
        Livewire::component('microweber-module-btn::template-settings-bootstrap', ButtonSettingsBootstrapTemplateComponent::class);
        Livewire::component('microweber-module-btn::template-settings-default', ButtonSettingsDefaultTemplateComponent::class);

        ModuleAdmin::registerSettings('btn', 'microweber-module-btn::settings');
        ModuleAdmin::registerSkinSettings('btn', 'default', 'microweber-module-btn::template-settings-default');
        ModuleAdmin::registerSkinSettings('btn', 'bootstrap', 'microweber-module-btn::template-settings-bootstrap');

    }

}
