<?php
namespace MicroweberPackages\Option\Models;

use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Database\Casts\ReplaceSiteUrlCast;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;

class ModuleOption extends Model
{

    protected $fillable=['option_group','option_value'];

    public $cacheTagsToClear = ['global','content','frontend'];

    protected $table = 'options';

    public $translatable = [];

    use CacheableQueryBuilderTrait;

    protected $casts = [

        'option_value' => ReplaceSiteUrlCast::class, //Casts like that: http://lorempixel.com/400/200/ =>  {SITE_URL}400/200/
    ];

}
