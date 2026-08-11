<?php

namespace MicroweberPackages\MediaThumbnail\Tests;

/**
 * Base test case for the media-thumbnail package.
 *
 * Extends the CMS harness (Tests\TestCase), which boots the full Microweber
 * application via Orchestra Testbench.
 */
abstract class TestCase extends \Tests\TestCase
{
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