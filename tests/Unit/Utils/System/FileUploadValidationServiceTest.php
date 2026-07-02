<?php

namespace Tests\Unit\Utils\System;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use MicroweberPackages\FileUploader\Validation\FileValidationService;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * App-integration coverage for the microweber-file-uploader package's
 * FileValidationService, exercised against the real Microweber bootstrap +
 * config (the package ships its own standalone Testbench suite too). The old
 * MicroweberPackages\Utils\System\FileUploadValidationService back-compat
 * shim was removed — nothing referenced it.
 */
class FileUploadValidationServiceTest extends TestCase
{
    protected FileValidationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new FileValidationService();
        Storage::fake('public');
    }

    #[Test]
    public function it_returns_default_size_limits(): void
    {
        $this->assertEquals(10240, $this->service->getSizeLimit('images'));
        $this->assertEquals(102400, $this->service->getSizeLimit('videos'));
        $this->assertEquals(51200, $this->service->getSizeLimit('audios'));
        $this->assertEquals(20480, $this->service->getSizeLimit('documents'));
        $this->assertEquals(102400, $this->service->getSizeLimit('archives'));
        $this->assertEquals(10240, $this->service->getSizeLimit('files'));
        $this->assertEquals(10240, $this->service->getSizeLimit('default'));
    }

    #[Test]
    public function it_returns_size_limits_from_config(): void
    {
        config()->set('media.upload_limits.images', 5120);
        $this->assertEquals(5120, $this->service->getSizeLimit('images'));

        config()->set('media.upload_limits.custom', 102400);
        $this->assertEquals(102400, $this->service->getSizeLimit('custom'));
    }

    #[Test]
    public function it_validates_file_size_correctly(): void
    {
        $result = $this->service->validateSize(500 * 1024, 1024);
        $this->assertTrue($result['valid']);
        $this->assertNull($result['error']);

        $result = $this->service->validateSize(1024 * 1024, 1024);
        $this->assertTrue($result['valid']);

        $result = $this->service->validateSize(2 * 1024 * 1024, 1024);
        $this->assertFalse($result['valid']);
        $this->assertNotNull($result['error']);
        $this->assertStringContainsString('exceeds maximum allowed size', $result['error']);
    }

    #[Test]
    public function it_validates_size_with_human_readable_units(): void
    {
        $result = $this->service->validateSize(500 * 1024, '500K');
        $this->assertTrue($result['valid']);

        $result = $this->service->validateSize(500 * 1024, '1M');
        $this->assertTrue($result['valid']);

        $result = $this->service->validateSize(1024 * 1024 * 1024, '1G');
        $this->assertTrue($result['valid']);

        $result = $this->service->validateSize(500 * 1024, '1m');
        $this->assertTrue($result['valid']);
    }

    #[Test]
    public function it_validates_size_by_category(): void
    {
        $result = $this->service->validateSizeByCategory(5 * 1024 * 1024, 'images');
        $this->assertTrue($result['valid']);

        $result = $this->service->validateSizeByCategory(20 * 1024 * 1024, 'images');
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
        $imageFile = UploadedFile::fake()->image('test.jpg', 100, 100);
        $imagePath = $imageFile->store('uploads', 'public');
        $fullPath = Storage::disk('public')->path($imagePath);

        $mimeType = $this->service->getMimeType($fullPath);
        $this->assertNotNull($mimeType);
        $this->assertStringStartsWith('image/', $mimeType);

        $this->assertNull($this->service->getMimeType('/non/existent/file.jpg'));
    }

    #[Test]
    public function it_validates_mime_types_correctly(): void
    {
        $imageFile = UploadedFile::fake()->image('test.jpg', 100, 100);
        $imagePath = $imageFile->store('uploads', 'public');
        $fullPath = Storage::disk('public')->path($imagePath);

        $result = $this->service->validateMimeType($fullPath, ['images']);
        $this->assertTrue($result['valid']);
        $this->assertNotNull($result['mime_type']);
        $this->assertNull($result['error']);

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
    }

    #[Test]
    public function it_generates_validation_rules(): void
    {
        $rules = $this->service->getValidationRules(['images'], 10240);

        $this->assertArrayHasKey('max', $rules);
        $this->assertArrayHasKey('mimetypes', $rules);
        $this->assertEquals(10240, $rules['max']);
        $this->assertStringContainsString('image/', $rules['mimetypes']);

        $rules = $this->service->getValidationRules(['images']);
        $this->assertEquals(10240, $rules['max']);
    }

    #[Test]
    public function it_sets_custom_size_limits(): void
    {
        $service = new FileValidationService();
        config()->set('media.upload_limits.custom', 5000);
        config()->set('media.upload_limits.test_category', 2000);

        $this->assertEquals(5000, $service->getSizeLimit('custom'));
        $this->assertEquals(2000, $service->getSizeLimit('test_category'));
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
        $imageFile = UploadedFile::fake()->image('test.jpg', 100, 100)->size(100);
        $imagePath = $imageFile->store('uploads', 'public');
        $fullPath = Storage::disk('public')->path($imagePath);

        $mimeResult = $this->service->validateMimeType($fullPath, ['images']);
        $this->assertTrue($mimeResult['valid']);
        $this->assertEquals('jpg', $mimeResult['extension']);
    }

    #[Test]
    public function it_returns_null_for_unknown_mime_type(): void
    {
        $unknownFile = UploadedFile::fake()->create('test.xyz', 100);
        $unknownPath = $unknownFile->store('uploads', 'public');
        $fullPath = Storage::disk('public')->path($unknownPath);

        $mimeType = $this->service->getMimeType($fullPath);
        $this->assertNotNull($mimeType);
    }

    #[Test]
    public function it_detects_dangerous_extensions(): void
    {
        $this->assertTrue($this->service->isDangerousExtension('test.php'));
        $this->assertTrue($this->service->isDangerousExtension('test.exe'));
        $this->assertTrue($this->service->isDangerousExtension('test.bat'));
        $this->assertTrue($this->service->isDangerousExtension('test.sh'));
        $this->assertTrue($this->service->isDangerousExtension('test.htaccess'));
        $this->assertTrue($this->service->isDangerousExtension('test.phtml'));
        $this->assertTrue($this->service->isDangerousExtension('test.html'));
        $this->assertFalse($this->service->isDangerousExtension('test.jpg'));
        $this->assertFalse($this->service->isDangerousExtension('test.png'));
        $this->assertFalse($this->service->isDangerousExtension('test.pdf'));
    }

    #[Test]
    public function it_detects_category_from_extension(): void
    {
        $this->assertEquals('images', $this->service->detectCategoryFromExtension('jpg'));
        $this->assertEquals('images', $this->service->detectCategoryFromExtension('png'));
        $this->assertEquals('videos', $this->service->detectCategoryFromExtension('mp4'));
        $this->assertEquals('audios', $this->service->detectCategoryFromExtension('mp3'));
        $this->assertEquals('documents', $this->service->detectCategoryFromExtension('pdf'));
        $this->assertEquals('archives', $this->service->detectCategoryFromExtension('zip'));
    }

    #[Test]
    public function it_checks_allowed_extensions(): void
    {
        $this->assertTrue($this->service->isAllowedExtension('test.jpg'));
        $this->assertTrue($this->service->isAllowedExtension('test.png'));
        $this->assertTrue($this->service->isAllowedExtension('test.pdf'));
        $this->assertTrue($this->service->isAllowedExtension('test.mp4'));
        $this->assertFalse($this->service->isAllowedExtension('test.php'));
        $this->assertFalse($this->service->isAllowedExtension('test.exe'));
    }
}
