<?php

namespace Modules\Currency\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Modules\Currency\Models\Currency;
use Modules\Currency\Models\ExchangeRate;

/**
 * Currency Conversion Service
 *
 * Handles all currency conversion operations including:
 * - Converting amounts between currencies
 * - Managing exchange rate lookups
 * - Supporting cross-currency conversions
 * - Caching conversion rates for performance
 */
class CurrencyConversionService
{
    /**
     * Cache TTL for exchange rates in seconds.
     *
     * @var int
     */
    protected int $cacheTtl = 3600; // 1 hour

    /**
     * Base currency for conversions (default).
     *
     * @var string
     */
    protected string $baseCurrency;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->baseCurrency = $this->getDefaultCurrencyCode();
    }

    /**
     * Convert an amount from one currency to another.
     *
     * @param float $amount The amount to convert
     * @param string $fromCurrency Source currency code (e.g., 'USD')
     * @param string $toCurrency Target currency code (e.g., 'EUR')
     * @param bool $useCache Whether to use cached rates
     * @return float The converted amount
     * @throws \InvalidArgumentException If currency conversion is not possible
     */
    public function convert(
        float $amount,
        string $fromCurrency,
        string $toCurrency,
        bool $useCache = true
    ): float {
        $fromCurrency = strtoupper($fromCurrency);
        $toCurrency = strtoupper($toCurrency);

        // No conversion needed for same currency
        if ($fromCurrency === $toCurrency) {
            return $amount;
        }

        $rate = $this->getExchangeRate($fromCurrency, $toCurrency, $useCache);

        if ($rate === null) {
            throw new \InvalidArgumentException(
                "Exchange rate not found from {$fromCurrency} to {$toCurrency}"
            );
        }

        return round($amount * $rate, $this->getPrecision($toCurrency));
    }

    /**
     * Get the exchange rate between two currencies.
     *
     * @param string $fromCurrency Source currency code
     * @param string $toCurrency Target currency code
     * @param bool $useCache Whether to use cached rates
     * @return float|null The exchange rate or null if not found
     */
    public function getExchangeRate(
        string $fromCurrency,
        string $toCurrency,
        bool $useCache = true
    ): ?float {
        $fromCurrency = strtoupper($fromCurrency);
        $toCurrency = strtoupper($toCurrency);

        if ($fromCurrency === $toCurrency) {
            return 1.0;
        }

        $cacheKey = "exchange_rate:{$fromCurrency}:{$toCurrency}";

        if ($useCache) {
            $cachedRate = Cache::get($cacheKey);
            if ($cachedRate !== null) {
                return (float) $cachedRate;
            }
        }

        // Try direct rate
        $rate = $this->getDirectRate($fromCurrency, $toCurrency);

        // Try cross-rate via base currency
        if ($rate === null && $fromCurrency !== $this->baseCurrency) {
            $rate = $this->getCrossRate($fromCurrency, $toCurrency);
        }

        // Try inverse rate
        if ($rate === null) {
            $rate = $this->getInverseRate($fromCurrency, $toCurrency);
        }

        if ($rate !== null && $useCache) {
            Cache::put($cacheKey, $rate, $this->cacheTtl);
        }

        return $rate;
    }

    /**
     * Get direct exchange rate from database.
     *
     * @param string $fromCurrency
     * @param string $toCurrency
     * @return float|null
     */
    protected function getDirectRate(string $fromCurrency, string $toCurrency): ?float
    {
        $exchangeRate = ExchangeRate::getRate($fromCurrency, $toCurrency);

        if ($exchangeRate && $exchangeRate->rate > 0) {
            return (float) $exchangeRate->rate;
        }

        return null;
    }

    /**
     * Calculate cross-rate via base currency.
     *
     * @param string $fromCurrency
     * @param string $toCurrency
     * @return float|null
     */
    protected function getCrossRate(string $fromCurrency, string $toCurrency): ?float
    {
        $fromToBase = $this->getDirectRate($fromCurrency, $this->baseCurrency);

        // Try inverse if direct from->base not found (e.g., base->from exists)
        if ($fromToBase === null) {
            $baseToFrom = $this->getDirectRate($this->baseCurrency, $fromCurrency);
            if ($baseToFrom !== null && $baseToFrom > 0) {
                $fromToBase = 1 / $baseToFrom;
            }
        }

        $baseToTarget = $this->getDirectRate($this->baseCurrency, $toCurrency);

        // Try inverse if direct base->target not found
        if ($baseToTarget === null) {
            $targetToBase = $this->getDirectRate($toCurrency, $this->baseCurrency);
            if ($targetToBase !== null && $targetToBase > 0) {
                $baseToTarget = 1 / $targetToBase;
            }
        }

        if ($fromToBase !== null && $baseToTarget !== null && $fromToBase > 0) {
            return $baseToTarget / $fromToBase;
        }

        return null;
    }

    /**
     * Get rate by inverting existing rate.
     *
     * @param string $fromCurrency
     * @param string $toCurrency
     * @return float|null
     */
    protected function getInverseRate(string $fromCurrency, string $toCurrency): ?float
    {
        $inverseRate = ExchangeRate::getRate($toCurrency, $fromCurrency);

        if ($inverseRate && $inverseRate->rate > 0) {
            return 1 / (float) $inverseRate->rate;
        }

        return null;
    }

    /**
     * Get the default currency code from settings.
     *
     * @return string
     */
    public function getDefaultCurrencyCode(): string
    {
        $currency = get_option('currency', 'payments');
        return $currency ?: 'USD';
    }

    /**
     * Get the precision (decimal places) for a currency.
     *
     * @param string $currencyCode
     * @return int
     */
    public function getPrecision(string $currencyCode): int
    {
        $currency = Currency::where('code', strtoupper($currencyCode))->first();
        return $currency ? (int) $currency->precision : 2;
    }

    /**
     * Get all available currencies.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAvailableCurrencies()
    {
        return Currency::all();
    }

    /**
     * Get currencies that have exchange rates defined.
     *
     * @param string|null $baseCurrency
     * @return array
     */
    public function getCurrenciesWithRates(?string $baseCurrency = null): array
    {
        $baseCurrency = $baseCurrency ?: $this->baseCurrency;

        return ExchangeRate::active()
            ->fromCurrency($baseCurrency)
            ->pluck('to_currency')
            ->toArray();
    }

    /**
     * Check if a currency conversion is possible.
     *
     * @param string $fromCurrency
     * @param string $toCurrency
     * @return bool
     */
    public function canConvert(string $fromCurrency, string $toCurrency): bool
    {
        $fromCurrency = strtoupper($fromCurrency);
        $toCurrency = strtoupper($toCurrency);

        if ($fromCurrency === $toCurrency) {
            return true;
        }

        try {
            $rate = $this->getExchangeRate($fromCurrency, $toCurrency);
            return $rate !== null && $rate > 0;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Clear the exchange rate cache.
     *
     * @param string|null $fromCurrency Clear only rates from specific currency
     * @param string|null $toCurrency Clear only rates to specific currency
     * @return void
     */
    public function clearCache(?string $fromCurrency = null, ?string $toCurrency = null): void
    {
        if ($fromCurrency && $toCurrency) {
            Cache::forget("exchange_rate:{$fromCurrency}:{$toCurrency}");
        } elseif ($fromCurrency) {
            // Clear all rates from this currency
            $currencies = $this->getAvailableCurrencies();
            foreach ($currencies as $currency) {
                Cache::forget("exchange_rate:{$fromCurrency}:{$currency->code}");
            }
        } else {
            // Clear all exchange rate caches
            // Note: This is a pattern match, may not work with all cache drivers
            Cache::flush();
        }
    }

    /**
     * Set the cache TTL.
     *
     * @param int $seconds
     * @return $this
     */
    public function setCacheTtl(int $seconds): self
    {
        $this->cacheTtl = $seconds;
        return $this;
    }

    /**
     * Batch convert multiple amounts.
     *
     * @param array $amounts Array of amounts
     * @param string $fromCurrency Source currency
     * @param string $toCurrency Target currency
     * @return array Array of converted amounts
     */
    public function batchConvert(
        array $amounts,
        string $fromCurrency,
        string $toCurrency
    ): array {
        $rate = $this->getExchangeRate($fromCurrency, $toCurrency);

        if ($rate === null) {
            throw new \InvalidArgumentException(
                "Cannot convert from {$fromCurrency} to {$toCurrency}"
            );
        }

        $precision = $this->getPrecision($toCurrency);

        return array_map(function ($amount) use ($rate, $precision) {
            return round($amount * $rate, $precision);
        }, $amounts);
    }

    /**
     * Format an amount with currency symbol.
     *
     * @param float $amount
     * @param string $currencyCode
     * @return string
     */
    public function formatWithSymbol(float $amount, string $currencyCode): string
    {
        $currencyCode = strtoupper($currencyCode);
        $currency = Currency::where('code', $currencyCode)->first();

        if (!$currency) {
            return number_format($amount, 2) . ' ' . $currencyCode;
        }

        $formatted = number_format(
            $amount,
            $currency->precision,
            $currency->decimal_separator,
            $currency->thousand_separator
        );

        if ($currency->swap_currency_symbol) {
            return $formatted . ' ' . $currency->symbol;
        }

        return $currency->symbol . ' ' . $formatted;
    }

    /**
     * Log conversion attempt for debugging.
     *
     * @param float $amount
     * @param string $fromCurrency
     * @param string $toCurrency
     * @param float $result
     * @return void
     */
    protected function logConversion(
        float $amount,
        string $fromCurrency,
        string $toCurrency,
        float $result
    ): void {
        Log::debug('Currency conversion', [
            'amount' => $amount,
            'from' => $fromCurrency,
            'to' => $toCurrency,
            'result' => $result,
        ]);
    }
}
