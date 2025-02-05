<?php

namespace Modules\Accordion\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MicroweberPackages\Database\Casts\ReplaceSiteUrlCast;

class Accordion extends Model
{
    protected $table = 'accordion';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'icon',
        'content',
        'position',
        'rel_id',
        'rel_type',
        'settings',
        'updated_at',
        'created_at',
    ];
    protected $casts = [
        'settings' => 'array',
        'content' => ReplaceSiteUrlCast::class, //Casts like that: http://lorempixel.com/400/200/ =>  {SITE_URL}400/200/
    ];


}
