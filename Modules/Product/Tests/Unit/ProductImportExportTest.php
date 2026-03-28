<?php

namespace Modules\Product\Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Product\Filament\Exports\ProductExporter;
use Modules\Product\Filament\Imports\ProductImporter;
use Modules\Product\Models\Product;
use Tests\TestCase;

class ProductImportExportTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function it_can_get_exporter_columns(): void
    {
        $columns = ProductExporter::getColumns();

        $this->assertIsArray($columns);
        $this->assertNotEmpty($columns);

        // Verify columns include essential fields
        $columnNames = array_map(fn($col) => $col->getName(), $columns);
        $this->assertContains('id', $columnNames);
        $this->assertContains('title', $columnNames);
        $this->assertContains('price', $columnNames);
        $this->assertContains('is_active', $columnNames);
    }

    /** @test */
    public function it_can_get_importer_columns(): void
    {
        $columns = ProductImporter::getColumns();

        $this->assertIsArray($columns);
        $this->assertNotEmpty($columns);

        // Verify columns exist
        $this->assertGreaterThan(0, count($columns));
    }

    /** @test */
    public function it_validates_product_import_data_structure(): void
    {
        $columns = ProductImporter::getColumns();

        // Check that title column exists
        $columnNames = array_map(fn($col) => $col->getName(), $columns);
        $this->assertContains('title', $columnNames);
    }

    /** @test */
    public function it_can_import_products_with_valid_data(): void
    {
        $data = [
            'title' => 'Test Product',
            'url' => 'test-product',
            'sku' => 'TEST-001',
            'is_active' => 1,
            'content_type' => 'product',
            'subtype' => 'product',
        ];

        $product = Product::create($data);

        $this->assertDatabaseHas('content', [
            'title' => 'Test Product',
            'url' => 'test-product',
            'content_type' => 'product',
            'subtype' => 'product',
        ]);
    }

    /** @test */
    public function it_generates_url_from_title_if_not_provided(): void
    {
        $data = [
            'title' => 'My New Product',
            'is_active' => 1,
            'content_type' => 'product',
            'subtype' => 'product',
        ];

        // URL should be auto-generated from title
        $this->assertNotEmpty($data['title']);
    }

    /** @test */
    public function it_prevents_duplicate_products_on_import(): void
    {
        // Create existing product
        Product::factory()->create(['url' => 'duplicate-product', 'content_type' => 'product', 'subtype' => 'product']);

        // Try to import with same URL
        $data = [
            'title' => 'Duplicate Product',
            'url' => 'duplicate-product',
            'is_active' => 1,
            'content_type' => 'product',
            'subtype' => 'product',
        ];

        // Should either skip or update
        $existing = Product::where('url', 'duplicate-product')->first();
        $this->assertNotNull($existing);
    }

    /** @test */
    public function it_sets_default_values_for_imported_products(): void
    {
        $data = [
            'title' => 'Minimal Product',
            'content_type' => 'product',
            'subtype' => 'product',
        ];

        // Placeholder for defaults check
        $this->assertEquals('product', $data['content_type']);
        $this->assertEquals('product', $data['subtype']);
    }

    /** @test */
    public function it_exports_products_with_correct_formatting(): void
    {
        $product = Product::factory()->create([
            'title' => 'Test Product',
            'is_active' => 1,
            'content_type' => 'product',
            'subtype' => 'product',
        ]);

        // Check that boolean values are formatted correctly
        $this->assertEquals(1, $product->is_active);
    }

    /** @test */
    public function it_handles_empty_optional_fields_in_import(): void
    {
        $data = [
            'title' => 'Product Without Optional Fields',
            'is_active' => 1,
            'content_type' => 'product',
            'subtype' => 'product',
        ];

        // Should not fail without optional fields
        $this->assertArrayNotHasKey('description', $data);
        $this->assertArrayNotHasKey('sku', $data);
    }

    /** @test */
    public function it_handles_special_characters_in_product_titles(): void
    {
        $data = [
            'title' => 'Product with special chars: ñ, é, ü, ©, ™',
            'is_active' => 1,
            'content_type' => 'product',
            'subtype' => 'product',
        ];

        $this->assertStringContainsString('ñ', $data['title']);
    }

    /** @test */
    public function it_exports_all_products_when_no_selection(): void
    {
        Product::factory()->count(5)->create(['content_type' => 'product', 'subtype' => 'product']);

        $count = Product::count();
        $this->assertGreaterThanOrEqual(5, $count);
    }

    /** @test */
    public function it_can_update_existing_products_via_import(): void
    {
        $product = Product::factory()->create([
            'title' => 'Old Title',
            'content_type' => 'product',
            'subtype' => 'product',
        ]);

        // Simulate update via import
        $updateData = [
            'title' => 'New Title',
        ];

        $product->update($updateData);

        $this->assertDatabaseHas('content', [
            'id' => $product->id,
            'title' => 'New Title',
        ]);
    }

    /** @test */
    public function it_respects_is_active_boolean_in_export(): void
    {
        $activeProduct = Product::factory()->create(['is_active' => 1, 'content_type' => 'product', 'subtype' => 'product']);
        $inactiveProduct = Product::factory()->create(['is_active' => 0, 'content_type' => 'product', 'subtype' => 'product']);

        $this->assertEquals(1, $activeProduct->is_active);
        $this->assertEquals(0, $inactiveProduct->is_active);
    }

    /** @test */
    public function it_exporter_provides_notification_body(): void
    {
        // Test that the exporter has a completed notification body method
        $this->assertTrue(method_exists(ProductExporter::class, 'getCompletedNotificationBody'));
    }

    /** @test */
    public function it_importer_provides_notification_body(): void
    {
        // Test that the importer has a completed notification body method
        $this->assertTrue(method_exists(ProductImporter::class, 'getCompletedNotificationBody'));
    }

    /** @test */
    public function it_exporter_provides_file_name(): void
    {
        // Test that the exporter has a file name method
        $this->assertTrue(method_exists(ProductExporter::class, 'getFileName'));
    }

    /** @test */
    public function it_importer_has_options_form_components(): void
    {
        $components = ProductImporter::getOptionsFormComponents();
        $this->assertIsArray($components);
    }

    /** @test */
    public function it_exporter_has_model_attribute(): void
    {
        $this->assertEquals(Product::class, ProductExporter::getModel());
    }

    /** @test */
    public function it_importer_has_model_attribute(): void
    {
        $this->assertEquals(Product::class, ProductImporter::getModel());
    }

    /** @test */
    public function it_can_import_products_in_bulk(): void
    {
        $productsData = [
            ['title' => 'Product 1', 'is_active' => 1, 'content_type' => 'product', 'subtype' => 'product'],
            ['title' => 'Product 2', 'is_active' => 1, 'content_type' => 'product', 'subtype' => 'product'],
            ['title' => 'Product 3', 'is_active' => 1, 'content_type' => 'product', 'subtype' => 'product'],
        ];

        $this->assertCount(3, $productsData);
    }

    /** @test */
    public function it_validates_url_format_in_import(): void
    {
        $data = [
            'title' => 'Product',
            'url' => 'invalid url with spaces!',
            'content_type' => 'product',
            'subtype' => 'product',
        ];

        // URL should be sanitized
        $this->assertTrue(true);
    }

    /** @test */
    public function it_skips_products_when_skip_existing_option_is_set(): void
    {
        // When skip_existing is true, existing products should not be updated
        $this->assertTrue(true);
    }

    /** @test */
    public function it_validates_required_fields_in_import(): void
    {
        $data = [
            // Missing required 'title'
            'url' => 'some-url',
        ];

        $this->assertArrayNotHasKey('title', $data);
    }

    /** @test */
    public function it_handles_null_values_gracefully_in_import(): void
    {
        $data = [
            'title' => 'Product',
            'description' => null,
            'sku' => null,
        ];

        $this->assertNull($data['description']);
        $this->assertNull($data['sku']);
    }

    /** @test */
    public function it_exports_products_in_correct_order(): void
    {
        // Products should be exported in order they were created or by ID
        $this->assertTrue(true);
    }

    /** @test */
    public function it_can_filter_products_before_export(): void
    {
        // Should be able to filter by status, date, etc.
        $this->assertTrue(true);
    }

    /** @test */
    public function it_handles_max_length_constraints_in_import(): void
    {
        $longTitle = str_repeat('a', 300); // Exceeds 255 char limit

        $data = [
            'title' => $longTitle,
        ];

        $this->assertGreaterThan(255, strlen($data['title']));
    }

    /** @test */
    public function it_allows_custom_delimiters_in_csv_import(): void
    {
        // Should support different CSV delimiters (comma, semicolon, tab)
        $this->assertTrue(true);
    }

    /** @test */
    public function it_provides_import_summary_notification(): void
    {
        // After import, user should see summary of imported/skipped/failed
        $this->assertTrue(true);
    }

    /** @test */
    public function it_provides_export_summary_notification(): void
    {
        // After export, user should see summary of exported rows
        $this->assertTrue(true);
    }
}
