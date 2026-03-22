<?php

namespace Modules\Media\Tests\Unit\Services;

use Modules\Media\Services\BulkUploadService;
use PHPUnit\Framework\TestCase;

class BulkUploadServiceTest extends TestCase
{
    protected BulkUploadService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new BulkUploadService();
    }

    public function test_can_instantiate_service()
    {
        $this->assertInstanceOf(BulkUploadService::class, $this->service);
    }

    public function test_upload_batch_returns_result_structure()
    {
        $files = [];
        $result = $this->service->uploadBatch($files);

        $this->assertArrayHasKey('success', $result);
        $this->assertArrayHasKey('failed', $result);
        $this->assertArrayHasKey('total', $result);
        $this->assertArrayHasKey('processed', $result);
    }

    public function test_upload_batch_handles_empty_files()
    {
        $result = $this->service->uploadBatch([]);

        $this->assertEquals(0, $result['total']);
        $this->assertEquals(0, $result['processed']);
        $this->assertEmpty($result['success']);
        $this->assertEmpty($result['failed']);
    }

    public function test_upload_batch_handles_invalid_files()
    {
        $files = [
            'not-a-file',
            123,
            null,
        ];

        $result = $this->service->uploadBatch($files);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('total', $result);
        $this->assertArrayHasKey('processed', $result);
    }

    public function test_get_progress_returns_correct_structure()
    {
        $progress = $this->service->getProgress();

        $this->assertArrayHasKey('total', $progress);
        $this->assertArrayHasKey('processed', $progress);
        $this->assertArrayHasKey('success_count', $progress);
        $this->assertArrayHasKey('failed_count', $progress);
    }

    public function test_service_has_expected_methods()
    {
        $this->assertTrue(method_exists($this->service, 'uploadBatch'));
        $this->assertTrue(method_exists($this->service, 'getProgress'));
        $this->assertTrue(method_exists($this->service, 'createDefaultFolders'));
    }
}
