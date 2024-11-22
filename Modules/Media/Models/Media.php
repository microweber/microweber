<?php
namespace Modules\Media\Models;

use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Database\Casts\ReplaceSiteUrlCast;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;
use MicroweberPackages\Database\Traits\MaxPositionTrait;

class Media extends Model
{
    //use \Conner\Tagging\Taggable;
    public $cacheTagsToClear = ['media','media_thumbnails'];

    use MaxPositionTrait;
    use CacheableQueryBuilderTrait;

    public $table = 'media';

    protected $fillable = [
        'id',
        'title',
        'description',
        'rel_id',
        'rel_type',
        'media_type',
        'position',
        'filename',
        'session_id',
        'image_options',
    ];


    protected $casts = [
        'image_options' => 'json',
        'filename' => ReplaceSiteUrlCast::class, //Casts like that: http://lorempixel.com/400/200/ =>  {SITE_URL}400/200/
    ];

    protected $attributes = [
        'media_type' => 'picture',
    ];

}
