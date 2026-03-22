# Coupon/Discount System Documentation

## Overview

The enhanced coupon/discount system provides comprehensive coupon management with advanced rules, usage limits, and sophisticated validation logic.

## Features

### Core Features
- **Percentage Discounts**: Apply percentage-based discounts (e.g., 20% off)
- **Fixed Amount Discounts**: Apply fixed monetary discounts (e.g., $10 off)
- **Free Shipping**: Grant free shipping instead of monetary discount
- **Maximum Discount Cap**: Limit percentage discounts to a maximum amount
- **Minimum Order Amount**: Require minimum cart total before coupon applies

### Advanced Rules

#### Stacking Rules
- **Is Stackable**: Allow coupons to be combined with other coupons
- Stacking validation ensures non-stackable coupons cannot be combined
- Multiple stackable coupons can be applied to a single order

#### Customer Restrictions
- **Customer Groups**: Restrict coupons to specific customer groups
- **First-Time Customers Only**: Allow only for customers with no previous orders
- **Uses Per Customer**: Limit how many times a customer can use the coupon

#### Product Restrictions
- **Product Restrictions**: Apply only to specific products (comma-separated IDs)
- **Excluded Products**: Exclude specific products from the coupon
- **Category Restrictions**: Apply only to specific categories

#### Usage Limits
- **Total Usage Limit**: Global limit on total coupon uses
- **Per-Customer Limit**: Limit per individual customer
- **Usage Tracking**: Denormalized statistics for performance

#### Auto-Apply
- **Auto-Apply**: Automatically apply when cart conditions are met
- Works in conjunction with other validation rules

## API Usage

### Apply Coupon
```php
$couponService = app(\Modules\Coupons\Services\CouponService::class);

$result = $couponService->applyCoupon(
    'SAVE20',
    100.00,           // Cart total
    'user@email.com', // Customer email
    '127.0.0.1',      // Customer IP
    [
        'user_id' => 123,
        'customer_group_id' => 1,
        'cart_product_ids' => [1, 2, 3],
        'cart_category_ids' => [5, 6],
    ]
);
```

### Validate Coupon
```php
$validation = $couponService->canApplyCoupon('SAVE20', 100.00, [
    'customer_group_id' => 1,
    'cart_product_ids' => [1, 2],
    'cart_category_ids' => [5],
]);

if ($validation['can_apply']) {
    // Coupon can be applied
} else {
    // Handle error: $validation['message']
}
```

### Get Auto-Apply Coupons
```php
$autoCoupons = $couponService->getAutoApplyCoupons(100.00, [
    'user_id' => 123,
    'customer_group_id' => 1,
]);
```

## Database Schema

### New Fields Added (Migration: 2025_03_22_000001_add_advanced_coupon_fields)

| Field | Type | Description |
|-------|------|-------------|
| `is_stackable` | boolean | Allow combining with other coupons |
| `customer_group_ids` | text | Comma-separated allowed customer group IDs |
| `category_ids` | text | Comma-separated allowed category IDs |
| `excluded_product_ids` | text | Comma-separated excluded product IDs |
| `first_time_only` | boolean | Only for first-time customers |
| `auto_apply` | boolean | Auto-apply when conditions met |
| `free_shipping` | boolean | Grant free shipping |
| `max_discount_amount` | decimal | Maximum discount for percentage coupons |
| `times_used` | integer | Denormalized usage count |
| `total_discount_given` | decimal | Total discount amount given |
| `description` | text | Admin notes |

## Filament Admin UI

### Form Sections
1. **Basic Information**: Name, code, description
2. **Discount Settings**: Type, value, maximum discount, minimum order
3. **Usage Limits**: Total uses, per-customer uses
4. **Advanced Rules**: Stacking, customer groups, product/category restrictions

### Table Features
- Usage statistics display (e.g., "25/100")
- Stackable/Auto-apply icons
- Bulk activate/deactivate actions
- Duplicate coupon functionality
- Advanced filtering

## Coupon Model Methods

### Validation Methods
```php
$coupon->isValid();                           // Check if coupon is active and valid
$coupon->isValidForCustomer($email, $ip);    // Check per-customer limits
$coupon->isValidForCustomerGroup($groupId);  // Check customer group
$coupon->isValidForFirstTimeCustomer($userId, $email); // Check first-time status
$coupon->isStackable();                       // Check if can be combined
```

### Restriction Methods
```php
$coupon->appliesToProducts($productIds);     // Check product restrictions
$coupon->appliesToCategories($categoryIds);    // Check category restrictions
$coupon->getExcludedProducts($productIds);   // Get excluded products
```

### Calculation Methods
```php
$coupon->calculateDiscount($amount);                    // Calculate discount
$coupon->calculateDiscountForItems($items);              // Calculate for specific items
$coupon->getFormattedDiscountAttribute();              // Get formatted display
$coupon->getUsageStats();                              // Get usage statistics
$coupon->incrementUsage($discountAmount);               // Increment usage stats
```

## Testing

Run coupon tests:
```bash
php artisan test Modules/Coupons/Tests/Unit/CouponServiceTest.php
php artisan test Modules/Coupons/Tests/Unit/CouponAdvancedRulesTest.php
php artisan test Modules/Coupons/Tests/Unit/CouponValidationTest.php
php artisan test Modules/Coupons/Tests/Unit/Filament/CouponResourceTest.php
```

## Error Messages

All error messages are translatable using Laravel's `lang()` function:

- "The coupon code is not valid."
- "This coupon cannot be combined with other coupons."
- "This coupon is not valid for your customer group."
- "This coupon is only valid for first-time customers."
- "This coupon cannot be applied to products in your cart."
- "This coupon is not valid for the categories in your cart."
- "The coupon has reached its maximum usage limit for this customer."
- "The coupon is not valid at this time."
- "The coupon has expired."
- "The coupon can't be applied because the minimum total amount is..."
- "Coupon code applied."

## Future Enhancements

Potential future additions:
- Buy X Get Y promotions
- Tiered discounts (spend more, save more)
- Product bundles
- Time-based flash sales
- Personalized coupon codes
