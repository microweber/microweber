<?php

namespace MicroweberPackages\Thumbnailer\Tests;

use MicroweberPackages\Thumbnailer\ThumbnailerServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            ThumbnailerServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'Thumbnailer' => \MicroweberPackages\Thumbnailer\Facades\Thumbnailer::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('thumbnailer.thumbnails_path', sys_get_temp_dir() . '/thumbnailer-tests');
        $app['config']->set('thumbnailer.thumbnails_url', '/storage/thumbnails');
    }

    protected function tearDown(): void
    {
        $dir = sys_get_temp_dir() . '/thumbnailer-tests';
        if (is_dir($dir)) {
            $this->recursiveDelete($dir);
        }
        parent::tearDown();
    }

    private function recursiveDelete(string $dir): void
    {
        $items = scandir($dir);
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $path = $dir . DIRECTORY_SEPARATOR . $item;
            if (is_dir($path)) {
                $this->recursiveDelete($path);
            } else {
                @unlink($path);
            }
        }
        @rmdir($dir);
    }
}