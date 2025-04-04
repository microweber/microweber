<?php

namespace Modules\Coupons\Models;

use Illuminate\Database\Eloquent\Model;

class CartCouponLog extends Model
{
    protected $table = 'cart_coupon_logs';
    
    protected $fillable = [
        'coupon_code',
        'coupon_id',
        'discount_type',
        'discount_value',
        'customer_email',
        'customer_ip',
        'cart_total',
        'discount_amount'
    ];
}
