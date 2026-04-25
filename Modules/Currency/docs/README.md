# `Currency` module

> **Slug:** `currency`
> **Tier:** 2
>
> *Auto-generated from filesystem survey on 2026-04-25 with
> column / route / method extraction. Domain section is
> the only hand-edit needed; the rest of this file is
> regenerable from source.*

## Domain

*Hand-edit this section: describe what the module does
operationally, who consumes it, and which sibling modules
it interacts with.*

## Data model

### `currencies` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `increments` | — |
  | `name` | `string` | — |
  | `code` | `string` | — |
  | `symbol` | `string` | — |
  | `precision` | `integer` | — |
  | `thousand_separator` | `string` | — |
  | `decimal_separator` | `string` | — |
  | `swap_currency_symbol` | `boolean` | — |
  | `timestamps` | `timestamps` | — |
  | `is_active` | `boolean` | has-default |
  | `is_default` | `boolean` | has-default |
  | `position` | `integer` | has-default |
  | `is_active` | `dropColumn` | — |
  | `is_default` | `dropColumn` | — |
  | `position` | `dropColumn` | — |

### `exchange_rates` table

  | Column | Type | Modifiers |
  |--------|------|-----------|
  | `id` | `id` | — |
  | `from_currency` | `string` | indexed |
  | `to_currency` | `string` | indexed |
  | `rate` | `decimal` | — |
  | `inverse_rate` | `decimal` | nullable |
  | `source` | `string` | has-default |
  | `last_updated` | `timestamp` | — |
  | `is_active` | `boolean` | indexed, has-default |
  | `timestamps` | `timestamps` | — |

## Models

### `Modules\Currency\Models\Currency`

Source: `Models/Currency.php`. Table: `currencies`. 

**Fillable:** `name`, `code`, `symbol`, `precision`, `thousand_separator`, `decimal_separator`, `swap_currency_symbol`, `is_active`, `is_default`, `position`

**Casts:**

  - `precision` → `integer`
  - `swap_currency_symbol` → `boolean`
  - `is_active` → `boolean`
  - `is_default` → `boolean`
  - `position` → `integer`

### `Modules\Currency\Models\ExchangeRate`

Source: `Models/ExchangeRate.php`. Table: `exchange_rates`. 

**Fillable:** `from_currency`, `to_currency`, `rate`, `inverse_rate`, `source`, `last_updated`, `is_active`

**Casts:**

  - `rate` → `decimal:8`
  - `inverse_rate` → `decimal:8`
  - `last_updated` → `datetime`
  - `is_active` → `boolean`

## Service classes

### `Modules\Currency\Services\CurrencyConversionService`

Source: `Services/CurrencyConversionService.php`.

  - `convert(float $amount, string $fromCurrency, string $toCurrency, bool $useCache = true): float`
  - `getExchangeRate(string $fromCurrency, string $toCurrency, bool $useCache = true): ?float`
  - `getDefaultCurrencyCode(): string`
  - `getPrecision(string $currencyCode): int`
  - `getAvailableCurrencies()`
  - `getCurrenciesWithRates(?string $baseCurrency = null): array`
  - `canConvert(string $fromCurrency, string $toCurrency): bool`
  - `clearCache(?string $fromCurrency = null, ?string $toCurrency = null): void`
  - `setCacheTtl(int $seconds): self`
  - `batchConvert(array $amounts, string $fromCurrency, string $toCurrency): array`
  - `formatWithSymbol(float $amount, string $currencyCode): string`

### `Modules\Currency\Services\CurrencyManager`

Source: `Services/CurrencyManager.php`.

  - `getCurrentCurrencyCode(): string`
  - `getCurrentCurrency(): ?Currency`
  - `setCurrency(string $currencyCode): bool`
  - `switchCurrency(string $currencyCode): bool`
  - `getActiveCurrencies()`
  - `clearCache(): void`
  - `getCurrencyOptions(): array`
  - `format(float $amount, ?string $currencyCode = null): string`
  - `formatPlain(float $amount, ?string $currencyCode = null): string`
  - `getSymbol(?string $currencyCode = null): string`
  - `isMultiCurrencyEnabled(): bool`
  - `getAvailableForSwitching()`
  - `autoDetectCurrency(): ?string`
  - `getPriceDisplay(float $amount, ?string $fromCurrency = null): array`
  - `getDefaultCurrencyCode(): string`
  - `isSameCurrency(string $currency1, string $currency2): bool`

## Events

  - `Modules\Currency\Events\CurrencyChanged`

## Filament admin

  | Class | Navigation group | Label |
  |-------|------------------|-------|
  | `Modules\Currency\Filament\Admin\Resources\CurrencyResource` | Shop Settings | — |
  | `Modules\Currency\Filament\Admin\Resources\CurrencyResource\Pages\CreateCurrency` | — | — |
  | `Modules\Currency\Filament\Admin\Resources\CurrencyResource\Pages\EditCurrency` | — | — |
  | `Modules\Currency\Filament\Admin\Resources\CurrencyResource\Pages\ListCurrencies` | — | — |
  | `Modules\Currency\Filament\Admin\Resources\ExchangeRateResource` | Shop Settings | — |
  | `Modules\Currency\Filament\Admin\Resources\ExchangeRateResource\Pages\CreateExchangeRate` | — | — |
  | `Modules\Currency\Filament\Admin\Resources\ExchangeRateResource\Pages\EditExchangeRate` | — | — |
  | `Modules\Currency\Filament\Admin\Resources\ExchangeRateResource\Pages\ListExchangeRates` | — | — |

## Tests

Run: `php vendor/bin/phpunit Modules/Currency/Tests`

### `Tests/Filament/CurrencyResourceTest.php`

  - `it_exchange_rate_resource_exists`
  - `it_exchange_rate_resource_has_model`

### `Tests/Unit/MultiCurrencyTest.php`

  - `it_can_get_exchange_rate_via_cross_rate`

## Service providers

  - `Modules\Currency\Providers\CurrencyServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
