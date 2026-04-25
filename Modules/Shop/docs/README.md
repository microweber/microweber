# `Shop` module

> **Slug:** `shop`
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

This module owns no migrations of its own.

## Service classes

### `Modules\Shop\Services\ShopManager`

Source: `Services/ShopManager.php`.

  - `set_table_names($tables = false)`
  - `add_to_cart($data)`
  - `sum($return_amount = true)`
  - `cart_sum($return_amount = true)`
  - `checkout($data)`
  - `update_order($params = false)`
  - `place_order($place_order)`
  - `delete_client($data)`
  - `get_product_prices($product_id = false, $return_full_custom_fields_array = false)`
  - `get_product_price($content_id = false)`
  - `get_default_currency()`
  - `currency_format($amount, $currency = false)`
  - `currency_symbol($curr = false, $key = 3)`
  - `currency_get()`
  - `checkout_url()`
  - `redirect_to_checkout()`

## Filament admin

  | Class | Navigation group | Label |
  |-------|------------------|-------|
  | `Modules\Shop\Filament\ShopModuleSettings` | — | — |

## Tests

Run: `php vendor/bin/phpunit Modules/Shop/Tests`

## Service providers

  - `Modules\Shop\Providers\ShopServiceProvider`

## Further reading

  - [`docs/modules/MODULE_DOCS_TEMPLATE.md`](../../../docs/modules/MODULE_DOCS_TEMPLATE.md) — canonical doc shape.
  - [`docs/modules/README.md`](../../../docs/modules/README.md) — index of all per-module docs.
  - [`Modules/Settings/docs/README.md`](../../Settings/docs/README.md) — hand-curated example.
