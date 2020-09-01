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
        $this->_newMediaToAssociate[] = $this->media()->create($mediaArr);
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
            foreach($model->_newMediaToAssociate as $mediaField) {
                $model->media()->save($mediaField);
                $mediaField->updateMaxPositionFieldOnModel();
            }

            $model->_newMediaToAssociate = []; //empty the array

            $model->refresh();
        });
    }


}