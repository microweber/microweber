<?php

namespace MicroweberPackages\Modules\Newsletter\Providers;

use BladeUI\Icons\Factory;
use Filament\Events\ServingFilament;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use MicroweberPackages\Modules\Newsletter\Console\Commands\ProcessCampaigns;
use MicroweberPackages\Modules\Newsletter\Filament\Admin\Pages\Homepage;
use MicroweberPackages\Modules\Newsletter\Filament\Admin\Pages\SenderAccounts;
use MicroweberPackages\Modules\Newsletter\Filament\Admin\Resources\SenderAccountResource;
use MicroweberPackages\Modules\Newsletter\Http\Livewire\Admin\Filament\CampaignsList;
use MicroweberPackages\Modules\Newsletter\Http\Livewire\Admin\NewsletterCampaignsLogModal;
use MicroweberPackages\Modules\Newsletter\Http\Livewire\Admin\NewsletterChooseTemplateModal;
use MicroweberPackages\Modules\Newsletter\Http\Livewire\Admin\NewsletterDashboardStats;
use MicroweberPackages\Modules\Newsletter\Http\Livewire\Admin\NewsletterImportSubscribersModal;
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

//        Livewire::component('admin-newsletter-subscribers-list', NewsletterSubscribersList::class);
//        Livewire::component('admin-newsletter-choose-template-modal', NewsletterChooseTemplateModal::class);
//        Livewire::component('admin-newsletter-process-campaigns-modal', NewsletterProcessCampaignsModal::class);
//        Livewire::component('admin-newsletter-campaigns-log-modal', NewsletterCampaignsLogModal::class);
//        Livewire::component('admin-newsletter-import-subscribers-modal', NewsletterImportSubscribersModal::class);
//        Livewire::component('admin-newsletter-dashboard-stats', NewsletterDashboardStats::class);
//

        Livewire::component('admin-newsletter-campaign-list', CampaignsList::class);

        Event::listen(ServingFilament::class, function () {
            ModuleAdmin::registerAdminUrl('newsletter', route('filament.admin.pages.newsletter.homepage'));
        });


    }

    public function register(): void
    {
        parent::register();


        // Register Microweber Icons set
        $this->callAfterResolving(Factory::class, function (Factory $factory) {
            $factory->add('newsletter', [
                'path' => realpath(__DIR__ . '/../resources/svg'),
                'prefix' => 'newsletter',
            ]);
        });

        $this->loadRoutesFrom((dirname(__DIR__)) . '/routes/admin.php');
        $this->loadRoutesFrom((dirname(__DIR__)) . '/routes/web.php');
        ModuleAdmin::registerPanelPage(Homepage::class);
        ModuleAdmin::registerPanelPage(SenderAccounts::class);
      //  ModuleAdmin::registerPanelResource(SenderAccountResource::class);

        if (is_cli()) {
            $this->commands(ProcessCampaigns::class);
        }
    }
}
