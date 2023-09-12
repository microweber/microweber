<?php
namespace MicroweberPackages\Modules\Editor\Fonts\FontsSettings\Providers;


use Illuminate\Support\Facades\View;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use MicroweberPackages\Modules\TemplateSettings\Http\Livewire\TemplatesSettingsSettingsComponent;

class FontsSettingsSettingsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-editor-fonts');
    }

    public function register(): void
    {

        parent::register();

        View::addNamespace('microweber-module-editor-fonts', normalize_path((dirname(__DIR__)) . '/resources/views'));

    }

}
