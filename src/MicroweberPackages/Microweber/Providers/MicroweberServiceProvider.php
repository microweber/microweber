<?php
/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber CMS LTD
 *
 * For full license information see
 * https://github.com/microweber/microweber/blob/master/LICENSE
 *
 */

namespace MicroweberPackages\Microweber\Providers;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\Admin\Providers\AdminServiceProvider;
use MicroweberPackages\Event\EventManagerServiceProvider;
use MicroweberPackages\FormBuilder\Providers\FormBuilderServiceProvider;
use MicroweberPackages\Fortify\FortifyServiceProvider;
use MicroweberPackages\Frontend\Providers\FrontendServiceProvider;
use MicroweberPackages\Helper\HelpersServiceProvider;
use MicroweberPackages\Install\InstallServiceProvider;
use MicroweberPackages\LaravelModules\LaravelModulesServiceProvider;
use MicroweberPackages\LaravelModulesFilament\LaravelModulesFilamentServiceProvider;
use MicroweberPackages\LaravelModulesLivewire\LaravelModulesLivewireServiceProvider;
use MicroweberPackages\LaravelTemplates\LaravelTemplatesServiceProvider;
use MicroweberPackages\LiveEdit\Providers\LiveEditRouteServiceProvider;
use MicroweberPackages\LiveEdit\Providers\LiveEditServiceProvider;
use MicroweberPackages\Livewire\LivewireServiceProvider;
use MicroweberPackages\MetaTags\Providers\MetaTagsServiceProvider;
use MicroweberPackages\Microweber\Repositories\MicroweberRepository;
use MicroweberPackages\MicroweberUI\Providers\MicroweberUIServiceProvider;
use MicroweberPackages\Module\ModuleServiceProvider;
use MicroweberPackages\Multilanguage\MultilanguageServiceProvider;
use MicroweberPackages\Notification\Providers\NotificationServiceProvider;
use MicroweberPackages\Option\Providers\OptionServiceProvider;
use MicroweberPackages\Pagination\PaginationServiceProvider;
use MicroweberPackages\Queue\Providers\QueueEventServiceProvider;
use MicroweberPackages\Queue\Providers\QueueServiceProvider;
use MicroweberPackages\Repository\Providers\RepositoryEventServiceProvider;
use MicroweberPackages\Repository\Providers\RepositoryServiceProvider;
use MicroweberPackages\Role\RoleServiceProvider;

use MicroweberPackages\Template\TemplateEventServiceProvider;
use MicroweberPackages\Template\TemplateManagerServiceProvider;
use MicroweberPackages\Translation\Providers\TranslationServiceProvider;
use MicroweberPackages\Update\Providers\UpdateMigratorServiceProvider;
use MicroweberPackages\User\Providers\UserEventServiceProvider;
use MicroweberPackages\User\Providers\UserServiceProvider;

use MicroweberPackages\View\ViewServiceProvider;
use Spatie\Permission\PermissionServiceProvider;


//use MicroweberPackages\Customer\Providers\CustomerServiceProvider;

//use Modules\Shipping\Providers\ShippingManagerServiceProvider;


class MicroweberServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'microweber');

        /**
         * @property \MicroweberPackages\Microweber\Repositories\MicroweberRepository $microweber
         */
        $this->app->singleton('microweber', function () {
            return new MicroweberRepository();
        });

        $this->registerInternalProviders();

    }

    public function registerInternalProviders(): void
    {


        $this->app->register(\MicroweberPackages\Database\DatabaseManagerServiceProvider::class);
        $this->app->register(HelpersServiceProvider::class);
        $this->app->register(LiveEditRouteServiceProvider::class);

        $this->app->register(ViewServiceProvider::class);
        $this->app->register(MicroweberUIServiceProvider::class);
//        $this->app->register(BladeUIServiceProvider::class);
        $this->app->register(TemplateManagerServiceProvider::class);


        $this->app->register(FortifyServiceProvider::class);
        $this->app->register(UserServiceProvider::class);
        $this->app->register(OptionServiceProvider::class);

        $this->app->register(InstallServiceProvider::class);
        $this->app->register(AdminServiceProvider::class);


        $this->app->register(RepositoryServiceProvider::class);
        $this->app->register(RepositoryEventServiceProvider::class);
       // $this->app->register(MediaManagerServiceProvider::class);
        //$this->app->register(DebugbarServiceProvider::class);

        $this->app->register(PaginationServiceProvider::class);


        //   $this->app->register(TaggableFileCacheServiceProvider::class);
        //$this->app->register(AlternativeCacheStoresServiceProvider::class);

        // $this->app->register(AssetsServiceProvider::class);
        $this->app->register(TranslationServiceProvider::class);
     //   $this->app->register(TagsManagerServiceProvider::class);
        $this->app->register(LivewireServiceProvider::class);


        //$this->app->register('Conner\Tagging\Providers\TaggingServiceProvider');
        $this->app->register(EventManagerServiceProvider::class);
        $this->app->register(ModuleServiceProvider::class);

       // $this->app->register(PageServiceProvider::class);
//        $this->app->register(ContentServiceProvider::class);
//        $this->app->register(ContentManagerServiceProvider::class);
      //$this->app->register(CategoryServiceProvider::class);
//        $this->app->register(CategoryEventServiceProvider::class);
        //$this->app->register(MenuServiceProvider::class);
       //  $this->app->register(ProductServiceProvider::class);
        //$this->app->register(PostServiceProvider::class);
        //$this->app->register(ContentDataServiceProvider::class);
        //$this->app->register(ContentDataVariantServiceProvider::class);
        //$this->app->register(ContentDataEventServiceProvider::class);
      //  $this->app->register(ContentFieldServiceProvider::class);
        //$this->app->register(CustomFieldServiceProvider::class);
        //$this->app->register(CustomFieldEventServiceProvider::class);
        $this->app->register(TemplateEventServiceProvider::class);

        //$this->app->register(DatabaseManagerServiceProvider::class);
        $this->app->register(MetaTagsServiceProvider::class);

        // Shop
       // $this->app->register(ShopManagerServiceProvider::class);
      //  $this->app->register(TaxManagerServiceProvider::class);
      //  $this->app->register(PaymentManagerServiceProvider::class);
//        $this->app->register(OrderServiceProvider::class);
//        $this->app->register(OrderEventServiceProvider::class);
      //  $this->app->register(CurrencyServiceProvider::class);
      //  $this->app->register(CheckoutManagerServiceProvider::class);
       // $this->app->register(CartManagerServiceProvider::class);
        //$this->app->register(ShippingManagerServiceProvider::class);
        //$this->app->register(OfferServiceProvider::class);
       // $this->app->register(FileManagerServiceProvider::class);
      //  $this->app->register(FormServiceProvider::class);
        $this->app->register(FormBuilderServiceProvider::class);
        $this->app->register(UserEventServiceProvider::class);
    //    $this->app->register(CartEventServiceProvider::class);


        // Others
       // $this->app->register(MarketplaceServiceProvider::class);
        //$this->app->register(CaptchaServiceProvider::class);
        //$this->app->register(CaptchaEventServiceProvider::class);
        //.$this->app->register(BackupServiceProvider::class);
        //  $this->app->register(ImportServiceProvider::class);
      //  $this->app->register(CustomerServiceProvider::class);
       // $this->app->register(\Modules\Customer\Providers\CustomerEventServiceProvider::class);
        $this->app->register(PermissionServiceProvider::class);
        //$this->app->register(PaymentServiceProvider::class);
    //    $this->app->register(RoleServiceProvider::class);
        $this->app->register(\Barryvdh\DomPDF\ServiceProvider::class);
        //   $this->app->register(  \L5Swagger\L5SwaggerServiceProvider::class);
      //  $this->app->register(SwaggerServiceProvider::class);
        //   $this->app->register(  \Laravel\Sanctum\SanctumServiceProvider::class);
       // $this->app->register(CountryServiceProvider::class);
        // $this->app->register(\EloquentFilter\ServiceProvider::class);
     //   $this->app->register(MailTemplatesServiceProvider::class);
        $this->app->register(NotificationServiceProvider::class);
        $this->app->register(QueueServiceProvider::class);
        $this->app->register(QueueEventServiceProvider::class);
       // $this->app->register(ContentFilterServiceProvider::class);
     //   $this->app->register(BlogServiceProvider::class);

        $this->app->register(MultilanguageServiceProvider::class);
        $this->app->register(LiveEditServiceProvider::class);
      //   Debugbar::startMeasure('modules_load_service_providers', 'Loading modules');

        $this->app->register(LaravelModulesServiceProvider::class);

//        $this->app->register(LaravelModulesLivewireServiceProvider::class);



        if (is_cli()) {
            $this->app->register(LaravelModulesFilamentServiceProvider::class);
        }


        $this->app->register(LaravelTemplatesServiceProvider::class);

       //  Debugbar::stopMeasure('modules_load_service_providers');

        $this->app->register(UpdateMigratorServiceProvider::class);


            // $this->app->register(FilamentLiveEditPanelProvider::class);

  //      $this->app->register(FilamentAdminPanelProvider::class);

    }


    public function boot()
    {

        // FrontendServiceProvider must be loaded after all other providers on boot as it has catch all routes
        $this->app->register(FrontendServiceProvider::class);

    }
}
