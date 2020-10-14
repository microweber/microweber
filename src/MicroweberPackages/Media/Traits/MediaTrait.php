<?php

namespace MicroweberPackages\Media\Traits;

use Illuminate\Support\Facades\Session;
use MicroweberPackages\Media\Models\Media;


trait MediaTrait {

    private $_newMediaToAssociate = []; //When enter in bootHasCustomFieldsTrait

    public function media()
    {
        return $this->morphMany(Media::class, 'rel');
    }

    public function addMedia($mediaArr)
    {
        $this->_newMediaToAssociate[] = $mediaArr;
        return $this;
    }

    public function deleteMedia($media)
    {
        $media->delete();
        $this->refresh();
    }

    public function getMediaAttribute()
    {
        return $this->media()->get();
    }

    public function initializeMediaTrait()
    {
        $this->appends[] = 'media';
    }

    public static function bootMediaTrait()
    {
        static::saved(function ($model)  {

            Media::where('session_id', Session::getId())->where('rel_id', 0)->update(['rel_id'=> $model->id]);

            if (is_array($model->_newMediaToAssociate) && !empty($model->_newMediaToAssociate)) {
                foreach ($model->_newMediaToAssociate as $mediaArr) {
                    $model->media()->create($mediaArr);
                }

                $model->_newMediaToAssociate = []; //empty the array
                $model->refresh();
            }

        });
    }


}