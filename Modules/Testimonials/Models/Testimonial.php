<?php

namespace Modules\Testimonials\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MicroweberPackages\Database\Casts\ReplaceSiteUrlCast;

class Testimonial extends Model
{
    protected $table = 'testimonials';

    protected $fillable = [
        'name',
        'content',
        'client_image',
        'client_company',
        'client_role',
        'client_website',
        'position',
        'rel_id',
        'rel_type',
    ];

    protected $casts = [
        'client_image' => ReplaceSiteUrlCast::class, //Casts like that: http://lorempixel.com/400/200/ =>  {SITE_URL}400/200/
    ];

}
