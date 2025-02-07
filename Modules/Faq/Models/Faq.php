<?php

namespace Modules\Faq\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MicroweberPackages\Database\Casts\ReplaceSiteUrlCast;

class Faq extends Model
{
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

    protected $casts = [
        'answer' => ReplaceSiteUrlCast::class,
        'is_active' => 'boolean',
    ];
}
