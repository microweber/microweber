<?php

namespace Modules\Coupons\Services;

use Modules\Coupons\Models\Coupon;
use Modules\Coupons\Models\CouponLog;
use Illuminate\Support\Facades\Session;

class CouponService
{
    public function applyCoupon(string $code, float $cartTotal, ?string $customerEmail = null, ?string $customerIp = null): array
    {
//        if (!get_option('enable_coupons', 'shop')) {
//            return [
//                'success' => false,
//                'message' => __('The coupon code usage is disabled.')
//            ];
//        }

        $coupon = Coupon::where('coupon_code', $code)
            ->where('is_active', 1)
            ->first();

        if (!$coupon) {
            return [
                'success' => false,
                'message' => __('The coupon code is not valid.')
            ];
        }

        if ($coupon->total_amount && $cartTotal < $coupon->total_amount) {
            return [
                'success' => false,
                'message' => __('The coupon can\'t be applied because the minimum total amount is ') . currency_format($coupon->total_amount)
            ];
        }

        if ($customerEmail && $customerIp && !$coupon->isValidForCustomer($customerEmail, $customerIp)) {
            return [
                'success' => false,
                'message' => __('The coupon cannot be applied cause maximum uses exceeded.')
            ];
        }

        $discountAmount = $coupon->calculateDiscount($cartTotal);

        $this->storeCouponInSession($coupon);

        return [
            'success' => true,
            'message' => __('Coupon code applied.'),
            'discount_amount' => $discountAmount,
            'coupon' => $coupon
        ];
    }

    public function consumeCoupon(string $code, string $customerEmail, string $customerIp): void
    {
        $coupon = Coupon::where('coupon_code', $code)->first();
        if (!$coupon) {
            return;
        }

        CouponLog::logUsage($coupon, $customerEmail, $customerIp);
        $this->clearCouponSession();
    }

    public function clearCouponSession(): void
    {
        Session::forget([
            'coupon_code',
            'coupon_id',
            'discount_value',
            'discount_type',
            'applied_coupon_data'
        ]);
    }

    private function storeCouponInSession(Coupon $coupon): void
    {
        Session::put([
            'coupon_code' => $coupon->coupon_code,
            'coupon_id' => $coupon->id,
            'discount_value' => $coupon->discount_value,
            'discount_type' => $coupon->discount_type,
            'applied_coupon_data' => $coupon->toArray()
        ]);
    }

    public function generateCouponCode(int $length = 8): string
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = '';

        do {
            $code = '';
            for ($i = 0; $i < $length; $i++) {
                $code .= $characters[rand(0, strlen($characters) - 1)];
            }
        } while (Coupon::where('coupon_code', $code)->exists());

        return $code;
    }

    public function getAppliedCoupon(): ?array
    {
        return Session::get('applied_coupon_data');
    }

    public function getCouponSession(): ?array
    {
        $couponData = $this->getAppliedCoupon();
        if (!$couponData) {
            return null;
        }
        
        return [
            'coupon_code' => $couponData['coupon_code'],
            'discount_value' => $couponData['discount_value'],
            'discount_type' => $couponData['discount_type']
        ];
    }

    public function getAppliedDiscount(float $cartTotal): float
    {
        $appliedCoupon = $this->getAppliedCoupon();
        if (!$appliedCoupon) {
            return 0;
        }

        $coupon = new Coupon($appliedCoupon);
        return $coupon->calculateDiscount($cartTotal);
    }
}
