<?php

namespace MicroweberPackages\Customer\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Currency\Currency;
use MicroweberPackages\Customer\Models\ModelFilters\CustomerFilter;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;
use MicroweberPackages\Order\Models\Order;

class Customer extends Model
{
    protected $table = 'customers';

    use Filterable;
    use CacheableQueryBuilderTrait;

    public $cacheTagsToClear = ['countries', 'addresses', 'customers', 'users'];


    public $fillable = [
        'name',
        'first_name',
        'last_name',
        'phone',
        'email',
        'active',
        'user_id',
        'currency_id',
        'company_id'
    ];

    public $translatable = ['first_name', 'last_name'];


    public function modelFilter()
    {
        return $this->provideFilter(CustomerFilter::class);
    }

   /* public function getActiveAttribute($attribute)
    {
        $activeOptions = $this->activeOptions();
        if (isset($activeOptions[$attribute])) {
            return $activeOptions[$attribute];
        }
    }*/

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function scopeInactive($query)
    {
        return $query->where('active', 0);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

//    public function currency()
//    {
//        return $this->belongsTo(Currency::class);
//    }

    public function billingAddress()
    {
        return $this->hasOne(Address::class)->where('type', Address::BILLING_TYPE);
    }

    public function shippingAddress()
    {
        return $this->hasOne(Address::class)->where('type', Address::SHIPPING_TYPE);
    }

//    public function payments()
//    {
//        return $this->hasMany(Payment::class);
//    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(\MicroweberPackages\User\Models\User::class);
    }

    public function scopeWhereDisplayName($query, $displayName)
    {
        return $query->where('name', 'LIKE', '%' . $displayName . '%');
    }

    public function scopeWherePhone($query, $phone)
    {
        return $query->where('phone', 'LIKE', '%' . $phone . '%');
    }

    public function scopeApplyFilters($query, array $filters)
    {
        $filters = collect($filters);

        if ($filters->get('search')) {

            $search = trim($filters->get('search'));
            $keywords = explode(' ', $search);

            $query->where(function ($query) use ($keywords) {
                foreach ($keywords as $search) {
                    $query->where('name', 'like', '%' . $search . '%');
                    $query->orWhere('first_name', 'like', '%' . $search . '%');
                    $query->orWhere('last_name', 'like', '%' . $search . '%');
                    $query->orWhere('phone', 'like', '%' . $search . '%');
                    $query->orWhere('email', 'like', '%' . $search . '%');
                }
            });
        }

        if ($filters->get('name')) {
            $query->whereName($filters->get('name'));
        }

        if ($filters->get('name')) {
            $query->where('first_name', 'like', '%' . $filters->get('name') . '%');
            $query->orWhere('last_name', 'like', '%' . $filters->get('name') . '%');
        }

        if ($filters->get('phone')) {
            $query->wherePhone($filters->get('phone'));
        }

        if ($filters->get('orderByField') || $filters->get('orderBy')) {
            $field = $filters->get('orderByField') ? $filters->get('orderByField') : 'name';
            $orderBy = $filters->get('orderBy') ? $filters->get('orderBy') : 'asc';
            $query->whereOrder($field, $orderBy);
        }
    }

    public function activeOptions()
    {
        return [
            1 => 'Active',
            0 => 'Inactive',
            2 => 'In-Progress'
        ];
    }

    /* public function delete()
     {
         if ($this->payments()->exists()) {
             $this->payments()->delete();
         }

         if ($this->addresses()->exists()) {
             $this->addresses()->delete();
         }

         $this->delete();

         return true;
     }*/

    public function cityAndCountry()
    {
        $city = false;
        $country = false;
        if (isset($this->addresses[0]->city)) {
            $city = $this->addresses[0]->city;
        }
        if (isset($this->addresses[0]->country_id)) {
            $findCountry = \MicroweberPackages\Country\Models\Country::where('id', $this->addresses[0]->country_id)->first();
            if ($findCountry) {
                $country = $findCountry->name;
            }
        }

        $cityCountryText = $city;
        if ($country) {
            $cityCountryText .= ' / ' . $country;
        }

        if (empty($cityCountryText)) {
            $cityCountryText = '...';
        }

        return $cityCountryText;
    }

    public function getFullName()
    {
        if ((isset($this->first_name) && !empty($this->first_name)) and (isset($this->last_name) && !empty($this->last_name))) {
            return $this->first_name . ' ' . $this->last_name;
        }

        $userName = user_name($this->user_id);
        if ($userName) {
            return $userName;
        }

        return '...';
    }

    public function getEmail()
    {
        if (isset($this->email) && !empty($this->email)) {
            return $this->email;
        }

        $findUser = $this->user()->first();
        if ($findUser) {
            if (isset($findUser->email) && !empty($findUser->email)) {
                return $findUser->email;
            }
        }

        return '...';
    }

    public function getPhone()
    {
        if (isset($this->phone) && !empty($this->phone)) {
            return $this->phone;
        }
        $findUser = $this->user()->first();
        if ($findUser) {
           if (isset($findUser->phone) && !empty($findUser->phone)) {
               return $findUser->phone;
           }
        }
        return '...';
    }
}
