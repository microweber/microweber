<?php

namespace Modules\Currency\Tests\Unit;

use Modules\Currency\Models\Currency;
use Modules\Currency\Models\ExchangeRate;
use Modules\Currency\Services\CurrencyConversionService;
use Modules\Currency\Services\CurrencyManager;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Contract for the "switching currency actually converts the displayed price"
 * wiring: CurrencyManager::formatPrice() converts a STORED base-currency amount
 * into the selected currency before formatting, and the ExchangeRate model keeps
 * inverse_rate / codes consistent on save (so admin-entered rates are complete).
 */
class CurrencyConversionDisplayTest extends TestCase
{
    protected CurrencyManager $currencyManager;
    protected CurrencyConversionService $conversionService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->currencyManager = app(CurrencyManager::class);
        $this->conversionService = app(CurrencyConversionService::class);

        ExchangeRate::query()->delete();
        Currency::query()->delete();
        $this->currencyManager->clearCache();
        $this->conversionService->clearCache();

        // Base currency USD (default) + EUR. Prices are stored in USD.
        Currency::create([
            'name' => 'US Dollar', 'code' => 'USD', 'symbol' => '$',
            'precision' => 2, 'thousand_separator' => ',', 'decimal_separator' => '.',
            'swap_currency_symbol' => false, 'is_active' => true, 'is_default' => true,
        ]);
        Currency::create([
            'name' => 'Euro', 'code' => 'EUR', 'symbol' => '€',
            'precision' => 2, 'thousand_separator' => '.', 'decimal_separator' => ',',
            'swap_currency_symbol' => false, 'is_active' => true, 'is_default' => false,
        ]);
        ExchangeRate::updateOrCreateRate('USD', 'EUR', 0.90, 'manual');
    }

    protected function tearDown(): void
    {
        ExchangeRate::query()->delete();
        Currency::query()->delete();
        parent::tearDown();
    }

    #[Test]
    public function format_price_converts_a_base_amount_into_the_selected_currency(): void
    {
        config(['currency.multi_currency_enabled' => true]);
        $this->currencyManager->setCurrency('EUR');

        // 100 USD * 0.90 = 90 EUR (the number must change, not just the symbol).
        $this->assertSame(90.0, $this->currencyManager->convertFromBase(100.0, 'EUR'));

        $formatted = $this->currencyManager->formatPrice(100);
        $this->assertStringContainsString('90', $formatted);
        $this->assertStringNotContainsString('100', $formatted);

        $this->currencyManager->setCurrency('USD');
    }

    #[Test]
    public function format_price_is_a_noop_when_multi_currency_is_disabled(): void
    {
        config(['currency.multi_currency_enabled' => false]);
        $this->currencyManager->setCurrency('EUR');

        // Disabled → the stored amount is shown as-is (no rate multiplication).
        $this->assertSame(100.0, $this->currencyManager->convertFromBase(100.0, 'EUR'));
        $this->assertStringContainsString('100', $this->currencyManager->formatPrice(100));

        $this->currencyManager->setCurrency('USD');
    }

    #[Test]
    public function convert_from_base_returns_the_amount_unchanged_when_no_rate_exists(): void
    {
        config(['currency.multi_currency_enabled' => true]);
        ExchangeRate::query()->delete();
        $this->conversionService->clearCache();

        // No rate → render the base amount (symbol still swaps) rather than throw.
        $this->assertSame(100.0, $this->currencyManager->convertFromBase(100.0, 'EUR'));
    }

    #[Test]
    public function exchange_rate_autopopulates_inverse_and_uppercases_codes_on_save(): void
    {
        // setUp seeds a USD→EUR rate; clear so this create isn't a duplicate of
        // the unique (from_currency,to_currency) pair.
        ExchangeRate::query()->delete();

        $rate = ExchangeRate::create([
            'from_currency' => 'usd',
            'to_currency' => 'eur',
            'rate' => 0.80,
            'source' => 'manual',
            'is_active' => true,
        ]);

        $this->assertSame('USD', $rate->from_currency);
        $this->assertSame('EUR', $rate->to_currency);
        $this->assertEqualsWithDelta(1.25, (float) $rate->inverse_rate, 0.0001);
        $this->assertNotNull($rate->last_updated);
    }
}
