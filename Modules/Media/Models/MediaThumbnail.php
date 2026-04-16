<?php

namespace Modules\Media\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MicroweberPackages\Database\Casts\ReplaceSiteUrlCast;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;

class MediaThumbnail extends Model
{

    public $cacheTagsToClear = ['media', 'media_thumbnails'];

    use CacheableQueryBuilderTrait;
    use HasUuids;

    public $table = 'media_thumbnails';

    protected $guarded = ['id'];

    protected $casts = [
        'image_options' => 'json',
        'filename' => ReplaceSiteUrlCast::class, //Casts like that: http://lorempixel.com/400/200/ =>  {SITE_URL}400/200/
    ];

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


    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = $model->newUniqueId();
        });
    }

    /**
     * Find a cached thumbnail item by filename.
     *
     * @return array|false
     */
    public static function queryCachedItem(string $filename)
    {
        $check = DB::table('media_thumbnails')
            ->select(['id', 'filename', 'image_options', 'uuid'])
            ->where('filename', $filename)
            ->first();

        if ($check && !empty($check)) {
            $ready = (array) $check;

            if (isset($ready['image_options']) && is_string($ready['image_options'])) {
                $ready['image_options'] = @json_decode($ready['image_options'], true);
            }

            return $ready;
        }

        return false;
    }

}
