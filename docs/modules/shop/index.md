# Shop Module

> **Slug:** `shop`
> **Tier:** 1 (e-commerce engine — coordinates Product / Cart / Checkout / Order / Payment / Shipping)
> **Source:** `Modules/Shop/`

The Shop module is the **e-commerce engine** at the centre of Microweber's commerce ecosystem. It owns the storefront-display Livewire component, the shop-wide settings, and the `ShopManager` singleton that coordinates the satellite modules (Product, Cart, Checkout, Order, Payment, Invoice, Shipping, Coupons, Currency, Tax). Where each satellite module owns its own data + logic, Shop is the **orchestration layer** that ties them together.

## What this module does

- Renders the public storefront via `Livewire\ShopComponent` — the `module-shop` Livewire tag operators drag into Live Edit
- Exposes `app('shop_manager')` — central API for `add_to_cart()`, `checkout()`, currency conversion, price formatting, cart sums
- Provides shop-wide Filament admin settings (`ShopModuleSettings`) for currency, tax mode, display rules
- Coordinates pricing across product / cart / checkout flows (`get_product_prices`, `get_product_price`)
- Owns the storefront-side product grid + filter UX (price range, sort, pagination, category/tag filters)
- Routes `<module type="shop" />` short-tag to the canonical Livewire component
- Provides currency-formatting helpers (`currency_format`, `currency_symbol`, `get_default_currency`)
- Owns the `getMainPageId()` resolution — the Page row marked `is_shop=1` is the storefront's URL anchor

## Domain

Shop is the **commerce orchestrator** — not a data owner. It coordinates:

- **Product** (`Modules/Product/`) — catalog + variants + inventory
- **Cart** (`Modules/Cart/`) — session-state line items + sums
- **Checkout** (`Modules/Checkout/`) — multi-step wizard + form validation
- **Order** (`Modules/Order/`) — persisted purchase records
- **Payment** (`Modules/Payment/`) — gateway integrations (Stripe / PayPal / etc.)
- **Invoice** (`Modules/Invoice/`) — PDF generation
- **Shipping** (`Modules/Shipping/`) — methods + rate calc
- **Coupons** (`Modules/Coupons/`) — discount codes
- **Currency** (`Modules/Currency/`) — multi-currency display
- **Tax** (`Modules/Tax/`) — line-item tax calc

The Shop module is intentionally thin — heavy lifting lives in the satellites. Bugs that look like "Shop module bug" usually trace to one of the satellites; the docs route operators to the right owner.

Cross-references (see also):

- [`docs/modules/product/`](../product/) — product catalog management
- [`docs/modules/cart/`](../cart/) — cart line-item handling
- [`docs/modules/checkout/`](../checkout/) — checkout wizard
- [`docs/modules/order/`](../order/) — order lifecycle
- [`docs/modules/payment/`](../payment/) — payment-gateway integration
- [`docs/modules/shipping/`](../shipping/) — shipping methods
- [`docs/modules/content/`](../content/) — Page module owns the `is_shop=1` flag on the storefront page

## Documentation map

| Page | Purpose |
|---|---|
| [`index.md`](./index.md) | This overview |
| [`installation.md`](./installation.md) | Registration, the shop landing page, satellite module dependencies, config |
| [`usage.md`](./usage.md) | Manager singleton, ShopComponent rendering, currency, price reads, filter UX |
| [`api.md`](./api.md) | ShopManager method reference + ShopComponent Livewire surface |
| [`examples.md`](./examples.md) | End-to-end recipes |
| [`troubleshooting.md`](./troubleshooting.md) | Common issues |

## Quick start

```php
$shop = app('shop_manager');

// Add a product to the cart
$shop->add_to_cart([
    'content_id' => $productId,
    'qty'        => 2,
    'options'    => ['size' => 'L', 'color' => 'blue'],
]);

// Read the cart sum
$total = $shop->cart_sum(true);  // numeric
$formatted = $shop->currency_format($total);

// Currency basics
echo $shop->currency_symbol();      // '$', '€', etc.
echo $shop->get_default_currency(); // 'USD'

// Get a product's effective price (after discount, special pricing, custom fields)
$price = $shop->get_product_price($productId);
$priceTable = $shop->get_product_prices($productId, true);
```

Render the storefront in a Blade template / Live Edit canvas:

```html
<module type="shop" />
```

The component reads the current Page's `is_shop=1` parent + the active category + the URL query params (price range, sort, page) and renders the product grid.

## Key files

- `Modules/Shop/Services/ShopManager.php` — singleton (`app('shop_manager')`)
- `Modules/Shop/Livewire/ShopComponent.php` — frontend storefront Livewire (extends `BlogComponent` for shared filter logic)
- `Modules/Shop/Microweber/ShopModule.php` — Microweber module-system registration (the `module-shop` tag)
- `Modules/Shop/Filament/ShopModuleSettings.php` — admin settings page
- `Modules/Shop/Providers/ShopServiceProvider.php` — module bootstrap

## Status

Production-stable. The Shop coordination layer has been stable for many releases; per-module bugs (product variants not saving, payment gateway timeouts, shipping rate mis-calc, etc.) belong against the relevant satellite, not against Shop.
