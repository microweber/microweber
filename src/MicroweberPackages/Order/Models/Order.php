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
use MicroweberPackages\Cart\Models\Cart;
use MicroweberPackages\Customer\Models\Customer;
use MicroweberPackages\Order\Models\ModelFilters\OrderFilter;
use MicroweberPackages\User\Models\User;

class Order extends Model
{
    use Notifiable;
    use Filterable;
    use PowerJoins;

    public $table = 'cart_orders';
    public $fillable = [
        'id',
        'email',
        'first_name',
        'last_name',
        'country',
        'amount',
        'payment_amount',
        'transaction_id',
        'city',
        'state',
        'zip',
        'address',
        'phone',
        'user_ip',
        'is_completed',
        'payment_gw'
    ];

    protected $searchable = [
        'is_completed',

    ];

    public function modelFilter()
    {
        return $this->provideFilter(OrderFilter::class);
    }

    public function cart()
    {
        return $this->hasMany(Cart::class);
    }

    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

    public function shippingMethodName()
    {
        if ($this->shipping_service == 'shop/shipping/gateways/pickup') {
            return 'Pickup';
        }
        if ($this->shipping_service == 'shop/shipping/gateways/country') {
            return 'Shipping to address';
        }

        return '';
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
        $orderUser = $this->user()->first();
        if ($orderUser != null) {
            if ($this->customer_id > 0) {
                $orderUser = \MicroweberPackages\Customer\Models\Customer::where('id', $this->customer_id)->first();
            }
            if ($orderUser->first_name) {
                $fullName = $orderUser->first_name;
                if ($orderUser->last_name) {
                    $fullName .= $orderUser->last_name;
                }
                return $fullName;
            } else if ($orderUser) {
                return $orderUser->username;
            }
        }

        if (!empty($this->first_name) || !empty($this->last_name)) {
            $name = '';
            if (!empty($this->first_name)) {
                $name = $this->first_name;
            }
            if (!empty($this->last_name)) {
                $name .= ' ' . $this->last_name;
            }
            return $name;
        }

        return "";
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

}
