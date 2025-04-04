<?php

namespace Modules\Coupons\Services;

use Modules\Coupons\Models\Coupon;
use Modules\Coupons\Models\CouponLog;
use Illuminate\Support\Facades\Session;

class CouponService
{
    public function isEnabled(): bool
    {
        return (bool) get_option('enable_coupons', 'shop');
    }

    public function setEnabled(bool $enabled): void
    {
        save_option('enable_coupons', $enabled ? 1 : 0, 'shop');
    }

    public function applyCoupon(string $code, float $cartTotal, ?string $customerEmail = null, ?string $customerIp = null): array
    {
if (!$this->isEnabled()) {
    return [
        'success' => false,
        'message' => __('Coupons are currently disabled')
    ];
}
//        }

        $coupon = Coupon::where('coupon_code', $code)
            ->where('is_active', 1)
            ->first();

        if (!$coupon) {
            return [
                'error' => true,
                'message' => lang('The coupon code is not valid.')
            ];
        }

        if ($coupon->total_amount && $cartTotal < $coupon->total_amount) {
            return [
                'error' => true,
                'message' => lang('The coupon can\'t be applied because the minimum total amount is ') . currency_format($coupon->total_amount)
            ];
        }

        // Date validation
        $now = now();
        if ($coupon->start_date && $now->lt($coupon->start_date)) {
            return [
                'error' => true,
                'message' => lang('This coupon is not yet valid.')
            ];
        }

        if ($coupon->end_date && $now->gt($coupon->end_date)) {
            return [
                'error' => true,
                'message' => lang('This coupon has expired.')
            ];
        }

        if ($customerEmail && $customerIp && !$coupon->isValidForCustomer($customerEmail, $customerIp)) {
            return [
                'error' => true,
                'message' => lang('The coupon cannot be applied cause maximum uses exceeded.')
            ];
        }

        $discountAmount = $coupon->calculateDiscount($cartTotal);

        $this->storeCouponInSession($coupon);

        return [
            'success' => true,
            'message' => lang('Coupon code applied.'),
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
