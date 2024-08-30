<?php
namespace MicroweberPackages\Modules\Testimonials\Models;

use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Database\Casts\ReplaceSiteUrlCast;

class Testimonial extends Model
{

    public $timestamps = false;

    public $fillable = [
        'name',
        'content',
        'read_more_url',
        'project_name',
        'client_company',
        'client_role',
        'client_picture',
        'client_website',
        'position'
    ];
    protected $casts = [
         'content' => ReplaceSiteUrlCast::class, //Casts like that: http://lorempixel.com/400/200/ =>  {SITE_URL}400/200/
         'client_picture' => ReplaceSiteUrlCast::class, //Casts like that: http://lorempixel.com/400/200/ =>  {SITE_URL}400/200/
    ];


}
