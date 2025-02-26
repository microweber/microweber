<?php
namespace MicroweberPackages\Multilanguage\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;

class MultilanguageSupportedLocales extends Model
{
    public $timestamps = false;
    use CacheableQueryBuilderTrait;

    protected $fillable = [
        'locale',
        'language',
        'display_locale',
        'display_name',
        'display_icon',
        'position',
        'is_active',
    ];
}
