<?php

namespace MicroweberPackages\MediaThumbnail\Facades;

use Illuminate\Support\Facades\Facade;
use MicroweberPackages\MediaThumbnail\Repositories\MediaThumbnailRepository;

/**
 * MediaThumbnail facade — greppable public API for media thumbnails.
 *
 * @see \MicroweberPackages\MediaThumbnail\Repositories\MediaThumbnailRepository
 * @mixin \MicroweberPackages\MediaThumbnail\Repositories\MediaThumbnailRepository
 */
class MediaThumbnail extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return MediaThumbnailRepository::class;
    }
}
