<?php

namespace MicroweberPackages\Customer\Models;

use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Country\Models\Country;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;

class Address extends Model
{
    public $table = 'addresses';

    use CacheableQueryBuilderTrait;
    public $cacheTagsToClear = [ 'addresses', 'customers', 'users','countries'];

    const BILLING_TYPE = 'billing';
    const SHIPPING_TYPE = 'shipping';

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
        return $this->belongsTo(Country::class);
    }
}
