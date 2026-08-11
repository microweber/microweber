<?php

namespace MicroweberPackages\MediaPixum\Tests;

/**
 * Base test case for the media-pixum package.
 *
 * Extends the CMS harness (Tests\TestCase), which boots the full Microweber
 * application via Orchestra Testbench.
 */
abstract class TestCase extends \Tests\TestCase
{
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