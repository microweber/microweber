<?php


namespace Modules\Media\Repositories;


use MicroweberPackages\Repository\Repositories\AbstractRepository;
use Modules\Media\Models\Media;
use Modules\Media\Models\MediaThumbnail;

class MediaRepository extends AbstractRepository
{
    public $model = Media::class;

    public function getPictureByRelIdAndRelType($relId, $relType = 'content')
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($relId, $relType) {
            return Media::queryPictureByRelIdAndRelType($relId, $relType);
        });
    }

    public function getThumbnailCachedItem($tn_cache_id)
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($tn_cache_id) {
            return MediaThumbnail::queryCachedItem($tn_cache_id);
        });
    }

}
