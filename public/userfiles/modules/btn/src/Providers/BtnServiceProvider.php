<?php


namespace MicroweberPackages\Modules\Btn\Providers;



use MicroweberPackages\Modules\Btn\Filament\ButtonModuleSettings;
use MicroweberPackages\Package\MicroweberPackageServiceProvider;
use MicroweberPackages\Package\ModulePackage;
use Spatie\LaravelPackageTools\Package;


class BtnServiceProvider extends MicroweberPackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-btn');
        $package->hasViews('microweber-module-btn');
    }

    public function configureModule(ModulePackage $module): void
    {
        $module->type('btn');


        $module->hasLiveEditSettings(ButtonModuleSettings::class);
      //  $module->hasFrontendController();


//        $module->hasFilamentPage(ButtonModuleSettings::class);
//        $module->hasFilamentResource(ButtonModuleSettings::class);
//        $module->hasFilamentWidget(ButtonModuleSettings::class);
//        $module->hasFilamentPlugin(ButtonModuleSettings::class);

    }






//    public function register(): void
//    {
//        parent::register();
//
//        FilamentRegistry::registerPage(ButtonModuleSettings::class);
//
//    }

//    public function boot(): void
//    {
//        parent::boot();
//        Filament::serving(function () {
//            $panelId = Filament::getCurrentPanel()->getId();
//            if ($panelId == 'admin') {
//               // ModuleAdmin::registerLiveEditSettingsUrl('btn', ButtonModuleSettings::getUrl());
//                ModuleAdmin::registerSettingsComponent('btn', ButtonModuleSettings::class);
//            }
//        });
//
//    }
}
