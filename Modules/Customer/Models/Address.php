<?php

namespace Modules\Customer\Models;

use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;

class Address extends Model
{
    const BILLING_TYPE = 'billing';
    const SHIPPING_TYPE = 'shipping';

    public function isBilling()
    {
        return $this->type === self::BILLING_TYPE;
    }

    public function isShipping()
    {
        return $this->type === self::SHIPPING_TYPE;
    }
    public $table = 'addresses';

    use CacheableQueryBuilderTrait;
    public $cacheTagsToClear = [ 'addresses', 'customers', 'users','countries'];

    protected $fillable = [
        'name',
        'address_street_1',
        'address_street_2',
        'city',
        'state',
        'country_id',
        'zip',
        'phone',
        'fax',
        'type',
        'customer_id'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function country()
    {
        return $this->belongsTo(\Modules\Country\Models\Country::class);
    }
}
