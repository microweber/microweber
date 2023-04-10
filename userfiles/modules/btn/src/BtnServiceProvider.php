<?php


namespace MicroweberPackages\Modules\Btn;


use Illuminate\Support\Facades\View;
use Livewire\Livewire;
use MicroweberPackages\Modules\Btn\Http\Livewire\ButtonSettingsBootstrapTemplateComponent;
use MicroweberPackages\Modules\Btn\Http\Livewire\ButtonSettingsComponent;
use MicroweberPackages\Modules\Btn\Http\Livewire\ButtonSettingsDefaultTemplateComponent;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;


class BtnServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-btn');
    }

    public function register(): void
    {
        parent::register();
        Livewire::component('live-edit::btn', ButtonSettingsComponent::class);
        Livewire::component('live-edit::btn-template-bootstrap', ButtonSettingsBootstrapTemplateComponent::class);
        Livewire::component('live-edit::btn-template-default', ButtonSettingsDefaultTemplateComponent::class);

        $this->loadRoutesFrom(__DIR__ . '/routes/live_edit.php');
        View::addNamespace('modules.btn', __DIR__ . '/resources/views');
    }

}
