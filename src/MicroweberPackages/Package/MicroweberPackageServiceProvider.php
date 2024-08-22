<?php

namespace MicroweberPackages\Package;

use Filament\Facades\Filament;

abstract class MicroweberPackageServiceProvider extends \Spatie\LaravelPackageTools\PackageServiceProvider
{

    protected ModulePackage $module;

    abstract public function configureModule(ModulePackage $module): void;

    public function newModulePackage(): ModulePackage
    {
        return new ModulePackage();
    }

    public function register()
    {
        parent::register();

        $this->module = $this->newModulePackage();

        $this->configureModule($this->module);

        if (empty($this->module->type)) {
            throw PackageManagerException::moduleTypeIsRequired();

        }

        return $this;
    }

    public function boot()
    {
        parent::boot();
//        Filament::serving(function () {
//            $panelId = Filament::getCurrentPanel()->getId();
//            if ($panelId == 'admin') {
//
//            }
//        });
    }
}
