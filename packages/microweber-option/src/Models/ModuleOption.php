<?php
namespace MicroweberPackages\Option\Models;

use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Database\Casts\ReplaceSiteUrlCast;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;
use MicroweberPackages\Multilanguage\Models\Traits\HasMultilanguageTrait;

/**
 * @property string|null $option_key
 * @property string|null $option_value
 * @property string|null $option_group
 * @property string|null $module
 * @property string|null $lang
 * @property array<mixed>|null $multilanguage
 */
class ModuleOption extends Model
{

    protected $fillable=['option_group','option_value','option_key','module'];

    public $cacheTagsToClear = ['global','content','frontend'];

    protected $table = 'options';

    public $translatable = [];

    use HasMultilanguageTrait;
    use CacheableQueryBuilderTrait;

    protected $casts = [
        'option_value' => ReplaceSiteUrlCast::class, //Casts like that: http://picsum.photos/400/200/ =>  {SITE_URL}400/200/
    ];

}
