<?php

namespace Modules\Media\Traits;

use Illuminate\Support\Facades\Session;
use Modules\Media\Models\Media;


trait MediaTrait
{
    private $_newMediaToAssociate = []; //When enter in bootHasCustomFieldsTrait
    private $_newMediaToAssociateIds = [];

    public function initializeMediaTrait()
    {
        $this->fillable[] = 'mediaIds';
        // $this->fillable[] = 'media_files';
        //    $this->fillable[] = 'mediaUrls';
        // $this->casts['media_files'] = 'array';
        $this->casts['mediaIds'] = 'array';
        // $this->casts['mediaUrls'] = 'array';
    }

    public function media()
    {
        return $this->hasMany(Media::class, 'rel_id')
            ->where('rel_type', $this->getMorphClass())
            ->orderBy('position', 'asc');
    }


    public function thumbnail($width = 100, $height = 100, $crop = false)
    {
        $media = $this->media()->first();
        if ($media) {
            return thumbnail($media->filename, $width, $height, $crop);
        }

        return pixum($width, $height);
    }

    public function mediaUrl()
    {
        $media = $this->media()->first();
        if ($media) {
            return $media->filename;
        }

        return pixum(100, 100);
    }

    public function getMediaUrlAttribute()
    {
        return $this->mediaUrl();
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

    public function getMediaFilesAttribute()
    {
        $medias = $this->media()->get();

        $mediaFiles = [];
        if ($medias) {
            foreach ($medias as $media) {
                $mediaFiles[] = $media->filename;
            }
        }
        return $mediaFiles;
    }

    public static function bootMediaTrait()
    {
        static::saving(function ($model) {
            if (isset($model->media_files)) {

                $model->_newMediaFiles = $model->media_files;
                unset($model->media_files);

            }
            if (isset($model->_newMediaFiles)) {

                $model->_newMediaFiles = $model->_newMediaFiles;
                unset($model->_newMediaFiles);

            }

            if (isset($model->attributes['media_files'])) {
                $model->_newMediaFiles = $model->attributes['media_files'];
                unset($model->attributes['media_files']);

            }

            if (array_key_exists('mediaIds', $model->attributes)) {

                $model->_newMediaToAssociateIds = $model->attributes['mediaIds'];
                unset($model->attributes['mediaIds']);

            }


            if (isset($model->mediaIds)) {

                $model->_newMediaToAssociateIds = $model->mediaIds;
                unset($model->mediaIds);

            }

            if (isset($model->media_urls)) {
                if (!empty($model->media_urls)) {

                    $mediaUrls = [];
                    if (is_string($model->media_urls)) {
                        $mediaUrls[] = $model->media_urls;
                    }
                    if (is_array($model->media_urls)) {
                        $mediaUrls = $model->media_urls;
                    }

                    if (!empty($mediaUrls)) {
                        foreach ($mediaUrls as $url) {
                            save_media(array(
                                //  'allow_remote_download' => 1,
                                'rel_type' => $this->getMorphClass(),
                                'rel_id' => $model->id,
                                'title' => 'Picture',
                                'media_type' => 'picture',
                                'filename' => $url,
                            ));
                        }
                    }

                }
                unset($model->media_urls);
            }

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

        static::saved(function ($model) {


            Media::where('session_id', Session::getId())
                ->where('rel_id', 0)
                ->where('rel_type', $model->getMorphClass())
                ->update(['rel_id' => $model->id]);

            $appendByUserId = user_id();
            if($appendByUserId){
                Media::where('rel_id', 0)
                    ->where('rel_type', $model->getMorphClass())
                    ->where('created_by', $appendByUserId)
                    ->update(['rel_id' => $model->id]);
            }

            if (is_array($model->_newMediaFiles)) {
                foreach ($model->_newMediaFiles as $filename) {
                    $mediaArr = [];
                    $mediaArr['rel_type'] = $model->getMorphClass();
                    $mediaArr['rel_id'] = $model->id;
                    $mediaArr['filename'] = media_uploads_url() . $filename;
                    $saved = $model->media()->create($mediaArr);
                    if ($saved) {
                        $model->_newMediaToAssociateIds[] = $saved->id;
                    }
                }

            }
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

                // $model->setMedias($model->_newMediaToAssociateIds);
            }


            if (!empty($model->_newMediaToAssociateIds)) {

                $model->setMedias($model->_newMediaToAssociateIds);
            }

        });
    }

    public function setMedias($mediaIds)
    {

        if (is_string($mediaIds)) {
            $mediaIds = str_replace(' ', '', $mediaIds);
            $mediaIds = str_replace('"', '', $mediaIds);
            $mediaIds = str_replace("'", '', $mediaIds);
            $mediaIds = str_replace('[', '', $mediaIds);
            $mediaIds = str_replace(']', '', $mediaIds);
            $mediaIds = explode(',', $mediaIds);
            $mediaIds = array_map('intval', $mediaIds);
        }

//        $entityMedias = Media::where('rel_id', $this->id)->where('rel_type', $this->getMorphClass())->get();
//        if ($entityMedias) {
//            foreach ($entityMedias as $entityMedia) {
//                if (!in_array($entityMedia->id, $mediaIds)) {
//                    $entityMedia->delete();
//                }
//            }
//        }

        $relId = isset($this->id) ? $this->id : $this->attributes['id'] ?? null;
        $relType = $this->getMorphClass();


        if (!empty($mediaIds)) {
            foreach ($mediaIds as $mediaId) {

                $media = Media::where('id', $mediaId)->first();
                if (!$media) {
                    continue;
                }

                $media->rel_id = $relId;
                $media->rel_type = $relType;
                $media->save();
            }

            //clean the other
            Media::where('rel_id', $relId)->where('rel_type', $relType)->whereNotIn('id', $mediaIds)->delete();

        }

    }
}
