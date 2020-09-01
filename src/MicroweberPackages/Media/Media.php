<?php
namespace MicroweberPackages\Media;

use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Database\Casts\ReplaceSiteUrlCast;
use MicroweberPackages\Database\Traits\HasMaxPositionTrait;

class Media extends Model
{
    //use \Conner\Tagging\Taggable;

    use HasMaxPositionTrait;

    public $table = 'media';

    protected $guarded = ['id'];

    protected $casts = [
        'image_options' => 'json',
        'filename' => ReplaceSiteUrlCast::class, //Casts like that: http://lorempixel.com/400/200/ =>  {SITE_URL}400/200/
    ];

    protected $attributes = [
        'media_type' => 'picture',
    ];

//    public static function boot()
//    {
//        parent::boot();
//    }

//    public function tags()
//    {
//        return $this->belongsToMany('Tag');
//    }

}