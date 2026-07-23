<?php

namespace Modules\Media\Models;

use MicroweberPackages\Database\Casts\ReplaceSiteUrlCast;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;
use MicroweberPackages\MediaThumbnail\Models\MediaThumbnail as BaseMediaThumbnail;

/**
 * CMS-specific MediaThumbnail model.
 *
 * Extends the standalone package model with CMS-specific features
 * (CacheableQueryBuilderTrait and ReplaceSiteUrlCast).
 */
class MediaThumbnail extends BaseMediaThumbnail
{
    /** @var list<string> */
    public $cacheTagsToClear = ['media', 'media_thumbnails'];

    use CacheableQueryBuilderTrait;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'filename' => ReplaceSiteUrlCast::class,
        ]);
    }

    /**
     * Legacy method — delegates to the package's findByFilename().
     *
     * @return array<string, mixed>|false
     */
    public static function queryCachedItem(string $filename): array|false
    {
        $result = static::findByFilename($filename);

        return $result ?? false;
    }
}
