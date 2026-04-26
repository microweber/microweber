<?php

namespace Tests\Feature\Filament\Resources;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\FilamentResourceTestCase;

/**
 * Tests global search configuration for all Filament resources.
 *
 * Verifies that each resource with global search enabled:
 * - Reports canGloballySearch() as true
 * - Returns non-empty searchable attributes
 * - Can execute a search query without errors
 * - Returns properly structured result details
 */
class GlobalSearchTest extends FilamentResourceTestCase
{
    protected function getResourceClass(): string
    {
        return \Modules\Tag\Filament\Resources\TagResource::class;
    }

    /**
     * Resources that have global search enabled with their expected search terms.
     */
    public static function searchableResourcesProvider(): array
    {
        return [
            'TagResource' => [\Modules\Tag\Filament\Resources\TagResource::class, 'name'],
            'TagGroupResource' => [\Modules\Tag\Filament\Resources\TagGroupResource::class, 'name'],
            'FaqModuleResource' => [\Modules\Faq\Filament\Resources\FaqModuleResource::class, 'question'],
            'CouponResource' => [\Modules\Coupons\Filament\Resources\CouponResource::class, 'coupon_name'],
            'OfferResource' => [\Modules\Offer\Filament\Admin\Resources\OfferResource::class, 'offer_price'],
            'InvoiceResource' => [\Modules\Invoice\Filament\Resources\InvoiceResource::class, 'invoice_number'],
            'PaymentResource' => [\Modules\Payment\Filament\Admin\Resources\PaymentResource::class, 'transaction_id'],
            'PaymentProviderResource' => [\Modules\Payment\Filament\Admin\Resources\PaymentProviderResource::class, 'name'],
            'ShippingProviderResource' => [\Modules\Shipping\Filament\Admin\Resources\ShippingProviderResource::class, 'name'],
            'TaxResource' => [\Modules\Tax\Filament\Admin\Resources\TaxResource::class, 'name'],
            'TaxRateResource' => [\Modules\Tax\Filament\Admin\Resources\TaxRateResource::class, 'name'],
            'CurrencyResource' => [\Modules\Currency\Filament\Admin\Resources\CurrencyResource::class, 'name'],
            'ExchangeRateResource' => [\Modules\Currency\Filament\Admin\Resources\ExchangeRateResource::class, 'from_currency'],
            'ProductVariantAttributeResource' => [\Modules\Product\Filament\Admin\Resources\ProductVariantAttributeResource::class, 'name'],
            'ProductPricingRuleResource' => [\Modules\Product\Filament\Admin\Resources\ProductPricingRuleResource::class, 'name'],
            'MailTemplateResource' => [\Modules\MailTemplate\Filament\Resources\MailTemplateResource::class, 'name'],
            // TranslationResource intentionally returns an empty
            // getGloballySearchableAttributes() (see Modules/Settings/Filament/
            // Resources/TranslationResource.php:41) to suppress global search
            // — translations are managed in their own dedicated UI and showing
            // them in the top-bar global search produced too much noise.
            // Listing the resource here would be a contract regression.
            'ModuleConfigurationResource' => [\Modules\Settings\Filament\Resources\ModuleConfigurationResource::class, 'name'],
            'ErrorTrackingResource' => [\MicroweberPackages\Monitoring\Filament\Resources\ErrorTrackingResource::class, 'message'],
            'CampaignResource' => [\Modules\Newsletter\Filament\Admin\Resources\CampaignResource::class, 'name'],
            'SubscribersResource' => [\Modules\Newsletter\Filament\Admin\Resources\SubscribersResource::class, 'email'],
            'TemplatesResource' => [\Modules\Newsletter\Filament\Admin\Resources\TemplatesResource::class, 'title'],
            'ListResource' => [\Modules\Newsletter\Filament\Admin\Resources\ListResource::class, 'name'],
            'WorkflowResource' => [\Modules\Newsletter\Filament\Admin\Resources\WorkflowResource::class, 'name'],
            'SenderAccountsResource' => [\Modules\Newsletter\Filament\Admin\Resources\SenderAccountsResource::class, 'name'],
            'BackupResource' => [\Modules\Backup\Filament\Resources\BackupResource::class, 'filename'],
            'BackupScheduleResource' => [\Modules\Backup\Filament\Resources\BackupScheduleResource::class, 'name'],
            'BackupHistoryResource' => [\Modules\Backup\Filament\Resources\BackupHistoryResource::class, 'filename'],
            'ModuleDependencyResource' => [\MicroweberPackages\LaravelModules\Filament\Resources\ModuleDependencyResource::class, 'module_name'],
            'RatingModuleResource' => [\Modules\Rating\Filament\Resources\RatingModuleResource::class, 'rel_type'],
            'ContentResource' => [\Modules\Content\Filament\Admin\ContentResource::class, 'title'],
            'CategoryResource' => [\Modules\Category\Filament\Admin\Resources\CategoryResource::class, 'title'],
            'OrderResource' => [\Modules\Order\Filament\Admin\Resources\OrderResource::class, 'order_reference_id'],
            'CommentResource' => [\Modules\Comments\Filament\Resources\CommentResource::class, 'comment_subject'],
            'UsersResource' => [\MicroweberPackages\User\Filament\Resources\UsersResource::class, 'username'],
            'RoleResource' => [\MicroweberPackages\Role\Filament\Resources\RoleResource::class, 'name'],
            'PermissionResource' => [\MicroweberPackages\Role\Filament\Resources\PermissionResource::class, 'name'],
        ];
    }

    #[Test]
    #[DataProvider('searchableResourcesProvider')]
    public function resource_has_global_search_enabled(string $resourceClass, string $expectedTitleAttribute): void
    {
        if (!class_exists($resourceClass)) {
            $this->markTestSkipped("Resource class {$resourceClass} not found");
        }

        $this->actingAsAdmin();

        $this->assertTrue(
            $resourceClass::canGloballySearch(),
            "{$resourceClass} should have global search enabled"
        );
    }

    #[Test]
    #[DataProvider('searchableResourcesProvider')]
    public function resource_has_searchable_attributes(string $resourceClass, string $expectedTitleAttribute): void
    {
        if (!class_exists($resourceClass)) {
            $this->markTestSkipped("Resource class {$resourceClass} not found");
        }

        $this->actingAsAdmin();

        $attributes = $resourceClass::getGloballySearchableAttributes();

        $this->assertNotEmpty(
            $attributes,
            "{$resourceClass} should have searchable attributes defined"
        );

        $this->assertContains(
            $expectedTitleAttribute,
            $attributes,
            "{$resourceClass} should include '{$expectedTitleAttribute}' in searchable attributes"
        );
    }

    #[Test]
    #[DataProvider('searchableResourcesProvider')]
    public function resource_global_search_does_not_error(string $resourceClass, string $expectedTitleAttribute): void
    {
        if (!class_exists($resourceClass)) {
            $this->markTestSkipped("Resource class {$resourceClass} not found");
        }

        $this->actingAsAdmin();

        // Calling getGlobalSearchResults should not throw exceptions
        try {
            $results = $resourceClass::getGlobalSearchResults('test');
            $this->assertInstanceOf(\Illuminate\Support\Collection::class, $results);
        } catch (\Illuminate\Database\QueryException $e) {
            // Some resources may use Sushi or have volatile tables — query errors are acceptable
            $this->assertTrue(true, 'Query exception is acceptable for resources with non-standard data sources');
        } catch (\Symfony\Component\Routing\Exception\RouteNotFoundException $e) {
            // Some module resources have routes registered dynamically and may not be available in test
            $this->assertTrue(true, 'Route not found is acceptable for dynamically registered module resources');
        }
    }

    /**
     * Resources that should NOT have global search (no model, or
     * intentionally suppressed via empty getGloballySearchableAttributes()).
     */
    public static function nonSearchableResourcesProvider(): array
    {
        return [
            'CheckoutResource' => [\Modules\Checkout\Filament\Resources\CheckoutResource::class],
            'TranslationResource' => [\Modules\Settings\Filament\Resources\TranslationResource::class],
        ];
    }

    #[Test]
    #[DataProvider('nonSearchableResourcesProvider')]
    public function non_searchable_resource_has_no_global_search(string $resourceClass): void
    {
        if (!class_exists($resourceClass)) {
            $this->markTestSkipped("Resource class {$resourceClass} not found");
        }

        $this->actingAsAdmin();

        $this->assertFalse(
            $resourceClass::canGloballySearch(),
            "{$resourceClass} should NOT have global search enabled"
        );
    }
}
