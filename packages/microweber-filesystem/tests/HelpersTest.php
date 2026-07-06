<?php

namespace MicroweberPackages\Filesystem\Tests;

class HelpersTest extends TestCase
{
    public function test_normalize_path_function(): void
    {
        $this->assertTrue(function_exists('normalize_path'));
        $result = normalize_path('/some/path', false);
        $expected = DIRECTORY_SEPARATOR . 'some' . DIRECTORY_SEPARATOR . 'path';
        $this->assertSame($expected, $result);
    }

    public function test_reduce_double_slashes_function(): void
    {
        $this->assertTrue(function_exists('reduce_double_slashes'));
        $this->assertSame('/a/b/', reduce_double_slashes('/a//b/'));
    }

    public function test_get_file_extension_function(): void
    {
        $this->assertTrue(function_exists('get_file_extension'));
        $this->assertSame('txt', get_file_extension('file.txt'));
        $this->assertSame('php', get_file_extension('index.php'));
    }

    public function test_no_ext_function(): void
    {
        $this->assertTrue(function_exists('no_ext'));
        $this->assertSame('file', no_ext('file.txt'));
        $this->assertSame('archive.tar', no_ext('archive.tar.gz'));
    }

    public function test_file_size_nice_function(): void
    {
        $this->assertTrue(function_exists('file_size_nice'));
        $this->assertSame('1 KB', file_size_nice(1024));
        $this->assertSame('1 MB', file_size_nice(1048576));
    }

    public function test_mkdir_recursive_function(): void
    {
        $this->assertTrue(function_exists('mkdir_recursive'));
        $dir = sys_get_temp_dir() . '/mw_helper_test_' . uniqid() . '/deep/path';
        $result = mkdir_recursive($dir);
        $this->assertTrue($result);
        $this->assertDirectoryExists($dir);
        rmdir_recursive(dirname(dirname($dir)), false);
    }

    public function test_rmdir_recursive_function(): void
    {
        $this->assertTrue(function_exists('rmdir_recursive'));
        $dir = sys_get_temp_dir() . '/mw_rmdir_test_' . uniqid();
        mkdir($dir, 0755, true);
        file_put_contents($dir . '/test.txt', 'data');
        $result = rmdir_recursive($dir, false);
        $this->assertTrue($result);
        $this->assertDirectoryDoesNotExist($dir);
    }

    public function test_rglob_function(): void
    {
        $this->assertTrue(function_exists('rglob'));
    }

    public function test_directory_map_function(): void
    {
        $this->assertTrue(function_exists('directory_map'));
        $dir = sys_get_temp_dir() . '/mw_dirmap_test_' . uniqid();
        mkdir($dir, 0755, true);
        file_put_contents($dir . '/a.txt', 'hello');

        $map = directory_map($dir);
        $this->assertIsArray($map);
        $this->assertContains('a.txt', $map);

        rmdir_recursive($dir, false);
    }
}
