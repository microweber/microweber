<?php
namespace Modules\Country\Models;


use MicroweberPackages\Core\Concerns\FiresModelLifecycleEvents;
use Illuminate\Database\Eloquent\Model;

use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;

class Country extends Model
{
    use FiresModelLifecycleEvents;

    use CacheableQueryBuilderTrait;


    public $timestamps = false;
    protected $fillable = ['code','name','phonecode'];

//    public function address()
//    {
//        return $this->hasMany(Address::class);
//    }
}
