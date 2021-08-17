<?php


namespace MicroweberPackages\Media\Repositories;


use MicroweberPackages\Media\Models\Media;
use MicroweberPackages\Media\Models\MediaThumbnail;
use MicroweberPackages\Repository\Repositories\AbstractRepository;

class MediaRepository extends AbstractRepository
{
    public $model = Media::class;

    public function getThumbnailCachedItem($tn_cache_id)
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($tn_cache_id) {
            $return = false;

            $check = DB::table('media_thumbnails')->where('filename', $tn_cache_id)->first();

             //$check = MediaThumbnail::where('filename', $tn_cache_id)->first();
            if ($check and !empty($check)) {
                return $check->toArray();
            }

            return false;
        });
    }

}