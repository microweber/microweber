<?php

declare(strict_types=1);

namespace MicroweberPackages\ClassLoader\Tests\Unit;

use MicroweberPackages\ClassLoader\PathNormalizer;
use PHPUnit\Framework\TestCase;

class PathNormalizerTest extends TestCase
{
    private PathNormalizer $normalizer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->normalizer = new PathNormalizer();
    }

    public function test_trailing_slash_is_stripped(): void
    {
        $a = $this->normalizer->canonicalize('/var/www/app/');
        $b = $this->normalizer->canonicalize('/var/www/app');
        $this->assertSame($a, $b);
        $this->assertSame('/var/www/app', $a);
    }

    public function test_backslash_and_forward_slash_are_equivalent(): void
    {
        $a = $this->normalizer->canonicalize('C:\\Users\\test\\app\\');
        $b = $this->normalizer->canonicalize('C:/Users/test/app');
        $this->assertSame($a, $b);
        $this->assertSame('C:/Users/test/app', $a);
    }

    public function test_duplicate_separators_collapsed(): void
    {
        $this->assertSame(
            '/var/www/app',
            $this->normalizer->canonicalize('/var//www///app//')
        );
    }

    public function test_dot_segments_resolved(): void
    {
        $this->assertSame(
            '/var/www/app',
            $this->normalizer->canonicalize('/var/www/./app/../app')
        );
    }

    public function test_equals_detects_equivalent_paths(): void
    {
        // Use a path that likely does not exist so realpath is skipped.
        $base = sys_get_temp_dir() . '/mw-class-loader-eq-' . uniqid();
        $this->assertTrue($this->normalizer->equals($base . '/', $base));
        $mixed = str_replace('/', DIRECTORY_SEPARATOR, $base) . DIRECTORY_SEPARATOR;
        $this->assertTrue($this->normalizer->equals($base, $mixed));
    }

    public function test_empty_path(): void
    {
        $this->assertSame('', $this->normalizer->normalize(''));
        $this->assertSame('', $this->normalizer->normalize('   '));
    }

    public function test_to_os_path(): void
    {
        $normalized = '/tmp/foo/bar';
        $os = $this->normalizer->toOsPath($normalized);
        if (DIRECTORY_SEPARATOR === '/') {
            $this->assertSame($normalized, $os);
        } else {
            $this->assertSame('\\tmp\\foo\\bar', $os);
        }
    }

    public function test_realpath_when_directory_exists(): void
    {
        $dir = sys_get_temp_dir();
        $normalized = $this->normalizer->normalize($dir);
        $this->assertNotSame('', $normalized);
        $this->assertTrue($this->normalizer->equals($dir, $dir . DIRECTORY_SEPARATOR));
    }
}
