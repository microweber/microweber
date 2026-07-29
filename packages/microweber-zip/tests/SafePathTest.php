<?php

declare(strict_types=1);

namespace MicroweberPackages\Zip\Tests;

use MicroweberPackages\Zip\Exceptions\UnsafePathException;
use MicroweberPackages\Zip\Support\SafePath;
use PHPUnit\Framework\Attributes\Test;

class SafePathTest extends TestCase
{
    #[Test]
    public function it_normalizes_slashes(): void
    {
        $this->assertSame('a/b/c', SafePath::normalize('a\\b//c'));
        $this->assertSame('a/b/c/', SafePath::normalize('a\\b//c', true));
    }

    #[Test]
    public function it_joins_paths(): void
    {
        $this->assertSame('dir/file.txt', SafePath::join('dir', 'file.txt'));
        $this->assertSame('dir/file.txt', SafePath::join('dir/', '/file.txt'));
    }

    #[Test]
    public function it_rejects_path_traversal(): void
    {
        $this->expectException(UnsafePathException::class);
        SafePath::assertSafeEntry('../etc/passwd');
    }

    #[Test]
    public function it_rejects_nested_traversal(): void
    {
        $this->expectException(UnsafePathException::class);
        SafePath::assertSafeEntry('foo/../../etc/passwd');
    }

    #[Test]
    public function it_rejects_absolute_paths(): void
    {
        $this->expectException(UnsafePathException::class);
        SafePath::assertSafeEntry('/etc/passwd');
    }

    #[Test]
    public function it_rejects_forbidden_characters(): void
    {
        $this->expectException(UnsafePathException::class);
        SafePath::assertSafeEntry('file:name.txt');
    }

    #[Test]
    public function it_rejects_newlines(): void
    {
        $this->expectException(UnsafePathException::class);
        SafePath::assertSafeEntry("evil\nname.txt");
    }

    #[Test]
    public function it_rejects_carriage_returns(): void
    {
        $this->expectException(UnsafePathException::class);
        SafePath::assertSafeEntry("evil\rname.txt");
    }

    #[Test]
    public function it_rejects_control_characters(): void
    {
        $this->expectException(UnsafePathException::class);
        // \x1f (unit separator) and \x7f (DEL) are representative control chars.
        SafePath::assertSafeEntry("folder/\x1ffile.txt");
    }

    #[Test]
    public function it_rejects_tabs(): void
    {
        $this->expectException(UnsafePathException::class);
        SafePath::assertSafeEntry("evil\tname.txt");
    }

    #[Test]
    public function it_accepts_normal_relative_paths(): void
    {
        $result = SafePath::assertSafeEntry('folder/sub/file.txt');
        $this->assertSame('folder/sub/file.txt', $result);
    }

    #[Test]
    public function it_rejects_overlong_paths(): void
    {
        $this->expectException(UnsafePathException::class);
        SafePath::assertSafeEntry(str_repeat('a', 600), 512);
    }

    #[Test]
    public function it_creates_directories_recursively(): void
    {
        $path = $this->tempDir . '/a/b/c';
        $this->assertTrue(SafePath::mkdirRecursive($path));
        $this->assertDirectoryExists($path);
    }

    #[Test]
    public function resolve_target_stays_under_base(): void
    {
        $target = SafePath::resolveTarget($this->tempDir, 'nested/file.txt');
        $this->assertStringStartsWith(SafePath::normalize($this->tempDir, false), $target);
        $this->assertStringEndsWith('nested/file.txt', $target);
    }
}
