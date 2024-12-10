<?php
/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber CMS LTD
 *
 * For full license information see
 * https://github.com/microweber/microweber/blob/master/LICENSE
 *
 */

namespace Modules\Order\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use MicroweberPackages\User\Models\User;
use Modules\Cart\Traits\HasCartItems;
use Modules\Customer\Models\Customer;
use Modules\Order\Enums\OrderStatus;
use Modules\Order\Models\ModelFilters\OrderFilter;
use Modules\Payment\Enums\PaymentStatus;

class Order extends Model
{
    use Notifiable;
    use Filterable;

    // use PowerJoins;
    use SoftDeletes;

    use HasCartItems;

    public $table = 'cart_orders';
    public $fillable = [

        'email',
        'first_name',
        'last_name',
        'country',
        'city',
        'state',
        'zip',
        'address',
        'address2',
        'other_info',
        'phone',
        'custom_fields_data',
        'order_status',
        'customer_id',
        'order_reference_id',
    ];

    protected $searchable = [
        'is_completed',
        'order_reference_id',
        'email',
        'first_name',
        'last_name',
        'country',
        'city',
        'state',
        'zip',
        'address',
        'address2',
        'other_info',
        'phone',
        'custom_fields_data',
        'order_status',
        'customer_id',

    ];

    protected $casts = [
       // 'order_status' => OrderStatus::class,
        'payment_data' => 'array',
        'custom_fields_data' => 'array',
    ];


    protected static function boot()
    {
        parent::boot();

//        static::created(function ($model) {
//            $model->calculateNewAmounts();
//        });
//        static::updated(function ($model) {
//            $model->calculateNewAmounts();
//        });

    }

//    public function calculateNewAmounts()
//    {
//        $paymentAmount = 0;
//        $amount = 0;
////        $cart = $this->cart;
////        if ($cart) {
////            foreach ($cart as $cartItem) {
////                $amount += $cartItem->price * $cartItem->qty;
////            }
////        }
//
//        $payments = $this->payments()->where('status', PaymentStatus::Completed)->get();
//        if ($payments) {
//            foreach ($payments as $payment) {
//                $paymentAmount += $payment->amount;
//            }
//        }
//
//        $this->payment_amount = $paymentAmount;
//        $this->amount = $amount;
//        $this->saveQuietly();
//    }

    public function modelFilter()
    {
        return $this->provideFilter(OrderFilter::class);
    }


    public function payments()
    {
        return $this->morphMany(\Modules\Payment\Models\Payment::class, 'rel');
    }

    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }

    public function shippingMethodName()
    {
        if ($this->shipping_service == 'shop/shipping/gateways/pickup') {
            return 'Pickup';
        }
        if ($this->shipping_service == 'shop/shipping/gateways/country') {
            return 'Shipping to address';
        }

        return $this->shipping_service;
    }

    public function addressText()
    {
        if (empty(trim($this->address))) {
            return 'No address';
        }

        return $this->address;
    }

    public function paymentMethodName()
    {

        return 'paymentMethodName here';
    }

    public function customerName()
    {
        if (!empty($this->first_name) or !empty($this->last_name)) {
            return $this->first_name . ' ' . $this->last_name;
        }
        if ($this->customer_id > 0) {
            $customer = \Modules\Customer\Models\Customer::where('id', $this->customer_id)->first();
            if ($customer) {
                if (!empty($customer->first_name) and !empty($customer->last_name)) {
                    return $customer->first_name . ' ' . $customer->last_name;
                }
            }
        }

        return 'Anonymous';
    }


    public function thumbnail($width = 100, $height = 100)
    {
        $cart = $this->cart->first();
        if ($cart) {
            return get_picture($cart->rel_id);
        }

        return pixum($width, $height);
    }

    public function cartProducts()
    {
        $carts = [];
        if (!empty($this->cart)) {
            $carts = $this->cart;
        }
        $cart = $this->cart->first();

        $cartProduct = [];
        if (isset($cart->products)) {
            $cartProduct = $cart->products->first();
        }

        return [
            'firstProduct' => $cartProduct,
            'products' => $carts
        ];
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }


    public function getPaymentStatuses()
    {
        return [
            'refunded' => 'Refunded',
            'completed' => 'Completed',
            'pending' => 'Pending',
        ];
    }

    public static function getOrderStatuses()
    {
        return [
            'new' => 'New',
            'completed' => 'Completed',
            'pending' => 'Pending',
        ];
    }
}
