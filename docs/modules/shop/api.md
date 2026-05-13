# Shop Module — API Reference

Shop does NOT expose its own REST API — read/write operations route through the satellite modules' controllers (Cart, Checkout, Order, Product). This page documents Shop's **server-side API surface**: `ShopManager` methods + the `ShopComponent` Livewire surface.

## `ShopManager`

`app('shop_manager')` returns the singleton.

### Cart operations

| Method | Signature | Returns |
|---|---|---|
| `add_to_cart(array $data)` | `data = ['content_id', 'qty', 'options']` | Cart row id or false |
| `sum(bool $return_amount = true)` | Subtotal (no tax/shipping) | numeric or formatted string |
| `cart_sum(bool $return_amount = true)` | Full total (with tax + shipping) | numeric or formatted string |

### Checkout

| Method | Signature | Returns |
|---|---|---|
| `checkout(array $data)` | Standard checkout payload (email, addresses, methods) | Order id on success |
| `update_order(array $params)` | `params = ['order_id', 'status', 'tracking_number', ...]` | bool |

### Customer

| Method | Signature | Returns |
|---|---|---|
| `delete_client(array $data)` | `data = ['user_id']` — admin tool for GDPR-style removal | bool |

### Pricing

| Method | Signature | Returns |
|---|---|---|
| `get_product_prices(int $product_id, bool $full = false)` | Full variant price table | array |
| `get_product_price(int $content_id)` | Effective price after all rules | numeric |

### Currency

| Method | Signature | Returns |
|---|---|---|
| `get_default_currency()` | Reads `option_group='shop' option_key='currency'` | string code (e.g. `'USD'`) |
| `currency_format($amount, $currency = false)` | Format an amount with the configured/override currency | string |
| `currency_symbol($curr = false, $key = 3)` | Symbol for the given currency code | string |

### Table-name override (advanced)

| Method | Signature | Notes |
|---|---|---|
| `set_table_names($tables = false)` | Override the satellite table names for multi-tenant setups | rarely used |

## `ShopComponent` Livewire surface

`Modules\Shop\Livewire\ShopComponent` extends `BlogComponent` (shared filter logic).

### Public properties

The component exposes Livewire-bound public properties: `keywords`, `offers`, `priceFrom`, `priceTo`, `sortKey`, `limit`, `tags`, `category`. Each has a matching `updated*` handler that re-renders.

### Methods

| Method | Signature | Purpose |
|---|---|---|
| `updatedOffers()` | — | Re-render when sale-only checkbox toggled |
| `updatedPriceFrom()` | — | Re-render when min-price slider moved |
| `updatedPriceTo()` | — | Re-render when max-price slider moved |
| `updatedKeywords()` | — | Re-render when search input changes |
| `filterLimit($limit)` | int | Override page size |
| `filterSort($field, $direction)` | `'price'/'date'/'title'/'popularity'`, `'asc'/'desc'` | Apply sort |
| `updatedSortKey($value)` | string | Same as `filterSort` but for the dropdown binding |
| `updatedLimit()` | — | Re-render after page-size change |
| `resetFilters()` | — | Clear every filter + redirect to root storefront |
| `render()` | — | Main render path; called every Livewire tick |
| `getMainPageId()` | — | Reads the Page with `is_shop=1` |
| `getAvailableCategories($mainPageId)` | int | Categories with ≥1 product under current filter |

### Render output

The component renders the storefront grid + filter sidebar + pagination. Templates can override the blade view by publishing or overriding:

```bash
php artisan vendor:publish --tag=shop-views
```

Or in code:

```php
// Subclass with a custom view
class CustomShopComponent extends \Modules\Shop\Livewire\ShopComponent
{
    protected $view = 'custom-shop';
}
```

## `Microweber\ShopModule`

`Modules\Shop\Microweber\ShopModule` registers the `module-shop` Microweber short-tag binding. Templates that use `<module type="shop" />` resolve through this class to the Livewire component.

Customizing the short-tag → component binding requires registering a new ModuleType in your AppServiceProvider with the same name (`shop`) AFTER ShopServiceProvider boots.

## Filament admin

`Modules\Shop\Filament\ShopModuleSettings` exposes the shop-wide options (currency, tax mode, display rules, inventory behaviour). Each field maps to an `options` row under `option_group='shop'`.

## Events fired

| Event | When | Listener notes |
|---|---|---|
| `cart.added` | `add_to_cart` succeeded | Cart module records the cart row |
| `cart.removed` | `remove_from_cart` succeeded | Cart module |
| `cart.cleared` | `clear_cart` called | Cart module |
| `checkout.started` | First step of checkout submitted | Order module pre-creates a pending order row |
| `order.created` | After `place_order` | Invoice module generates PDF; Notification module mails customer |
| `order.updated` | After `update_order` | Shipping module re-evaluates if shipping_method changed |
| `order.paid` | After payment gateway returns success | Order status → 'paid' |
| `order.shipped` | After tracking number added | Email customer + update Order.status |

All listeners attach via the standard Laravel `Event::listen()` API.

## Helpers (Shop-specific)

The Shop module doesn't add global helpers — currency formatting goes through `app('shop_manager')->currency_format(...)`. For thread-safe use in non-DI contexts:

```php
$shop = app('shop_manager');
echo $shop->currency_format($amount);
```

## Testing

```bash
./vendor/bin/phpunit --filter=ShopManagerTest
```

Tests live in `Modules/Shop/Tests/`. Most coverage is integration-style (add to cart → checkout → place order → verify Order/Invoice/Payment side-effects).

For unit testing the price/currency helpers in isolation:

```php
$shop = new \Modules\Shop\Services\ShopManager;
$this->assertSame('$24.99', $shop->currency_format(24.99));
```

(No DI required — the constructor's `$app` arg is optional.)

## Multi-store / multi-currency

For multi-currency display + storage, the Currency module owns the rate table; Shop manager reads from it. For multi-store (separate currency / tax per tenant), see the Multisite operational doc — typically each tenant gets its own options group + its own shop landing page.
