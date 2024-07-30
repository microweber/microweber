<?php

namespace MicroweberPackages\Modules\Newsletter\Providers;

use BladeUI\Icons\Factory;
use Filament\Events\ServingFilament;
use Illuminate\Support\Facades\Event;
use Livewire\Livewire;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use MicroweberPackages\Modules\Newsletter\Console\Commands\ProcessCampaigns;
use MicroweberPackages\Modules\Newsletter\Filament\Admin\Pages\CreateTemplate;
use MicroweberPackages\Modules\Newsletter\Filament\Admin\Pages\TemplateEditor;
use MicroweberPackages\Modules\Newsletter\Http\Livewire\Admin\Filament\NewsletterImportSubscribersActionButton;

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

//

       // ModuleAdmin::registerAdminUrl('newsletter', admin_url('newsletter'));


         Event::listen(ServingFilament::class, function () {
            Livewire::component('admin-newsletter-import-subscribers-action-button', NewsletterImportSubscribersActionButton::class);
             ModuleAdmin::registerAdminUrl('newsletter', route('filament.admin-newsletter.pages.homepage'));
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

        $this->app->register(NewsletterFilamentAdminPanelProvider::class);



//        ModuleAdmin::registerFilamentPage(Homepage::class);
//        ModuleAdmin::registerFilamentPage(SenderAccounts::class);
//        ModuleAdmin::registerFilamentPage(Templates::class);
//        ModuleAdmin::registerFilamentPage(Subscribers::class);
//        ModuleAdmin::registerFilamentPage(Lists::class);
//        ModuleAdmin::registerFilamentPage(Campaigns::class);
        ModuleAdmin::registerFilamentPage(TemplateEditor::class);
//       // ModuleAdmin::registerFilamentPage(CreateTemplate::class);
//        ModuleAdmin::registerFilamentPage(CreateCampaign::class);
      //  ModuleAdmin::registerPanelResource(SenderAccountResource::class);

        if (is_cli()) {
            $this->commands(ProcessCampaigns::class);
        }
    }
}
