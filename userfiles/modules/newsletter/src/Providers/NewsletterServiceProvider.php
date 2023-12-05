<?php

namespace MicroweberPackages\Modules\Newsletter\Providers;

use MicroweberPackages\Modules\Newsletter\Console\Commands\ProcessCampaigns;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class NewsletterServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-newsletter');
        $package->hasViews('microweber-module-newsletter');
    }


    public function register(): void
    {
        parent::register();

        $this->loadRoutesFrom((dirname(__DIR__)) . '/routes/admin.php');

        if (is_cli()) {
            $this->commands(ProcessCampaigns::class);
        }
    }
}
