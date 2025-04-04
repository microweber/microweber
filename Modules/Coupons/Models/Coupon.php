<?php

namespace Modules\Coupons\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    protected $table = 'cart_coupons';

    protected $fillable = [
        'coupon_name',
        'coupon_code',
        'discount_type',
        'discount_value',
        'total_amount',
        'uses_per_coupon',
        'uses_per_customer',
        'is_active',
        'valid_from',
        'valid_to',
        'product_ids'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'discount_value' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'uses_per_coupon' => 'integer',
        'uses_per_customer' => 'integer',
        'valid_from' => 'datetime',
        'valid_to' => 'datetime'
    ];

    public function logs(): HasMany
    {
        return $this->hasMany(CouponLog::class, 'coupon_id');
    }

    public function isValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->uses_per_coupon > 0 && $this->logs()->count() >= $this->uses_per_coupon) {
            return false;
        }

        $now = now();
        if ($this->valid_from && $now->lt($this->valid_from)) {
            return false;
        }

        if ($this->valid_to && $now->gt($this->valid_to)) {
            return false;
        }

        return true;
    }

    public function isValidForCustomer(string $customerEmail, string $customerIp): bool
    {
        if (!$this->isValid()) {
            return false;
        }

        if ($this->uses_per_customer > 0) {
            $customerUses = $this->logs()
                ->where('customer_email', $customerEmail)
                ->where('customer_ip', $customerIp)
                ->sum('uses_count');

            if ($customerUses >= $this->uses_per_customer) {
                return false;
            }
        }

        return true;
    }

    public function calculateDiscount(float $amount): float
    {
        if ($this->total_amount && $amount < $this->total_amount) {
            return 0;
        }

        if ($this->discount_type === 'percentage') {
            return $amount * ($this->discount_value / 100);
        }

        return min($this->discount_value, $amount);
    }
}
