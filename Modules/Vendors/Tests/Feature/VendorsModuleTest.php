<?php

namespace Modules\Vendors\Tests\Feature;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Schema;
use MicroweberPackages\User\Models\User;
use Modules\Cart\Models\Cart;
use Modules\Order\Events\OrderWasPaid;
use Modules\Order\Models\Order;
use Modules\Product\Models\Product;
use Modules\Vendors\Filament\Pages\MyVendorProducts;
use Modules\Vendors\Models\Vendor;
use Modules\Vendors\Models\VendorOrderNotificationLog;
use Modules\Vendors\Notifications\VendorOrderNotification;
use Modules\Vendors\Services\VendorOrderNotifier;
use Modules\Vendors\Services\VendorManager;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * End-to-end contract for the multi-vendor marketplace:
 *   - schema + role seeding
 *   - VendorManager lifecycle (role gate, lazy profile provisioning, slug)
 *   - product ownership via created_by (FilterByAuthor)
 *   - the My Products page create path stamps created_by + attaches media
 *   - the public /vendor/{slug} + /vendors routes and their status/robots
 */
class VendorsModuleTest extends TestCase
{
    protected array $userIds = [];
    protected array $vendorIds = [];
    protected array $productIds = [];
    protected array $orderIds = [];

    protected function setUp(): void
    {
        parent::setUp();
        app(VendorManager::class)->ensureRole();
    }

    protected function tearDown(): void
    {
        if (! empty($this->orderIds)) {
            Cart::query()->whereIn('order_id', $this->orderIds)->forceDelete();
            VendorOrderNotificationLog::query()->whereIn('order_id', $this->orderIds)->delete();
            Order::query()->whereIn('id', $this->orderIds)->forceDelete();
        }
        Product::query()->whereIn('id', $this->productIds)->forceDelete();
        VendorOrderNotificationLog::query()->whereIn('vendor_id', $this->vendorIds)->delete();
        Vendor::query()->whereIn('id', $this->vendorIds)->delete();
        User::query()->whereIn('id', $this->userIds)->forceDelete();
        parent::tearDown();
    }

    private function makeVendorUser(): User
    {
        $user = User::factory()->create();
        $this->userIds[] = $user->id;
        $user->assignRole(VendorManager::ROLE);
        return $user;
    }

    #[Test]
    public function vendors_table_and_role_exist(): void
    {
        $this->assertTrue(Schema::hasTable('vendors'));
        $this->assertTrue(
            \MicroweberPackages\Role\Models\Role::where('name', 'vendor')->exists(),
            'The vendor role must be seeded so admins can assign it.'
        );
    }

    #[Test]
    public function manager_recognises_vendor_role_and_provisions_profile(): void
    {
        $manager = app(VendorManager::class);
        $plain = User::factory()->create();
        $this->userIds[] = $plain->id;
        $vendorUser = $this->makeVendorUser();

        $this->assertFalse($manager->isVendor($plain), 'A user without the role is not a vendor.');
        $this->assertTrue($manager->isVendor($vendorUser), 'A user with the role is a vendor.');

        $profile = $manager->profileFor($vendorUser);
        $this->vendorIds[] = $profile->id;

        $this->assertInstanceOf(Vendor::class, $profile);
        $this->assertSame($vendorUser->id, (int) $profile->user_id);
        $this->assertNotEmpty($profile->slug, 'A slug must be auto-generated.');
        $this->assertTrue((bool) $profile->is_approved, 'Vendors are approved by default when marked.');

        // Idempotent: calling again returns the same row (no duplicate).
        $again = $manager->profileFor($vendorUser);
        $this->assertSame($profile->id, $again->id);
    }

    #[Test]
    public function slugs_are_unique(): void
    {
        $a = Vendor::create(['user_id' => 900001, 'shop_name' => 'Acme Shop']);
        $b = Vendor::create(['user_id' => 900002, 'shop_name' => 'Acme Shop']);
        $this->vendorIds[] = $a->id;
        $this->vendorIds[] = $b->id;

        $this->assertNotSame($a->slug, $b->slug, 'Duplicate shop names must get distinct slugs.');
        $this->assertSame('acme-shop', $a->slug);
    }

    #[Test]
    public function products_are_scoped_to_their_vendor_owner(): void
    {
        $vendorA = $this->makeVendorUser();
        $vendorB = $this->makeVendorUser();

        $pA = $this->makeProductFor($vendorA->id, 'Widget A');
        $pB = $this->makeProductFor($vendorB->id, 'Widget B');

        $ownedByA = Product::query()->filter(['author' => $vendorA->id])->pluck('id')->all();

        $this->assertContains($pA->id, $ownedByA);
        $this->assertNotContains($pB->id, $ownedByA, 'A vendor must not see another vendor\'s products.');
    }

    #[Test]
    public function my_products_page_create_stamps_created_by_and_persists_price(): void
    {
        $vendorUser = $this->makeVendorUser();
        Auth::login($vendorUser);

        $page = new MyVendorProducts();
        $ref = new \ReflectionMethod($page, 'createProduct');
        $ref->setAccessible(true);

        /** @var Product $product */
        $product = $ref->invoke($page, [
            'title' => 'Vendor Upload ' . uniqid(),
            'description' => 'From the vendor panel',
            'price' => 19.99,
            'is_active' => true,
            'image' => null,
        ]);
        $this->productIds[] = $product->id;

        $this->assertSame($vendorUser->id, (int) $product->created_by, 'created_by must be the signed-in vendor.');
        $this->assertSame('product', $product->content_type);
        $this->assertEquals(19.99, (float) $product->fresh()->price, 'Price must persist via the ProductPrice trait.');
        $this->assertContains(
            $product->id,
            Product::query()->filter(['author' => $vendorUser->id])->pluck('id')->all()
        );

        Auth::logout();
    }

    #[Test]
    public function public_vendor_page_lists_only_that_vendors_products(): void
    {
        $vendorUser = $this->makeVendorUser();
        $vendor = app(VendorManager::class)->profileFor($vendorUser);
        $this->vendorIds[] = $vendor->id;

        $mine = $this->makeProductFor($vendorUser->id, 'Public Visible Widget');
        $otherUser = $this->makeVendorUser();
        $theirs = $this->makeProductFor($otherUser->id, 'Someone Elses Widget');

        $res = $this->get(route('vendors.show', $vendor->slug));
        $res->assertStatus(200);
        $res->assertSee('Public Visible Widget', false);
        $res->assertDontSee('Someone Elses Widget', false);
    }

    #[Test]
    public function unknown_vendor_slug_returns_chromed_404(): void
    {
        $res = $this->get(route('vendors.show', 'no-such-vendor-' . uniqid()));
        $res->assertStatus(404);
        $res->assertHeader('X-Robots-Tag', 'noindex, nofollow');
    }

    #[Test]
    public function unapproved_vendor_is_not_public(): void
    {
        $vendorUser = $this->makeVendorUser();
        $vendor = app(VendorManager::class)->profileFor($vendorUser);
        $vendor->forceFill(['is_approved' => false])->save();
        $this->vendorIds[] = $vendor->id;

        $res = $this->get(route('vendors.show', $vendor->slug));
        $res->assertStatus(404);
    }

    #[Test]
    public function vendors_directory_route_responds(): void
    {
        $res = $this->get(route('vendors.index'));
        $res->assertStatus(200);
    }

    #[Test]
    public function vendor_is_notified_when_their_product_is_ordered(): void
    {
        Notification::fake();

        $vendorUser = $this->makeVendorUser();
        $vendor = app(VendorManager::class)->profileFor($vendorUser);
        $this->vendorIds[] = $vendor->id;
        $product = $this->makeProductFor($vendorUser->id, 'Ordered Widget');

        $order = $this->makeOrderWith($product->id, 25.00);

        $count = app(VendorOrderNotifier::class)->notifyForOrder($order);

        $this->assertSame(1, $count);
        Notification::assertSentTo(
            $vendorUser,
            VendorOrderNotification::class,
            fn (VendorOrderNotification $n) => ($n->order->id ?? null) === $order->id
                && count($n->items) === 1
        );
    }

    #[Test]
    public function vendor_notification_is_idempotent_per_order(): void
    {
        Notification::fake();

        $vendorUser = $this->makeVendorUser();
        $vendor = app(VendorManager::class)->profileFor($vendorUser);
        $this->vendorIds[] = $vendor->id;
        $product = $this->makeProductFor($vendorUser->id, 'Once Widget');
        $order = $this->makeOrderWith($product->id, 10.00);

        app(VendorOrderNotifier::class)->notifyForOrder($order);
        app(VendorOrderNotifier::class)->notifyForOrder($order); // repeat (e.g. mark-paid)

        Notification::assertSentToTimes($vendorUser, VendorOrderNotification::class, 1);
    }

    #[Test]
    public function vendor_opted_out_is_not_notified(): void
    {
        Notification::fake();

        $vendorUser = $this->makeVendorUser();
        $vendor = app(VendorManager::class)->profileFor($vendorUser);
        $vendor->forceFill(['notify_on_order' => false])->save();
        $this->vendorIds[] = $vendor->id;
        $product = $this->makeProductFor($vendorUser->id, 'Silent Widget');
        $order = $this->makeOrderWith($product->id, 10.00);

        $count = app(VendorOrderNotifier::class)->notifyForOrder($order);

        $this->assertSame(0, $count);
        Notification::assertNothingSentTo($vendorUser);
    }

    #[Test]
    public function vendor_channel_preference_is_respected(): void
    {
        Notification::fake();

        $vendorUser = $this->makeVendorUser();
        $vendor = app(VendorManager::class)->profileFor($vendorUser);
        $vendor->forceFill(['notify_channels' => [Vendor::CHANNEL_MAIL]])->save();
        $this->vendorIds[] = $vendor->id;
        $product = $this->makeProductFor($vendorUser->id, 'Mail Only Widget');
        $order = $this->makeOrderWith($product->id, 10.00);

        app(VendorOrderNotifier::class)->notifyForOrder($order);

        Notification::assertSentTo($vendorUser, VendorOrderNotification::class, function (VendorOrderNotification $n) use ($vendorUser) {
            $via = $n->via($vendorUser);
            return in_array(\MicroweberPackages\Notification\Channels\AppMailChannel::class, $via, true)
                && ! in_array('database', $via, true);
        });
    }

    #[Test]
    public function order_paid_event_triggers_vendor_notification(): void
    {
        Notification::fake();

        $vendorUser = $this->makeVendorUser();
        $vendor = app(VendorManager::class)->profileFor($vendorUser);
        $this->vendorIds[] = $vendor->id;
        $product = $this->makeProductFor($vendorUser->id, 'Event Widget');
        $order = $this->makeOrderWith($product->id, 30.00);

        event(new OrderWasPaid($order));

        Notification::assertSentTo($vendorUser, VendorOrderNotification::class);
    }

    #[Test]
    public function my_orders_page_scopes_to_the_vendors_products(): void
    {
        $vendorUser = $this->makeVendorUser();
        $vendor = app(VendorManager::class)->profileFor($vendorUser);
        $this->vendorIds[] = $vendor->id;
        $mine = $this->makeProductFor($vendorUser->id, 'My Sold Widget');

        $otherUser = $this->makeVendorUser();
        $theirs = $this->makeProductFor($otherUser->id, 'Other Sold Widget');

        $myOrder = $this->makeOrderWith($mine->id, 15.00);
        $theirOrder = $this->makeOrderWith($theirs->id, 20.00);

        \Illuminate\Support\Facades\Auth::login($vendorUser);
        $page = new \Modules\Vendors\Filament\Pages\MyVendorOrders();
        $ref = new \ReflectionMethod($page, 'vendorProductIds');
        $ref->setAccessible(true);
        $ids = $ref->invoke($page);

        $this->assertContains($mine->id, $ids);
        $this->assertNotContains($theirs->id, $ids);

        // The order table query must include my order and exclude the other's.
        $tableQuery = Order::query()->whereIn('id', function ($q) use ($ids) {
            $q->select('order_id')->from('cart')->whereIn('rel_id', $ids ?: [0])->where('order_completed', 1);
        })->pluck('id')->all();

        $this->assertContains($myOrder->id, $tableQuery);
        $this->assertNotContains($theirOrder->id, $tableQuery);

        $subtotalRef = new \ReflectionMethod($page, 'vendorSubtotal');
        $subtotalRef->setAccessible(true);
        $this->assertEquals(15.00, (float) $subtotalRef->invoke($page, $myOrder->id));

        \Illuminate\Support\Facades\Auth::logout();
    }

    #[Test]
    public function vendor_badge_links_a_product_to_its_vendor_shop(): void
    {
        $vendorUser = $this->makeVendorUser();
        $vendor = app(VendorManager::class)->profileFor($vendorUser);
        $vendor->forceFill(['shop_name' => 'Badge Shop'])->save();
        $this->vendorIds[] = $vendor->id;
        $product = $this->makeProductFor($vendorUser->id, 'Badged Widget');

        $html = Blade::render('<x-vendor-badge :content-id="' . $product->id . '" />');

        $this->assertStringContainsString('Sold by', $html);
        $this->assertStringContainsString('/vendor/' . $vendor->slug, $html);
        $this->assertStringContainsString('Badge Shop', $html);
    }

    #[Test]
    public function vendor_badge_is_empty_for_a_non_vendor_product(): void
    {
        $plain = User::factory()->create();
        $this->userIds[] = $plain->id;
        $product = $this->makeProductFor($plain->id, 'Plain Store Widget');

        $html = trim(Blade::render('<x-vendor-badge :content-id="' . $product->id . '" />'));

        $this->assertStringNotContainsString('Sold by', $html);
    }

    private function makeOrderWith(int $productId, float $price): Order
    {
        $order = new Order();
        $order->amount = $price;
        $order->email = 'buyer_' . uniqid() . '@example.com';
        $order->is_paid = 1;
        $order->order_completed = 1;
        $order->save();
        $this->orderIds[] = $order->id;

        $item = new Cart();
        $item->order_id = $order->id;
        $item->rel_id = $productId;
        $item->rel_type = 'content';
        $item->title = 'Line ' . $productId;
        $item->qty = 1;
        $item->price = $price;
        $item->order_completed = 1;
        $item->save();

        // Sanity: the line item must be reachable via the order relation,
        // otherwise the notifier has nothing to group by.
        $this->assertSame(
            1,
            $order->fresh()->cart()->where('order_completed', 1)->count(),
            'order cart line item should be linked'
        );

        return $order->fresh();
    }

    private function makeProductFor(int $userId, string $title): Product
    {
        // Content stamps created_by from the authenticated user on create
        // (HasCreatedByFieldsTrait), so sign in as the owner — this mirrors
        // exactly how the vendor panel stamps ownership.
        Auth::loginUsingId($userId);

        $p = new Product();
        $p->title = $title;
        $p->url = \Illuminate\Support\Str::slug($title) . '-' . uniqid();
        $p->content_type = 'product';
        $p->subtype = 'product';
        $p->is_active = 1;
        $p->is_deleted = 0;
        $p->save();
        $this->productIds[] = $p->id;

        Auth::logout();

        return $p;
    }
}
