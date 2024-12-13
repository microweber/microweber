<?php

use Modules\Coupons\Models\Coupon;
use Modules\Coupons\Models\CouponLog;
use Modules\Coupons\Services\CouponService;

if (!function_exists('coupon_apply')) {
    function coupon_apply(array $params = []): array
    {
        $cartTotal = cart_sum(true);

        return app()->coupon_service->applyCoupon(
            $params['coupon_code'],
            $cartTotal,
            auth()->user()?->email,
            request()->ip()
        );
    }
}

if (!function_exists('coupon_consume')) {
    function coupon_consume(string $coupon_code, string $customer_email): void
    {
        app()->coupon_service->consumeCoupon($coupon_code, $customer_email, request()->ip());
    }
}

if (!function_exists('coupon_get_by_code')) {
    function coupon_get_by_code(string $coupon_code): ?array
    {
        $coupon = Coupon::where('coupon_code', $coupon_code)
            ->where('is_active', 1)
            ->first();

        return $coupon ? $coupon->toArray() : null;
    }
}

if (!function_exists('coupon_get_by_id')) {
    function coupon_get_by_id(int $coupon_id): ?array
    {
        $coupon = Coupon::find($coupon_id);
        return $coupon ? $coupon->toArray() : null;
    }
}

if (!function_exists('coupon_get_all')) {
    function coupon_get_all(): array
    {
        return Coupon::all()->toArray();
    }
}

if (!function_exists('coupon_get_count')) {
    function coupon_get_count(): int
    {
        return Coupon::count();
    }
}

if (!function_exists('coupon_logs')) {
    function coupon_logs(): array
    {
        return CouponLog::with('coupon')->get()->toArray();
    }
}

if (!function_exists('coupon_log_get_by_code_and_customer_email_and_ip')) {
    function coupon_log_get_by_code_and_customer_email_and_ip(string $coupon_code, string $customer_email, string $customer_ip): ?array
    {
        $log = CouponLog::where('coupon_code', $coupon_code)
            ->where('customer_email', $customer_email)
            ->where('customer_ip', $customer_ip)
            ->first();

        return $log ? $log->toArray() : null;
    }
}

if (!function_exists('coupon_log_get_by_code_and_customer_ip')) {
    function coupon_log_get_by_code_and_customer_ip(string $coupon_code, string $customer_ip): ?array
    {
        $log = CouponLog::where('coupon_code', $coupon_code)
            ->where('customer_ip', $customer_ip)
            ->first();

        return $log ? $log->toArray() : null;
    }
}

if (!function_exists('coupon_logs_get_by_code')) {
    function coupon_logs_get_by_code(string $coupon_code): array
    {
        return CouponLog::where('coupon_code', $coupon_code)->get()->toArray();
    }
}

if (!function_exists('coupons_delete_session')) {
    function coupons_delete_session(): array
    {
        app()->coupon_service->clearCouponSession();

        return ['success' => true];
    }
}

if (!function_exists('coupons_get_session')) {
    function coupons_get_session(): ?array
    {
        return app()->coupon_service->getCouponSession();
    }
}

if (!function_exists('coupons_save_coupon')) {
    function coupons_save_coupon(array $data): array
    {
        try {
            $coupon = Coupon::updateOrCreate(
                ['id' => $data['id'] ?? null],
                array_merge($data, [
                    'is_active' => $data['is_active'] ?? true,
                ])
            );

            if (coupon_get_count() == 1) {
                save_option('enable_coupons', 1, 'shop');
            }

            return [
                'success' => true,
                'coupon_id' => $coupon->id,
                'success_edit' => true
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }
    }
}

if (!function_exists('coupon_delete')) {
    function coupon_delete(array $data): array
    {
        if (!isset($data['coupon_id']) || !is_numeric($data['coupon_id'])) {
            return ['status' => 'failed'];
        }

        $coupon = Coupon::find($data['coupon_id']);
        if (!$coupon) {
            return ['status' => 'failed'];
        }

        $coupon->delete();
        return ['status' => 'success'];
    }
}
