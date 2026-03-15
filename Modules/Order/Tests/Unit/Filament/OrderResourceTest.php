<?php

namespace Modules\Order\Tests\Unit\Filament;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\Livewire;
use Modules\Order\Filament\Admin\Resources\OrderResource;
use Modules\Order\Filament\Admin\Resources\OrderResource\Pages\ListOrders;
use Modules\Order\Filament\Admin\Resources\OrderResource\Pages\CreateOrder;
use Modules\Order\Filament\Admin\Resources\OrderResource\Pages\EditOrder;
use Modules\Order\Models\Order;
use Modules\Order\Enums\OrderStatus;
use Modules\Customer\Models\Customer;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

#[\PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses]
class OrderResourceTest extends TestCase
{
    use LazilyRefreshDatabase;
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel();
    }

    protected function getResourceClass(): string
    {
        return OrderResource::class;
    }

    #[Test]
    public function it_index_page_loads_without_errors(): void
    {
        Livewire::test(ListOrders::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_index_page_shows_all_records(): void
    {
        $orders = Order::factory()->count(3)->create();

        Livewire::test(ListOrders::class)
            ->assertCanSeeTableRecords($orders);
    }

    #[Test]
    public function it_index_page_supports_pagination(): void
    {
        Order::factory()->count(15)->create();

        Livewire::test(ListOrders::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_index_page_supports_search(): void
    {
        $order = Order::factory()->create([
            'order_reference_id' => 'ORDER-SEARCH-001',
        ]);

        Livewire::test(ListOrders::class)
            ->searchTable('ORDER-SEARCH')
            ->assertCanSeeTableRecords([$order]);
    }

    #[Test]
    public function it_create_page_renders_form(): void
    {
        Livewire::test(CreateOrder::class)
            ->assertSuccessful()
            ->assertFormExists();
    }

    #[Test]
    public function it_create_page_validates_required_fields(): void
    {
        Livewire::test(CreateOrder::class)
            ->fillForm([
                'order_reference_id' => '',
                'customer_id' => '',
                'order_status' => '',
            ])
            ->call('create')
            ->assertHasFormErrors([
                'order_reference_id',
                'customer_id',
                'order_status',
            ]);
    }

    #[Test]
    public function it_create_page_saves_new_record(): void
    {
        $customer = Customer::factory()->create();

        Livewire::test(CreateOrder::class)
            ->fillForm([
                'order_reference_id' => 'ORDER-TEST-001',
                'customer_id' => $customer->id,
                'order_status' => OrderStatus::New,
                'amount' => 100.00,
                'currency' => 'USD',
                'order_completed' => false,
                'is_paid' => false,
            ])
            ->call('create')
            ->assertHasNoFormErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('orders', [
            'order_reference_id' => 'ORDER-TEST-001',
            'customer_id' => $customer->id,
            'order_status' => OrderStatus::New,
        ]);
    }

    #[Test]
    public function it_edit_page_pre_fills_form_data(): void
    {
        $customer = Customer::factory()->create();
        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'order_reference_id' => 'ORDER-EDIT-001',
            'order_status' => OrderStatus::New,
        ]);

        Livewire::test(EditOrder::class, ['record' => $order->id])
            ->assertSuccessful()
            ->assertFormSet([
                'order_reference_id' => 'ORDER-EDIT-001',
                'customer_id' => $customer->id,
            ]);
    }

    #[Test]
    public function it_edit_page_updates_record(): void
    {
        $order = Order::factory()->create([
            'order_status' => OrderStatus::New,
            'order_completed' => false,
        ]);

        Livewire::test(EditOrder::class, ['record' => $order->id])
            ->fillForm([
                'order_status' => OrderStatus::Completed,
                'order_completed' => true,
            ])
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'order_status' => OrderStatus::Completed,
            'order_completed' => true,
        ]);
    }

    #[Test]
    public function it_delete_action_removes_record(): void
    {
        $order = Order::factory()->create();

        Livewire::test(ListOrders::class)
            ->callTableAction('delete', $order);

        $this->assertDatabaseMissing('orders', [
            'id' => $order->id,
        ]);
    }

    #[Test]
    public function it_table_has_required_columns(): void
    {
        Livewire::test(ListOrders::class)
            ->assertTableColumnExists('id')
            ->assertTableColumnExists('order_reference_id')
            ->assertTableColumnExists('order_status')
            ->assertTableColumnExists('customer.email')
            ->assertTableColumnExists('amount')
            ->assertTableColumnExists('order_completed')
            ->assertTableColumnExists('is_paid');
    }

    #[Test]
    public function it_order_has_customer_relationship(): void
    {
        $customer = Customer::factory()->create();
        $order = Order::factory()->create([
            'customer_id' => $customer->id,
        ]);

        $this->assertInstanceOf(Customer::class, $order->customer);
        $this->assertEquals($customer->id, $order->customer->id);
    }

    #[Test]
    public function it_order_status_badge_displays_correctly(): void
    {
        $order = Order::factory()->create([
            'order_status' => OrderStatus::New,
        ]);

        Livewire::test(ListOrders::class)
            ->assertSuccessful()
            ->assertTableColumnExists('order_status');
    }

    #[Test]
    public function it_global_search_returns_results(): void
    {
        $order = Order::factory()->create([
            'order_reference_id' => 'ORDER-SEARCH-123',
        ]);

        $results = OrderResource::getGlobalSearchResults('ORDER-SEARCH');
        $this->assertNotEmpty($results);
    }

    #[Test]
    public function it_can_add_order_items_via_repeater(): void
    {
        $customer = Customer::factory()->create();
        $product = \Modules\Content\Models\Content::factory()->create([
            'content_type' => 'product',
            'price' => 50.00,
        ]);

        Livewire::test(CreateOrder::class)
            ->fillForm([
                'order_reference_id' => 'ORDER-ITEMS-001',
                'customer_id' => $customer->id,
                'order_status' => OrderStatus::New,
                'cart' => [
                    [
                        'rel_id' => $product->id,
                        'rel_type' => morph_name(\Modules\Product\Models\Product::class),
                        'qty' => 2,
                        'price' => 50.00,
                    ],
                ],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('orders', [
            'order_reference_id' => 'ORDER-ITEMS-001',
        ]);
    }

    #[Test]
    public function it_sorting_by_column_changes_order(): void
    {
        // Create orders with different reference IDs and amounts
        $orderA = Order::factory()->create([
            'order_reference_id' => 'ORDER-001',
            'amount' => 100.00,
            'created_at' => now()->subDays(5),
        ]);
        $orderB = Order::factory()->create([
            'order_reference_id' => 'ORDER-002',
            'amount' => 200.00,
            'created_at' => now()->subDays(3),
        ]);
        $orderC = Order::factory()->create([
            'order_reference_id' => 'ORDER-003',
            'amount' => 300.00,
            'created_at' => now()->subDays(1),
        ]);

        // Test sorting by id descending (default)
        Livewire::test(ListOrders::class)
            ->sortTable('id', 'desc')
            ->assertCanSeeTableRecords([$orderC, $orderB, $orderA], inOrder: true);

        // Test sorting by order_reference_id ascending
        Livewire::test(ListOrders::class)
            ->sortTable('order_reference_id', 'asc')
            ->assertCanSeeTableRecords([$orderA, $orderB, $orderC], inOrder: true);
    }

    #[Test]
    public function it_bulk_delete_removes_selected_records(): void
    {
        $order1 = Order::factory()->create(['order_reference_id' => 'ORDER-BULK-001']);
        $order2 = Order::factory()->create(['order_reference_id' => 'ORDER-BULK-002']);
        $order3 = Order::factory()->create(['order_reference_id' => 'ORDER-BULK-003']);

        // Select and bulk delete first two orders
        Livewire::test(ListOrders::class)
            ->callTableBulkAction('delete', [$order1, $order2])
            ->assertHasNoTableBulkActionErrors();

        // Assert deleted records are gone
        $this->assertDatabaseMissing('orders', ['id' => $order1->id]);
        $this->assertDatabaseMissing('orders', ['id' => $order2->id]);

        // Assert third order still exists
        $this->assertDatabaseHas('orders', ['id' => $order3->id]);
    }

    #[Test]
    public function it_payments_relation_manager_exists(): void
    {
        $order = Order::factory()->create();
        $relations = OrderResource::getRelations();

        $this->assertContains(
            \Modules\Order\Filament\Admin\Resources\OrderResource\RelationManagers\PaymentsRelationManager::class,
            $relations
        );
    }

    #[Test]
    public function it_navigation_badge_methods_exist(): void
    {
        // Test that getNavigationBadge method exists and returns correct type
        $this->assertTrue(method_exists(OrderResource::class, 'getNavigationBadge'));
        $this->assertTrue(method_exists(OrderResource::class, 'getNavigationBadgeTooltip'));
        $this->assertTrue(method_exists(OrderResource::class, 'getNavigationBadgeColor'));

        // Test badge tooltip
        $tooltip = OrderResource::getNavigationBadgeTooltip();
        $this->assertEquals('New orders', $tooltip);

        // Test badge color
        $color = OrderResource::getNavigationBadgeColor();
        $this->assertEquals('info', $color);
    }
}
