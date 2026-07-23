<?php

namespace Modules\Media\Repositories;

use MicroweberPackages\MediaThumbnail\Repositories\MediaThumbnailRepository;
use MicroweberPackages\Repository\Repositories\CachingModelRepository;
use Modules\Media\Models\Media;

class MediaRepository extends CachingModelRepository
{
    protected string $modelClass = Media::class;

    public function getPictureByRelIdAndRelType($relId, $relType = 'content')
    {
        return $this->cached(__FUNCTION__, func_get_args(), function () use ($relId, $relType) {
            return Media::queryPictureByRelIdAndRelType($relId, $relType);
        });
    }

    /**
     * @return array<string, mixed>|false
     */
    public function getThumbnailCachedItem(string $tn_cache_id): array|false
    {
        // Delegate to the standalone package repository
        $result = app(MediaThumbnailRepository::class)->findByFilename($tn_cache_id);

        return $result ?? false;
    }
}
