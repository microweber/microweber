<?php


namespace Modules\Media\Repositories;


use MicroweberPackages\Repository\Repositories\CachingModelRepository;
use Modules\Media\Models\Media;
use Modules\Media\Models\MediaThumbnail;

class MediaRepository extends CachingModelRepository
{
    protected string $modelClass = Media::class;

    public function getPictureByRelIdAndRelType($relId, $relType = 'content')
    {
        return $this->cached(__FUNCTION__, func_get_args(), function () use ($relId, $relType) {
            return Media::queryPictureByRelIdAndRelType($relId, $relType);
        });
    }

    public function getThumbnailCachedItem($tn_cache_id)
    {
        return $this->cached(__FUNCTION__, func_get_args(), function () use ($tn_cache_id) {
            return MediaThumbnail::queryCachedItem($tn_cache_id);
        });
    }

}
