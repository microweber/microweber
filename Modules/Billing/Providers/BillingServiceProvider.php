<?php

namespace Modules\Billing\Providers;

use MicroweberPackages\AiTools\Support\RegistersAiTools;
use Modules\Billing\Tools\BillingAccountStatusTool;
use Modules\Billing\Tools\BillingInvoiceCustomerHistoryTool;
use Modules\Billing\Tools\BillingInvoiceDetailTool;
use Modules\Billing\Tools\BillingInvoiceLookupTool;
use Modules\Billing\Tools\BillingInvoiceUnpaidSummaryTool;
use Modules\Billing\Tools\BillingMetricsSummaryTool;
use Modules\Billing\Tools\BillingPaymentDetailTool;
use Modules\Billing\Tools\BillingPaymentLookupTool;
use Modules\Billing\Tools\BillingPaymentProviderHealthTool;
use Modules\Billing\Tools\BillingPaymentWebhookHealthTool;
use Modules\Billing\Tools\BillingPlanSummaryTool;
use Modules\Billing\Tools\BillingSubscriptionLookupTool;

use Livewire\Livewire;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use Modules\Billing\Console\Commands\AutoActivateFreeTrial;
use Modules\Billing\Http\Livewire\Admin\SubscriptionPlanEditModal;
use Modules\Billing\Http\Livewire\Admin\SubscriptionPlanGroupEditModal;
use Modules\Billing\Http\Livewire\Admin\SubscriptionPlans;
use Modules\Billing\Http\Livewire\Admin\Subscriptions;
use Modules\Billing\Http\Livewire\Admin\Users;
use Modules\Billing\Http\Livewire\Admin\UserSubscriptionEditModal;


class BillingServiceProvider extends BaseModuleServiceProvider
{
    use RegistersAiTools;

    protected string $moduleName = 'Billing';

    protected string $moduleNameLower = 'billing';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        $this->registerAiTools([
            BillingAccountStatusTool::class,
            BillingInvoiceCustomerHistoryTool::class,
            BillingInvoiceDetailTool::class,
            BillingInvoiceLookupTool::class,
            BillingInvoiceUnpaidSummaryTool::class,
            BillingMetricsSummaryTool::class,
            BillingPaymentDetailTool::class,
            BillingPaymentLookupTool::class,
            BillingPaymentProviderHealthTool::class,
            BillingPaymentWebhookHealthTool::class,
            BillingPlanSummaryTool::class,
            BillingSubscriptionLookupTool::class,
        ]);

        // Navigation items are registered in BillingFilamentAdminPanelProvider::panel()
        // via ->navigationItems() method. Do not use Filament::serving() as it's deprecated in v5.
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        parent::register();

        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/web.php'));
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/admin.php'));
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/api.php'));
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/webhooks.php'));

        // Register Billing Services
        $this->app->singleton(\Modules\Billing\Services\StripeService::class, function ($app) {
            return new \Modules\Billing\Services\StripeService();
        });
        $this->app->singleton(\Modules\Billing\Services\SubscriptionManager::class, function ($app) {
            return new \Modules\Billing\Services\SubscriptionManager();
        });
        $this->app->singleton(\Modules\Billing\Services\UserDemoActivate::class, function ($app) {
            return new \Modules\Billing\Services\UserDemoActivate();
        });



        Livewire::component('billing::settings', \Modules\Billing\Http\Livewire\Admin\Settings::class);
        Livewire::component('billing::users', Users::class);
        Livewire::component('billing::user-subscription-edit-modal', UserSubscriptionEditModal::class);
        Livewire::component('billing::subscriptions', Subscriptions::class);
        Livewire::component('billing::subscription-plans', SubscriptionPlans::class);
        // SubscriptionPlanGroups livewire component is not implemented; route redirects instead.
        Livewire::component('billing::subscription-plan-edit-modal', SubscriptionPlanEditModal::class);
        Livewire::component('billing::subscription-plan-group-edit-modal', SubscriptionPlanGroupEditModal::class);
        $this->app->register(BillingEventServiceProvider::class);
        $this->app->register(BillingCashierServiceProvider::class);

        $this->app->register(BillingFilamentAdminPanelProvider::class);
        $this->app->register(BillingFilamentFrontendPanelProvider::class);
        $this->commands([
            AutoActivateFreeTrial::class,
        ]);


      //  FilamentRegistry::registerPage(\Modules\Billing\Filament\Fronetend\Pages\UserSubscriptionPanel::class);

        // Register filament page for Microweber module settings
//         FilamentRegistry::registerPage(Settings::class);
//         FilamentRegistry::registerResource(SubscriptionPlanResource::class);
//         FilamentRegistry::registerResource(SubscriptionPlanGroupsResource::class);
//         FilamentRegistry::registerResource(BillingUserResource::class);

    }

}
