<?php

namespace Modules\Currency\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Modules\Currency\Models\Currency;

/**
 * Currency Manager Service
 *
 * Manages currency switching, session persistence, and provides
 * helper methods for multi-currency support throughout the application.
 */
class CurrencyManager
{
    /**
     * Session key for storing selected currency.
     *
     * @var string
     */
    protected const SESSION_KEY = 'selected_currency';

    /**
     * Cache key prefix for currency data.
     *
     * @var string
     */
    protected const CACHE_PREFIX = 'currency_';

    /**
     * Cache TTL in seconds.
     *
     * @var int
     */
    protected int $cacheTtl = 3600; // 1 hour

    /**
     * Get the currently selected currency code.
     *
     * @return string
     */
    public function getCurrentCurrencyCode(): string
    {
        // Check session first
        $sessionCurrency = Session::get(self::SESSION_KEY);
        if ($sessionCurrency) {
            $currency = Currency::findByCode($sessionCurrency);
            if ($currency && $currency->is_active) {
                return $currency->code;
            }
        }

        // Fall back to default currency
        $defaultCurrency = Currency::getDefault();
        if ($defaultCurrency && $defaultCurrency->is_active) {
            return $defaultCurrency->code;
        }

        // Ultimate fallback
        return 'USD';
    }

    /**
     * Get the currently selected currency model.
     *
     * @return Currency|null
     */
    public function getCurrentCurrency(): ?Currency
    {
        $code = $this->getCurrentCurrencyCode();
        return Currency::findByCode($code);
    }

    /**
     * Set the current currency by code.
     *
     * @param string $currencyCode
     * @return bool
     */
    public function setCurrency(string $currencyCode): bool
    {
        $currencyCode = strtoupper($currencyCode);
        $currency = Currency::findByCode($currencyCode);

        if (!$currency || !$currency->is_active) {
            Log::warning("Attempted to set invalid or inactive currency: {$currencyCode}");
            return false;
        }

        Session::put(self::SESSION_KEY, $currencyCode);
        
        // Dispatch event for listeners
        event(new \Modules\Currency\Events\CurrencyChanged($currencyCode, $this->getCurrentCurrencyCode()));

        return true;
    }

    /**
     * Switch currency and convert cart amounts if needed.
     *
     * @param string $currencyCode
     * @return bool
     */
    public function switchCurrency(string $currencyCode): bool
    {
        $oldCurrency = $this->getCurrentCurrencyCode();
        
        if (!$this->setCurrency($currencyCode)) {
            return false;
        }

        // If currency actually changed, update cart
        if ($oldCurrency !== strtoupper($currencyCode)) {
            $this->updateCartCurrency($oldCurrency, $currencyCode);
        }

        return true;
    }

    /**
     * Update cart items when currency changes.
     *
     * @param string $fromCurrency
     * @param string $toCurrency
     * @return void
     */
    protected function updateCartCurrency(string $fromCurrency, string $toCurrency): void
    {
        try {
            $cart = app()->cart_manager->getCart();
            if ($cart && method_exists($cart, 'updateCurrency')) {
                $cart->updateCurrency($fromCurrency, $toCurrency);
            }
        } catch (\Exception $e) {
            Log::warning('Failed to update cart currency: ' . $e->getMessage());
        }
    }

    /**
     * Get all active currencies.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveCurrencies()
    {
        $cacheKey = self::CACHE_PREFIX . 'active_currencies';

        return Cache::remember($cacheKey, $this->cacheTtl, function () {
            return Currency::active()
                ->orderBy('position', 'asc')
                ->orderBy('name', 'asc')
                ->get();
        });
    }

    /**
     * Clear currency cache.
     *
     * @return void
     */
    public function clearCache(): void
    {
        Cache::forget(self::CACHE_PREFIX . 'active_currencies');
    }

    /**
     * Get currency display options for UI.
     *
     * @return array
     */
    public function getCurrencyOptions(): array
    {
        $currencies = $this->getActiveCurrencies();
        $options = [];

        foreach ($currencies as $currency) {
            $label = $currency->is_default 
                ? "{$currency->name} ({$currency->code}) - Default" 
                : "{$currency->name} ({$currency->code})";
            
            $options[$currency->code] = $label;
        }

        return $options;
    }

    /**
     * Format amount in current currency.
     *
     * @param float $amount
     * @param string|null $currencyCode
     * @return string
     */
    public function format(float $amount, ?string $currencyCode = null): string
    {
        $currencyCode = $currencyCode ?? $this->getCurrentCurrencyCode();
        $currency = Currency::findByCode($currencyCode);

        if (!$currency) {
            // task-2026-06-08-curfmt — on a fresh install the currencies table
            // is empty, so getCurrentCurrencyCode() returns the 'USD' ultimate
            // fallback but findByCode('USD') is null. Prepend the symbol fallback
            // (consistent with getSymbol()'s '$' default) instead of dropping it —
            // otherwise every storefront price renders as a bare number.
            return $this->getSymbol($currencyCode) . number_format($amount, 2);
        }

        return $currency->formatAmount($amount);
    }

    /**
     * Format amount without symbol.
     *
     * @param float $amount
     * @param string|null $currencyCode
     * @return string
     */
    public function formatPlain(float $amount, ?string $currencyCode = null): string
    {
        $currencyCode = $currencyCode ?? $this->getCurrentCurrencyCode();
        $currency = Currency::findByCode($currencyCode);

        if (!$currency) {
            return number_format($amount, 2);
        }

        return $currency->formatAmountPlain($amount);
    }

    /**
     * Get currency symbol.
     *
     * @param string|null $currencyCode
     * @return string
     */
    public function getSymbol(?string $currencyCode = null): string
    {
        $currencyCode = $currencyCode ?? $this->getCurrentCurrencyCode();
        $currency = Currency::findByCode($currencyCode);

        return $currency ? $currency->symbol : '$';
    }

    /**
     * Check if multi-currency is enabled.
     *
     * @return bool
     */
    public function isMultiCurrencyEnabled(): bool
    {
        return config('currency.multi_currency_enabled', false);
    }

    /**
     * Get available currencies for switching.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAvailableForSwitching()
    {
        if (!$this->isMultiCurrencyEnabled()) {
            return collect([$this->getCurrentCurrency()]);
        }

        return $this->getActiveCurrencies();
    }

    /**
     * Auto-detect currency from request (IP, browser locale, etc.).
     *
     * @return string|null
     */
    public function autoDetectCurrency(): ?string
    {
        // Try browser accept-language header
        $locale = request()->header('Accept-Language');
        if ($locale) {
            $detected = $this->detectFromLocale($locale);
            if ($detected) {
                return $detected;
            }
        }

        // Try IP geolocation if available
        $ipCurrency = $this->detectFromIp();
        if ($ipCurrency) {
            return $ipCurrency;
        }

        return null;
    }

    /**
     * Detect currency from locale string.
     *
     * @param string $locale
     * @return string|null
     */
    protected function detectFromLocale(string $locale): ?string
    {
        $localeToCurrency = [
            'en_US' => 'USD',
            'en_GB' => 'GBP',
            'en_CA' => 'CAD',
            'en_AU' => 'AUD',
            'de_DE' => 'EUR',
            'de_AT' => 'EUR',
            'de_CH' => 'CHF',
            'fr_FR' => 'EUR',
            'fr_CA' => 'CAD',
            'fr_CH' => 'CHF',
            'es_ES' => 'EUR',
            'it_IT' => 'EUR',
            'ja_JP' => 'JPY',
            'zh_CN' => 'CNY',
            'zh_TW' => 'TWD',
            'ko_KR' => 'KRW',
            'ru_RU' => 'RUB',
            'pl_PL' => 'PLN',
            'nl_NL' => 'EUR',
            'sv_SE' => 'SEK',
            'da_DK' => 'DKK',
            'no_NO' => 'NOK',
            'fi_FI' => 'EUR',
        ];

        // Extract primary locale
        $primaryLocale = explode(',', $locale)[0];
        $primaryLocale = explode(';', $primaryLocale)[0];
        $primaryLocale = trim($primaryLocale);

        // Check exact match
        if (isset($localeToCurrency[$primaryLocale])) {
            $code = $localeToCurrency[$primaryLocale];
            $currency = Currency::findByCode($code);
            if ($currency && $currency->is_active) {
                return $code;
            }
        }

        // Check language-only match (e.g., 'en' from 'en-US')
        $langCode = explode('-', $primaryLocale)[0];
        foreach ($localeToCurrency as $loc => $curr) {
            if (strpos($loc, $langCode . '_') === 0) {
                $currency = Currency::findByCode($curr);
                if ($currency && $currency->is_active) {
                    return $curr;
                }
            }
        }

        return null;
    }

    /**
     * Detect currency from IP address.
     *
     * @return string|null
     */
    protected function detectFromIp(): ?string
    {
        // This would require a geolocation service
        // For now, return null - can be extended with MaxMind GeoIP or similar
        return null;
    }

    /**
     * Convert price display for product listings.
     *
     * @param float $amount
     * @param string|null $fromCurrency
     * @return array
     */
    public function getPriceDisplay(float $amount, ?string $fromCurrency = null): array
    {
        $fromCurrency = $fromCurrency ?? $this->getDefaultCurrencyCode();
        $toCurrency = $this->getCurrentCurrencyCode();

        $conversionService = app(CurrencyConversionService::class);
        
        $convertedAmount = $amount;
        $exchangeRate = 1.0;
        $isConverted = false;

        if ($fromCurrency !== $toCurrency) {
            try {
                $convertedAmount = $conversionService->convert($amount, $fromCurrency, $toCurrency);
                $exchangeRate = $conversionService->getExchangeRate($fromCurrency, $toCurrency) ?? 1.0;
                $isConverted = true;
            } catch (\Exception $e) {
                Log::warning("Currency conversion failed: {$fromCurrency} to {$toCurrency}");
                $convertedAmount = $amount;
            }
        }

        return [
            'original_amount' => $amount,
            'original_currency' => $fromCurrency,
            'converted_amount' => $convertedAmount,
            'display_currency' => $toCurrency,
            'exchange_rate' => $exchangeRate,
            'is_converted' => $isConverted,
            'formatted_original' => $this->format($amount, $fromCurrency),
            'formatted_converted' => $this->format($convertedAmount, $toCurrency),
        ];
    }

    /**
     * Get default currency code from settings.
     *
     * @return string
     */
    public function getDefaultCurrencyCode(): string
    {
        $default = Currency::getDefault();
        return $default ? $default->code : 'USD';
    }

    /**
     * Check if two currencies are the same.
     *
     * @param string $currency1
     * @param string $currency2
     * @return bool
     */
    public function isSameCurrency(string $currency1, string $currency2): bool
    {
        return strtoupper($currency1) === strtoupper($currency2);
    }
}
