<?php

namespace Modules\Tax\Tests;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Modules\Tax\Models\TaxRate;
use Modules\Tax\Services\TaxCalculator;

class TaxCalculatorTest extends TestCase
{
    protected TaxCalculator $calculator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->calculator = app(TaxCalculator::class);
        TaxRate::query()->delete();
    }

    protected function tearDown(): void
    {
        TaxRate::query()->delete();
        parent::tearDown();
    }

    #[Test]
    public function it_calculates_percentage_tax(): void
    {
        $taxRate = TaxRate::create([
            'name' => 'VAT',
            'type' => 'percentage',
            'rate' => 20.00,
            'country_code' => null,
            'is_active' => true,
        ]);

        $result = $this->calculator->calculate(100.00, []);

        $this->assertEquals(20.00, $result['amount']);
        $this->assertCount(1, $result['breakdown']);
        $this->assertEquals('VAT', $result['breakdown'][0]['name']);
    }

    #[Test]
    public function it_calculates_fixed_amount_tax(): void
    {
        $taxRate = TaxRate::create([
            'name' => 'Environmental Fee',
            'type' => 'fixed',
            'rate' => 5.00,
            'country_code' => null,
            'is_active' => true,
        ]);

        $result = $this->calculator->calculate(100.00, []);

        $this->assertEquals(5.00, $result['amount']);
    }

    #[Test]
    public function it_applies_country_specific_tax(): void
    {
        // US specific tax
        TaxRate::create([
            'name' => 'US Sales Tax',
            'type' => 'percentage',
            'rate' => 10.00,
            'country_code' => 'US',
            'is_active' => true,
        ]);

        // UK specific tax
        TaxRate::create([
            'name' => 'UK VAT',
            'type' => 'percentage',
            'rate' => 20.00,
            'country_code' => 'GB',
            'is_active' => true,
            'priority' => 10,
        ]);

        // Test UK location - should only match UK VAT
        $result = $this->calculator->calculate(100.00, ['country_code' => 'GB']);
        $this->assertEquals(20.00, $result['amount']);

        // Test US location - should only match US Sales Tax
        $result = $this->calculator->calculate(100.00, ['country_code' => 'US']);
        $this->assertEquals(10.00, $result['amount']);

        // Test other location - should match nothing
        $result = $this->calculator->calculate(100.00, ['country_code' => 'FR']);
        $this->assertEquals(0.00, $result['amount']);
    }

    #[Test]
    public function it_applies_state_specific_tax(): void
    {
        TaxRate::create([
            'name' => 'US Sales Tax',
            'type' => 'percentage',
            'rate' => 8.00,
            'country_code' => 'US',
            'state_code' => 'CA',
            'is_active' => true,
            'priority' => 20,
        ]);

        $result = $this->calculator->calculate(100.00, [
            'country_code' => 'US',
            'state_code' => 'CA',
        ]);

        $this->assertEquals(8.00, $result['amount']);
    }

    #[Test]
    public function it_applies_zip_code_pattern_tax(): void
    {
        // Create a state-wide tax
        TaxRate::create([
            'name' => 'NY State Tax',
            'type' => 'percentage',
            'rate' => 4.00,
            'country_code' => 'US',
            'state_code' => 'NY',
            'is_active' => true,
            'priority' => 10,
        ]);

        // Create a ZIP code specific tax (more specific)
        TaxRate::create([
            'name' => 'NYC Tax',
            'type' => 'percentage',
            'rate' => 4.875,
            'country_code' => 'US',
            'state_code' => 'NY',
            'zip_code_pattern' => '100*',
            'is_active' => true,
            'priority' => 30,
        ]);

        $result = $this->calculator->calculate(100.00, [
            'country_code' => 'US',
            'state_code' => 'NY',
            'zip_code' => '10001',
        ]);

        // Should match both NY State Tax (4%) and NYC Tax (4.875%) = 8.875% = $8.88 rounded
        $this->assertEquals(8.88, $result['amount']);
    }

    #[Test]
    public function it_calculates_compound_tax(): void
    {
        TaxRate::create([
            'name' => 'Base Tax',
            'type' => 'percentage',
            'rate' => 10.00,
            'is_active' => true,
            'priority' => 1,
        ]);

        TaxRate::create([
            'name' => 'Compound Tax',
            'type' => 'percentage',
            'rate' => 5.00,
            'is_active' => true,
            'compound_tax' => true,
            'priority' => 0,
        ]);

        $result = $this->calculator->calculate(100.00, []);

        // Base tax: $100 * 10% = $10
        // After base tax: $100 + $10 = $110
        // Compound tax: $110 * 5% = $5.50
        // Total: $15.50 = $15.5
        $this->assertEquals(15.5, $result['amount']);
    }

    #[Test]
    public function it_returns_zero_for_inactive_tax(): void
    {
        TaxRate::create([
            'name' => 'Inactive Tax',
            'type' => 'percentage',
            'rate' => 20.00,
            'is_active' => false,
        ]);

        $result = $this->calculator->calculate(100.00, []);

        $this->assertEquals(0.00, $result['amount']);
        $this->assertEmpty($result['breakdown']);
    }

    #[Test]
    public function it_returns_zero_for_expired_tax(): void
    {
        TaxRate::create([
            'name' => 'Expired Tax',
            'type' => 'percentage',
            'rate' => 20.00,
            'is_active' => true,
            'valid_until' => now()->subDay(),
        ]);

        $result = $this->calculator->calculate(100.00, []);

        $this->assertEquals(0.00, $result['amount']);
    }

    #[Test]
    public function it_returns_zero_for_future_tax(): void
    {
        TaxRate::create([
            'name' => 'Future Tax',
            'type' => 'percentage',
            'rate' => 20.00,
            'is_active' => true,
            'valid_from' => now()->addDay(),
        ]);

        $result = $this->calculator->calculate(100.00, []);

        $this->assertEquals(0.00, $result['amount']);
    }

    #[Test]
    public function it_validates_location_data(): void
    {
        $location = [
            'country_code' => '  us  ',
            'state_code' => '  ca  ',
            'zip_code' => '  12345-6789  ',
        ];

        $validated = $this->calculator->validateLocation($location);

        $this->assertEquals('US', $validated['country_code']);
        $this->assertEquals('CA', $validated['state_code']);
        $this->assertEquals('123456789', $validated['zip_code']);
    }

    #[Test]
    public function it_prioritizes_specific_rates_over_general(): void
    {
        // Most general
        TaxRate::create([
            'name' => 'Global',
            'type' => 'percentage',
            'rate' => 5.00,
            'is_active' => true,
            'priority' => 0,
        ]);

        // Country specific
        TaxRate::create([
            'name' => 'US',
            'type' => 'percentage',
            'rate' => 10.00,
            'country_code' => 'US',
            'is_active' => true,
            'priority' => 10,
        ]);

        // State specific
        TaxRate::create([
            'name' => 'CA',
            'type' => 'percentage',
            'rate' => 15.00,
            'country_code' => 'US',
            'state_code' => 'CA',
            'is_active' => true,
            'priority' => 20,
        ]);

        $result = $this->calculator->calculate(100.00, [
            'country_code' => 'US',
            'state_code' => 'CA',
        ]);

        // Should apply all three taxes
        $this->assertEquals(30.00, $result['amount']);
    }

    #[Test]
    public function it_gets_tax_summary(): void
    {
        TaxRate::create([
            'name' => 'VAT',
            'type' => 'percentage',
            'rate' => 20.00,
            'is_active' => true,
        ]);

        $summary = $this->calculator->getTaxSummary(100.00, []);

        $this->assertEquals(100.00, $summary['subtotal']);
        $this->assertEquals(20.00, $summary['tax_amount']);
        $this->assertEquals(120.00, $summary['total']);
        $this->assertCount(1, $summary['tax_rates']);
    }

    #[Test]
    public function it_caches_tax_rates(): void
    {
        TaxRate::create([
            'name' => 'Test Tax',
            'type' => 'percentage',
            'rate' => 10.00,
            'is_active' => true,
        ]);

        $result1 = $this->calculator->calculate(100.00, []);
        $result2 = $this->calculator->calculate(100.00, []);

        $this->assertEquals($result1['amount'], $result2['amount']);
    }

    #[Test]
    public function it_handles_zero_amount(): void
    {
        TaxRate::create([
            'name' => 'Test Tax',
            'type' => 'percentage',
            'rate' => 20.00,
            'is_active' => true,
        ]);

        $result = $this->calculator->calculate(0.00, []);

        $this->assertEquals(0.00, $result['amount']);
        $this->assertEmpty($result['breakdown']);
    }
}
