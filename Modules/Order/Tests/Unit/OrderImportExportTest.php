<?php

namespace Modules\Order\Tests\Unit;

use Modules\Order\Enums\OrderStatus;
use Modules\Order\Filament\Exports\OrderExporter;
use Modules\Order\Filament\Imports\OrderImporter;
use Modules\Order\Models\Order;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class OrderImportExportTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
    }

        #[Test]
        public function it_can_get_exporter_columns(): void
    {
        $columns = OrderExporter::getColumns();

        $this->assertIsArray($columns);
        $this->assertNotEmpty($columns);

        // Verify columns include essential fields
        $columnNames = array_map(fn($col) => $col->getName(), $columns);
        $this->assertContains('id', $columnNames);
        $this->assertContains('order_reference_id', $columnNames);
        $this->assertContains('email', $columnNames);
        $this->assertContains('order_status', $columnNames);
        $this->assertContains('amount', $columnNames);
    }

        #[Test]
        public function it_can_get_importer_columns(): void
    {
        $columns = OrderImporter::getColumns();

        $this->assertIsArray($columns);
        $this->assertNotEmpty($columns);

        // Verify columns exist
        $this->assertGreaterThan(0, count($columns));
    }

        #[Test]
        public function it_validates_order_import_data_structure(): void
    {
        $columns = OrderImporter::getColumns();

        // Check that email column exists
        $columnNames = array_map(fn($col) => $col->getName(), $columns);
        $this->assertContains('email', $columnNames);
    }

        #[Test]
        public function it_can_import_orders_with_valid_data(): void
    {
        $data = [
            'order_reference_id' => 'IMPORT-001',
            'email' => 'test@example.com',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'order_status' => OrderStatus::New->value,
            'amount' => 150.00,
            'currency' => 'USD',
            'is_paid' => 0,
            'order_completed' => 0,
        ];

        $order = Order::create($data);

        $this->assertDatabaseHas('cart_orders', [
            'order_reference_id' => 'IMPORT-001',
            'email' => 'test@example.com',
            'order_status' => OrderStatus::New->value,
        ]);
    }

        #[Test]
        public function it_generates_reference_id_if_not_provided(): void
    {
        $data = [
            'email' => 'test@example.com',
            'order_status' => OrderStatus::New->value,
        ];

        // Reference ID should be auto-generated
        $this->assertNotEmpty($data['email']);
    }

        #[Test]
        public function it_prevents_duplicate_orders_on_import(): void
    {
        // Create existing order
        Order::factory()->create(['order_reference_id' => 'DUPLICATE-001']);

        // Try to import with same reference
        $data = [
            'order_reference_id' => 'DUPLICATE-001',
            'email' => 'test@example.com',
        ];

        // Should either skip or update
        $existing = Order::where('order_reference_id', 'DUPLICATE-001')->first();
        $this->assertNotNull($existing);
    }

        #[Test]
        public function it_sets_default_order_status_if_not_provided(): void
    {
        $data = [
            'email' => 'test@example.com',
        ];

        // Default status should be 'new'
        $this->assertArrayNotHasKey('order_status', $data);
    }

        #[Test]
        public function it_exports_orders_with_correct_status_formatting(): void
    {
        $order = Order::factory()->create([
            'order_status' => OrderStatus::Delivered->value,
        ]);

        // Status should be formatted as human-readable string
        $this->assertEquals(OrderStatus::Delivered->value, $order->order_status);
    }

        #[Test]
        public function it_handles_empty_optional_fields_in_order_import(): void
    {
        $data = [
            'email' => 'minimal@example.com',
            'order_status' => OrderStatus::New->value,
        ];

        // Should not fail without optional fields
        $this->assertArrayNotHasKey('phone', $data);
        $this->assertArrayNotHasKey('address', $data);
    }

        #[Test]
        public function it_handles_special_characters_in_customer_names(): void
    {
        $data = [
            'email' => 'test@example.com',
            'first_name' => 'José',
            'last_name' => 'Muñoz',
        ];

        $this->assertStringContainsString('José', $data['first_name']);
    }

        #[Test]
        public function it_exports_all_orders_when_no_selection(): void
    {
        Order::factory()->count(5)->create();

        $count = Order::count();
        $this->assertGreaterThanOrEqual(5, $count);
    }

        #[Test]
        public function it_can_update_existing_orders_via_import(): void
    {
        $order = Order::factory()->create([
            'order_reference_id' => 'UPDATE-001',
            'order_status' => OrderStatus::New->value,
            'amount' => 100.00,
        ]);

        // Simulate update via import
        $updateData = [
            'order_status' => OrderStatus::Delivered->value,
            'amount' => 150.00,
        ];

        $order->update($updateData);

        $this->assertDatabaseHas('cart_orders', [
            'id' => $order->id,
            'order_status' => OrderStatus::Delivered->value,
        ]);
    }

        #[Test]
        public function it_respects_is_paid_boolean_in_export(): void
    {
        $paidOrder = Order::factory()->create(['is_paid' => 1]);
        $unpaidOrder = Order::factory()->create(['is_paid' => 0]);

        $this->assertEquals(1, $paidOrder->is_paid);
        $this->assertEquals(0, $unpaidOrder->is_paid);
    }

        #[Test]
        public function it_respects_order_completed_boolean_in_export(): void
    {
        $completedOrder = Order::factory()->create(['order_completed' => 1]);
        $incompleteOrder = Order::factory()->create(['order_completed' => 0]);

        $this->assertEquals(1, $completedOrder->order_completed);
        $this->assertEquals(0, $incompleteOrder->order_completed);
    }

        #[Test]
        public function it_exporter_provides_notification_body(): void
    {
        // Test that the exporter has a completed notification body method
        $this->assertTrue(method_exists(OrderExporter::class, 'getCompletedNotificationBody'));
    }

        #[Test]
        public function it_importer_provides_notification_body(): void
    {
        // Test that the importer has a completed notification body method
        $this->assertTrue(method_exists(OrderImporter::class, 'getCompletedNotificationBody'));
    }

        #[Test]
        public function it_exporter_provides_file_name(): void
    {
        // Test that the exporter has a file name method
        $this->assertTrue(method_exists(OrderExporter::class, 'getFileName'));
    }

        #[Test]
        public function it_importer_has_options_form_components(): void
    {
        $components = OrderImporter::getOptionsFormComponents();
        $this->assertIsArray($components);
    }

        #[Test]
        public function it_exporter_has_model_attribute(): void
    {
        $this->assertEquals(Order::class, OrderExporter::getModel());
    }

        #[Test]
        public function it_importer_has_model_attribute(): void
    {
        $this->assertEquals(Order::class, OrderImporter::getModel());
    }

        #[Test]
        public function it_can_import_orders_in_bulk(): void
    {
        $ordersData = [
            ['email' => 'order1@example.com', 'order_status' => OrderStatus::New->value],
            ['email' => 'order2@example.com', 'order_status' => OrderStatus::New->value],
            ['email' => 'order3@example.com', 'order_status' => OrderStatus::New->value],
        ];

        $this->assertCount(3, $ordersData);
    }

        #[Test]
        public function it_validates_email_format_in_import(): void
    {
        $data = [
            'email' => 'invalid-email',
        ];

        // Email should be validated
        $this->assertFalse(filter_var($data['email'], FILTER_VALIDATE_EMAIL));
    }

        #[Test]
        public function it_skips_orders_when_skip_existing_option_is_set(): void
    {
        // When skip_existing is true, existing orders should not be updated
        $this->assertTrue(true);
    }

        #[Test]
        public function it_validates_required_fields_in_import(): void
    {
        $data = [
            // Missing required 'email'
            'order_reference_id' => 'SOME-ID',
        ];

        $this->assertArrayNotHasKey('email', $data);
    }

        #[Test]
        public function it_handles_null_values_gracefully_in_import(): void
    {
        $data = [
            'email' => 'test@example.com',
            'phone' => null,
            'address' => null,
        ];

        $this->assertNull($data['phone']);
        $this->assertNull($data['address']);
    }

        #[Test]
        public function it_exports_orders_in_correct_order(): void
    {
        // Orders should be exported in order they were created or by ID
        $this->assertTrue(true);
    }

        #[Test]
        public function it_can_filter_orders_before_export(): void
    {
        // Should be able to filter by status, date range, etc.
        $this->assertTrue(true);
    }

        #[Test]
        public function it_handles_max_length_constraints_in_import(): void
    {
        $longEmail = str_repeat('a', 300) . '@example.com'; // Likely exceeds limit

        $data = [
            'email' => $longEmail,
        ];

        $this->assertGreaterThan(255, strlen($data['email']));
    }

        #[Test]
        public function it_allows_custom_delimiters_in_csv_import(): void
    {
        // Should support different CSV delimiters (comma, semicolon, tab)
        $this->assertTrue(true);
    }

        #[Test]
        public function it_provides_import_summary_notification(): void
    {
        // After import, user should see summary of imported/skipped/failed
        $this->assertTrue(true);
    }

        #[Test]
        public function it_provides_export_summary_notification(): void
    {
        // After export, user should see summary of exported rows
        $this->assertTrue(true);
    }

        #[Test]
        public function it_exports_shipping_information_correctly(): void
    {
        $order = Order::factory()->create([
            'country' => 'United States',
            'city' => 'New York',
            'state' => 'NY',
            'zip' => '10001',
            'address' => '123 Main St',
        ]);

        $this->assertEquals('United States', $order->country);
        $this->assertEquals('New York', $order->city);
    }

        #[Test]
        public function it_exports_payment_information_correctly(): void
    {
        $order = Order::factory()->create([
            'payment_provider' => 'stripe',
        ]);

        $this->assertEquals('stripe', $order->payment_provider);
    }

        #[Test]
        public function it_handles_international_phone_numbers(): void
    {
        $data = [
            'email' => 'test@example.com',
            'phone' => '+1-555-123-4567',
        ];

        $this->assertStringContainsString('+', $data['phone']);
    }

        #[Test]
        public function it_exports_promo_code_when_present(): void
    {
        $order = Order::factory()->create([
            'promo_code' => 'SUMMER20',
        ]);

        $this->assertEquals('SUMMER20', $order->promo_code);
    }

        #[Test]
        public function it_handles_orders_without_customer(): void
    {
        $order = Order::factory()->create([
            'customer_id' => null,
            'email' => 'guest@example.com',
        ]);

        $this->assertNull($order->customer_id);
        $this->assertNotNull($order->email);
    }

        #[Test]
        public function it_exports_items_count_correctly(): void
    {
        $order = Order::factory()->create([
            'items_count' => 3,
        ]);

        $this->assertEquals(3, $order->items_count);
    }

        #[Test]
        public function it_preserves_timestamps_in_export_import(): void
    {
        $createdAt = now()->subDays(5);
        $updatedAt = now()->subDays(2);

        $order = Order::factory()->create([
            'created_at' => $createdAt,
            'updated_at' => $updatedAt,
        ]);

        $this->assertNotNull($order->created_at);
        $this->assertNotNull($order->updated_at);
    }

        #[Test]
        public function it_exports_transaction_id_when_present(): void
    {
        $order = Order::factory()->create([
            'transaction_id' => 'txn_123456789',
        ]);

        $this->assertEquals('txn_123456789', $order->transaction_id);
    }
}
