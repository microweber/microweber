<?php

namespace MicroweberPackages\Media\Traits;

use Illuminate\Support\Facades\Session;
use MicroweberPackages\Media\Models\Media;


trait MediaTrait {

    private $_newMediaToAssociate = []; //When enter in bootHasCustomFieldsTrait
    private $_newMediaToAssociateIds = [];

    public function media()
    {
        return $this->hasMany(Media::class, 'rel_id')->orderBy('position','asc');
    }

    public function addMedia($mediaArr)
    {
        $this->_newMediaToAssociate[] = $mediaArr;
        return $this;
    }

    public function deleteMediaById($id)
    {
        $this->media()->find($id)->delete();
        $this->refresh();
    }

    public function getMediaAttribute()
    {
        return $this->media()->get();
    }

    public function initializeMediaTrait()
    {
    	$this->appends[] = 'media';
        $this->fillable[] = 'media_ids';
    }

    public static function bootMediaTrait()
    {
        static::saving(function ($model)  {

            $mediaIds = [];

            // append content to categories
            if (isset($model->media_ids) && !empty($model->media_ids)) {
                $mediaIds = $model->media_ids;
            }

            if (!empty($mediaIds)) {
                if (is_string($mediaIds)) {
                    $mediaIds = explode(',', $mediaIds);
                }
                $model->_newMediaToAssociateIds[] = $mediaIds;
            }

            unset($model->media_ids);
        });

        static::saved(function ($model)  {

            Media::where('session_id', Session::getId())->where('rel_id', 0)->update(['rel_id'=> $model->id]);

            if (is_array($model->_newMediaToAssociate) && !empty($model->_newMediaToAssociate)) {
                foreach ($model->_newMediaToAssociate as $mediaArr) {
                    $mediaArr['rel_type'] = $model->getMorphClass();
                    $saved = $model->media()->create($mediaArr);
                    if ($saved) {
                        $model->_newMediaToAssociateIds[] = $saved->id;
                    }
                }

                $model->_newMediaToAssociate = []; //empty the array
                $model->refresh();

                $model->setMedias($model->_newMediaToAssociateIds);
            }

        });
    }

    public function setMedias($mediaIds) {

        if (is_string($mediaIds)) {
            $mediaIds = explode(',', $mediaIds);
        }

        $entityMedias = Media::where('rel_id', $this->id)->where('rel_type',$this->getMorphClass())->get();
        if ($entityMedias) {
            foreach ($entityMedias as $entityMedia) {
                if (!in_array($entityMedia->id, $mediaIds)) {
                    $entityMedia->delete();
                }
            }
        }

        if (!empty($mediaIds)) {
            foreach ($mediaIds as $mediaId) {

                $media = Media::where('rel_id', $this->id)->where('rel_type',$this->getMorphClass())->where('id', $mediaId)->first();
                if (!$media) {
                    $media = new Media();
                }

                $media->rel_id = $this->id;
                $media->rel_type = $this->getMorphClass();
                $media->save();
            }
        }

    }
}