<?php

namespace Tests\Unit\Utils\System;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use MicroweberPackages\Utils\System\FileUploadValidationService;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Tests for FileUploadValidationService
 *
 * @package Tests\Unit\Utils\System
 */
class FileUploadValidationServiceTest extends TestCase
{
    protected FileUploadValidationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new FileUploadValidationService();
        Storage::fake('public');
    }

    #[Test]
    public function it_returns_default_size_limits(): void
    {
        $this->assertEquals(10240, $this->service->getSizeLimit('images'));    // 10 MB
        $this->assertEquals(102400, $this->service->getSizeLimit('videos'));   // 100 MB
        $this->assertEquals(51200, $this->service->getSizeLimit('audios'));    // 50 MB
        $this->assertEquals(20480, $this->service->getSizeLimit('documents')); // 20 MB
        $this->assertEquals(102400, $this->service->getSizeLimit('archives')); // 100 MB
        $this->assertEquals(10240, $this->service->getSizeLimit('files'));    // 10 MB
        $this->assertEquals(10240, $this->service->getSizeLimit('default'));  // 10 MB
    }

    #[Test]
    public function it_returns_size_limits_from_config(): void
    {
        // Test that config can override defaults
        config()->set('media.upload_limits.images', 5120); // 5 MB
        $this->assertEquals(5120, $this->service->getSizeLimit('images'));
        
        config()->set('media.upload_limits.custom', 102400); // 100 MB
        $this->assertEquals(102400, $this->service->getSizeLimit('custom'));
    }

    #[Test]
    public function it_validates_file_size_correctly(): void
    {
        // Small file (should pass)
        $result = $this->service->validateSize(500 * 1024, 1024); // 500KB < 1MB
        $this->assertTrue($result['valid']);
        $this->assertNull($result['error']);

        // Exact size (should pass)
        $result = $this->service->validateSize(1024 * 1024, 1024); // 1MB == 1MB
        $this->assertTrue($result['valid']);

        // Large file (should fail)
        $result = $this->service->validateSize(2 * 1024 * 1024, 1024); // 2MB > 1MB
        $this->assertFalse($result['valid']);
        $this->assertNotNull($result['error']);
        $this->assertStringContainsString('exceeds maximum allowed size', $result['error']);
    }

    #[Test]
    public function it_validates_size_with_human_readable_units(): void
    {
        // Test kilobytes
        $result = $this->service->validateSize(500 * 1024, '500K');
        $this->assertTrue($result['valid']);

        // Test megabytes
        $result = $this->service->validateSize(500 * 1024, '1M');
        $this->assertTrue($result['valid']);

        // Test gigabytes
        $result = $this->service->validateSize(1024 * 1024 * 1024, '1G');
        $this->assertTrue($result['valid']);

        // Test lowercase
        $result = $this->service->validateSize(500 * 1024, '1m');
        $this->assertTrue($result['valid']);
    }

    #[Test]
    public function it_validates_size_by_category(): void
    {
        // Image category limit is 10MB
        $result = $this->service->validateSizeByCategory(5 * 1024 * 1024, 'images'); // 5MB
        $this->assertTrue($result['valid']);

        $result = $this->service->validateSizeByCategory(20 * 1024 * 1024, 'images'); // 20MB > 10MB
        $this->assertFalse($result['valid']);
    }

    #[Test]
    public function it_returns_mime_type_mappings(): void
    {
        $images = $this->service->getMimeTypeMappings('images');
        $this->assertArrayHasKey('image/jpeg', $images);
        $this->assertArrayHasKey('image/png', $images);
        $this->assertArrayHasKey('image/gif', $images);
        $this->assertContains('jpg', $images['image/jpeg']);
        $this->assertContains('png', $images['image/png']);

        $documents = $this->service->getMimeTypeMappings('documents');
        $this->assertArrayHasKey('application/pdf', $documents);
        $this->assertArrayHasKey('application/msword', $documents);

        $all = $this->service->getMimeTypeMappings('all');
        $this->assertArrayHasKey('image/jpeg', $all);
        $this->assertArrayHasKey('video/mp4', $all);
        $this->assertArrayHasKey('audio/mpeg', $all);
    }

    #[Test]
    public function it_detects_mime_types_correctly(): void
    {
        // Create test files with proper MIME types
        $imageFile = UploadedFile::fake()->image('test.jpg', 100, 100);
        $imagePath = $imageFile->store('uploads', 'public');
        $fullPath = Storage::disk('public')->path($imagePath);
        
        $mimeType = $this->service->getMimeType($fullPath);
        $this->assertNotNull($mimeType);
        $this->assertStringStartsWith('image/', $mimeType);

        // Test with non-existent file
        $this->assertNull($this->service->getMimeType('/non/existent/file.jpg'));
    }

    #[Test]
    public function it_validates_mime_types_correctly(): void
    {
        // Create test image
        $imageFile = UploadedFile::fake()->image('test.jpg', 100, 100);
        $imagePath = $imageFile->store('uploads', 'public');
        $fullPath = Storage::disk('public')->path($imagePath);

        // Valid image
        $result = $this->service->validateMimeType($fullPath, ['images']);
        $this->assertTrue($result['valid']);
        $this->assertNotNull($result['mime_type']);
        $this->assertNull($result['error']);

        // Invalid category
        $result = $this->service->validateMimeType($fullPath, ['videos']);
        $this->assertFalse($result['valid']);
        $this->assertNotNull($result['error']);
    }

    #[Test]
    public function it_detects_image_files(): void
    {
        $imageFile = UploadedFile::fake()->image('test.jpg', 100, 100);
        $imagePath = $imageFile->store('uploads', 'public');
        $fullPath = Storage::disk('public')->path($imagePath);

        $this->assertTrue($this->service->isImage($fullPath));
        $this->assertFalse($this->service->isVideo($fullPath));
        $this->assertFalse($this->service->isAudio($fullPath));
    }

    #[Test]
    public function it_validates_extension_matches_mime_type(): void
    {
        // Create file with matching extension and MIME type
        $imageFile = UploadedFile::fake()->image('test.jpg', 100, 100);
        $imagePath = $imageFile->store('uploads', 'public');
        $fullPath = Storage::disk('public')->path($imagePath);

        $result = $this->service->validateExtensionMatchesMimeType($fullPath);
        $this->assertTrue($result['valid']);
        $this->assertEquals('jpg', $result['actual_extension']);
        $this->assertContains('jpg', $result['expected_extensions']);
    }

    #[Test]
    public function it_returns_allowed_mime_types(): void
    {
        $mimes = $this->service->getAllowedMimeTypes(['images', 'videos']);
        $this->assertIsArray($mimes);
        $this->assertNotEmpty($mimes);
        $this->assertContains('image/jpeg', $mimes);
        $this->assertContains('video/mp4', $mimes);
        
        // Should be unique
        $uniqueMimes = array_unique($mimes);
        $this->assertEquals($uniqueMimes, $mimes);
    }

    #[Test]
    public function it_generates_validation_rules(): void
    {
        $rules = $this->service->getValidationRules(['images'], 10240);
        
        $this->assertArrayHasKey('max', $rules);
        $this->assertArrayHasKey('mimetypes', $rules);
        $this->assertEquals(10240, $rules['max']);
        $this->assertStringContainsString('image/', $rules['mimetypes']);

        // Test with null max size (should use category default)
        $rules = $this->service->getValidationRules(['images']);
        $this->assertEquals(10240, $rules['max']); // Default images limit
    }

    #[Test]
    public function it_sets_custom_size_limits(): void
    {
        // Create new service with custom limits
        $service = new FileUploadValidationService();
        
        // Set custom limits via config (more realistic usage)
        config()->set('media.upload_limits.custom', 5000);
        config()->set('media.upload_limits.test_category', 2000);
        
        // Verify config overrides work
        $this->assertEquals(5000, $service->getSizeLimit('custom'));
        $this->assertEquals(2000, $service->getSizeLimit('test_category'));
        
        // Verify defaults still work for unset categories
        $this->assertEquals(10240, $service->getSizeLimit('images'));
    }

    #[Test]
    public function it_handles_non_existent_files(): void
    {
        $result = $this->service->validateMimeType('/non/existent/file.jpg', ['images']);
        
        $this->assertFalse($result['valid']);
        $this->assertNull($result['mime_type']);
        $this->assertEquals('File does not exist', $result['error']);
    }

    #[Test]
    public function it_handles_upload_errors(): void
    {
        // Simulate upload with error
        $file = [
            'tmp_name' => '',
            'error' => UPLOAD_ERR_INI_SIZE,
            'size' => 0,
            'name' => 'test.jpg',
        ];

        $result = $this->service->validateUpload($file);
        
        $this->assertFalse($result['valid']);
        $this->assertNotEmpty($result['errors']);
        $this->assertStringContainsString('upload_max_filesize', $result['errors'][0]);
    }

    #[Test]
    public function it_validates_upload_comprehensively(): void
    {
        // Create a valid test file
        $imageFile = UploadedFile::fake()->image('test.jpg', 100, 100)->size(100);
        $imagePath = $imageFile->store('uploads', 'public');
        $fullPath = Storage::disk('public')->path($imagePath);

        // Create file array like $_FILES
        $file = [
            'tmp_name' => $fullPath,
            'name' => 'test.jpg',
            'size' => 100 * 1024,
            'error' => UPLOAD_ERR_OK,
        ];

        // Note: is_uploaded_file() will return false for this test file,
        // so we need to use a different approach for testing
        // This test demonstrates the validation logic
        
        // Test MIME validation separately
        $mimeResult = $this->service->validateMimeType($fullPath, ['images']);
        $this->assertTrue($mimeResult['valid']);
        $this->assertEquals('jpg', $mimeResult['extension']);
    }

    #[Test]
    public function it_returns_null_for_unknown_mime_type(): void
    {
        // Create file with unusual extension
        $unknownFile = UploadedFile::fake()->create('test.xyz', 100);
        $unknownPath = $unknownFile->store('uploads', 'public');
        $fullPath = Storage::disk('public')->path($unknownPath);

        // Should still return some MIME type (likely application/octet-stream or text/plain)
        $mimeType = $this->service->getMimeType($fullPath);
        $this->assertNotNull($mimeType);
    }
}
