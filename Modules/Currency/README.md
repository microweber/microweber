# Currency

Multi-currency support for the shop. Manages currencies, exchange rates, currency conversion, and display formatting with middleware-based visitor currency detection.

## Key Features

- Currency catalog with symbols, codes, and formatting rules
- Exchange rate management with auto-conversion
- Currency conversion service for price calculations
- HTTP middleware for detecting visitor currency preference
- Blade directives for currency display
- Money formatting configuration
- Livewire components for currency selector

## Key Classes

| Class | Purpose |
|---|---|
| `Services\CurrencyManager` | Currency operations (singleton) |
| `Services\CurrencyConversionService` | Exchange rate conversion (singleton) |
| `Models\Currency` | Currency definition (code, symbol, format) |
| `Models\ExchangeRate` | Exchange rate pairs |
| `Http\Middleware\CurrencyMiddleware` | Detect/set active currency per request |

## Configuration

Two config files merged into the app:
- `config/currency.php` -- module icon
- `config/money.php` -- money formatting rules (decimal places, separators, symbol position)

## Database Tables

- `currencies` -- currency definitions with multi-currency columns
- `exchange_rates` -- currency pair exchange rates

## Middleware

Register on routes to auto-detect currency:
```php
Route::middleware('currency')->group(function () {
    // Routes with currency detection
});
```

## Usage

```php
$currencyManager = app(\Modules\Currency\Services\CurrencyManager::class);
$converter = app(\Modules\Currency\Services\CurrencyConversionService::class);
$priceInEur = $converter->convert(100, 'USD', 'EUR');
```
