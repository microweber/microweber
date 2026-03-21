<?php

namespace Modules\Tax\Tests;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Modules\Tax\Models\TaxRate;

class TaxRateModelTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        TaxRate::query()->delete();
    }

    protected function tearDown(): void
    {
        TaxRate::query()->delete();
        parent::tearDown();
    }

    #[Test]
    public function it_creates_a_tax_rate(): void
    {
        $taxRate = TaxRate::create([
            'name' => 'VAT',
            'type' => 'percentage',
            'rate' => 20.00,
            'country_code' => 'GB',
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('tax_rates', [
            'name' => 'VAT',
            'rate' => 20.00,
            'country_code' => 'GB',
        ]);
    }

    #[Test]
    public function it_calculates_percentage_tax(): void
    {
        $taxRate = TaxRate::create([
            'name' => 'VAT',
            'type' => 'percentage',
            'rate' => 20.00,
            'is_active' => true,
        ]);

        $this->assertEquals(20.00, $taxRate->calculate(100.00));
        $this->assertEquals(10.00, $taxRate->calculate(50.00));
    }

    #[Test]
    public function it_calculates_fixed_tax(): void
    {
        $taxRate = TaxRate::create([
            'name' => 'Fixed Fee',
            'type' => 'fixed',
            'rate' => 5.00,
            'is_active' => true,
        ]);

        $this->assertEquals(5.00, $taxRate->calculate(100.00));
        $this->assertEquals(5.00, $taxRate->calculate(1000.00));
    }

    #[Test]
    public function it_applies_to_location_country_only(): void
    {
        $taxRate = TaxRate::create([
            'name' => 'UK VAT',
            'type' => 'percentage',
            'rate' => 20.00,
            'country_code' => 'GB',
            'is_active' => true,
        ]);

        $this->assertTrue($taxRate->appliesToLocation(['country_code' => 'GB']));
        $this->assertFalse($taxRate->appliesToLocation(['country_code' => 'US']));
    }

    #[Test]
    public function it_applies_to_location_with_state(): void
    {
        $taxRate = TaxRate::create([
            'name' => 'California Tax',
            'type' => 'percentage',
            'rate' => 8.00,
            'country_code' => 'US',
            'state_code' => 'CA',
            'is_active' => true,
        ]);

        $this->assertTrue($taxRate->appliesToLocation([
            'country_code' => 'US',
            'state_code' => 'CA',
        ]));
        $this->assertFalse($taxRate->appliesToLocation([
            'country_code' => 'US',
            'state_code' => 'NY',
        ]));
    }

    #[Test]
    public function it_applies_to_location_with_zip_code(): void
    {
        $taxRate = TaxRate::create([
            'name' => 'NYC Tax',
            'type' => 'percentage',
            'rate' => 8.875,
            'country_code' => 'US',
            'state_code' => 'NY',
            'zip_code_pattern' => '100*',
            'is_active' => true,
        ]);

        $this->assertTrue($taxRate->appliesToLocation([
            'country_code' => 'US',
            'state_code' => 'NY',
            'zip_code' => '10001',
        ]));
        $this->assertFalse($taxRate->appliesToLocation([
            'country_code' => 'US',
            'state_code' => 'NY',
            'zip_code' => '20001',
        ]));
    }

    #[Test]
    public function it_checks_validity_with_dates(): void
    {
        $pastTax = TaxRate::create([
            'name' => 'Past Tax',
            'type' => 'percentage',
            'rate' => 10.00,
            'valid_from' => now()->subDays(30),
            'valid_until' => now()->subDay(),
            'is_active' => true,
        ]);

        $this->assertFalse($pastTax->isValid());

        $currentTax = TaxRate::create([
            'name' => 'Current Tax',
            'type' => 'percentage',
            'rate' => 10.00,
            'valid_from' => now()->subDay(),
            'valid_until' => now()->addDay(),
            'is_active' => true,
        ]);

        $this->assertTrue($currentTax->isValid());

        $futureTax = TaxRate::create([
            'name' => 'Future Tax',
            'type' => 'percentage',
            'rate' => 10.00,
            'valid_from' => now()->addDay(),
            'is_active' => true,
        ]);

        $this->assertFalse($futureTax->isValid());
    }

    #[Test]
    public function it_returns_location_description(): void
    {
        $globalTax = TaxRate::create([
            'name' => 'Global',
            'is_active' => true,
        ]);

        $this->assertEquals('All locations', $globalTax->location_description);

        $countryTax = TaxRate::create([
            'name' => 'Country',
            'country_code' => 'US',
            'is_active' => true,
        ]);

        $this->assertStringContainsString('US', $countryTax->location_description);
    }

    #[Test]
    public function it_returns_formatted_rate(): void
    {
        $percentageTax = TaxRate::create([
            'name' => 'VAT',
            'type' => 'percentage',
            'rate' => 20.00,
        ]);

        $this->assertEquals('20%', $percentageTax->formatted_rate);

        $fixedTax = TaxRate::create([
            'name' => 'Fee',
            'type' => 'fixed',
            'rate' => 5.00,
        ]);

        // Fixed rate should return currency formatted
        $this->assertNotNull($fixedTax->formatted_rate);
    }

    #[Test]
    public function it_scopes_active_rates(): void
    {
        TaxRate::create([
            'name' => 'Active',
            'is_active' => true,
        ]);

        TaxRate::create([
            'name' => 'Inactive',
            'is_active' => false,
        ]);

        $activeRates = TaxRate::active()->get();

        $this->assertCount(1, $activeRates);
        $this->assertEquals('Active', $activeRates->first()->name);
    }

    #[Test]
    public function it_scopes_by_country(): void
    {
        TaxRate::create([
            'name' => 'UK',
            'country_code' => 'GB',
            'is_active' => true,
        ]);

        TaxRate::create([
            'name' => 'US',
            'country_code' => 'US',
            'is_active' => true,
        ]);

        TaxRate::create([
            'name' => 'Global',
            'country_code' => null,
            'is_active' => true,
        ]);

        $ukRates = TaxRate::forCountry('GB')->get();
        $this->assertCount(2, $ukRates); // UK + Global

        $globalRates = TaxRate::forCountry(null)->get();
        $this->assertCount(1, $globalRates); // Only Global
    }

    #[Test]
    public function it_scopes_currently_valid(): void
    {
        TaxRate::create([
            'name' => 'Current',
            'is_active' => true,
            'valid_from' => now()->subDay(),
            'valid_until' => now()->addDay(),
        ]);

        TaxRate::create([
            'name' => 'Expired',
            'is_active' => true,
            'valid_from' => now()->subDays(30),
            'valid_until' => now()->subDay(),
        ]);

        $validRates = TaxRate::currentlyValid()->get();
        $this->assertCount(1, $validRates);
        $this->assertEquals('Current', $validRates->first()->name);
    }
}
