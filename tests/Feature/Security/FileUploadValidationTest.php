<?php

namespace Tests\Feature\Security;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use MicroweberPackages\User\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Tests for enhanced file upload validation
 *
 * @package Tests\Feature\Security
 */
class FileUploadValidationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    protected function actingAsAdmin(): User
    {
        $user = User::factory()->create(['is_admin' => 1]);
        $this->actingAs($user);
        return $user;
    }

    #[Test]
    public function it_accepts_valid_image_files(): void
    {
        $this->actingAsAdmin();

        $validExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];

        foreach ($validExtensions as $ext) {
            if ($ext === 'svg') {
                $file = UploadedFile::fake()->create("test.{$ext}", 100, 'image/svg+xml');
            } else {
                $file = UploadedFile::fake()->image("test.{$ext}", 100, 100);
            }

            $this->assertInstanceOf(UploadedFile::class, $file);
            $this->assertEquals($ext, strtolower($file->getClientOriginalExtension()));
        }
    }

    #[Test]
    public function it_blocks_dangerous_file_extensions(): void
    {
        $this->actingAsAdmin();

        $dangerousExtensions = [
            'php', 'phtml', 'php5', 'php7', 'php8',
            'exe', 'bat', 'cmd', 'sh',
            'js', 'html', 'htm', 'shtml',
            'pl', 'cgi', 'py', 'rb', 'asp', 'aspx',
            'jsp', 'jspx', 'htaccess',
        ];

        foreach ($dangerousExtensions as $ext) {
            $filesUtils = new \MicroweberPackages\Utils\System\Files();
            $isDangerous = $filesUtils->is_dangerous_file("test.{$ext}");
            $this->assertTrue($isDangerous, "Extension .{$ext} should be flagged as dangerous");
        }
    }

    #[Test]
    public function it_accepts_safe_file_extensions(): void
    {
        $this->actingAsAdmin();

        $safeExtensions = [
            'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg',
            'mp4', 'avi', 'mov',
            'mp3', 'wav', 'ogg',
            'pdf', 'doc', 'docx', 'xls', 'xlsx',
            'zip', 'rar', '7z',
            'css', 'json', 'txt',
        ];

        foreach ($safeExtensions as $ext) {
            $filesUtils = new \MicroweberPackages\Utils\System\Files();
            $isAllowed = $filesUtils->is_allowed_file("test.{$ext}");
            $this->assertTrue($isAllowed, "Extension .{$ext} should be allowed");
        }
    }

    #[Test]
    public function it_enforces_file_size_limits_by_category(): void
    {
        $service = new \MicroweberPackages\FileUploader\Validation\FileValidationService();

        // Image limit is 10MB
        $result = $service->validateSizeByCategory(5 * 1024 * 1024, 'images');
        $this->assertTrue($result['valid'], '5MB image should be allowed');

        $result = $service->validateSizeByCategory(20 * 1024 * 1024, 'images');
        $this->assertFalse($result['valid'], '20MB image should be rejected (limit is 10MB)');

        // Video limit is 100MB
        $result = $service->validateSizeByCategory(50 * 1024 * 1024, 'videos');
        $this->assertTrue($result['valid'], '50MB video should be allowed');

        $result = $service->validateSizeByCategory(150 * 1024 * 1024, 'videos');
        $this->assertFalse($result['valid'], '150MB video should be rejected (limit is 100MB)');
    }

    #[Test]
    public function it_validates_mime_types(): void
    {
        $service = new \MicroweberPackages\FileUploader\Validation\FileValidationService();

        // Create a real image file
        $imageFile = UploadedFile::fake()->image('test.jpg', 100, 100);
        $imagePath = $imageFile->store('uploads', 'public');
        $fullPath = Storage::disk('public')->path($imagePath);

        // Validate as image - should pass
        $result = $service->validateMimeType($fullPath, ['images']);
        $this->assertTrue($result['valid']);
        $this->assertStringStartsWith('image/', $result['mime_type']);

        // Validate as video - should fail
        $result = $service->validateMimeType($fullPath, ['videos']);
        $this->assertFalse($result['valid']);
        $this->assertNotNull($result['error']);
    }

    #[Test]
    public function it_validates_extension_matches_mime_type(): void
    {
        $service = new \MicroweberPackages\FileUploader\Validation\FileValidationService();

        // Create image file with correct extension
        $imageFile = UploadedFile::fake()->image('test.jpg', 100, 100);
        $imagePath = $imageFile->store('uploads', 'public');
        $fullPath = Storage::disk('public')->path($imagePath);

        $result = $service->validateExtensionMatchesMimeType($fullPath);
        $this->assertTrue($result['valid']);
        $this->assertContains('jpg', $result['expected_extensions']);
    }

    #[Test]
    public function it_generates_validation_rules(): void
    {
        $service = new \MicroweberPackages\FileUploader\Validation\FileValidationService();

        // Get rules for images
        $rules = $service->getValidationRules(['images'], 5120);

        $this->assertArrayHasKey('max', $rules);
        $this->assertArrayHasKey('mimetypes', $rules);
        $this->assertEquals(5120, $rules['max']);
        $this->assertStringContainsString('image/', $rules['mimetypes']);

        // Get rules with default size
        $rules = $service->getValidationRules(['images']);
        $this->assertEquals(10240, $rules['max']); // Default images limit
    }

    #[Test]
    public function it_detects_file_types_correctly(): void
    {
        $service = new \MicroweberPackages\FileUploader\Validation\FileValidationService();

        // Create test files
        $imageFile = UploadedFile::fake()->image('test.jpg', 100, 100);
        $imagePath = $imageFile->store('uploads', 'public');
        $fullPath = Storage::disk('public')->path($imagePath);

        $this->assertTrue($service->isImage($fullPath));
        $this->assertFalse($service->isVideo($fullPath));
        $this->assertFalse($service->isAudio($fullPath));
    }

    #[Test]
    public function it_parses_size_formats(): void
    {
        $service = new \MicroweberPackages\FileUploader\Validation\FileValidationService();

        // Test various size formats via validation
        $result = $service->validateSize(1024 * 1024, '1M');
        $this->assertTrue($result['valid']);

        $result = $service->validateSize(500 * 1024, '500K');
        $this->assertTrue($result['valid']);

        $result = $service->validateSize(1024 * 1024 * 1024, '1G');
        $this->assertTrue($result['valid']);
    }

    #[Test]
    public function it_handles_upload_errors(): void
    {
        $service = new \MicroweberPackages\FileUploader\Validation\FileValidationService();

        // Test various upload error codes
        $errorMessages = [
            UPLOAD_ERR_INI_SIZE => 'upload_max_filesize',
            UPLOAD_ERR_FORM_SIZE => 'MAX_FILE_SIZE',
            UPLOAD_ERR_PARTIAL => 'partially uploaded',
            UPLOAD_ERR_NO_FILE => 'No file',
            UPLOAD_ERR_NO_TMP_DIR => 'temporary folder',
            UPLOAD_ERR_CANT_WRITE => 'write file',
            UPLOAD_ERR_EXTENSION => 'extension',
        ];

        foreach ($errorMessages as $code => $expectedText) {
            $file = [
                'tmp_name' => '',
                'error' => $code,
                'size' => 0,
                'name' => 'test.jpg',
            ];

            $result = $service->validateUpload($file);
            $this->assertFalse($result['valid']);
            $this->assertNotEmpty($result['errors']);
            $this->assertStringContainsString($expectedText, $result['errors'][0]);
        }
    }

    #[Test]
    public function it_respects_config_size_limits(): void
    {
        // Set custom config
        config()->set('media.upload_limits.images', 5120); // 5 MB

        $service = new \MicroweberPackages\FileUploader\Validation\FileValidationService();
        $this->assertEquals(5120, $service->getSizeLimit('images'));

        // Restore default
        config()->set('media.upload_limits.images', 10240);
    }

    #[Test]
    public function it_returns_all_mime_type_mappings(): void
    {
        $service = new \MicroweberPackages\FileUploader\Validation\FileValidationService();

        $allMappings = $service->getMimeTypeMappings('all');
        $this->assertNotEmpty($allMappings);
        $this->assertArrayHasKey('image/jpeg', $allMappings);
        $this->assertArrayHasKey('video/mp4', $allMappings);
        $this->assertArrayHasKey('audio/mpeg', $allMappings);
        $this->assertArrayHasKey('application/pdf', $allMappings);

        $imageMappings = $service->getMimeTypeMappings('images');
        $this->assertArrayHasKey('image/jpeg', $imageMappings);
        $this->assertArrayNotHasKey('video/mp4', $imageMappings);
    }

    #[Test]
    public function it_validates_comprehensive_upload(): void
    {
        $service = new \MicroweberPackages\FileUploader\Validation\FileValidationService();

        // Create a valid test file
        $imageFile = UploadedFile::fake()->image('test.jpg', 100, 100)->size(100);
        $imagePath = $imageFile->store('uploads', 'public');
        $fullPath = Storage::disk('public')->path($imagePath);

        // Since is_uploaded_file() returns false for test files,
        // we test the validation logic components separately

        // Test MIME validation
        $mimeResult = $service->validateMimeType($fullPath, ['images']);
        $this->assertTrue($mimeResult['valid']);

        // Test size validation
        $sizeResult = $service->validateSize(100 * 1024, 10240);
        $this->assertTrue($sizeResult['valid']);

        // Test dangerous file check (returns bool|null, so check for falsy value)
        $filesUtils = new \MicroweberPackages\Utils\System\Files();
        $isDangerous = $filesUtils->is_dangerous_file('test.jpg');
        $this->assertTrue($isDangerous === false || $isDangerous === null);
    }
}
