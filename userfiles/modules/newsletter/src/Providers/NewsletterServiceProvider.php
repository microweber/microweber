<?php

namespace MicroweberPackages\Modules\Newsletter\Providers;

use Livewire\Livewire;
use MicroweberPackages\Modules\Newsletter\Console\Commands\ProcessCampaigns;
use MicroweberPackages\Modules\Newsletter\Http\Livewire\Admin\NewsletterCampaignsLogModal;
use MicroweberPackages\Modules\Newsletter\Http\Livewire\Admin\NewsletterChooseTemplateModal;
use MicroweberPackages\Modules\Newsletter\Http\Livewire\Admin\NewsletterProcessCampaignsModal;
use MicroweberPackages\Modules\Newsletter\Http\Livewire\Admin\NewsletterSubscribersList;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class NewsletterServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-newsletter');
        $package->hasViews('microweber-module-newsletter');
    }

    public function boot(): void
    {
        parent::boot();

        $this->loadViewsFrom((dirname(__DIR__)) . '/resources/views', 'microweber-module-newsletter');

        Livewire::component('admin-newsletter-subscribers-list', NewsletterSubscribersList::class);
        Livewire::component('admin-newsletter-choose-template-modal', NewsletterChooseTemplateModal::class);
        Livewire::component('admin-newsletter-process-campaigns-modal', NewsletterProcessCampaignsModal::class);
        Livewire::component('admin-newsletter-campaigns-log-modal', NewsletterCampaignsLogModal::class);
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
