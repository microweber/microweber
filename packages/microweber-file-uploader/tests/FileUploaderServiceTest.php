<?php

namespace MicroweberPackages\FileUploader\Tests;

use Illuminate\Support\Facades\Storage;
use MicroweberPackages\FileUploader\FileUploaderService;
use MicroweberPackages\FileUploader\Validation\FileValidationService;
use MicroweberPackages\FileUploader\Support\ImageProcessor;
use MicroweberPackages\FileUploader\Support\FilenameSanitizer;

class FileUploaderServiceTest extends TestCase
{
    protected FileUploaderService $service;
    protected string $tempDir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app('file_uploader');
        $this->tempDir = sys_get_temp_dir() . '/file_uploader_service_tests_' . uniqid();
        mkdir($this->tempDir, 0755, true);
        Storage::fake('public');
    }

    protected function tearDown(): void
    {
        $files = glob($this->tempDir . '/*');
        foreach ($files as $file) {
            @unlink($file);
        }
        @rmdir($this->tempDir);
        parent::tearDown();
    }

    // =====================================================
    // Service Resolution
    // =====================================================

    public function test_service_is_resolved_from_container(): void
    {
        $this->assertInstanceOf(FileUploaderService::class, app('file_uploader'));
    }

    public function test_service_is_singleton(): void
    {
        $a = app('file_uploader');
        $b = app('file_uploader');
        $this->assertSame($a, $b);
    }

    public function test_service_has_validator(): void
    {
        $this->assertInstanceOf(FileValidationService::class, $this->service->validator());
    }

    public function test_service_has_image_processor(): void
    {
        $this->assertInstanceOf(ImageProcessor::class, $this->service->imageProcessor());
    }

    public function test_service_has_filename_sanitizer(): void
    {
        $this->assertInstanceOf(FilenameSanitizer::class, $this->service->filenameSanitizer());
    }

    // =====================================================
    // Error Response
    // =====================================================

    public function test_error_response_format(): void
    {
        $result = $this->service->errorResponse(100, 'Test error');
        $this->assertFalse($result['success']);
        $this->assertTrue($result['error']);
        $this->assertEquals(100, $result['error_code']);
        $this->assertEquals('Test error', $result['error_message']);
        $this->assertEquals(401, $result['http_status']);
    }

    public function test_error_response_custom_http_status(): void
    {
        $result = $this->service->errorResponse(108, 'Too large', 413);
        $this->assertEquals(413, $result['http_status']);
    }

    // =====================================================
    // Human Filesize
    // =====================================================

    public function test_human_filesize_bytes(): void
    {
        $this->assertStringContainsString('B', $this->service->humanFilesize(500));
    }

    public function test_human_filesize_kilobytes(): void
    {
        $result = $this->service->humanFilesize(1024);
        $this->assertStringContainsString('KB', $result);
    }

    public function test_human_filesize_megabytes(): void
    {
        $result = $this->service->humanFilesize(1024 * 1024);
        $this->assertStringContainsString('MB', $result);
    }

    // =====================================================
    // Cleanup
    // =====================================================

    public function test_cleanup_temp_files(): void
    {
        // Create some old .part files
        $oldPartFile = $this->tempDir . '/old_file.part';
        file_put_contents($oldPartFile, 'test');
        touch($oldPartFile, time() - 20000); // 20000 seconds ago

        $currentPartFile = $this->tempDir . '/current.part';
        file_put_contents($currentPartFile, 'test');

        $currentFilePath = $this->tempDir . '/current';

        $this->service->cleanupTempFiles($this->tempDir, $currentFilePath);

        // Old part file should be cleaned up
        $this->assertFalse(is_file($oldPartFile));
        // Current part file should still exist
        $this->assertTrue(is_file($currentPartFile));
    }

    public function test_cleanup_temp_files_nonexistent_dir(): void
    {
        // Should not throw
        $this->service->cleanupTempFiles('/nonexistent/dir', '/some/path');
        $this->assertTrue(true); // No exception means success
    }

    // =====================================================
    // Upload Path
    // =====================================================

    public function test_default_upload_path(): void
    {
        $path = $this->service->getDefaultUploadPath();
        $this->assertNotEmpty($path);
    }

    // =====================================================
    // Facade Access
    // =====================================================

    public function test_facade_resolves(): void
    {
        $validator = \MicroweberPackages\FileUploader\Facades\FileUploader::validator();
        $this->assertInstanceOf(FileValidationService::class, $validator);
    }
}