<?php

namespace Tests;

use Modules\Coupons\Services\CouponService;

class TestCouponService extends CouponService
{
    protected function checkSystemRequirements(): bool
    {
        return true; // Bypass all system checks
    }

    protected function getOption(string $key, string $group = null)
    {
        // Return test values without database
        if ($key === 'enable_coupons') return '1';
        if ($key === 'enable_taxes') return '0';
        if ($key === 'enabled' && $group === 'white_label') return '0';
        return null;
    }
}