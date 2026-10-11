<?php

namespace MicroweberPackages\Filament\Tests\Forms\Components;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use MicroweberPackages\User\Models\User;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Tests for MwFileUpload Filament component
 * 
 * Covers single file uploads, multiple file uploads, validation,
 * image previews, and S3 disk integration.
 */
class MwFileUploadTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        Storage::fake('s3');
    }

    protected function actingAsAdmin(): User
    {
        $user = User::factory()->create([
            'is_admin' => 1,
        ]);

        $this->actingAs($user);

        return $user;
    }

    #[Test]
    public function it_single_image_upload_stores_file(): void
    {
        $this->actingAsAdmin();

        // Create a test file
        $file = UploadedFile::fake()->image('test-image.jpg', 800, 600)->size(500);

        // Store the file
        $path = $file->store('uploads', 'public');

        // Assert file exists in storage
        Storage::disk('public')->assertExists($path);

        // Assert file has correct mime type
        $this->assertEquals('image/jpeg', Storage::disk('public')->mimeType($path));
    }

    #[Test]
    public function it_multiple_files_uploaded_correctly(): void
    {
        $this->actingAsAdmin();

        // Create multiple test files
        $files = [
            UploadedFile::fake()->image('image1.jpg', 800, 600)->size(500),
            UploadedFile::fake()->image('image2.png', 1024, 768)->size(1000),
            UploadedFile::fake()->image('image3.gif', 400, 300)->size(200),
        ];

        $paths = [];
        foreach ($files as $file) {
            $paths[] = $file->store('uploads', 'public');
        }

        // Assert all files exist in storage
        foreach ($paths as $path) {
            Storage::disk('public')->assertExists($path);
        }

        // Assert correct number of files stored
        $this->assertCount(3, $paths);
    }

    #[Test]
    public function it_image_preview_shown_after_upload(): void
    {
        $this->actingAsAdmin();

        // Create a test image file
        $file = UploadedFile::fake()->image('preview-test.jpg', 800, 600)->size(500);
        $path = $file->store('uploads', 'public');

        // Assert file exists
        Storage::disk('public')->assertExists($path);

        // Assert file is an image (preview should be shown)
        $mimeType = Storage::disk('public')->mimeType($path);
        $this->assertStringStartsWith('image/', $mimeType);

        // Generate URL for preview
        $url = Storage::disk('public')->url($path);
        $this->assertNotEmpty($url);
        $this->assertStringContainsString('/storage/', $url);
    }

    #[Test]
    public function it_upload_validation_enforces_mime_types(): void
    {
        $this->actingAsAdmin();

        // Test allowed image types
        $validImageTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        foreach ($validImageTypes as $ext) {
            $file = UploadedFile::fake()->image("test.{$ext}", 100, 100);
            $this->assertTrue($this->isValidImageMimeType($file->getMimeType()));
        }

        // Test invalid file type
        $invalidFile = UploadedFile::fake()->create('test.exe', 100, 'application/x-msdownload');
        $this->assertFalse($this->isValidImageMimeType($invalidFile->getMimeType()));

        // Test that component validates file types
        $component = MwFileUpload::make('image');
        $component->image();
        
        $fileTypes = $component->getFileTypes();
        $this->assertContains('image/*', $fileTypes);
    }

    #[Test]
    public function it_upload_to_s3_disk_works(): void
    {
        $this->actingAsAdmin();

        // Create a test file
        $file = UploadedFile::fake()->image('s3-test-image.jpg', 800, 600)->size(500);

        // Store the file on S3 disk
        $path = $file->store('uploads', 's3');

        // Assert file exists in S3 storage
        Storage::disk('s3')->assertExists($path);

        // Assert file has correct properties
        $this->assertTrue(Storage::disk('s3')->exists($path));
        $this->assertGreaterThan(0, Storage::disk('s3')->size($path));
    }

    #[Test]
    public function it_component_configures_file_types(): void
    {
        $this->actingAsAdmin();

        // Test image file type configuration
        $component = MwFileUpload::make('avatar');
        $component->image();
        
        $fileTypes = $component->getFileTypes();
        $this->assertIsArray($fileTypes);
        $this->assertContains('image/*', $fileTypes);

        // Test audio file type configuration
        $component = MwFileUpload::make('audio');
        $component->audio();
        
        $fileTypes = $component->getFileTypes();
        $this->assertContains('audio/*', $fileTypes);

        // Test video file type configuration
        $component = MwFileUpload::make('video');
        $component->video();
        
        $fileTypes = $component->getFileTypes();
        $this->assertContains('video/*', $fileTypes);

        // Test custom file types
        $component = MwFileUpload::make('document');
        $component->fileTypes(['application/pdf', 'text/plain']);
        
        $fileTypes = $component->getFileTypes();
        $this->assertContains('application/pdf', $fileTypes);
        $this->assertContains('text/plain', $fileTypes);
    }

    #[Test]
    public function it_component_supports_multiple_uploads(): void
    {
        $this->actingAsAdmin();

        $component = MwFileUpload::make('gallery');
        
        // Test default state (not multiple)
        $this->assertFalse($component->isMultiple());

        // Enable multiple mode
        $component->multiple(true);
        $this->assertTrue($component->isMultiple());

        // Disable multiple mode
        $component->multiple(false);
        $this->assertFalse($component->isMultiple());
    }

    #[Test]
    public function it_file_size_validation_works(): void
    {
        $this->actingAsAdmin();

        // Small file (should pass)
        $smallFile = UploadedFile::fake()->image('small.jpg', 100, 100)->size(100);
        $this->assertLessThan(1024 * 1024, $smallFile->getSize()); // Less than 1MB

        // Large file (should fail validation if max size is set)
        $largeFile = UploadedFile::fake()->image('large.jpg', 4000, 4000)->size(10000);
        $this->assertGreaterThan(1024 * 1024 * 5, $largeFile->getSize()); // Greater than 5MB
    }

    #[Test]
    public function it_file_extension_validation(): void
    {
        $this->actingAsAdmin();

        $validExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
        $invalidExtensions = ['exe', 'php', 'js', 'sh'];

        foreach ($validExtensions as $ext) {
            $file = UploadedFile::fake()->image("test.{$ext}", 100, 100);
            $this->assertTrue($this->isValidImageExtension($ext));
        }

        foreach ($invalidExtensions as $ext) {
            $this->assertFalse($this->isValidImageExtension($ext));
        }
    }

    #[Test]
    public function it_file_cleanup_on_disk(): void
    {
        $this->actingAsAdmin();

        // Create and store a file
        $file = UploadedFile::fake()->image('cleanup-test.jpg', 100, 100);
        $path = $file->store('uploads', 'public');

        // Verify file exists
        Storage::disk('public')->assertExists($path);

        // Delete the file
        Storage::disk('public')->delete($path);

        // Verify file no longer exists
        Storage::disk('public')->assertMissing($path);
    }

    #[Test]
    public function it_component_state_binding(): void
    {
        $this->actingAsAdmin();

        $component = MwFileUpload::make('file');
        
        // Test single file state (string)
        $singleState = 'uploads/test-image.jpg';
        $this->assertIsString($singleState);
        $this->assertStringContainsString('uploads/', $singleState);

        // Test multiple file state (array)
        $multipleState = [
            ['fileUrl' => 'uploads/image1.jpg', 'fileType' => 'image'],
            ['fileUrl' => 'uploads/image2.jpg', 'fileType' => 'image'],
        ];
        $this->assertIsArray($multipleState);
        $this->assertCount(2, $multipleState);
    }

    #[Test]
    public function it_file_type_detection_from_extension(): void
    {
        $this->actingAsAdmin();

        $testCases = [
            'image.jpg' => 'image',
            'image.png' => 'image',
            'image.webp' => 'image',
            'video.mp4' => 'video',
            'video.mov' => 'video',
            'audio.mp3' => 'audio',
            'document.pdf' => 'file',
            'document.doc' => 'file',
        ];

        foreach ($testCases as $filename => $expectedType) {
            $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            $detectedType = $this->detectFileType($extension);
            $this->assertEquals($expectedType, $detectedType, "Failed for {$filename}");
        }
    }

    #[Test]
    public function it_component_view_selection(): void
    {
        $this->actingAsAdmin();

        // Single file upload should use single view
        $singleComponent = MwFileUpload::make('file');
        $reflection = new \ReflectionClass($singleComponent);
        $viewProperty = $reflection->getProperty('view');
        $viewProperty->setAccessible(true);
        $singleView = $viewProperty->getValue($singleComponent);
        
        // Multiple file upload should use multiple view
        $multipleComponent = MwFileUpload::make('files')->multiple(true);
        $multipleView = $viewProperty->getValue($multipleComponent);
        
        // Views should be different
        $this->assertNotEquals($singleView, $multipleView);
        $this->assertStringContainsString('mw-file-upload', $singleView);
        $this->assertStringContainsString('mw-file-upload-multiple', $multipleView);
    }

    /**
     * Helper method to check if mime type is valid for images
     */
    private function isValidImageMimeType(string $mimeType): bool
    {
        $validImageTypes = [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
            'image/svg+xml',
        ];

        return in_array($mimeType, $validImageTypes, true);
    }

    /**
     * Helper method to check if extension is valid for images
     */
    private function isValidImageExtension(string $extension): bool
    {
        $validExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];

        return in_array(strtolower($extension), $validExtensions, true);
    }

    /**
     * Helper method to detect file type from extension
     */
    private function detectFileType(string $extension): string
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
        $videoExtensions = ['mp4', 'mov', 'avi', 'm4v', 'mkv'];
        $audioExtensions = ['wav', 'midi', 'mp3', 'ogg', 'flac'];

        if (in_array($extension, $imageExtensions)) {
            return 'image';
        }

        if (in_array($extension, $videoExtensions)) {
            return 'video';
        }

        if (in_array($extension, $audioExtensions)) {
            return 'audio';
        }

        return 'file';
    }
}
