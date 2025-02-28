<?php

namespace MicroweberPackages\Modules\Newsletter\Providers;

use BladeUI\Icons\Factory;
use Filament\Events\ServingFilament;
use Illuminate\Support\Facades\Event;
use Livewire\Livewire;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use Modules\Newsletter\Console\Commands\ProcessCampaigns;
use Modules\Newsletter\Console\Commands\ProcessCampaignsPerformanceTest;
use Modules\Newsletter\Filament\Admin\Pages\CreateTemplate;
use Modules\Newsletter\Filament\Admin\Pages\TemplateEditor;
use Modules\Newsletter\Livewire\Admin\Filament\NewsletterImportSubscribersActionButton;

use Modules\Newsletter\Livewire\UnsubscribePage;
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



//        Livewire::component('admin-newsletter-subscribers-list', NewsletterSubscribersList::class);
//        Livewire::component('admin-newsletter-choose-template-modal', NewsletterChooseTemplateModal::class);
//        Livewire::component('admin-newsletter-process-campaigns-modal', NewsletterProcessCampaignsModal::class);
//        Livewire::component('admin-newsletter-campaigns-log-modal', NewsletterCampaignsLogModal::class);
//        Livewire::component('admin-newsletter-import-subscribers-modal', NewsletterImportSubscribersModal::class);
//        Livewire::component('admin-newsletter-dashboard-stats', NewsletterDashboardStats::class);
//

//

       // ModuleAdmin::registerAdminUrl('newsletter', admin_url('newsletter'));




    }

    public function register(): void
    {
        parent::register();






        $this->loadRoutesFrom((dirname(__DIR__)) . '/routes/admin.php');
        $this->loadRoutesFrom((dirname(__DIR__)) . '/routes/web.php');


    //    Event::listen(ServingFilament::class, function () {
      //  });
     //   FilamentRegistry::registerPage(TemplateEditor::class,NewsletterFilamentAdminPanelProvider::class);
//        ModuleAdmin::registerFilamentPage(Homepage::class);
//        ModuleAdmin::registerFilamentPage(SenderAccounts::class);
//        ModuleAdmin::registerFilamentPage(Templates::class);
//        ModuleAdmin::registerFilamentPage(Subscribers::class);
//        ModuleAdmin::registerFilamentPage(Lists::class);
//        ModuleAdmin::registerFilamentPage(Campaigns::class);
  //      ModuleAdmin::registerFilamentPage(TemplateEditor::class);
//       // ModuleAdmin::registerFilamentPage(CreateTemplate::class);
//        ModuleAdmin::registerFilamentPage(CreateCampaign::class);
      //  ModuleAdmin::registerPanelResource(SenderAccountResource::class);


    }
}
