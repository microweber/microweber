# `Currency` module

> **Slug:** `currency`
> **Tier:** 2
>
> Tier-2 module — service / API surface on top of shared infrastructure.
>
> *(Auto-generated from filesystem survey on 2026-04-25;
> hand-edit to add operator-side context. The canonical
> shape lives in [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md);
> use `Modules/Settings/docs/README.md` as the
> hand-curated example.)*

## Domain

*Hand-edit this section to describe what the module does
operationally and which sibling modules it interacts
with.*

## Data model

Migrations under `Modules/Currency/database/migrations/`:

  - `database/migrations/2014_10_11_125754_create_currencies_table.php`
  - `database/migrations/2026_03_22_000001_create_exchange_rates_table.php`
  - `database/migrations/2026_03_22_000002_add_multi_currency_columns_to_currencies.php`

*Hand-edit to inline the column lists + relationships per
table.*

## Models

| Eloquent class | File |
|---|---|
| `Modules\Currency\Models\Currency` | `Models/Currency.php` |
| `Modules\Currency\Models\ExchangeRate` | `Models/ExchangeRate.php` |

## Service classes

  - `Modules\Currency\Services\CurrencyConversionService`
  - `Modules\Currency\Services\CurrencyManager`

## Events

  - `Modules\Currency\Events\CurrencyChanged`

## Filament admin

  - `Modules\Currency\Filament\Admin\Resources\CurrencyResource`
  - `Modules\Currency\Filament\Admin\Resources\CurrencyResource\Pages\CreateCurrency`
  - `Modules\Currency\Filament\Admin\Resources\CurrencyResource\Pages\EditCurrency`
  - `Modules\Currency\Filament\Admin\Resources\CurrencyResource\Pages\ListCurrencies`
  - `Modules\Currency\Filament\Admin\Resources\ExchangeRateResource`
  - `Modules\Currency\Filament\Admin\Resources\ExchangeRateResource\Pages\CreateExchangeRate`
  - `Modules\Currency\Filament\Admin\Resources\ExchangeRateResource\Pages\EditExchangeRate`
  - `Modules\Currency\Filament\Admin\Resources\ExchangeRateResource\Pages\ListExchangeRates`

## Tests

Run: `php vendor/bin/phpunit Modules/Currency/Tests`

Test files:

  - `Tests/CurrencyTest.php`
  - `Tests/Filament/CurrencyResourceTest.php`
  - `Tests/Unit/MultiCurrencyTest.php`

## Service providers

  - `Modules\Currency\Providers\CurrencyServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
