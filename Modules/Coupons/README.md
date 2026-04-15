# Coupons

Discount coupon system for e-commerce. Create percentage or fixed-amount coupons with usage limits, validity dates, product restrictions, and advanced discount rules.

## Key Features

- Percentage and fixed-amount discounts
- Coupon usage tracking and limits
- Validity date ranges (start/end dates)
- Product-specific restrictions
- Advanced discount rules
- Automatic coupon logging on order creation
- Cart coupon application and validation
- Admin management via Filament

## Key Classes

| Class | Purpose |
|---|---|
| `Services\CouponService` | Coupon operations (`app('coupon_service')`) |
| `Models\Coupon` | Coupon definition (code, discount, rules) |
| `Models\CouponLog` | Usage tracking log |
| `Models\CartCoupon` | Applied coupon on a cart |
| `Models\CartCouponLog` | Cart-level coupon log |
| `Listeners\OrderWasCreatedCouponCodeLogger` | Logs coupon usage on order creation |

## Events

Listens to: `OrderWasCreated` (from Order module) to log coupon usage via `OrderWasCreatedCouponCodeLogger`.

## Database Tables

- `coupons` -- coupon definitions (with validity dates, product restrictions, advanced fields)
- `coupon_logs` -- coupon usage records
- `cart_coupons` -- coupons applied to carts
- `cart_coupons_log` -- cart coupon audit log

## Admin Panel (Filament)

- **CouponResource** -- create, edit, and manage coupons

## API Endpoints

Defined in `routes/api.php` for coupon validation and application.

## Usage

```php
$couponService = app('coupon_service');
$coupon = \Modules\Coupons\Models\Coupon::where('code', 'SAVE20')->first();
```
