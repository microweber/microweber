<?php

declare(strict_types=1);

namespace MicroweberPackages\ClassLoader\Tests\Feature;

use MicroweberPackages\ClassLoader\ClassLoader;
use MicroweberPackages\ClassLoader\Tests\TestCase;

class HelpersTest extends TestCase
{
    public function test_mw_class_loader_helper(): void
    {
        $this->assertTrue(function_exists('mw_class_loader'));
        $this->assertInstanceOf(ClassLoader::class, mw_class_loader());
        $this->assertSame(app(ClassLoader::class), mw_class_loader());
    }

    public function test_add_directories_helper(): void
    {
        $dir = sys_get_temp_dir() . '/mw-cl-helper-' . uniqid();
        class_loader_add_directories($dir);
        $dirs = mw_class_loader()->getDirectories();
        $normalized = mw_class_loader()->getPathNormalizer()->normalize($dir);
        $this->assertContains($normalized, $dirs);
    }

    public function test_add_namespace_helper(): void
    {
        $dir = sys_get_temp_dir() . '/mw-cl-ns-' . uniqid();
        class_loader_add_namespace('Helper\\Ns', $dir);
        $this->assertArrayHasKey('Helper\\Ns', mw_class_loader()->getNamespaces());
    }

    public function test_stats_helper(): void
    {
        $stats = class_loader_stats();
        $this->assertIsArray($stats);
        $this->assertArrayHasKey('version', $stats);
    }

    public function test_resolve_helper(): void
    {
        $this->assertNull(class_loader_resolve('Definitely\\Missing\\Class' . uniqid()));
    }
}
