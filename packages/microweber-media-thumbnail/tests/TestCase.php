<?php

namespace MicroweberPackages\MediaThumbnail\Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

/**
 * Base test case for the media-thumbnail package.
 *
 * Uses the full CMS application when available.
 */
abstract class TestCase extends BaseTestCase
{
    use \Tests\CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        $thumbnailsPath = storage_path('app/public/thumbnails-test');
        config(['thumbnailer.thumbnails_path' => $thumbnailsPath]);
        config(['thumbnailer.thumbnails_url' => '/storage/thumbnails']);
        config(['media-thumbnail.thumbnails_path' => $thumbnailsPath]);
        config(['media-pixum.cache_path' => $thumbnailsPath . '/pixum']);
    }
}