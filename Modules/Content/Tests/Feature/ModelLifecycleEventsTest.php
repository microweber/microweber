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
