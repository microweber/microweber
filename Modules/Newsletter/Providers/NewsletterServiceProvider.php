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
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Microweber\Facades\Microweber;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use Modules\Newsletter\Filament\NewsletterModuleSettings;
use Modules\Newsletter\Livewire\Admin\Filament\NewsletterImportSubscribersActionButton;
use Modules\Newsletter\Livewire\UnsubscribePage;
use Modules\Newsletter\Console\Commands\ProcessCampaigns;
use Modules\Newsletter\Console\Commands\ProcessCampaignsPerformanceTest;
use Modules\Newsletter\Filament\Admin\Pages\TemplateEditor;


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
        $this->app->register(NewsletterFilamentAdminPanelProvider::class);
        FilamentRegistry::registerPage(TemplateEditor::class);

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
        Livewire::component('newsletter-unsubscribe-page', UnsubscribePage::class);


        // Register filament page for Microweber module settings
         FilamentRegistry::registerPage(NewsletterModuleSettings::class);

        // Register Microweber module
        Microweber::module(\Modules\Newsletter\Microweber\NewsletterModule::class);
        $this->commands(ProcessCampaigns::class);

        Schedule::command('newsletter:process-campaigns')
            ->everyMinute()
            ->withoutOverlapping();




        if (is_cli()) {

            $this->commands(ProcessCampaignsPerformanceTest::class);
        }
    }

}
