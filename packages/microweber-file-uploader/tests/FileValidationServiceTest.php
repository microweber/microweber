<?php

namespace MicroweberPackages\FileUploader\Tests;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use MicroweberPackages\FileUploader\Validation\FileValidationService;

class FileValidationServiceTest extends TestCase
{
    protected FileValidationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new FileValidationService();
        Storage::fake('public');
    }

    // =====================================================
    // Size Limits
    // =====================================================

    public function test_returns_default_size_limits(): void
    {
        $this->assertEquals(10240, $this->service->getSizeLimit('images'));
        $this->assertEquals(102400, $this->service->getSizeLimit('videos'));
        $this->assertEquals(51200, $this->service->getSizeLimit('audios'));
        $this->assertEquals(20480, $this->service->getSizeLimit('documents'));
        $this->assertEquals(102400, $this->service->getSizeLimit('archives'));
        $this->assertEquals(10240, $this->service->getSizeLimit('files'));
        $this->assertEquals(10240, $this->service->getSizeLimit('default'));
    }

    public function test_returns_size_limits_from_config(): void
    {
        config()->set('media.upload_limits.images', 5120);
        $this->assertEquals(5120, $this->service->getSizeLimit('images'));

        config()->set('media.upload_limits.custom_cat', 99999);
        $this->assertEquals(99999, $this->service->getSizeLimit('custom_cat'));
    }

    public function test_custom_size_limits_via_setter(): void
    {
        $this->service->setSizeLimits(['images' => 1024, 'custom' => 2048]);
        // Note: config override still takes precedence if set
        // Clear any config overrides for this test
        config()->set('media.upload_limits.images', null);
        config()->set('media.upload_limits.custom', null);

        $this->assertEquals(1024, $this->service->getSizeLimit('images'));
        $this->assertEquals(2048, $this->service->getSizeLimit('custom'));
    }

    // =====================================================
    // Size Validation
    // =====================================================

    public function test_validates_file_size_pass(): void
    {
        $result = $this->service->validateSize(500 * 1024, 1024); // 500KB < 1MB
        $this->assertTrue($result['valid']);
        $this->assertNull($result['error']);
    }

    public function test_validates_file_size_exact_boundary(): void
    {
        $result = $this->service->validateSize(1024 * 1024, 1024); // 1MB == 1MB
        $this->assertTrue($result['valid']);
    }

    public function test_validates_file_size_fail(): void
    {
        $result = $this->service->validateSize(2 * 1024 * 1024, 1024); // 2MB > 1MB
        $this->assertFalse($result['valid']);
        $this->assertStringContainsString('exceeds maximum allowed size', $result['error']);
    }

    public function test_validates_size_human_readable_kilobytes(): void
    {
        $result = $this->service->validateSize(500 * 1024, '500K');
        $this->assertTrue($result['valid']);
    }

    public function test_validates_size_human_readable_megabytes(): void
    {
        $result = $this->service->validateSize(500 * 1024, '1M');
        $this->assertTrue($result['valid']);
    }

    public function test_validates_size_human_readable_gigabytes(): void
    {
        $result = $this->service->validateSize(1024 * 1024 * 1024, '1G');
        $this->assertTrue($result['valid']);
    }

    public function test_validates_size_by_category(): void
    {
        $result = $this->service->validateSizeByCategory(5 * 1024 * 1024, 'images');
        $this->assertTrue($result['valid']);

        $result = $this->service->validateSizeByCategory(20 * 1024 * 1024, 'images');
        $this->assertFalse($result['valid']);
    }

    // =====================================================
    // Dangerous Extensions
    // =====================================================

    public function test_detects_dangerous_php_extensions(): void
    {
        $phpExts = ['php', 'php3', 'php4', 'php5', 'php7', 'php8', 'phtml', 'phps'];
        foreach ($phpExts as $ext) {
            $this->assertTrue(
                $this->service->isDangerousExtension("test.{$ext}"),
                "Extension .{$ext} should be flagged as dangerous"
            );
        }
    }

    public function test_detects_dangerous_executable_extensions(): void
    {
        $exeExts = ['exe', 'bat', 'cmd', 'com', 'sh', 'msi', 'vbs', 'vb', 'lnk'];
        foreach ($exeExts as $ext) {
            $this->assertTrue(
                $this->service->isDangerousExtension("test.{$ext}"),
                "Extension .{$ext} should be flagged as dangerous"
            );
        }
    }

    public function test_detects_dangerous_web_extensions(): void
    {
        $webExts = ['html', 'htm', 'shtml', 'xhtml', 'js', 'jsp', 'jspx', 'asp', 'aspx', 'htaccess'];
        foreach ($webExts as $ext) {
            $this->assertTrue(
                $this->service->isDangerousExtension("test.{$ext}"),
                "Extension .{$ext} should be flagged as dangerous"
            );
        }
    }

    public function test_safe_extensions_not_flagged_as_dangerous(): void
    {
        $safeExts = ['jpg', 'png', 'gif', 'pdf', 'doc', 'mp4', 'mp3', 'zip', 'csv', 'txt'];
        foreach ($safeExts as $ext) {
            $this->assertFalse(
                $this->service->isDangerousExtension("test.{$ext}"),
                "Extension .{$ext} should NOT be flagged as dangerous"
            );
        }
    }

    // =====================================================
    // Allowed Extensions
    // =====================================================

    public function test_allowed_extensions_for_images(): void
    {
        $this->assertTrue($this->service->isAllowedExtension('photo.jpg'));
        $this->assertTrue($this->service->isAllowedExtension('photo.png'));
        $this->assertTrue($this->service->isAllowedExtension('photo.gif'));
        $this->assertTrue($this->service->isAllowedExtension('photo.svg'));
        $this->assertTrue($this->service->isAllowedExtension('photo.webp'));
    }

    public function test_allowed_extensions_for_documents(): void
    {
        $this->assertTrue($this->service->isAllowedExtension('file.pdf'));
        $this->assertTrue($this->service->isAllowedExtension('file.doc'));
        $this->assertTrue($this->service->isAllowedExtension('file.docx'));
        $this->assertTrue($this->service->isAllowedExtension('file.xls'));
        $this->assertTrue($this->service->isAllowedExtension('file.xlsx'));
    }

    public function test_dangerous_extensions_never_allowed(): void
    {
        $this->assertFalse($this->service->isAllowedExtension('test.php'));
        $this->assertFalse($this->service->isAllowedExtension('test.exe'));
        $this->assertFalse($this->service->isAllowedExtension('test.sh'));
        $this->assertFalse($this->service->isAllowedExtension('test.bat'));
    }

    public function test_allowed_with_specific_types(): void
    {
        $this->assertTrue($this->service->isAllowedExtension('test.jpg', ['jpg', 'png']));
        $this->assertFalse($this->service->isAllowedExtension('test.gif', ['jpg', 'png']));
    }

    // =====================================================
    // Category Detection
    // =====================================================

    public function test_detects_image_category(): void
    {
        $this->assertEquals('images', $this->service->detectCategoryFromExtension('jpg'));
        $this->assertEquals('images', $this->service->detectCategoryFromExtension('png'));
        $this->assertEquals('images', $this->service->detectCategoryFromExtension('gif'));
        $this->assertEquals('images', $this->service->detectCategoryFromExtension('svg'));
        $this->assertEquals('images', $this->service->detectCategoryFromExtension('webp'));
    }

    public function test_detects_video_category(): void
    {
        $this->assertEquals('videos', $this->service->detectCategoryFromExtension('mp4'));
        $this->assertEquals('videos', $this->service->detectCategoryFromExtension('avi'));
        $this->assertEquals('videos', $this->service->detectCategoryFromExtension('mov'));
    }

    public function test_detects_audio_category(): void
    {
        $this->assertEquals('audios', $this->service->detectCategoryFromExtension('mp3'));
        $this->assertEquals('audios', $this->service->detectCategoryFromExtension('wav'));
    }

    public function test_detects_document_category(): void
    {
        $this->assertEquals('documents', $this->service->detectCategoryFromExtension('pdf'));
        $this->assertEquals('documents', $this->service->detectCategoryFromExtension('doc'));
        $this->assertEquals('documents', $this->service->detectCategoryFromExtension('xlsx'));
    }

    public function test_detects_archive_category(): void
    {
        $this->assertEquals('archives', $this->service->detectCategoryFromExtension('zip'));
        $this->assertEquals('archives', $this->service->detectCategoryFromExtension('rar'));
        $this->assertEquals('archives', $this->service->detectCategoryFromExtension('7z'));
    }

    public function test_unknown_extension_defaults_to_files(): void
    {
        $this->assertEquals('files', $this->service->detectCategoryFromExtension('xyz'));
    }

    // =====================================================
    // MIME Type Mappings
    // =====================================================

    public function test_mime_type_mappings_by_category(): void
    {
        $images = $this->service->getMimeTypeMappings('images');
        $this->assertArrayHasKey('image/jpeg', $images);
        $this->assertArrayHasKey('image/png', $images);
        $this->assertArrayNotHasKey('video/mp4', $images);

        $videos = $this->service->getMimeTypeMappings('videos');
        $this->assertArrayHasKey('video/mp4', $videos);
        $this->assertArrayNotHasKey('image/jpeg', $videos);
    }

    public function test_all_mime_type_mappings(): void
    {
        $all = $this->service->getMimeTypeMappings('all');
        $this->assertArrayHasKey('image/jpeg', $all);
        $this->assertArrayHasKey('video/mp4', $all);
        $this->assertArrayHasKey('audio/mpeg', $all);
        $this->assertArrayHasKey('application/pdf', $all);
        $this->assertArrayHasKey('application/zip', $all);
    }

    public function test_unknown_category_returns_empty(): void
    {
        $this->assertEmpty($this->service->getMimeTypeMappings('nonexistent'));
    }

    // =====================================================
    // MIME Type Detection
    // =====================================================

    public function test_detects_mime_type_of_image(): void
    {
        $file = UploadedFile::fake()->image('test.jpg', 100, 100);
        $path = $file->store('uploads', 'public');
        $fullPath = Storage::disk('public')->path($path);

        $mime = $this->service->getMimeType($fullPath);
        $this->assertNotNull($mime);
        $this->assertStringStartsWith('image/', $mime);
    }

    public function test_returns_null_for_nonexistent_file(): void
    {
        $this->assertNull($this->service->getMimeType('/nonexistent/file.jpg'));
    }

    // =====================================================
    // MIME Type Validation
    // =====================================================

    public function test_validates_valid_image_mime_type(): void
    {
        $file = UploadedFile::fake()->image('test.jpg', 100, 100);
        $path = $file->store('uploads', 'public');
        $fullPath = Storage::disk('public')->path($path);

        $result = $this->service->validateMimeType($fullPath, ['images']);
        $this->assertTrue($result['valid']);
        $this->assertNotNull($result['mime_type']);
    }

    public function test_rejects_image_as_video(): void
    {
        $file = UploadedFile::fake()->image('test.jpg', 100, 100);
        $path = $file->store('uploads', 'public');
        $fullPath = Storage::disk('public')->path($path);

        $result = $this->service->validateMimeType($fullPath, ['videos']);
        $this->assertFalse($result['valid']);
    }

    public function test_validates_nonexistent_file_fails(): void
    {
        $result = $this->service->validateMimeType('/nonexistent/file.jpg', ['images']);
        $this->assertFalse($result['valid']);
        $this->assertEquals('File does not exist', $result['error']);
    }

    // =====================================================
    // Extension-MIME Match Validation
    // =====================================================

    public function test_extension_matches_mime_type(): void
    {
        $file = UploadedFile::fake()->image('test.jpg', 100, 100);
        $path = $file->store('uploads', 'public');
        $fullPath = Storage::disk('public')->path($path);

        $result = $this->service->validateExtensionMatchesMimeType($fullPath);
        $this->assertTrue($result['valid']);
        $this->assertEquals('jpg', $result['actual_extension']);
    }

    // =====================================================
    // Allowed MIME Types
    // =====================================================

    public function test_get_allowed_mime_types(): void
    {
        $mimes = $this->service->getAllowedMimeTypes(['images']);
        $this->assertContains('image/jpeg', $mimes);
        $this->assertContains('image/png', $mimes);
        $this->assertNotContains('video/mp4', $mimes);

        $mimes = $this->service->getAllowedMimeTypes(['images', 'videos']);
        $this->assertContains('image/jpeg', $mimes);
        $this->assertContains('video/mp4', $mimes);
    }

    // =====================================================
    // Validation Rules
    // =====================================================

    public function test_generates_validation_rules(): void
    {
        $rules = $this->service->getValidationRules(['images'], 10240);
        $this->assertArrayHasKey('max', $rules);
        $this->assertArrayHasKey('mimetypes', $rules);
        $this->assertEquals(10240, $rules['max']);
        $this->assertStringContainsString('image/', $rules['mimetypes']);
    }

    public function test_validation_rules_use_category_default_size(): void
    {
        $rules = $this->service->getValidationRules(['images']);
        $this->assertEquals(10240, $rules['max']);
    }

    // =====================================================
    // File Type Checks
    // =====================================================

    public function test_is_image(): void
    {
        $file = UploadedFile::fake()->image('test.jpg', 100, 100);
        $path = $file->store('uploads', 'public');
        $fullPath = Storage::disk('public')->path($path);

        $this->assertTrue($this->service->isImage($fullPath));
        $this->assertFalse($this->service->isVideo($fullPath));
        $this->assertFalse($this->service->isAudio($fullPath));
    }

    // =====================================================
    // Upload Error Messages
    // =====================================================

    public function test_upload_error_messages(): void
    {
        $codes = [
            UPLOAD_ERR_INI_SIZE   => 'upload_max_filesize',
            UPLOAD_ERR_FORM_SIZE  => 'MAX_FILE_SIZE',
            UPLOAD_ERR_PARTIAL    => 'partially uploaded',
            UPLOAD_ERR_NO_FILE    => 'No file',
            UPLOAD_ERR_NO_TMP_DIR => 'temporary folder',
            UPLOAD_ERR_CANT_WRITE => 'write file',
            UPLOAD_ERR_EXTENSION  => 'extension',
        ];

        foreach ($codes as $code => $expectedText) {
            $msg = $this->service->getUploadErrorMessage($code);
            $this->assertStringContainsString($expectedText, $msg);
        }
    }

    // =====================================================
    // Comprehensive Upload Validation
    // =====================================================

    public function test_validate_upload_with_error_code(): void
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
    }

    // =====================================================
    // Allowed Extensions For Category
    // =====================================================

    public function test_allowed_extensions_for_category_images(): void
    {
        $result = $this->service->getAllowedExtensionsForCategory('images');
        $this->assertStringContainsString('jpg', $result);
        $this->assertStringContainsString('png', $result);
    }

    public function test_allowed_extensions_for_category_as_array(): void
    {
        $result = $this->service->getAllowedExtensionsForCategory('images', true);
        $this->assertIsArray($result);
        $this->assertContains('jpg', $result);
        $this->assertContains('png', $result);
    }

    public function test_allowed_extensions_for_category_videos(): void
    {
        $result = $this->service->getAllowedExtensionsForCategory('videos', true);
        $this->assertIsArray($result);
        $this->assertContains('mp4', $result);
    }

    public function test_allowed_extensions_for_category_documents(): void
    {
        $result = $this->service->getAllowedExtensionsForCategory('documents', true);
        $this->assertIsArray($result);
        $this->assertContains('pdf', $result);
    }

    public function test_allowed_extensions_for_category_all(): void
    {
        $result = $this->service->getAllowedExtensionsForCategory('all');
        $this->assertEquals('*', $result);
    }

    // =====================================================
    // Parse Size to KB
    // =====================================================

    public function test_parse_size_to_kb_numeric(): void
    {
        $this->assertEquals(1024, $this->service->parseSizeToKb(1024));
    }

    public function test_parse_size_to_kb_with_suffix(): void
    {
        $this->assertEquals(1024, $this->service->parseSizeToKb('1M'));
        $this->assertEquals(500, $this->service->parseSizeToKb('500K'));
        $this->assertEquals(1048576, $this->service->parseSizeToKb('1G'));
    }
}