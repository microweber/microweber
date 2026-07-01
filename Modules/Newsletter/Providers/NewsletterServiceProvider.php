<?php

namespace Modules\Newsletter\Providers;

use BladeUI\Icons\Factory;
use Filament\Events\ServingFilament;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\FilamentRegistry\Facades\FilamentRegistry;
use MicroweberPackages\Microweber\Facades\Microweber;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use Modules\Newsletter\Filament\NewsletterModuleSettings;
use Modules\Newsletter\Livewire\Admin\Filament\NewsletterImportSubscribersActionButton;
use Modules\Newsletter\Livewire\Admin\NewsletterDashboardStats;
use Modules\Newsletter\Livewire\Admin\NewsletterSubscribersList;
use Modules\Newsletter\Livewire\UnsubscribePage;
use Modules\Newsletter\Console\Commands\ProcessCampaigns;
use Modules\Newsletter\Console\Commands\ProcessCampaignsPerformanceTest;
use Modules\Newsletter\Console\Commands\ProcessAbandonedCarts;
use Modules\Newsletter\Console\Commands\ProcessAutomationQueue;
use Modules\Newsletter\Console\Commands\SeedDemoData;
use Modules\Newsletter\Listeners\NewsletterAutomationSubscriber;
class NewsletterServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Newsletter';

    protected string $moduleNameLower = 'newsletter';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {


    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/web.php'));
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/admin.php'));
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/api.php'));
        $this->app->register(NewsletterFilamentAdminPanelProvider::class);

        // Register Microweber Icons set
        if(is_dir(realpath(__DIR__ . '/../resources/svg'))) {
            $this->callAfterResolving(Factory::class, function (Factory $factory) {
                $factory->add('newsletter', [
                    'path' => realpath(__DIR__ . '/../resources/svg'),
                    'prefix' => 'newsletter',
                ]);
            });
        }
        $this->loadViewsFrom((dirname(__DIR__)) . '/resources/views', 'microweber-module-newsletter');
        Event::listen(ServingFilament::class, function () {
            Livewire::component('admin-newsletter-import-subscribers-action-button', NewsletterImportSubscribersActionButton::class);
            ModuleAdmin::registerAdminUrl('newsletter', route('filament.admin-newsletter.pages.homepage'));
        });
        Livewire::component('admin-newsletter-dashboard-stats', NewsletterDashboardStats::class);
        Livewire::component('admin-newsletter-subscribers-list', NewsletterSubscribersList::class);
        Livewire::component('newsletter-unsubscribe-page', UnsubscribePage::class);


        // Register filament page for Microweber module settings
         FilamentRegistry::registerPage(NewsletterModuleSettings::class);

        // Register Microweber module
        Microweber::module(\Modules\Newsletter\Microweber\NewsletterModule::class);
        $this->commands(ProcessCampaigns::class);
        $this->commands(ProcessAbandonedCarts::class);
        $this->commands(ProcessAutomationQueue::class);
        $this->commands(SeedDemoData::class);

        // Register event subscriber for automated campaigns
        Event::subscribe(NewsletterAutomationSubscriber::class);

        // Schedule campaign processing
        Schedule::command('newsletter:process-campaigns')
            ->everyMinute()
            ->withoutOverlapping();

        // Schedule abandoned cart processing (every 15 minutes)
        Schedule::command('newsletter:process-abandoned-carts')
            ->everyFifteenMinutes()
            ->withoutOverlapping();

        // Schedule automation queue processing (every minute)
        Schedule::command('newsletter:process-automation-queue')
            ->everyMinute()
            ->withoutOverlapping();

        if (is_cli()) {
            $this->commands(ProcessCampaignsPerformanceTest::class);
        }
    }

}
