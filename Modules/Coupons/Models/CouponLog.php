<?php

namespace Modules\Coupons\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CouponLog extends Model
{
    protected $table = 'cart_coupon_logs';

    protected $fillable = [
        'coupon_id',
        'coupon_code',
        'customer_email',
        'customer_ip',
        'uses_count',
        'use_date'
    ];

    protected $casts = [
        'use_date' => 'datetime',
        'uses_count' => 'integer'
    ];

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }

    public static function logUsage(Coupon $coupon, string $customerEmail, string $customerIp): self
    {
        $log = static::firstOrNew([
            'coupon_id' => $coupon->id,
            'customer_email' => $customerEmail,
            'customer_ip' => $customerIp,
        ]);

        if (!$log->exists) {
            $log->coupon_code = $coupon->coupon_code;
            $log->uses_count = 0;
        }

        $log->uses_count++;
        $log->use_date = now();
        $log->save();

        return $log;
    }
}
