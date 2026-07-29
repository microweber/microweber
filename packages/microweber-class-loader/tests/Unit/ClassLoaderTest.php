<?php

declare(strict_types=1);

namespace MicroweberPackages\ClassLoader\Tests\Unit;

use MicroweberPackages\ClassLoader\ClassLoader;
use MicroweberPackages\ClassLoader\PathNormalizer;
use PHPUnit\Framework\TestCase;

class ClassLoaderTest extends TestCase
{
    private string $fixtureRoot;

    private ClassLoader $loader;

    protected function setUp(): void
    {
        parent::setUp();
        $this->fixtureRoot = sys_get_temp_dir() . '/mw-class-loader-' . uniqid('', true);
        mkdir($this->fixtureRoot . '/src/Demo', 0777, true);
        mkdir($this->fixtureRoot . '/flat', 0777, true);

        file_put_contents(
            $this->fixtureRoot . '/src/Demo/Hello.php',
            "<?php\nnamespace Demo;\nclass Hello { public static function ping(): string { return 'pong'; } }\n"
        );
        file_put_contents(
            $this->fixtureRoot . '/flat/FlatClass.php',
            "<?php\nclass FlatClass { public static function ping(): string { return 'flat'; } }\n"
        );

        $this->loader = new ClassLoader(new PathNormalizer(), true);
    }

    protected function tearDown(): void
    {
        $this->loader->reset();
        $this->removeTree($this->fixtureRoot);
        parent::tearDown();
    }

    public function test_is_instance_based_not_static(): void
    {
        $a = new ClassLoader();
        $b = new ClassLoader();
        $a->addDirectories('/tmp/a');
        $this->assertSame([], $b->getDirectories());
        $this->assertNotSame($a->getDirectories(), $b->getDirectories());
    }

    public function test_directory_deduplication(): void
    {
        $base = $this->fixtureRoot . '/flat';
        $this->loader->addDirectories($base);
        $this->loader->addDirectories($base . '/');
        $this->loader->addDirectories(str_replace('/', DIRECTORY_SEPARATOR, $base) . DIRECTORY_SEPARATOR);

        $this->assertCount(1, $this->loader->getDirectories());
    }

    public function test_namespace_path_deduplication(): void
    {
        $base = $this->fixtureRoot . '/src';
        $this->loader->addNamespace('Demo', $base);
        $this->loader->addNamespace('Demo', $base . '/');
        $this->loader->addNamespace('Demo\\', $base);

        $namespaces = $this->loader->getNamespaces();
        $this->assertArrayHasKey('Demo', $namespaces);
        $this->assertCount(1, $namespaces['Demo']);
    }

    public function test_normalize_class(): void
    {
        $this->assertSame('Foo/Bar/Baz.php', $this->loader->normalizeClass('\\Foo\\Bar_Baz'));
        $this->assertSame('FlatClass.php', $this->loader->normalizeClass('FlatClass'));
    }

    public function test_load_from_directory(): void
    {
        $this->loader->addDirectories($this->fixtureRoot . '/flat');
        $this->loader->register();

        $this->assertTrue($this->loader->load('FlatClass'));
        $this->assertTrue(class_exists('FlatClass', false));
        $this->assertSame('flat', \FlatClass::ping());
    }

    public function test_load_from_psr4_namespace(): void
    {
        $this->loader->addNamespace('Demo', $this->fixtureRoot . '/src/Demo');
        // When namespace maps to the Demo folder itself, relative class is Hello
        // Better: map Demo to src
        $this->loader->reset();
        $this->loader = new ClassLoader();
        $this->loader->addNamespace('Demo', $this->fixtureRoot . '/src/Demo');
        // Wait - if namespace is Demo and class is Demo\Hello, relative is Hello
        // and path is src/Demo/Hello.php — so base should be parent of Demo folder? 
        // PSR-4: namespace Demo maps to path ending where Demo classes live.
        // Class Demo\Hello => path + Hello.php, so path = .../src/Demo

        $resolved = $this->loader->resolve('Demo\\Hello');
        $this->assertNotNull($resolved);
        $this->assertTrue(is_file($resolved));

        $this->loader->register();
        $this->assertTrue($this->loader->load('Demo\\Hello'));
        $this->assertTrue(class_exists(\Demo\Hello::class, false));
        $this->assertSame('pong', \Demo\Hello::ping());
    }

    public function test_resolve_returns_null_for_missing(): void
    {
        $this->loader->addDirectories($this->fixtureRoot . '/flat');
        $this->assertNull($this->loader->resolve('Missing\\Thing'));
    }

    public function test_not_found_cache(): void
    {
        $this->loader->addDirectories($this->fixtureRoot . '/flat');
        $this->assertFalse($this->loader->load('NoSuchClassXyz'));
        $stats = $this->loader->getStatistics();
        $this->assertGreaterThanOrEqual(1, $stats['not_found_cache_count']);

        // Second call uses cache
        $this->assertFalse($this->loader->load('NoSuchClassXyz'));
    }

    public function test_clear_cache_and_reset(): void
    {
        $this->loader->addDirectories($this->fixtureRoot . '/flat');
        $this->loader->load('NoSuchClassXyz');
        $this->loader->clearCache();
        $this->assertSame(0, $this->loader->getStatistics()['not_found_cache_count']);

        $this->loader->register();
        $this->assertTrue($this->loader->isRegistered());
        $this->loader->reset();
        $this->assertFalse($this->loader->isRegistered());
        $this->assertSame([], $this->loader->getDirectories());
    }

    public function test_remove_directories(): void
    {
        $this->loader->addDirectories([$this->fixtureRoot . '/flat', $this->fixtureRoot . '/src']);
        $this->assertCount(2, $this->loader->getDirectories());
        $this->loader->removeDirectories($this->fixtureRoot . '/flat/');
        $this->assertCount(1, $this->loader->getDirectories());
        $this->loader->removeDirectories();
        $this->assertSame([], $this->loader->getDirectories());
    }

    public function test_remove_namespace(): void
    {
        $this->loader->addNamespace('Demo', $this->fixtureRoot . '/src/Demo');
        $this->loader->removeNamespace('Demo');
        $this->assertSame([], $this->loader->getNamespaces());
    }

    public function test_self_test_passes(): void
    {
        $result = $this->loader->selfTest();
        $this->assertTrue($result['ok']);
        $this->assertTrue($result['path_dedup']);
        $this->assertTrue($result['class_normalize']);
    }

    public function test_register_is_idempotent(): void
    {
        $this->loader->register();
        $this->loader->register();
        $this->assertTrue($this->loader->isRegistered());
        $this->loader->unregister();
        $this->assertFalse($this->loader->isRegistered());
    }

    public function test_statistics_shape(): void
    {
        $this->loader->addDirectories($this->fixtureRoot . '/flat');
        $this->loader->addNamespace('Demo', $this->fixtureRoot . '/src/Demo');
        $stats = $this->loader->getStatistics();
        $this->assertArrayHasKey('directories_count', $stats);
        $this->assertArrayHasKey('namespaces_count', $stats);
        $this->assertArrayHasKey('version', $stats);
        $this->assertSame(1, $stats['directories_count']);
        $this->assertSame(1, $stats['namespaces_count']);
    }

    private function removeTree(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }
        $items = scandir($dir);
        if ($items === false) {
            return;
        }
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $path = $dir . DIRECTORY_SEPARATOR . $item;
            if (is_dir($path)) {
                $this->removeTree($path);
            } else {
                @unlink($path);
            }
        }
        @rmdir($dir);
    }
}
