<?php
namespace MicroweberPackages\Media\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Database\Casts\ReplaceSiteUrlCast;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;

class MediaThumbnail extends Model
{

    public $cacheTagsToClear = ['media','media_thumbnails'];

    use CacheableQueryBuilderTrait;
    use HasUuids;

    public function getKeyType()
    {
        return 'int';
    }

    public function getKeyName()
    {
        return 'id';
    }

    public function uniqueIds()
    {
        return ['uuid'];
    }




    public $table = 'media_thumbnails';

    protected $guarded = ['id'];

    protected $casts = [
        'image_options' => 'json',
        'filename' => ReplaceSiteUrlCast::class, //Casts like that: http://lorempixel.com/400/200/ =>  {SITE_URL}400/200/
    ];

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = $model->newUniqueId();
        });
    }


}
