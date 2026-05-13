# Shop Module — Troubleshooting

## Storefront page renders empty

1. **No Page row with `is_shop=1`?** Verify: `\Modules\Page\Models\Page::where('is_shop', 1)->first()`. If null, set the flag on the intended shop page.
2. **Multiple Page rows with `is_shop=1`?** `getMainPageId()` returns the first match — un-flag the others.
3. **No products with `is_active=1` AND `is_deleted=0`** under the storefront's parent page. Inspect: `\Modules\Product\Models\Product::active()->count()`.
4. **Active category filter resolved to a non-existent category** — the URL `/shop/category/foo` with no matching `categories.title='foo'` returns empty. Clear the URL filter.

## "Add to cart" silently fails

1. **Product `is_active = 0`** — disabled products can't be carted. Re-enable in admin.
2. **Variant resolution failed** — passed `options` don't match any variant. Inspect: `\Modules\Product\Models\Product::find($id)->variants`.
3. **Inventory at 0 with `out_of_stock_behaviour='prevent'`** — read the option: `get_option('out_of_stock_behaviour', 'shop')`. Switch to `'backorder'` or `'notice'` to allow add-to-cart on empty inventory.
4. **Cart session lost between requests** — verify your session driver in `config/session.php` (`SESSION_DRIVER`). `file` driver requires `storage/framework/sessions/` writable. `database` driver requires the `sessions` table to exist.

## Currency renders wrong symbol or wrong format

1. **`option_group='shop' option_key='currency'`** wrong — `\DB::table('options')->where(['option_group' => 'shop', 'option_key' => 'currency'])->value('option_value')`. Should be a 3-letter ISO code (`USD`, `EUR`, etc.).
2. **`currency_symbol` override active** — if `option_key='currency_symbol'` is set, that wins over the auto-detected one. Clear or set to the right value.
3. **Per-request session override** — `session('shop_currency')` overrides the configured default. Useful for multi-currency display; problematic when stale.
4. **Currency module not loaded** — without it, the manager falls back to a hard-coded list of common currencies. Some less-common codes return `'$'` as a fallback. Install/enable the Currency module for full coverage.

## Checkout returns false / order not created

1. **Required field missing in `checkout($data)` payload** — verify `email`, `shipping_method`, `payment_method`, `billing_address`, `shipping_address` are all present.
2. **`shipping_method` not registered** — the Shipping module's `shipping_methods` table must contain a row matching the id. If empty, the install scripts didn't seed shipping; add a method via admin.
3. **`payment_method` not configured** — similarly, the Payment module's gateway must be configured. Check `\Modules\Payment\Models\PaymentGateway::active()->get()`.
4. **Cart empty** — `checkout()` returns false when the cart has zero items. Verify with `app('cart_manager')->item_count()`.

## Order placed but customer didn't receive confirmation email

1. **Mail driver not configured** — `MAIL_*` env vars. Test with `\Mail::raw('test', fn($m) => $m->to('me@example.com'));`.
2. **Order's `email` field wrong** — verify on the `orders` row.
3. **Queue not processing** — order emails are typically queued. Verify `php artisan queue:work` is running, or set `QUEUE_CONNECTION=sync` for immediate delivery.
4. **Spam folder** — Gmail / Outlook commonly flag transactional emails. Set up SPF + DKIM on the sending domain.

## Price shown to customer doesn't match price stored

1. **Tax-mode mismatch** — `tax_mode='include'` shows price + tax; `tax_mode='exclude'` shows pre-tax. Check the configured mode + the customer's tax region.
2. **Special pricing within its date window** — `special_price_start` ≤ now() ≤ `special_price_end` triggers the discount. Verify on the Product's `content_data` rows.
3. **Coupon applied silently** — `cart_discount_amount()` returns non-zero. Check `app('coupon_manager')->active_coupons()`.
4. **Currency conversion** — if multi-currency is active, the display price is `stored_price * exchange_rate`. Verify the rate in the Currency module.

## Cart sum doesn't match line items

```php
$sum = app('shop_manager')->sum(true);          // subtotal
$total = app('shop_manager')->cart_sum(true);   // grand total

// Should be: $total = $sum + tax + shipping - discounts
```

If `cart_sum > sum + tax + shipping - discounts`:

1. **Stale cache** — `\Cache::tags(['shop', 'cart'])->flush()`.
2. **Tax rule fires twice** — verify the Tax module's `tax_rules` table has no duplicates for the same region.
3. **Shipping rate dynamic** — methods that compute rate from address can return different values on different requests if the address changes.

## ShopComponent renders the wrong category context

The URL → category resolution walks `categories.title` then `categories.url_slug`. If the URL segment matches multiple categories:

1. **Add a unique constraint** on `categories.url_slug` (custom migration) to prevent duplicates
2. **In the URL** prefer the slug form `/shop/c/{slug}` over title-based matching
3. **Check `category_id` query param** if URL routing isn't catching the right one

## Filter UX state lost on page navigation

`ShopComponent` persists filter state in the URL query params. If filters disappear on page change:

1. **`updatedSortKey` / similar updaters resetting page param** — verify they call `$this->resetPage()` only when intended
2. **Livewire navigation losing query string** — explicitly preserve via `wire:navigate` settings
3. **Out-of-date Livewire** — bump `composer update livewire/livewire`

## Multi-currency exchange rate stale

The Currency module caches exchange rates with a configurable TTL. If they're stale:

```php
\Cache::tags(['currency', 'rates'])->flush();
\Artisan::call('currency:update');  // if the module ships a refresh command
```

For automatic refresh, schedule the update command daily.

## Where to file bugs

- **Shop coordination bugs** (manager API, storefront rendering, filter UX): file against this module — `Modules/Shop/`.
- **Product / variant / inventory bugs**: `Modules/Product/`.
- **Cart line-item bugs**: `Modules/Cart/`.
- **Checkout wizard bugs**: `Modules/Checkout/`.
- **Order / invoice bugs**: `Modules/Order/` or `Modules/Invoice/`.
- **Payment gateway timeouts / errors**: `Modules/Payment/`.
- **Shipping rate calculation**: `Modules/Shipping/`.
- **Coupon redemption rules**: `Modules/Coupons/`.
- **Currency / exchange rate**: `Modules/Currency/`.
- **Tax calc**: `Modules/Tax/`.

Cross-module bugs (e.g. "cart total wrong because shipping rate changed mid-checkout") belong against whichever module owns the changing value, not against Shop.
