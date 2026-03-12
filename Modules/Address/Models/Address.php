<?php

namespace Modules\Address\Models;

use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;
use Modules\Customer\Models\Customer;

/**
 * @property int $id
 * @property string|null $name
 * @property string|null $address_street_1
 * @property string|null $address_street_2
 * @property string|null $city
 * @property string|null $state
 * @property string|null $country
 * @property int|null $country_id
 * @property string|null $zip
 * @property string|null $phone
 * @property string|null $type
 * @property string|null $rel_type
 * @property string|null $rel_id
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Modules\Customer\Models\Customer|null $customer
 * @property-read \Modules\Country\Models\Country|null $country
 */
class Address extends Model
{
    use CacheableQueryBuilderTrait;

    public $table = 'addresses';

    public $cacheTagsToClear = ['addresses', 'customers', 'users', 'countries', 'companies'];

    const BILLING_TYPE = 'billing';
    const SHIPPING_TYPE = 'shipping';
    const OTHER_TYPE = 'other';

    protected $fillable = [
        'name',
        'address_street_1',
        'address_street_2',
        'city',
        'state',
        'country',
        'country_id',
        'zip',
        'phone',
        'type',
        'customer_id',
        'rel_type',
        'rel_id',

    ];

    public function customer()
    {
      //  return $this->belongsTo(Customer::class);
       return $this->belongsTo(Customer::class,'rel_id');

      //  return $this->morphMany(Customer::class, 'rel');

    }

    public function country()
    {
        return $this->belongsTo(\Modules\Country\Models\Country::class);
    }


    public function isBilling()
    {
        return $this->type === self::BILLING_TYPE;
    }

    public function isShipping()
    {
        return $this->type === self::SHIPPING_TYPE;
    }
}
