<?php

namespace Modules\Currency\Tests\Unit;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Modules\Currency\Models\Currency;
use Modules\Currency\Models\ExchangeRate;
use Modules\Currency\Services\CurrencyManager;
use Modules\Currency\Services\CurrencyConversionService;
use Tests\TestCase;

class MultiCurrencyTest extends TestCase
{
    use LazilyRefreshDatabase;

    protected CurrencyManager $currencyManager;
    protected CurrencyConversionService $conversionService;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->currencyManager = app(CurrencyManager::class);
        $this->conversionService = app(CurrencyConversionService::class);
        
        // Clear cache before each test
        $this->currencyManager->clearCache();
        $this->conversionService->clearCache();
    }

    /** @test */
    public function it_can_create_currencies_with_required_fields(): void
    {
        $currency = Currency::create([
            'name' => 'Euro',
            'code' => 'EUR',
            'symbol' => '€',
            'precision' => 2,
            'thousand_separator' => '.',
            'decimal_separator' => ',',
            'swap_currency_symbol' => false,
            'is_active' => true,
            'is_default' => false,
            'position' => 1,
        ]);

        $this->assertDatabaseHas('currencies', [
            'code' => 'EUR',
            'name' => 'Euro',
            'symbol' => '€',
        ]);

        $this->assertEquals('EUR', $currency->code);
    }

    /** @test */
    public function currency_code_is_always_uppercase(): void
    {
        $currency = Currency::create([
            'name' => 'Test Currency',
            'code' => 'eur',
            'symbol' => '€',
            'precision' => 2,
            'thousand_separator' => '.',
            'decimal_separator' => ',',
            'swap_currency_symbol' => false,
        ]);

        $this->assertEquals('EUR', $currency->code);
    }

    /** @test */
    public function it_can_set_and_get_default_currency(): void
    {
        $currency = Currency::create([
            'name' => 'US Dollar',
            'code' => 'USD',
            'symbol' => '$',
            'precision' => 2,
            'thousand_separator' => ',',
            'decimal_separator' => '.',
            'swap_currency_symbol' => false,
            'is_active' => true,
        ]);

        $currency->setAsDefault();

        $this->assertTrue($currency->fresh()->is_default);
        $this->assertEquals('USD', Currency::getDefault()->code);
    }

    /** @test */
    public function only_one_currency_can_be_default(): void
    {
        $usd = Currency::create([
            'name' => 'US Dollar',
            'code' => 'USD',
            'symbol' => '$',
            'precision' => 2,
            'thousand_separator' => ',',
            'decimal_separator' => '.',
            'swap_currency_symbol' => false,
            'is_active' => true,
        ]);
        $usd->setAsDefault();

        $eur = Currency::create([
            'name' => 'Euro',
            'code' => 'EUR',
            'symbol' => '€',
            'precision' => 2,
            'thousand_separator' => '.',
            'decimal_separator' => ',',
            'swap_currency_symbol' => false,
            'is_active' => true,
        ]);
        $eur->setAsDefault();

        $this->assertFalse($usd->fresh()->is_default);
        $this->assertTrue($eur->fresh()->is_default);
    }

    /** @test */
    public function it_can_format_amounts_with_currency(): void
    {
        $currency = Currency::create([
            'name' => 'US Dollar',
            'code' => 'USD',
            'symbol' => '$',
            'precision' => 2,
            'thousand_separator' => ',',
            'decimal_separator' => '.',
            'swap_currency_symbol' => false,
        ]);

        $formatted = $currency->formatAmount(1234.56);
        $this->assertEquals('$ 1,234.56', $formatted);
    }

    /** @test */
    public function it_can_swap_currency_symbol_position(): void
    {
        $currency = Currency::create([
            'name' => 'Euro',
            'code' => 'EUR',
            'symbol' => '€',
            'precision' => 2,
            'thousand_separator' => '.',
            'decimal_separator' => ',',
            'swap_currency_symbol' => true,
        ]);

        $formatted = $currency->formatAmount(1234.56);
        $this->assertEquals('1.234,56 €', $formatted);
    }

    /** @test */
    public function it_can_create_exchange_rates(): void
    {
        $usd = Currency::create([
            'name' => 'US Dollar',
            'code' => 'USD',
            'symbol' => '$',
            'precision' => 2,
            'thousand_separator' => ',',
            'decimal_separator' => '.',
            'swap_currency_symbol' => false,
        ]);

        $eur = Currency::create([
            'name' => 'Euro',
            'code' => 'EUR',
            'symbol' => '€',
            'precision' => 2,
            'thousand_separator' => '.',
            'decimal_separator' => ',',
            'swap_currency_symbol' => true,
        ]);

        $rate = ExchangeRate::updateOrCreateRate('USD', 'EUR', 0.85, 'manual');

        $this->assertDatabaseHas('exchange_rates', [
            'from_currency' => 'USD',
            'to_currency' => 'EUR',
            'rate' => 0.85,
        ]);

        $this->assertEquals(0.85, $rate->fresh()->rate);
    }

    /** @test */
    public function it_can_convert_currencies(): void
    {
        Currency::create([
            'name' => 'US Dollar',
            'code' => 'USD',
            'symbol' => '$',
            'precision' => 2,
            'thousand_separator' => ',',
            'decimal_separator' => '.',
            'swap_currency_symbol' => false,
        ]);

        Currency::create([
            'name' => 'Euro',
            'code' => 'EUR',
            'symbol' => '€',
            'precision' => 2,
            'thousand_separator' => '.',
            'decimal_separator' => ',',
            'swap_currency_symbol' => true,
        ]);

        ExchangeRate::updateOrCreateRate('USD', 'EUR', 0.85, 'manual');

        $converted = $this->conversionService->convert(100, 'USD', 'EUR');
        
        $this->assertEquals(85.00, $converted);
    }

    /** @test */
    public function same_currency_conversion_returns_same_amount(): void
    {
        $converted = $this->conversionService->convert(100, 'USD', 'USD');
        
        $this->assertEquals(100, $converted);
    }

    /** @test */
    public function it_can_get_exchange_rate_via_cross_rate(): void
    {
        Currency::create([
            'name' => 'US Dollar',
            'code' => 'USD',
            'symbol' => '$',
            'precision' => 2,
            'thousand_separator' => ',',
            'decimal_separator' => '.',
            'swap_currency_symbol' => false,
        ]);

        Currency::create([
            'name' => 'Euro',
            'code' => 'EUR',
            'symbol' => '€',
            'precision' => 2,
            'thousand_separator' => '.',
            'decimal_separator' => ',',
            'swap_currency_symbol' => true,
        ]);

        Currency::create([
            'name' => 'British Pound',
            'code' => 'GBP',
            'symbol' => '£',
            'precision' => 2,
            'thousand_separator' => ',',
            'decimal_separator' => '.',
            'swap_currency_symbol' => false,
        ]);

        ExchangeRate::updateOrCreateRate('USD', 'EUR', 0.85, 'manual');
        ExchangeRate::updateOrCreateRate('USD', 'GBP', 0.75, 'manual');

        // This should calculate EUR->GBP via USD
        $rate = $this->conversionService->getExchangeRate('EUR', 'GBP');
        
        // Expected: 0.75 / 0.85 = ~0.882
        $this->assertNotNull($rate);
        $this->assertGreaterThan(0, $rate);
    }

    /** @test */
    public function currency_manager_can_set_and_get_current_currency(): void
    {
        Currency::create([
            'name' => 'US Dollar',
            'code' => 'USD',
            'symbol' => '$',
            'precision' => 2,
            'thousand_separator' => ',',
            'decimal_separator' => '.',
            'swap_currency_symbol' => false,
            'is_active' => true,
        ]);

        Currency::create([
            'name' => 'Euro',
            'code' => 'EUR',
            'symbol' => '€',
            'precision' => 2,
            'thousand_separator' => '.',
            'decimal_separator' => ',',
            'swap_currency_symbol' => true,
            'is_active' => true,
        ]);

        // Initially should return default or first active
        $current = $this->currencyManager->getCurrentCurrencyCode();
        $this->assertContains($current, ['USD', 'EUR']);

        // Switch to EUR
        $result = $this->currencyManager->setCurrency('EUR');
        $this->assertTrue($result);
        $this->assertEquals('EUR', $this->currencyManager->getCurrentCurrencyCode());
    }

    /** @test */
    public function currency_manager_cannot_set_inactive_currency(): void
    {
        Currency::create([
            'name' => 'US Dollar',
            'code' => 'USD',
            'symbol' => '$',
            'precision' => 2,
            'thousand_separator' => ',',
            'decimal_separator' => '.',
            'swap_currency_symbol' => false,
            'is_active' => true,
        ]);

        Currency::create([
            'name' => 'Euro',
            'code' => 'EUR',
            'symbol' => '€',
            'precision' => 2,
            'thousand_separator' => '.',
            'decimal_separator' => ',',
            'swap_currency_symbol' => true,
            'is_active' => false, // Inactive
        ]);

        $result = $this->currencyManager->setCurrency('EUR');
        $this->assertFalse($result);
    }

    /** @test */
    public function it_can_get_active_currencies_only(): void
    {
        Currency::create([
            'name' => 'US Dollar',
            'code' => 'USD',
            'symbol' => '$',
            'precision' => 2,
            'thousand_separator' => ',',
            'decimal_separator' => '.',
            'swap_currency_symbol' => false,
            'is_active' => true,
        ]);

        Currency::create([
            'name' => 'Euro',
            'code' => 'EUR',
            'symbol' => '€',
            'precision' => 2,
            'thousand_separator' => '.',
            'decimal_separator' => ',',
            'swap_currency_symbol' => true,
            'is_active' => false, // Inactive
        ]);

        Currency::create([
            'name' => 'British Pound',
            'code' => 'GBP',
            'symbol' => '£',
            'precision' => 2,
            'thousand_separator' => ',',
            'decimal_separator' => '.',
            'swap_currency_symbol' => false,
            'is_active' => true,
        ]);

        $activeCurrencies = $this->currencyManager->getActiveCurrencies();

        $this->assertCount(2, $activeCurrencies);
        $this->assertTrue($activeCurrencies->contains('code', 'USD'));
        $this->assertTrue($activeCurrencies->contains('code', 'GBP'));
        $this->assertFalse($activeCurrencies->contains('code', 'EUR'));
    }

    /** @test */
    public function helper_functions_work_correctly(): void
    {
        Currency::create([
            'name' => 'US Dollar',
            'code' => 'USD',
            'symbol' => '$',
            'precision' => 2,
            'thousand_separator' => ',',
            'decimal_separator' => '.',
            'swap_currency_symbol' => false,
            'is_active' => true,
            'is_default' => true,
        ]);

        $this->assertInstanceOf(CurrencyManager::class, currency_manager());
        $this->assertInstanceOf(CurrencyConversionService::class, currency_conversion());
        $this->assertEquals('USD', current_currency_code());
        $this->assertEquals('$', current_currency_symbol());
        $this->assertStringContainsString('$', currency_format(100));
    }

    /** @test */
    public function exchange_rate_is_calculated_correctly(): void
    {
        Currency::create([
            'name' => 'US Dollar',
            'code' => 'USD',
            'symbol' => '$',
            'precision' => 2,
        ]);

        Currency::create([
            'name' => 'Euro',
            'code' => 'EUR',
            'symbol' => '€',
            'precision' => 2,
        ]);

        $rate = ExchangeRate::updateOrCreateRate('USD', 'EUR', 0.85, 'manual');

        $this->assertEquals(0.85, $rate->rate);
        $this->assertEqualsWithDelta(1 / 0.85, $rate->inverse_rate, 0.0001);
    }

    /** @test */
    public function it_can_detect_stale_exchange_rates(): void
    {
        Currency::create([
            'name' => 'US Dollar',
            'code' => 'USD',
            'symbol' => '$',
            'precision' => 2,
        ]);

        Currency::create([
            'name' => 'Euro',
            'code' => 'EUR',
            'symbol' => '€',
            'precision' => 2,
        ]);

        $rate = ExchangeRate::updateOrCreateRate('USD', 'EUR', 0.85, 'manual');
        
        // Should not be stale immediately
        $this->assertFalse($rate->isStale(1));

        // Update last_updated to be old
        $rate->last_updated = now()->subHours(25);
        $rate->save();

        $this->assertTrue($rate->fresh()->isStale(24));
    }

    /** @test */
    public function currency_conversion_can_check_if_possible(): void
    {
        Currency::create([
            'name' => 'US Dollar',
            'code' => 'USD',
            'symbol' => '$',
            'precision' => 2,
        ]);

        Currency::create([
            'name' => 'Euro',
            'code' => 'EUR',
            'symbol' => '€',
            'precision' => 2,
        ]);

        ExchangeRate::updateOrCreateRate('USD', 'EUR', 0.85, 'manual');

        $this->assertTrue($this->conversionService->canConvert('USD', 'EUR'));
        $this->assertTrue($this->conversionService->canConvert('EUR', 'USD'));
        $this->assertTrue($this->conversionService->canConvert('USD', 'USD'));
    }
}
