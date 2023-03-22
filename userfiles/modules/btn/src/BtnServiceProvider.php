<?php


namespace MicroweberPackages\Modules\Btn;


use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use MicroweberPackages\LiveEdit\Events\ServingLiveEdit;
use MicroweberPackages\LiveEdit\Facades\LiveEditManager;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;


class BtnServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('btn');
    }

    public function register(): void
    {
        parent::register();
        Event::listen(ServingLiveEdit::class, [$this, 'registerLiveEditAssets']);
        $this->loadRoutesFrom(__DIR__ . '/routes/live_edit.php');
        View::addNamespace('modules.btn', __DIR__ . '/resources/views');
    }

    public function registerLiveEditAssets(ServingLiveEdit $event): void
    {
        $scriptUrl = modules_url() . 'btn/quick-settings.js';
        LiveEditManager::addScript('mw-module-btn-quick-settings', $scriptUrl, ['type' => 'module']);
     }
}
