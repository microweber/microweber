<?php

namespace Modules\Faq\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MicroweberPackages\Database\Casts\ReplaceSiteUrlCast;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;
use MicroweberPackages\Database\Traits\MaxPositionTrait;
use MicroweberPackages\Multilanguage\Models\Traits\HasMultilanguageTrait;
use Modules\Faq\Database\Factories\FaqFactory;

class Faq extends Model
{
    use HasFactory;
    use HasMultilanguageTrait;
    use CacheableQueryBuilderTrait;
    use MaxPositionTrait;

    protected static function newFactory(): FaqFactory
    {
        return FaqFactory::new();
    }

    protected $table = 'faqs';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'question',
        'answer',
        'position',
        'rel_id',
        'rel_type',
        'is_active',
        'updated_at',
        'created_at',
    ];

    public $translatable = ['question', 'answer'];

    protected $casts = [
        'answer' => ReplaceSiteUrlCast::class,
        'is_active' => 'boolean',
    ];
}
