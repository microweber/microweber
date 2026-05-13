# Shop Module — Installation

The Shop module is a **core module** — ships with Microweber, registered automatically.

## Prerequisites

- PHP ≥ 8.2
- Laravel 11 base
- Filament v5 — admin settings page
- Livewire v4 — storefront component
- The full e-commerce satellite suite (all owned by separate modules):
  - **Product** (`Modules/Product/`) — catalog
  - **Cart** (`Modules/Cart/`) — session state
  - **Checkout** (`Modules/Checkout/`) — multi-step wizard
  - **Order** (`Modules/Order/`) — purchase persistence
  - **Payment** (`Modules/Payment/`) — gateway integrations
  - **Invoice** (`Modules/Invoice/`) — PDF generation
  - **Shipping** (`Modules/Shipping/`) — method + rate calc
  - **Coupons** (`Modules/Coupons/`) — discounts
  - **Currency** (`Modules/Currency/`) — multi-currency
  - **Tax** (`Modules/Tax/`) — line-item tax

If any satellite is missing, Shop still loads but the corresponding feature is unavailable (e.g. without Payment, checkout can't complete; without Shipping, no shipping options render).

## Registration

Standard module pipeline:

1. **`Modules/Shop/module.json`** declares the module + provider
2. **`Modules/Shop/Providers/ShopServiceProvider.php`** registers the `shop_manager` singleton, the `ShopComponent` Livewire registration, the Filament settings page, and the `module-shop` Microweber short-tag binding
3. **`composer.json`** PSR-4: `"Modules\\Shop\\": "Modules/Shop/"`

`composer dump-autoload` after a fresh clone is sufficient.

## Database schema

Shop owns NO tables of its own. The data lives in the satellite modules:

| Satellite | Owns |
|---|---|
| Product | `content` (rows where `content_type='product'`), `product_variants`, `inventory_*` |
| Cart | `cart`, `cart_orders` |
| Order | `orders`, `orders_items` |
| Payment | `payment_gateways`, `payment_methods` |
| Invoice | `invoices` |
| Shipping | `shipping_methods`, `shipping_rates` |
| Coupons | `coupons`, `coupons_usage` |
| Currency | `currencies` |
| Tax | `tax_rates`, `tax_rules` |

The Page module's `is_shop=1` flag is the storefront's URL anchor — `getMainPageId()` in ShopComponent reads this.

## The storefront page

Exactly one Page row should carry `is_shop=1`. `microweber:install` creates it automatically:

- Title: `'Shop'`
- URL: `'shop'`
- `subtype='dynamic'`
- `is_shop=1`

Verify with:

```php
\Modules\Page\Models\Page::where('is_shop', 1)->first();
```

To swap which page is the shop landing:

```php
\Modules\Page\Models\Page::where('is_shop', 1)->update(['is_shop' => 0]);
\Modules\Page\Models\Page::find($newShopPageId)->update(['is_shop' => 1]);
```

## Shop settings

The Filament admin page `Modules\Shop\Filament\ShopModuleSettings` (typically at `/admin/settings/shop`) exposes:

- Default currency (e.g. `USD`)
- Currency symbol position (before / after amount)
- Decimal separator + thousands separator
- Default tax mode (prices include tax / prices exclude tax)
- Inventory display mode (show count / hide count / hide when out of stock)
- Out-of-stock behaviour (prevent purchase / allow with backorder / allow with notice)

All values stored in the `options` table under `option_group='shop'` — see [`docs/modules/settings/`](../settings/) for the Option store.

Read programmatically:

```php
$currency = get_option('currency', 'shop') ?: 'USD';
$taxMode = get_option('tax_mode', 'shop') ?: 'exclude';
```

## What `microweber:install` does

- Creates the Shop landing Page
- Creates the Cart parent Page (Cart module's seed)
- Creates the Checkout parent Page (Checkout module's seed)
- Seeds default currency settings (typically `USD`) in `options`
- Seeds default tax mode (`exclude`)
- Creates initial admin User
- Runs all satellite-module migrations (Product / Order / Payment / etc.)

## Multi-currency

Multi-currency support requires the Currency module (`Modules/Currency/`) — the `Currency` table holds the active list, exchange rates, and per-locale defaults. The Shop manager's `currency_symbol($curr)` and `currency_format($amount, $curr)` both accept an override currency code; without one, they use `get_default_currency()`.

For full multi-store (separate currencies per tenant), see the Multisite operational doc.

## Configuration options (via the Settings module)

| Key | Group | Purpose |
|---|---|---|
| `currency` | `shop` | Default currency code |
| `currency_symbol` | `shop` | Override symbol if auto-detected one is wrong |
| `tax_mode` | `shop` | `'include'` or `'exclude'` |
| `inventory_show_count` | `shop` | `1` = show "12 in stock" labels |
| `out_of_stock_behaviour` | `shop` | `'prevent'` / `'backorder'` / `'notice'` |
| `cart_persist_days` | `cart` | How long anonymous carts persist (0 = session only) |

## Disabling / replacing

Shop **can be disabled** if the site is non-commerce (blog-only, brochure site). Public pages without `<module type="shop">` short-tags render normally; the satellite tables stay empty. To re-enable later, re-enable the module + the cart parent page returns to the menu.

To customize:

- Extend `ShopManager` and rebind via `$this->app->singleton('shop_manager', ...)` in a custom ServiceProvider
- Subclass `ShopComponent` for storefront-side customizations (custom sort orders, additional filter fields)
- Override `ShopModuleSettings` for tenant-aware shop config
