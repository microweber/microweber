<?php

namespace MicroweberPackages\Filesystem\Tests;

use MicroweberPackages\Filesystem\FilesystemService;

class FilesystemServiceTest extends TestCase
{
    private string $tempDir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tempDir = sys_get_temp_dir() . '/mw_fs_test_' . uniqid();
        mkdir($this->tempDir, 0755, true);
    }

    protected function tearDown(): void
    {
        if (is_dir($this->tempDir)) {
            $this->service->removeDirectory($this->tempDir);
        }
        parent::tearDown();
    }

    // ------------------------------------------------------------------
    //  Path utilities
    // ------------------------------------------------------------------

    public function test_normalize_path_with_slash(): void
    {
        $result = $this->service->normalizePath('/some/path');
        $expected = DIRECTORY_SEPARATOR . 'some' . DIRECTORY_SEPARATOR . 'path' . DIRECTORY_SEPARATOR;
        $this->assertSame($expected, $result);
    }

    public function test_normalize_path_without_slash(): void
    {
        $result = $this->service->normalizePath('/some/path', false);
        $expected = DIRECTORY_SEPARATOR . 'some' . DIRECTORY_SEPARATOR . 'path';
        $this->assertSame($expected, $result);
    }

    public function test_reduce_double_slashes(): void
    {
        $this->assertSame('/a/b/c/', $this->service->reduceDoubleSlashes('/a//b//c/'));
        $this->assertSame('http://example.com/path', $this->service->reduceDoubleSlashes('http://example.com//path'));
    }

    public function test_get_file_extension(): void
    {
        $this->assertSame('jpg', $this->service->getFileExtension('photo.jpg'));
        $this->assertSame('gz', $this->service->getFileExtension('archive.tar.gz'));
        $this->assertSame('php', $this->service->getFileExtension('/var/www/index.php'));
    }

    public function test_no_ext(): void
    {
        $this->assertSame('photo', $this->service->noExt('photo.jpg'));
        $this->assertSame('archive.tar', $this->service->noExt('archive.tar.gz'));
    }

    // ------------------------------------------------------------------
    //  File size
    // ------------------------------------------------------------------

    public function test_file_size_nice(): void
    {
        $this->assertSame('0 B', $this->service->fileSizeNice(0));
        $this->assertSame('1 KB', $this->service->fileSizeNice(1024));
        $this->assertSame('1 MB', $this->service->fileSizeNice(1048576));
        $this->assertSame('1.5 MB', $this->service->fileSizeNice(1572864));
    }

    // ------------------------------------------------------------------
    //  Directory operations
    // ------------------------------------------------------------------

    public function test_copy_directory(): void
    {
        $src = $this->tempDir . '/src';
        $dst = $this->tempDir . '/dst';
        mkdir($src . '/sub', 0755, true);
        file_put_contents($src . '/a.txt', 'hello');
        file_put_contents($src . '/sub/b.txt', 'world');

        $copies = $this->service->copyDirectory($src, $dst);

        $this->assertFileExists($dst . '/a.txt');
        $this->assertFileExists($dst . '/sub/b.txt');
        $this->assertSame('hello', file_get_contents($dst . '/a.txt'));
        $this->assertSame('world', file_get_contents($dst . '/sub/b.txt'));
        $this->assertNotEmpty($copies);
    }

    public function test_copy_directory_single_file(): void
    {
        $src = $this->tempDir . '/single.txt';
        $dst = $this->tempDir . '/single_copy.txt';
        file_put_contents($src, 'content');

        $copies = $this->service->copyDirectory($src, $dst);

        $this->assertFileExists($dst);
        $this->assertSame('content', file_get_contents($dst));
        $this->assertCount(1, $copies);
    }

    public function test_remove_directory(): void
    {
        $dir = $this->tempDir . '/to_remove';
        mkdir($dir . '/sub', 0755, true);
        file_put_contents($dir . '/file.txt', 'data');
        file_put_contents($dir . '/sub/deep.txt', 'data');

        $this->service->removeDirectory($dir);

        $this->assertDirectoryDoesNotExist($dir);
    }

    public function test_remove_directory_nonexistent(): void
    {
        // Should not throw
        $this->service->removeDirectory('/nonexistent/path/xyz_' . uniqid());
        $this->assertTrue(true);
    }

    public function test_remove_dir_recursive_keeps_top_level_when_empty_true(): void
    {
        $dir = $this->tempDir . '/keep_top';
        mkdir($dir, 0755, true);
        file_put_contents($dir . '/file.txt', 'data');

        $result = $this->service->removeDirRecursive($dir, true);

        $this->assertTrue($result);
        $this->assertDirectoryExists($dir);
        $this->assertFileDoesNotExist($dir . '/file.txt');
    }

    public function test_remove_dir_recursive_removes_top_level_when_empty_false(): void
    {
        $dir = $this->tempDir . '/remove_top';
        mkdir($dir, 0755, true);
        file_put_contents($dir . '/file.txt', 'data');

        $result = $this->service->removeDirRecursive($dir, false);

        $this->assertTrue($result);
        $this->assertDirectoryDoesNotExist($dir);
    }

    public function test_remove_dir_recursive_returns_false_for_invalid(): void
    {
        $this->assertFalse($this->service->removeDirRecursive('/nonexistent/xyz_' . uniqid()));
    }

    public function test_mkdir_recursive(): void
    {
        $dir = $this->tempDir . '/deep/nested/dir';
        $result = $this->service->mkdirRecursive($dir);

        $this->assertTrue($result);
        $this->assertDirectoryExists($dir);
    }

    public function test_mkdir_recursive_empty_returns_false(): void
    {
        $this->assertFalse($this->service->mkdirRecursive(''));
    }

    public function test_mkdir_recursive_already_exists(): void
    {
        $dir = $this->tempDir . '/existing';
        mkdir($dir, 0755, true);
        $this->assertTrue($this->service->mkdirRecursive($dir));
    }

    public function test_md5_dir(): void
    {
        $dir = $this->tempDir . '/md5test';
        mkdir($dir, 0755, true);
        file_put_contents($dir . '/a.txt', 'hello');
        file_put_contents($dir . '/b.txt', 'world');

        $result = $this->service->md5Dir($dir);

        $this->assertCount(2, $result);
        $this->assertArrayHasKey(md5('hello'), $result);
        $this->assertArrayHasKey(md5('world'), $result);
    }

    public function test_md5_dir_recursive(): void
    {
        $dir = $this->tempDir . '/md5recursive';
        mkdir($dir . '/sub', 0755, true);
        file_put_contents($dir . '/a.txt', 'hello');
        file_put_contents($dir . '/sub/b.txt', 'world');

        $result = $this->service->md5Dir($dir);

        $this->assertCount(2, $result);
    }

    public function test_md5_dir_throws_on_missing_dir(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->service->md5Dir('/nonexistent/path/xyz_' . uniqid());
    }

    public function test_directory_map(): void
    {
        $dir = $this->tempDir . '/map_test';
        mkdir($dir . '/sub', 0755, true);
        file_put_contents($dir . '/a.txt', 'hello');
        file_put_contents($dir . '/sub/b.txt', 'world');

        $map = $this->service->directoryMap($dir);

        $this->assertIsArray($map);
        $this->assertContains('a.txt', $map);
        $this->assertArrayHasKey('sub', $map);
        $this->assertContains('b.txt', $map['sub']);
    }

    public function test_directory_map_with_depth(): void
    {
        $dir = $this->tempDir . '/depth_test';
        mkdir($dir . '/sub/deep', 0755, true);
        file_put_contents($dir . '/a.txt', 'hello');
        file_put_contents($dir . '/sub/deep/b.txt', 'world');

        $map = $this->service->directoryMap($dir, 1);

        $this->assertContains('a.txt', $map);
        // sub should be listed as a file entry (not recursed) since depth=1
    }

    public function test_directory_map_returns_false_on_invalid_dir(): void
    {
        $this->assertFalse($this->service->directoryMap('/nonexistent/xyz_' . uniqid()));
    }

    public function test_directory_map_full_path(): void
    {
        $dir = $this->tempDir . '/fullpath';
        mkdir($dir, 0755, true);
        file_put_contents($dir . '/a.txt', 'hello');

        $map = $this->service->directoryMap($dir, 1, false, true);

        $this->assertIsArray($map);
        // entries should contain full paths
        foreach ($map as $entry) {
            if (is_string($entry)) {
                $this->assertStringContainsString($dir, $entry);
            }
        }
    }

    // ------------------------------------------------------------------
    //  Recursive glob
    // ------------------------------------------------------------------

    public function test_rglob(): void
    {
        $dir = $this->tempDir . '/glob_test';
        mkdir($dir . '/sub', 0755, true);
        file_put_contents($dir . '/a.txt', 'hello');
        file_put_contents($dir . '/sub/b.txt', 'world');

        $result = $this->service->rglob('*.txt', 0, $dir . '/');

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
    }

    public function test_rglob_skips_git_dirs(): void
    {
        $result = $this->service->rglob('*', 0, $this->tempDir . '/.git/');
        $this->assertFalse($result);
    }

    // ------------------------------------------------------------------
    //  Security: extension validation
    // ------------------------------------------------------------------

    public function test_is_dangerous_file_detects_php(): void
    {
        $this->assertTrue($this->service->isDangerousFile('shell.php'));
    }

    public function test_is_dangerous_file_detects_exe(): void
    {
        $this->assertTrue($this->service->isDangerousFile('hack.exe'));
    }

    public function test_is_dangerous_file_detects_phtml(): void
    {
        $this->assertTrue($this->service->isDangerousFile('exploit.phtml'));
    }

    public function test_is_dangerous_file_safe_jpg(): void
    {
        $this->assertFalse($this->service->isDangerousFile('photo.jpg'));
    }

    public function test_is_dangerous_file_safe_pdf(): void
    {
        $this->assertFalse($this->service->isDangerousFile('doc.pdf'));
    }

    public function test_is_dangerous_file_case_insensitive(): void
    {
        $this->assertTrue($this->service->isDangerousFile('test.PHP'));
        $this->assertTrue($this->service->isDangerousFile('test.Exe'));
    }

    public function test_is_allowed_file_images(): void
    {
        $this->assertTrue($this->service->isAllowedFile('photo.jpg'));
        $this->assertTrue($this->service->isAllowedFile('image.png'));
        $this->assertTrue($this->service->isAllowedFile('icon.gif'));
    }

    public function test_is_allowed_file_documents(): void
    {
        $this->assertTrue($this->service->isAllowedFile('doc.pdf'));
        $this->assertTrue($this->service->isAllowedFile('spreadsheet.xlsx'));
    }

    public function test_is_allowed_file_archives(): void
    {
        $this->assertTrue($this->service->isAllowedFile('archive.zip'));
        $this->assertTrue($this->service->isAllowedFile('backup.rar'));
    }

    public function test_is_allowed_file_rejects_special_chars(): void
    {
        $this->assertFalse($this->service->isAllowedFile('file*.php'));
        $this->assertFalse($this->service->isAllowedFile('file?.php'));
        $this->assertFalse($this->service->isAllowedFile('file<>.php'));
        $this->assertFalse($this->service->isAllowedFile('file::name.txt'));
    }

    public function test_is_allowed_file_rejects_unknown_extension(): void
    {
        $this->assertFalse($this->service->isAllowedFile('test.xyz_unknown'));
    }

    public function test_get_allowed_extensions_images(): void
    {
        $result = $this->service->getAllowedExtensionsForUpload('images', true);
        $this->assertContains('jpg', $result);
        $this->assertContains('png', $result);
        $this->assertContains('gif', $result);
        $this->assertContains('webp', $result);
    }

    public function test_get_allowed_extensions_videos(): void
    {
        $result = $this->service->getAllowedExtensionsForUpload('videos', true);
        $this->assertContains('mp4', $result);
        $this->assertContains('avi', $result);
    }

    public function test_get_allowed_extensions_documents(): void
    {
        $result = $this->service->getAllowedExtensionsForUpload('documents', true);
        $this->assertContains('pdf', $result);
        $this->assertContains('doc', $result);
    }

    public function test_get_allowed_extensions_archives(): void
    {
        $result = $this->service->getAllowedExtensionsForUpload('archives', true);
        $this->assertContains('zip', $result);
        $this->assertContains('rar', $result);
    }

    public function test_get_allowed_extensions_string_return(): void
    {
        $str = $this->service->getAllowedExtensionsForUpload('images', false);
        $this->assertIsString($str);
        $this->assertStringContainsString('jpg', $str);
    }

    public function test_get_allowed_extensions_all(): void
    {
        $result = $this->service->getAllowedExtensionsForUpload('all', true);
        $this->assertContains('*', $result);
    }

    public function test_get_allowed_extensions_aliases(): void
    {
        // img, image, media should all return same as images
        $images = $this->service->getAllowedExtensionsForUpload('images', true);
        $this->assertEquals($images, $this->service->getAllowedExtensionsForUpload('img', true));
        $this->assertEquals($images, $this->service->getAllowedExtensionsForUpload('image', true));
        $this->assertEquals($images, $this->service->getAllowedExtensionsForUpload('media', true));
    }

    public function test_get_dangerous_extensions_returns_non_empty_array(): void
    {
        $result = $this->service->getDangerousExtensions();
        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
        $this->assertContains('php', $result);
        $this->assertContains('exe', $result);
    }

    // ------------------------------------------------------------------
    //  SVG
    // ------------------------------------------------------------------

    public function test_sanitize_svg(): void
    {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg"><circle r="10"/></svg>';
        $result = $this->service->sanitizeSvg($svg);
        $this->assertNotNull($result);
        $this->assertStringContainsString('<svg', $result);
    }

    public function test_check_if_svg_is_valid_with_clean_svg(): void
    {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg"><circle r="10"/></svg>';
        $this->assertTrue($this->service->checkIfSvgIsValid($svg));
    }

    public function test_check_if_svg_is_valid_with_malicious_svg(): void
    {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg"><script>alert("xss")</script></svg>';
        $this->assertFalse($this->service->checkIfSvgIsValid($svg));
    }

    // ------------------------------------------------------------------
    //  Download response
    // ------------------------------------------------------------------

    public function test_download_response(): void
    {
        $file = $this->tempDir . '/download.txt';
        file_put_contents($file, 'download content');

        $response = $this->service->downloadResponse($file);

        $this->assertInstanceOf(\Symfony\Component\HttpFoundation\BinaryFileResponse::class, $response);
        $this->assertSame(200, $response->getStatusCode());
    }

    public function test_download_response_throws_on_missing_file(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->service->downloadResponse('/nonexistent/file_' . uniqid() . '.txt');
    }
}
