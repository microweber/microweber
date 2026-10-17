<?php

namespace Modules\Tabs\Models;



use MicroweberPackages\Core\Concerns\FiresModelLifecycleEvents;
use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Database\Casts\ReplaceSiteUrlCast;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;
use MicroweberPackages\Database\Traits\MaxPositionTrait;
use MicroweberPackages\Multilanguage\Models\Traits\HasMultilanguageTrait;

class Tab extends Model
{
    use FiresModelLifecycleEvents;

    use HasMultilanguageTrait;
    use CacheableQueryBuilderTrait;
    use MaxPositionTrait;

    protected $fillable = [
        'id',
        'title',
        'icon',
        'position',
        'rel_id',
        'rel_type',
        'settings',
        'content'
    ];

    public $translatable = ['title', 'content'];

    protected $casts = [
        'content' => ReplaceSiteUrlCast::class, //Casts like that: http://lorempixel.com/400/200/ =>  {SITE_URL}400/200/
        'settings' => 'array',
    ];

}
