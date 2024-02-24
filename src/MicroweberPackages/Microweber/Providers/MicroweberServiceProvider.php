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
use MicroweberPackages\Backup\Providers\BackupServiceProvider;
use MicroweberPackages\BladeUI\Providers\BladeUIServiceProvider;
use MicroweberPackages\Blog\BlogServiceProvider;
use MicroweberPackages\Cart\CartManagerServiceProvider;
use MicroweberPackages\Cart\Providers\CartEventServiceProvider;
use MicroweberPackages\Category\Providers\CategoryEventServiceProvider;
use MicroweberPackages\Category\Providers\CategoryServiceProvider;
use MicroweberPackages\Checkout\CheckoutManagerServiceProvider;
use MicroweberPackages\Content\ContentManagerServiceProvider;
use MicroweberPackages\Content\ContentServiceProvider;
use MicroweberPackages\ContentData\Providers\ContentDataEventServiceProvider;
use MicroweberPackages\ContentData\Providers\ContentDataServiceProvider;
use MicroweberPackages\ContentDataVariant\Providers\ContentDataVariantServiceProvider;
use MicroweberPackages\ContentField\Providers\ContentFieldServiceProvider;
use MicroweberPackages\ContentFilter\Providers\ContentFilterServiceProvider;
use MicroweberPackages\Country\CountryServiceProvider;
use MicroweberPackages\Currency\CurrencyServiceProvider;
use MicroweberPackages\Customer\Providers\CustomerEventServiceProvider;
use MicroweberPackages\Customer\Providers\CustomerServiceProvider;
use MicroweberPackages\CustomField\Providers\CustomFieldEventServiceProvider;
use MicroweberPackages\CustomField\Providers\CustomFieldServiceProvider;
use MicroweberPackages\Database\DatabaseManagerServiceProvider;
use MicroweberPackages\Event\EventManagerServiceProvider;
use MicroweberPackages\FileManager\FileManagerServiceProvider;
use MicroweberPackages\Form\Providers\FormServiceProvider;
use MicroweberPackages\FormBuilder\Providers\FormBuilderServiceProvider;
use MicroweberPackages\Fortify\FortifyServiceProvider;
use MicroweberPackages\Helper\HelpersServiceProvider;
use MicroweberPackages\Install\InstallServiceProvider;
use MicroweberPackages\LiveEdit\Providers\LiveEditRouteServiceProvider;
use MicroweberPackages\LiveEdit\Providers\LiveEditServiceProvider;
use MicroweberPackages\Livewire\LivewireServiceProvider;
use MicroweberPackages\Marketplace\MarketplaceServiceProvider;
use MicroweberPackages\Media\MediaManagerServiceProvider;
use MicroweberPackages\Menu\Providers\MenuEventServiceProvider;
use MicroweberPackages\Menu\Providers\MenuServiceProvider;
use MicroweberPackages\MetaTags\Providers\MetaTagsServiceProvider;
use MicroweberPackages\Microweber\Microweber;
use MicroweberPackages\MicroweberUI\Providers\MicroweberUIServiceProvider;
use MicroweberPackages\Module\ModuleServiceProvider;
use MicroweberPackages\Multilanguage\MultilanguageServiceProvider;
use MicroweberPackages\Notification\Providers\NotificationServiceProvider;
use MicroweberPackages\Offer\Providers\OfferServiceProvider;
use MicroweberPackages\OpenApi\Providers\SwaggerServiceProvider;
use MicroweberPackages\Option\Providers\OptionServiceProvider;
use MicroweberPackages\Order\Providers\OrderEventServiceProvider;
use MicroweberPackages\Order\Providers\OrderServiceProvider;
use MicroweberPackages\Page\PageServiceProvider;
use MicroweberPackages\Payment\PaymentManagerServiceProvider;
use MicroweberPackages\Post\PostServiceProvider;
use MicroweberPackages\Product\ProductServiceProvider;
use MicroweberPackages\Queue\Providers\QueueEventServiceProvider;
use MicroweberPackages\Queue\Providers\QueueServiceProvider;
use MicroweberPackages\Repository\Providers\RepositoryEventServiceProvider;
use MicroweberPackages\Repository\Providers\RepositoryServiceProvider;
use MicroweberPackages\Role\RoleServiceProvider;
use MicroweberPackages\Shipping\ShippingManagerServiceProvider;
use MicroweberPackages\Shop\ShopManagerServiceProvider;
use MicroweberPackages\Tag\TagsManagerServiceProvider;
use MicroweberPackages\Tax\TaxManagerServiceProvider;
use MicroweberPackages\Template\TemplateEventServiceProvider;
use MicroweberPackages\Template\TemplateManagerServiceProvider;
use MicroweberPackages\Translation\Providers\TranslationServiceProvider;
use MicroweberPackages\User\Providers\UserEventServiceProvider;
use MicroweberPackages\User\Providers\UserServiceProvider;
use MicroweberPackages\Utils\Captcha\Providers\CaptchaEventServiceProvider;
use MicroweberPackages\Utils\Captcha\Providers\CaptchaServiceProvider;
use MicroweberPackages\View\ViewServiceProvider;
use Spatie\Permission\PermissionServiceProvider;

class MicroweberServiceProvider extends ServiceProvider
{

    public function register(): void
    {


        /**
         * @property Microweber $microweber
         */
        $this->app->singleton('microweber', function () {
            return new Microweber();
        });

        $this->registerInternalProviders();

    }

    public function registerInternalProviders(): void
    {


        $this->app->register(HelpersServiceProvider::class);
        $this->app->register(LiveEditRouteServiceProvider::class);

        $this->app->register(ViewServiceProvider::class);
        $this->app->register(MicroweberUIServiceProvider::class);
        $this->app->register(BladeUIServiceProvider::class);
        $this->app->register(LivewireServiceProvider::class);


        $this->app->register(FortifyServiceProvider::class);
        $this->app->register(UserServiceProvider::class);
        $this->app->register(OptionServiceProvider::class);

        $this->app->register(InstallServiceProvider::class);

        $this->app->register(AdminServiceProvider::class);


        $this->app->register(LiveEditServiceProvider::class);


        $this->app->register(RepositoryServiceProvider::class);
        $this->app->register(RepositoryEventServiceProvider::class);
        $this->app->register(MediaManagerServiceProvider::class);
        //$this->app->register(DebugbarServiceProvider::class);


        //   $this->app->register(TaggableFileCacheServiceProvider::class);
        //$this->app->register(AlternativeCacheStoresServiceProvider::class);

        // $this->app->register(AssetsServiceProvider::class);
        $this->app->register(TranslationServiceProvider::class);
        $this->app->register(TagsManagerServiceProvider::class);


        $this->app->register('Conner\Tagging\Providers\TaggingServiceProvider');
        $this->app->register(EventManagerServiceProvider::class);
        $this->app->register(PageServiceProvider::class);
        $this->app->register(ContentServiceProvider::class);
        $this->app->register(ContentManagerServiceProvider::class);
        $this->app->register(CategoryServiceProvider::class);
        $this->app->register(CategoryEventServiceProvider::class);
        $this->app->register(MenuServiceProvider::class);
        $this->app->register(MenuEventServiceProvider::class);
        $this->app->register(ProductServiceProvider::class);
        $this->app->register(PostServiceProvider::class);
        $this->app->register(ContentDataServiceProvider::class);
        $this->app->register(ContentDataVariantServiceProvider::class);
        $this->app->register(ContentDataEventServiceProvider::class);
        $this->app->register(ContentFieldServiceProvider::class);
        $this->app->register(CustomFieldServiceProvider::class);
        $this->app->register(CustomFieldEventServiceProvider::class);
        $this->app->register(TemplateEventServiceProvider::class);

        $this->app->register(TemplateManagerServiceProvider::class);
        $this->app->register(DatabaseManagerServiceProvider::class);
        $this->app->register(MetaTagsServiceProvider::class);

        // Shop
        $this->app->register(ShopManagerServiceProvider::class);
        $this->app->register(TaxManagerServiceProvider::class);
        $this->app->register(PaymentManagerServiceProvider::class);
        $this->app->register(OrderServiceProvider::class);
        $this->app->register(OrderEventServiceProvider::class);
        $this->app->register(CurrencyServiceProvider::class);
        $this->app->register(CheckoutManagerServiceProvider::class);
        $this->app->register(CartManagerServiceProvider::class);
        $this->app->register(ShippingManagerServiceProvider::class);
        $this->app->register(OfferServiceProvider::class);
        $this->app->register(FileManagerServiceProvider::class);
        $this->app->register(FormServiceProvider::class);
        $this->app->register(FormBuilderServiceProvider::class);
        $this->app->register(UserEventServiceProvider::class);
        $this->app->register(CartEventServiceProvider::class);

        // Others
        $this->app->register(MarketplaceServiceProvider::class);
        $this->app->register(CaptchaServiceProvider::class);
        $this->app->register(CaptchaEventServiceProvider::class);
        $this->app->register(BackupServiceProvider::class);
        //  $this->app->register(ImportServiceProvider::class);
        $this->app->register(CustomerServiceProvider::class);
        $this->app->register(CustomerEventServiceProvider::class);
        $this->app->register(PermissionServiceProvider::class);
        //$this->app->register(PaymentServiceProvider::class);
        $this->app->register(RoleServiceProvider::class);
        $this->app->register(\Barryvdh\DomPDF\ServiceProvider::class);
        //   $this->app->register(  \L5Swagger\L5SwaggerServiceProvider::class);
        $this->app->register(SwaggerServiceProvider::class);
        //   $this->app->register(  \Laravel\Sanctum\SanctumServiceProvider::class);
        $this->app->register(CountryServiceProvider::class);
        $this->app->register(\EloquentFilter\ServiceProvider::class);
        $this->app->register(NotificationServiceProvider::class);
        $this->app->register(QueueServiceProvider::class);
        $this->app->register(QueueEventServiceProvider::class);
        $this->app->register(ContentFilterServiceProvider::class);
        $this->app->register(BlogServiceProvider::class);

        $this->app->register(MultilanguageServiceProvider::class);
        $this->app->register(ModuleServiceProvider::class);
    }
}
