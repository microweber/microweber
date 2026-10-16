<?php

namespace Modules\Content\Tests\Feature;

use Illuminate\Support\Facades\Event;
use Modules\Cart\Models\Cart;
use Modules\Category\Models\Category;
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
            'Content'  => [Content::class,  'Modules\\Content\\Events\\ContentWas'],
            'Product'  => [Product::class,  'Modules\\Product\\Events\\ProductWas'],
            'Customer' => [Customer::class, 'Modules\\Customer\\Events\\CustomerWas'],
            'Cart'     => [Cart::class,     'Modules\\Cart\\Events\\CartWas'],
            'Category' => [Category::class, 'Modules\\Category\\Events\\CategoryWas'],
            'Invoice'  => [Invoice::class,  'Modules\\Invoice\\Events\\InvoiceWas'],
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
}
