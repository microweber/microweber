<?php

namespace Modules\Content\Tests\Feature;

use Illuminate\Support\Facades\Event;
use Modules\Cart\Models\Cart;
use Modules\Category\Models\Category;
use Modules\Company\Models\Company;
use Modules\Content\Models\Content;
use Modules\Customer\Models\Customer;
use Modules\Invoice\Models\Invoice;
use Modules\Product\Models\Product;
use PHPUnit\Framework\Attributes\Test;
use ReflectionClass;
use Tests\TestCase;

/**
 * Pins the model → lifecycle-event wiring added so other modules can react to
 * create/update/delete on the core commerce + content models.
 */
class ModelLifecycleEventsTest extends TestCase
{
    public static function modelEventMapProvider(): array
    {
        return [
            // core commerce + content
            'Content'  => [Content::class,  'Modules\\Content\\Events\\ContentWas'],
            'Product'  => [Product::class,  'Modules\\Product\\Events\\ProductWas'],
            'Customer' => [Customer::class, 'Modules\\Customer\\Events\\CustomerWas'],
            'Cart'     => [Cart::class,     'Modules\\Cart\\Events\\CartWas'],
            'Category' => [Category::class, 'Modules\\Category\\Events\\CategoryWas'],
            'Invoice'  => [Invoice::class,  'Modules\\Invoice\\Events\\InvoiceWas'],
            // stock / inventory
            'ProductInventoryMovement'  => [\Modules\Product\Models\ProductInventoryMovement::class,  'Modules\\Product\\Events\\ProductInventoryMovementWas'],
            'ProductStockReservation'   => [\Modules\Product\Models\ProductStockReservation::class,   'Modules\\Product\\Events\\ProductStockReservationWas'],
            'ProductVariantCombination' => [\Modules\Product\Models\ProductVariantCombination::class, 'Modules\\Product\\Events\\ProductVariantCombinationWas'],
            'ProductInventoryAlert'     => [\Modules\Product\Models\ProductInventoryAlert::class,     'Modules\\Product\\Events\\ProductInventoryAlertWas'],
            // other domain entities
            'Menu'             => [\Modules\Menu\Models\Menu::class,                 'Modules\\Menu\\Events\\MenuWas'],
            'MenuItem'         => [\Modules\Menu\Models\MenuItem::class,             'Modules\\Menu\\Events\\MenuItemWas'],
            'Media'            => [\Modules\Media\Models\Media::class,               'Modules\\Media\\Events\\MediaWas'],
            'ShippingProvider' => [\Modules\Shipping\Models\ShippingProvider::class, 'Modules\\Shipping\\Events\\ShippingProviderWas'],
            'Offer'            => [\Modules\Offer\Models\Offer::class,               'Modules\\Offer\\Events\\OfferWas'],
            'TaxRate'          => [\Modules\Tax\Models\TaxRate::class,               'Modules\\Tax\\Events\\TaxRateWas'],
            'Coupon'           => [\Modules\Coupons\Models\Coupon::class,            'Modules\\Coupons\\Events\\CouponWas'],
            'Comment'          => [\Modules\Comments\Models\Comment::class,          'Modules\\Comments\\Events\\CommentWas'],
            'Rating'           => [\Modules\Rating\Models\Rating::class,             'Modules\\Rating\\Events\\RatingWas'],
            'Address'          => [\Modules\Address\Models\Address::class,           'Modules\\Address\\Events\\AddressWas'],
            'Attribute'        => [\Modules\Attributes\Models\Attribute::class,      'Modules\\Attributes\\Events\\AttributeWas'],

            // Newsletter
            'NewsletterCampaign'        => [\Modules\Newsletter\Models\NewsletterCampaign::class,        'Modules\\Newsletter\\Events\\NewsletterCampaignWas'],
            'NewsletterList'            => [\Modules\Newsletter\Models\NewsletterList::class,            'Modules\\Newsletter\\Events\\NewsletterListWas'],
            'NewsletterSenderAccount'   => [\Modules\Newsletter\Models\NewsletterSenderAccount::class,   'Modules\\Newsletter\\Events\\NewsletterSenderAccountWas'],
            'NewsletterSubscriberList'  => [\Modules\Newsletter\Models\NewsletterSubscriberList::class,  'Modules\\Newsletter\\Events\\NewsletterSubscriberListWas'],
            'NewsletterSubscriber'      => [\Modules\Newsletter\Models\NewsletterSubscriber::class,      'Modules\\Newsletter\\Events\\NewsletterSubscriberWas'],
            'NewsletterTemplate'        => [\Modules\Newsletter\Models\NewsletterTemplate::class,        'Modules\\Newsletter\\Events\\NewsletterTemplateWas'],
            'Workflow'                  => [\Modules\Newsletter\Models\Workflow::class,                  'Modules\\Newsletter\\Events\\WorkflowWas'],
            'WorkflowNode'              => [\Modules\Newsletter\Models\WorkflowNode::class,              'Modules\\Newsletter\\Events\\WorkflowNodeWas'],

            // Billing
            'SubscriptionCancelReason'      => [\Modules\Billing\Models\SubscriptionCancelReason::class,      'Modules\\Billing\\Events\\SubscriptionCancelReasonWas'],
            'SubscriptionManual'            => [\Modules\Billing\Models\SubscriptionManual::class,            'Modules\\Billing\\Events\\SubscriptionManualWas'],
            'SubscriptionPlanFeature'       => [\Modules\Billing\Models\SubscriptionPlanFeature::class,       'Modules\\Billing\\Events\\SubscriptionPlanFeatureWas'],
            'SubscriptionPlanGroupFeature'  => [\Modules\Billing\Models\SubscriptionPlanGroupFeature::class,  'Modules\\Billing\\Events\\SubscriptionPlanGroupFeatureWas'],
            'SubscriptionPlanGroup'         => [\Modules\Billing\Models\SubscriptionPlanGroup::class,         'Modules\\Billing\\Events\\SubscriptionPlanGroupWas'],
            'SubscriptionPlan'              => [\Modules\Billing\Models\SubscriptionPlan::class,              'Modules\\Billing\\Events\\SubscriptionPlanWas'],

            // Order (Order itself keeps its richer inline events)
            'OrderAnonymousClient' => [\Modules\Order\Models\OrderAnonymousClient::class, 'Modules\\Order\\Events\\OrderAnonymousClientWas'],
            'OrderCancelReason'    => [\Modules\Order\Models\OrderCancelReason::class,    'Modules\\Order\\Events\\OrderCancelReasonWas'],
            'OrderRefund'          => [\Modules\Order\Models\OrderRefund::class,          'Modules\\Order\\Events\\OrderRefundWas'],
            'OrderStatusHistory'   => [\Modules\Order\Models\OrderStatusHistory::class,   'Modules\\Order\\Events\\OrderStatusHistoryWas'],

            // Product (variant/meta/pricing; ProductVariant inherits Product's events)
            'ProductCustomerPricing'       => [\Modules\Product\Models\ProductCustomerPricing::class,       'Modules\\Product\\Events\\ProductCustomerPricingWas'],
            'ProductMetaData'              => [\Modules\Product\Models\ProductMetaData::class,              'Modules\\Product\\Events\\ProductMetaDataWas'],
            'ProductPricingRule'           => [\Modules\Product\Models\ProductPricingRule::class,           'Modules\\Product\\Events\\ProductPricingRuleWas'],
            'ProductVariantAttribute'      => [\Modules\Product\Models\ProductVariantAttribute::class,      'Modules\\Product\\Events\\ProductVariantAttributeWas'],
            'ProductVariantAttributeValue' => [\Modules\Product\Models\ProductVariantAttributeValue::class, 'Modules\\Product\\Events\\ProductVariantAttributeValueWas'],

            // Form
            'FormData'      => [\Modules\Form\Models\FormData::class,      'Modules\\Form\\Events\\FormDataWas'],
            'FormList'      => [\Modules\Form\Models\FormList::class,      'Modules\\Form\\Events\\FormListWas'],
            'FormRecipient' => [\Modules\Form\Models\FormRecipient::class, 'Modules\\Form\\Events\\FormRecipientWas'],

            // Booking / Coupons / Vendors / DigitalProduct
            'Booking'                => [\Modules\Booking\Models\Booking::class,                       'Modules\\Booking\\Events\\BookingWas'],
            'CartCoupon'             => [\Modules\Coupons\Models\CartCoupon::class,                    'Modules\\Coupons\\Events\\CartCouponWas'],
            'Vendor'                 => [\Modules\Vendors\Models\Vendor::class,                        'Modules\\Vendors\\Events\\VendorWas'],
            'DigitalProductDownload' => [\Modules\DigitalProduct\Models\DigitalProductDownload::class, 'Modules\\DigitalProduct\\Events\\DigitalProductDownloadWas'],
            'ProductDownloadableFile'=> [\Modules\DigitalProduct\Models\ProductDownloadableFile::class, 'Modules\\DigitalProduct\\Events\\ProductDownloadableFileWas'],
        ];
    }

    #[Test]
    #[\PHPUnit\Framework\Attributes\DataProvider('modelEventMapProvider')]
    public function model_declares_created_updated_deleted_events(string $modelClass, string $eventPrefix): void
    {
        $model = new $modelClass();
        $prop = (new ReflectionClass($model))->getProperty('dispatchesEvents');
        $prop->setAccessible(true);
        $map = $prop->getValue($model);

        $this->assertIsArray($map);
        foreach (['created', 'updated', 'deleted'] as $event) {
            $this->assertArrayHasKey($event, $map, "$modelClass should map the '$event' event");
            $this->assertTrue(class_exists($map[$event]), "{$map[$event]} should exist");
            $this->assertStringStartsWith($eventPrefix, $map[$event]);
        }
    }

    #[Test]
    public function content_fires_lifecycle_events_on_save_and_delete(): void
    {
        Event::fake([
            \Modules\Content\Events\ContentWasCreated::class,
            \Modules\Content\Events\ContentWasUpdated::class,
            \Modules\Content\Events\ContentWasDeleted::class,
        ]);

        $c = new Content();
        $c->title = 'lifecycle-' . uniqid();
        $c->content_type = 'page';
        $c->url = $c->title;
        $c->save();
        Event::assertDispatched(\Modules\Content\Events\ContentWasCreated::class);

        $c->title = 'lifecycle-updated';
        $c->save();
        Event::assertDispatched(\Modules\Content\Events\ContentWasUpdated::class);

        $c->delete();
        Event::assertDispatched(\Modules\Content\Events\ContentWasDeleted::class);
    }

    #[Test]
    public function product_fires_its_own_events_not_contents(): void
    {
        Event::fake([
            \Modules\Product\Events\ProductWasCreated::class,
            \Modules\Content\Events\ContentWasCreated::class,
        ]);

        $p = new Product();
        $p->title = 'lifecycle-prod-' . uniqid();
        $p->url = $p->title;
        $p->save();

        Event::assertDispatched(\Modules\Product\Events\ProductWasCreated::class);
        Event::assertNotDispatched(\Modules\Content\Events\ContentWasCreated::class);

        $p->forceDelete();
    }

    #[Test]
    public function category_fires_created_event(): void
    {
        Event::fake([\Modules\Category\Events\CategoryWasCreated::class]);

        $cat = new Category();
        $cat->title = 'lifecycle-cat-' . uniqid();
        $cat->save();

        Event::assertDispatched(\Modules\Category\Events\CategoryWasCreated::class);

        $cat->forceDelete();
    }

    #[Test]
    public function models_using_the_generic_trait_fire_generic_events(): void
    {
        // A sample of models wired with FiresModelLifecycleEvents must use the
        // trait and dispatch the generic Model* events on create/update/delete.
        $sample = [
            \Modules\Accordion\Models\Accordion::class,
            \Modules\Company\Models\Company::class,
            \Modules\Payment\Models\PaymentProvider::class,
        ];
        foreach ($sample as $cls) {
            $this->assertContains(
                \MicroweberPackages\Core\Concerns\FiresModelLifecycleEvents::class,
                class_uses_recursive($cls),
                "$cls should use FiresModelLifecycleEvents"
            );
        }

        Event::fake([
            \MicroweberPackages\Core\Events\ModelWasCreated::class,
            \MicroweberPackages\Core\Events\ModelWasUpdated::class,
            \MicroweberPackages\Core\Events\ModelWasDeleted::class,
        ]);

        $company = new Company();
        $company->name = 'generic-evt-' . uniqid();
        $company->save();
        Event::assertDispatched(\MicroweberPackages\Core\Events\ModelWasCreated::class, fn ($e) => $e->getModel() instanceof Company);

        $company->name = 'generic-evt-updated';
        $company->save();
        Event::assertDispatched(\MicroweberPackages\Core\Events\ModelWasUpdated::class);

        $company->delete();
        Event::assertDispatched(\MicroweberPackages\Core\Events\ModelWasDeleted::class);
    }
}
