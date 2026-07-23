<?php

namespace MicroweberPackages\MediaPixum\Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

/**
 * Base test case for the media-pixum package.
 *
 * When running inside the CMS (Tests\CreatesApplication exists),
 * the full application is booted. For standalone usage with
 * Orchestra Testbench, extend this class and override as needed.
 */
abstract class TestCase extends BaseTestCase
{
    use \Tests\CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure pixum cache path is set
        $pixumPath = storage_path('app/public/pixum-test');
        config(['media-pixum.cache_path' => $pixumPath]);
    }

    protected function tearDown(): void
    {
        // Clean up generated test files
        $pixumPath = config('media-pixum.cache_path');
        if ($pixumPath && is_dir($pixumPath)) {
            $files = glob($pixumPath . '/*.png');
            if ($files) {
                foreach ($files as $f) {
                    @unlink($f);
                }
            }
            @rmdir($pixumPath);
        }

        parent::tearDown();
    }
}