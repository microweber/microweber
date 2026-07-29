<?php

declare(strict_types=1);

namespace MicroweberPackages\Zip\Tests;

use MicroweberPackages\Zip\ZipServiceProvider;
use ZipArchive;

// Support both standalone (Orchestra\Testbench) and CMS-integrated testing.
if (class_exists(\Orchestra\Testbench\TestCase::class)) {
    abstract class TestCase extends \Orchestra\Testbench\TestCase
    {
        protected function getPackageProviders($app): array
        {
            return [
                ZipServiceProvider::class,
            ];
        }

        protected function getEnvironmentSetUp($app): void
        {
            $app['config']->set('app.key', 'base64:' . base64_encode(random_bytes(32)));
        }

        protected string $tempDir = '';

        protected function setUp(): void
        {
            parent::setUp();
            $this->tempDir = sys_get_temp_dir() . '/mw_zip_test_' . uniqid('', true);
            mkdir($this->tempDir, 0755, true);
        }

        protected function tearDown(): void
        {
            $this->removeDir($this->tempDir);
            parent::tearDown();
        }

        protected function createSampleZip(array $files, ?string $path = null): string
        {
            $path ??= $this->tempDir . '/sample.zip';
            $zip = new ZipArchive();
            $zip->open($path, ZipArchive::CREATE | ZipArchive::OVERWRITE);
            foreach ($files as $name => $content) {
                if (str_ends_with($name, '/')) {
                    $zip->addEmptyDir(rtrim($name, '/'));
                } else {
                    $zip->addFromString($name, $content);
                }
            }
            $zip->close();

            return $path;
        }

        protected function removeDir(string $dir): void
        {
            if (!is_dir($dir)) {
                return;
            }
            $items = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::CHILD_FIRST
            );
            foreach ($items as $item) {
                $item->isDir() ? @rmdir($item->getPathname()) : @unlink($item->getPathname());
            }
            @rmdir($dir);
        }
    }
} else {
    abstract class TestCase extends \Tests\TestCase
    {
        protected string $tempDir = '';

        protected function setUp(): void
        {
            parent::setUp();
            $this->tempDir = sys_get_temp_dir() . '/mw_zip_test_' . uniqid('', true);
            mkdir($this->tempDir, 0755, true);
        }

        protected function tearDown(): void
        {
            $this->removeDir($this->tempDir);
            parent::tearDown();
        }

        protected function createSampleZip(array $files, ?string $path = null): string
        {
            $path ??= $this->tempDir . '/sample.zip';
            $zip = new ZipArchive();
            $zip->open($path, ZipArchive::CREATE | ZipArchive::OVERWRITE);
            foreach ($files as $name => $content) {
                if (str_ends_with($name, '/')) {
                    $zip->addEmptyDir(rtrim($name, '/'));
                } else {
                    $zip->addFromString($name, $content);
                }
            }
            $zip->close();

            return $path;
        }

        protected function removeDir(string $dir): void
        {
            if (!is_dir($dir)) {
                return;
            }
            $items = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::CHILD_FIRST
            );
            foreach ($items as $item) {
                $item->isDir() ? @rmdir($item->getPathname()) : @unlink($item->getPathname());
            }
            @rmdir($dir);
        }
    }
}
