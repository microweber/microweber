<?php
namespace MicroweberPackages\Media\Models;

use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Database\Casts\ReplaceSiteUrlCast;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;

class ThumbnailTemp extends Model
{
    use CacheableQueryBuilderTrait;

    public $table = 'media';

    protected $guarded = ['id'];

    protected $casts = [
        'image_options' => 'json',
        'filename' => ReplaceSiteUrlCast::class, //Casts like that: http://lorempixel.com/400/200/ =>  {SITE_URL}400/200/
    ];

    protected $attributes = [
        'media_type' => 'media_tn_temp',
    ];
}