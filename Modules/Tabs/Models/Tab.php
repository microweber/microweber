<?php

namespace Modules\Tabs\Models;


use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Database\Casts\ReplaceSiteUrlCast;

class Tab extends Model
{
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
    protected $casts = [
        'content' => ReplaceSiteUrlCast::class, //Casts like that: http://lorempixel.com/400/200/ =>  {SITE_URL}400/200/
    ];

}
