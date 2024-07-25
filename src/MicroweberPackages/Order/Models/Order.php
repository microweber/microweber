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

namespace MicroweberPackages\Order\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Kirschbaum\PowerJoins\PowerJoins;
use MicroweberPackages\Cart\Concerns\HasCartItems;
use MicroweberPackages\Cart\Models\Cart;
use MicroweberPackages\Content\Models\Content;
use MicroweberPackages\Customer\Models\Customer;
use MicroweberPackages\Order\Enums\OrderStatus;
use MicroweberPackages\Order\Models\ModelFilters\OrderFilter;
use MicroweberPackages\Payment\Models\Payment;
use MicroweberPackages\User\Models\User;

class Order extends Model
{
    use Notifiable;
    use Filterable;
    use PowerJoins;


    use HasCartItems;

    public $table = 'cart_orders';
    public $fillable = [
        'id',
        'order_id',
        'customer_id',
        'email',
        'first_name',
        'last_name',
        'country',
        'amount',
        'payment_amount',
        'transaction_id',
        'order_completed',
        'is_paid',
        'city',
        'state',
        'zip',
        'address',
        'address2',
        'other_info',
        'phone',
        'user_ip',
        'is_completed',
        'payment_gw',
        'order_status'
    ];

    protected $searchable = [
        'is_completed',
    ];

    protected $casts = [
        'order_status' => OrderStatus::class,
    ];


    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $model->calculateNewAmount();
        });
        static::updated(function ($model) {
            $model->calculateNewAmount();
        });

    }

    public function calculateNewAmount()
    {
        $amount = 0;
        $cart = $this->cart;
        if ($cart) {
            foreach ($cart as $cartItem) {
                $amount += $cartItem->price * $cartItem->qty;
            }
        }

        $this->amount = $amount;
        $this->save();
    }

    public function modelFilter()
    {
        return $this->provideFilter(OrderFilter::class);
    }


    public function payments()
    {
        return $this->morphMany(Payment::class, 'rel');
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
        if ($this->payment_gw == 'shop/payments/gateways/paypal') {
            return 'PayPal';
        } else {

            $name = $this->payment_gw;
            $name = str_replace('shop/payments/gateways/','', $name);
            $name = str_replace('_', ' ', $name);

            $name = ucwords($name);

            return $name;
        }

        return '';
    }

    public function customerName()
    {
        if (!empty($this->first_name) or !empty($this->last_name)) {
            return $this->first_name . ' ' . $this->last_name;
        }
        if ($this->customer_id > 0) {
            $customer = Customer::where('id', $this->customer_id)->first();
            if ($customer) {
                if (!empty($customer->first_name) and !empty($customer->last_name)) {
                    return $customer->first_name . ' ' . $customer->last_name;
                }
            }
        }

        return 'Anonymous';
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
            'firstProduct'=>$cartProduct,
            'products'=>$carts
        ];
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }


    public function getPaymentStatuses()
    {
        return [
            'refunded'=>'Refunded',
            'completed'=>'Completed',
            'pending'=>'Pending',
        ];
    }

    public static function getOrderStatuses()
    {
        return [
            'new'=>'New',
            'completed'=>'Completed',
            'pending'=>'Pending',
        ];
    }
}
