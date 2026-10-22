<?php
namespace Modules\Country\Models;


use MicroweberPackages\Core\Concerns\FiresModelLifecycleEvents;
use Illuminate\Database\Eloquent\Model;

use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;

class Country extends Model
{

    /**
     * Fire lifecycle events so other modules can hook this model's changes.
     */
    protected $dispatchesEvents = [
        'created' => \Modules\Country\Events\CountryWasCreated::class,
        'updated' => \Modules\Country\Events\CountryWasUpdated::class,
        'deleted' => \Modules\Country\Events\CountryWasDeleted::class,
    ];
    use FiresModelLifecycleEvents;

    use CacheableQueryBuilderTrait;


    public $timestamps = false;
    protected $fillable = ['code','name','phonecode'];

//    public function address()
//    {
//        return $this->hasMany(Address::class);
//    }
}
