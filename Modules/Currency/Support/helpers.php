<?php

use Modules\Currency\Services\CurrencyManager;
use Modules\Currency\Services\CurrencyConversionService;

if (!function_exists('currency_manager')) {
    /**
     * Get the currency manager instance.
     *
     * @return CurrencyManager
     */
    function currency_manager(): CurrencyManager
    {
        return app(CurrencyManager::class);
    }
}

if (!function_exists('currency_conversion')) {
    /**
     * Get the currency conversion service instance.
     *
     * @return CurrencyConversionService
     */
    function currency_conversion(): CurrencyConversionService
    {
        return app(CurrencyConversionService::class);
    }
}

if (!function_exists('current_currency_code')) {
    /**
     * Get the current currency code.
     *
     * @return string
     */
    function current_currency_code(): string
    {
        return currency_manager()->getCurrentCurrencyCode();
    }
}

if (!function_exists('current_currency_symbol')) {
    /**
     * Get the current currency symbol.
     *
     * @return string
     */
    function current_currency_symbol(): string
    {
        return currency_manager()->getSymbol();
    }
}

if (!function_exists('currency_format')) {
    /**
     * Format an amount with the current currency.
     *
     * @param float $amount
     * @param string|null $currencyCode
     * @return string
     */
    function currency_format(float $amount, ?string $currencyCode = null): string
    {
        return currency_manager()->format($amount, $currencyCode);
    }
}

if (!function_exists('currency_format_plain')) {
    /**
     * Format an amount without currency symbol.
     *
     * @param float $amount
     * @param string|null $currencyCode
     * @return string
     */
    function currency_format_plain(float $amount, ?string $currencyCode = null): string
    {
        return currency_manager()->formatPlain($amount, $currencyCode);
    }
}

if (!function_exists('currency_convert')) {
    /**
     * Convert an amount from one currency to another.
     *
     * @param float $amount
     * @param string $fromCurrency
     * @param string $toCurrency
     * @return float
     * @throws \InvalidArgumentException
     */
    function currency_convert(float $amount, string $fromCurrency, string $toCurrency): float
    {
        return currency_conversion()->convert($amount, $fromCurrency, $toCurrency);
    }
}

if (!function_exists('currency_exchange_rate')) {
    /**
     * Get the exchange rate between two currencies.
     *
     * @param string $fromCurrency
     * @param string $toCurrency
     * @return float|null
     */
    function currency_exchange_rate(string $fromCurrency, string $toCurrency): ?float
    {
        return currency_conversion()->getExchangeRate($fromCurrency, $toCurrency);
    }
}

if (!function_exists('currency_can_convert')) {
    /**
     * Check if a currency conversion is possible.
     *
     * @param string $fromCurrency
     * @param string $toCurrency
     * @return bool
     */
    function currency_can_convert(string $fromCurrency, string $toCurrency): bool
    {
        return currency_conversion()->canConvert($fromCurrency, $toCurrency);
    }
}

if (!function_exists('currency_switch')) {
    /**
     * Switch to a different currency.
     *
     * @param string $currencyCode
     * @return bool
     */
    function currency_switch(string $currencyCode): bool
    {
        return currency_manager()->switchCurrency($currencyCode);
    }
}

if (!function_exists('currency_get_active')) {
    /**
     * Get all active currencies.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    function currency_get_active()
    {
        return currency_manager()->getActiveCurrencies();
    }
}

if (!function_exists('currency_get_options')) {
    /**
     * Get currency options for dropdowns.
     *
     * @return array
     */
    function currency_get_options(): array
    {
        return currency_manager()->getCurrencyOptions();
    }
}

if (!function_exists('currency_is_multi_enabled')) {
    /**
     * Check if multi-currency is enabled.
     *
     * @return bool
     */
    function currency_is_multi_enabled(): bool
    {
        return currency_manager()->isMultiCurrencyEnabled();
    }
}

if (!function_exists('currency_price_display')) {
    /**
     * Get price display data with conversion info.
     *
     * @param float $amount
     * @param string|null $fromCurrency
     * @return array
     */
    function currency_price_display(float $amount, ?string $fromCurrency = null): array
    {
        return currency_manager()->getPriceDisplay($amount, $fromCurrency);
    }
}

if (!function_exists('default_currency_code')) {
    /**
     * Get the default currency code.
     *
     * @return string
     */
    function default_currency_code(): string
    {
        return currency_manager()->getDefaultCurrencyCode();
    }
}
