<?php

namespace MicroweberPackages\Modules\Editor\CodeEditor\Providers;

use Filament\Facades\Filament;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class CodeEditorModuleServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-code-editor');
        $package->hasViews('microweber-module-code-editor');
    }

    public function register(): void
    {

        parent::register();

       // FilamentRegistry::registerPage(CodeEditorModuleSettingsPage::class);
    }

    public function boot(): void
    {
//        parent::boot();
//        Filament::serving(function () {
//            $panelId = Filament::getCurrentPanel()->getId();
//            if ($panelId == 'admin') {
//            //    ModuleAdmin::registerLiveEditSettingsUrl('editor/code_editor', CodeEditorModuleSettingsPage::getUrl());
//            }
//        });

    }

}
