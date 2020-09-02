<?php

namespace MicroweberPackages\Media;


trait  HasMediaTrait {


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

    public static function bootHasMediaTrait()
    {
        static::saved(function ($model)  {
            foreach($model->_newMediaToAssociate as $mediaArr) {
                $model->media()->create($mediaArr);
            }

            $model->_newMediaToAssociate = []; //empty the array

            $model->refresh();
        });
    }


}