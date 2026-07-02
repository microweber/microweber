<?php

namespace MicroweberPackages\FileUploader\Tests;

use MicroweberPackages\FileUploader\FileUploaderService;
use MicroweberPackages\FileUploader\Validation\FileValidationService;

class ServiceProviderTest extends TestCase
{
    public function test_file_uploader_is_bound_in_container(): void
    {
        $this->assertTrue(app()->bound('file_uploader'));
    }

    public function test_file_uploader_resolves_to_correct_class(): void
    {
        $this->assertInstanceOf(FileUploaderService::class, app('file_uploader'));
    }

    public function test_validation_service_is_bound(): void
    {
        $this->assertInstanceOf(FileValidationService::class, app(FileValidationService::class));
    }

    public function test_file_uploader_service_class_is_bound(): void
    {
        $this->assertInstanceOf(FileUploaderService::class, app(FileUploaderService::class));
    }

    public function test_config_is_published(): void
    {
        $config = config('file-uploader');
        $this->assertNotNull($config);
        $this->assertArrayHasKey('disk', $config);
        $this->assertArrayHasKey('upload_path', $config);
        $this->assertArrayHasKey('size_limits', $config);
        $this->assertArrayHasKey('dangerous_extensions', $config);
        $this->assertArrayHasKey('mime_type_mappings', $config);
        $this->assertArrayHasKey('allowed_extensions', $config);
    }

    public function test_default_config_values(): void
    {
        $this->assertEquals('public', config('file-uploader.disk'));
        $this->assertEquals('uploads', config('file-uploader.upload_path'));
        $this->assertIsArray(config('file-uploader.size_limits'));
        $this->assertIsArray(config('file-uploader.dangerous_extensions'));
    }
}