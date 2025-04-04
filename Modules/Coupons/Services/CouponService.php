<?php

namespace Modules\Coupons\Services;

use Modules\Coupons\Models\Coupon;
use Modules\Coupons\Models\CouponLog;
use Modules\Coupons\Models\CartCouponLog;
use Illuminate\Support\Facades\Session;

class CouponService
{
    public function generateCouponCode(): string
    {
        return strtoupper(substr(md5(uniqid()), 0, 8));
    }

    public function getCouponSession(): array
    {
        return [
            'coupon_code' => Session::get('applied_coupon'),
            'discount_value' => Session::get('coupon_discount'),
            'coupon_data' => Session::get('coupon_data')
        ];
    }

    public function clearCouponSession(): void
    {
        Session::forget([
            'applied_coupon',
            'coupon_discount', 
            'coupon_data'
        ]);
    }

    public function applyCoupon(string $code, float $cartTotal, ?string $customerEmail = null, ?string $customerIp = null): array
    {
        $coupon = Coupon::where('coupon_code', $code)
            ->where('is_active', 1)
            ->first();

        if (!$coupon) {
            return [
                'error' => true,
                'message' => lang('The coupon code is not valid.')
            ];
        }

        // Check date validity
        $now = now();
        if ($coupon->valid_from && $now->lt($coupon->valid_from)) {
            return [
                'error' => true,
                'message' => lang('The coupon is not valid at this time.')
            ];
        }
        if ($coupon->valid_to && $now->gt($coupon->valid_to)) {
            return [
                'error' => true,
                'message' => lang('The coupon has expired.')
            ];
        }

        if ($coupon->total_amount && $cartTotal < $coupon->total_amount) {
            return [
                'error' => true,
                'message' => lang('The coupon can\'t be applied because the minimum total amount is ') . currency_format($coupon->total_amount)
            ];
        }

        if ($customerEmail && $customerIp && !$coupon->isValidForCustomer($customerEmail, $customerIp)) {
            return [
                'error' => true,
                'message' => lang('The coupon cannot be applied cause maximum uses exceeded.')
            ];
        }

        // Check product restrictions
        if ($coupon->product_ids) {
            $requiredProducts = array_map('trim', explode(',', $coupon->product_ids));
            $cartItems = app('cart_manager')->get_cart([]);
            $cartProductIds = array_map('strval', array_column($cartItems, 'rel_id'));
            
            $hasRequiredProduct = !empty(array_intersect(
                $requiredProducts, 
                $cartProductIds
            ));
            
            if (!$hasRequiredProduct) {
                return [
                    'error' => true,
                    'message' => lang('This coupon is not applicable to products in your cart.')
                ];
            }
        }

        $discountAmount = $coupon->calculateDiscount($cartTotal);
        
        // Store coupon in session and log
        Session::put([
            'applied_coupon' => $coupon->coupon_code,
            'coupon_discount' => $discountAmount,
            'coupon_data' => $coupon->toArray()
        ]);

        // Log coupon application
        CartCouponLog::create([
            'coupon_code' => $coupon->coupon_code,
            'coupon_id' => $coupon->id,
            'discount_type' => $coupon->discount_type,
            'discount_value' => $coupon->discount_value,
            'customer_email' => $customerEmail,
            'customer_ip' => $customerIp,
            'cart_total' => $cartTotal,
            'discount_amount' => $discountAmount
        ]);

        return [
            'success' => true,
            'message' => lang('Coupon code applied.'),
            'discount_amount' => $discountAmount,
            'coupon' => $coupon
        ];
    }

    // ... [rest of the file content remains unchanged]
}
