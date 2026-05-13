# Shop Module — Usage

Day-to-day patterns for using `ShopManager`, rendering the storefront, and coordinating with satellite modules.

## The `ShopManager` singleton

```php
$shop = app('shop_manager');
```

Available since the module boot. Use this for all shop-wide operations rather than calling satellite repositories directly — the manager handles cross-module concerns (cart-session lookup, tax/currency application, event firing).

## Add to cart

```php
$shop->add_to_cart([
    'content_id' => $productId,
    'qty'        => 2,
    'options'    => [
        'size'  => 'L',
        'color' => 'blue',
    ],
]);

// Returns a cart row id (or false on failure)
```

The manager validates the product exists, picks the right variant if `options` resolve, increments quantity on the existing cart row (if the same product+options combo already in cart), and fires the `cart.added` event.

## Read cart sums

```php
// Numeric total (after tax / shipping / discounts depending on cart_sum mode)
$total = $shop->cart_sum(true);

// Formatted string ("$24.99")
$formatted = $shop->cart_sum(false);

// Just the subtotal (no tax/shipping)
$subtotal = $shop->sum(true);
```

## Product prices

```php
// Effective price for a product (after discounts, special pricing, sale rules)
$price = $shop->get_product_price($productId);

// Full price breakdown including all custom-field-driven prices (variants)
$priceTable = $shop->get_product_prices($productId, $returnFullArray = true);
// Returns: ['variant_key' => price, ...] when the product has variants
```

The methods walk the Product module's `content_data` for `price`, `special_price`, `special_price_start`, `special_price_end`, plus the variant table.

## Currency formatting

```php
echo $shop->currency_format(24.99);
// "$24.99"  (uses configured currency)

echo $shop->currency_format(24.99, 'EUR');
// "€24,99"  (override currency)

echo $shop->currency_symbol();
// "$"

echo $shop->currency_symbol('EUR');
// "€"

echo $shop->get_default_currency();
// "USD"
```

The format respects the configured separator + symbol position (read from `option_group='shop'`).

## Checkout

```php
// Trigger the checkout flow (called from the multi-step wizard's confirm)
$shop->checkout([
    'email'           => $customer->email,
    'shipping_method' => $shippingMethodId,
    'payment_method'  => $paymentMethodId,
    'billing_address' => [...],
    'shipping_address' => [...],
]);

// Returns an order id on success
```

Most code shouldn't call this directly — the Checkout module's wizard pages do. Direct calls are for programmatic order creation (API integrations, queued imports).

## Update an order

```php
$shop->update_order([
    'order_id' => $orderId,
    'status'   => 'shipped',
    'tracking_number' => 'UPS-1Z...',
]);
```

This fires the `order.updated` event which the Invoice + Notification + Shipping modules listen for.

## Storefront rendering

The canonical render path is the `<module type="shop" />` short-tag:

```html
<module type="shop" />
```

The Microweber render layer routes this to `Livewire\ShopComponent` which:

1. Reads the current Page's `is_shop=1` parent (via `getMainPageId()`)
2. Detects the active category from URL (`/shop/category/{slug}`)
3. Reads query params for filter state: `price_from`, `price_to`, `sort`, `page`, `keywords`, `tags`, `offers`
4. Queries the Product table with the active filter set
5. Renders the grid with pagination + filter sidebar

For custom storefronts that don't use the short-tag:

```php
echo \Livewire\Livewire::mount('module-shop', ['categoryId' => $catId])->html();
```

## Storefront filter UX

The component supports these filter actions (Livewire-bound):

| Action | Trigger | Effect |
|---|---|---|
| `updatedKeywords` | Search input change | Filter by title / description substring |
| `updatedOffers` | Sale-only checkbox | Filter to `special_price IS NOT NULL` |
| `updatedPriceFrom` / `updatedPriceTo` | Price-range slider | Filter by price band |
| `filterSort($field, $direction)` | Sort dropdown | Order by price / date / title / popularity |
| `filterLimit($limit)` | Page-size dropdown | Override default page size |
| `resetFilters()` | "Clear filters" button | Reset all filters + redirect to root storefront |

## Available categories

The component computes available categories dynamically based on which categories actually have products under the current view:

```php
$component = new \Modules\Shop\Livewire\ShopComponent;
$mainPageId = $component->getMainPageId();
$categories = $component->getAvailableCategories($mainPageId);
```

This drives the sidebar category tree — categories with zero products in the current filter are hidden.

## Inventory / stock display

```php
$product = \Modules\Product\Models\Product::find($id);
$inStock = $product->getContentDataByFieldName('qty') > 0;
$qty = (int) $product->getContentDataByFieldName('qty');
```

The Shop settings determine whether the storefront shows the count, hides it, or hides the product entirely when out of stock. Read the configured behaviour:

```php
$display = get_option('inventory_show_count', 'shop') ?: '1';
$behaviour = get_option('out_of_stock_behaviour', 'shop') ?: 'notice';
```

## Coupons + discounts

```php
// Apply a coupon to the current cart
$result = app('coupon_manager')->apply_coupon($code);

// Read the discount the manager applied
$discount = $shop->cart_discount_amount();
```

The Coupons module (`Modules/Coupons/`) owns the validation logic; Shop reads the result for cart-sum calculations.

## Currency switching (multi-currency stores)

```php
// Per-request currency override (e.g. from a geo-detection layer)
session(['shop_currency' => 'EUR']);

// All subsequent shop reads use EUR
echo $shop->currency_format(24.99);  // "€24,99"
```

The Currency module's exchange-rate table converts product prices to the active currency at display time. Stored prices stay in the default currency.

## Tax application

```php
// Tax-inclusive vs tax-exclusive display
$mode = get_option('tax_mode', 'shop') ?: 'exclude';

if ($mode === 'include') {
    $price = $shop->get_product_price($id) * (1 + $taxRate);
} else {
    $price = $shop->get_product_price($id);
}
```

The Tax module's rules (per-country / per-state / per-product-category) compute the rate. The Shop manager applies the configured display mode.

## Events

| Event | Fires when | Owner |
|---|---|---|
| `cart.added` | After `add_to_cart` succeeds | Cart module |
| `cart.removed` | After `remove_from_cart` | Cart module |
| `cart.cleared` | After `clear_cart` | Cart module |
| `checkout.started` | First step of checkout | Checkout module |
| `order.created` | After `place_order` | Order module |
| `order.updated` | After `update_order` | Order module |
| `order.paid` | After payment success | Payment module |
| `order.shipped` | After shipment tracking added | Shipping module |

Listen via the standard Laravel event API in any service provider.

## REST API (delegated to satellites)

Shop itself doesn't expose its own REST API — operations route to the satellite controllers:

- Cart operations: `/api/cart/*` (Cart module)
- Checkout: `/api/checkout/*` (Checkout module)
- Orders: `/api/orders/*` (Order module)
- Products: `/api/products/*` (Product module)

See each satellite's `api.md` for the full endpoint list. The Shop manager is a server-side coordinator, not a REST surface.
