<?php


namespace MicroweberPackages\Media\Repositories;


use MicroweberPackages\Media\Models\Media;
use MicroweberPackages\Media\Models\MediaThumbnail;
use MicroweberPackages\Repository\Repositories\AbstractRepository;

class MediaRepository extends AbstractRepository
{
    public $model = Media::class;

    public function getPictureByRelIdAndRelType($relId, $relType = 'content')
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($relId, $relType) {

            $getMedia = \DB::table('media')
                ->select('filename')
                ->where('rel_type', $relType)
                ->where('rel_id', $relId)
                ->orderBy('position', 'ASC')
                ->first();

            if ($getMedia !== null) {

                $getMedia = (array)$getMedia;
                $surl = app()->url_manager->site();
                $getMedia['filename'] = app()->format->replace_once('{SITE_URL}', $surl, $getMedia['filename']);

                return $getMedia;
            }

            return [];
        });
    }

    public function getThumbnailCachedItem($tn_cache_id)
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($tn_cache_id) {
            $return = false;

            $check = \DB::table('media_thumbnails')
                ->select(['id', 'filename', 'image_options'])
                ->where('filename', $tn_cache_id)->first();

            //$check = MediaThumbnail::where('filename', $tn_cache_id)->first();
            if ($check and !empty($check)) {

                $ready = (array)$check;

                if (isset($ready['image_options']) and is_string($ready['image_options'])) {
                    $ready['image_options'] = @json_decode($ready['image_options'], true);
                }

                return $ready;
            }

            return false;
        });
    }

}
