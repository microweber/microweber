<?php

namespace Modules\Slider\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MicroweberPackages\Database\Casts\ReplaceSiteUrlCast;

class Slider extends Model
{

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

    protected $casts = [
        'media' => ReplaceSiteUrlCast::class, //Casts like that: http://lorempixel.com/400/200/ =>  {SITE_URL}400/200/
        'settings' => 'array',
        'position' => 'integer'
    ];
}
