<?php

namespace Modules\Slider\Models;


use MicroweberPackages\Core\Concerns\FiresModelLifecycleEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MicroweberPackages\Database\Casts\ReplaceSiteUrlCast;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;
use MicroweberPackages\Database\Traits\MaxPositionTrait;
use MicroweberPackages\Multilanguage\Models\Traits\HasMultilanguageTrait;

class Slider extends Model
{
    use FiresModelLifecycleEvents;

    use HasMultilanguageTrait;
    use CacheableQueryBuilderTrait;
    use MaxPositionTrait;

    protected $fillable = [
        'name',
        'description',
        'media',
        'link',
        'button_text',
        'settings',
        'rel_id',
        'rel_type',
        'position'
    ];

    public $translatable = ['name', 'description', 'button_text'];

    protected $casts = [
        'media' => ReplaceSiteUrlCast::class, //Casts like that: http://lorempixel.com/400/200/ =>  {SITE_URL}400/200/
        'settings' => 'array',
        'position' => 'integer'
    ];
}
