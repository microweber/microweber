<?php


namespace MicroweberPackages\Modules\Btn;


use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Livewire\Livewire;
use MicroweberPackages\LiveEdit\Events\ServingLiveEdit;
use MicroweberPackages\LiveEdit\Events\ServingModuleSettings;
use MicroweberPackages\LiveEdit\Facades\LiveEditManager;
use MicroweberPackages\Modules\Btn\Http\Livewire\ButtonSettingsComponent;
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
        Event::listen(ServingLiveEdit::class, [$this, 'registerLiveEditAssets']);
        Event::listen(ServingModuleSettings::class, [$this, 'registerLivewireComponents']);

        Livewire::component('admin-live-edit-button-settings', ButtonSettingsComponent::class);

        $this->loadRoutesFrom(__DIR__ . '/routes/live_edit.php');
        View::addNamespace('modules.btn', __DIR__ . '/resources/views');
    }

    public function registerLiveEditAssets(ServingLiveEdit $event): void
    {
        $scriptUrl = modules_url() . 'btn/quick-settings.js';
        LiveEditManager::addScript('mw-module-btn-quick-settings', $scriptUrl, ['type' => 'module']);
     }

     public function registerLivewireComponents(ServingModuleSettings $event): void
    {

     }
}
