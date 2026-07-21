<?php

namespace MicroweberPackages\CdnSync\Tests\Feature;

use Illuminate\Support\Facades\Schema;
use MicroweberPackages\CdnSync\Contracts\CdnSyncable;
use MicroweberPackages\CdnSync\Models\CdnSyncLog;
use MicroweberPackages\CdnSync\Services\CdnSyncService;
use MicroweberPackages\CdnSync\Tests\TestCase;
use MicroweberPackages\CdnSync\Traits\HasCdnSync;

class CdnSyncIntegrationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('integration_test_items', function ($table) {
            $table->id();
            $table->string('filename')->nullable();
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('integration_test_items');
        parent::tearDown();
    }

    public function test_full_sync_flow_with_real_file(): void
    {
        // Skip if CDN not configured via env
        $key = env('CDN_SYNC_KEY');
        $secret = env('CDN_SYNC_SECRET');
        $bucket = env('CDN_SYNC_BUCKET');

        if (empty($key) || empty($secret) || empty($bucket)) {
            $this->markTestSkipped('CDN_SYNC_KEY, CDN_SYNC_SECRET, and CDN_SYNC_BUCKET env vars required for integration test.');
        }

        config([
            'cdn-sync.enabled' => true,
            'cdn-sync.key' => $key,
            'cdn-sync.secret' => $secret,
            'cdn-sync.bucket' => $bucket,
            'cdn-sync.region' => env('CDN_SYNC_REGION', 'us-east-1'),
            'cdn-sync.endpoint' => env('CDN_SYNC_ENDPOINT', ''),
            'cdn-sync.use_path_style_endpoint' => env('CDN_SYNC_USE_PATH_STYLE', false),
        ]);

        // Create a test file
        $tmpFile = tempnam(sys_get_temp_dir(), 'cdn_test_');
        file_put_contents($tmpFile, 'CDN sync integration test content - ' . uniqid());

        $model = IntegrationTestItem::create(['filename' => $tmpFile]);

        $service = new CdnSyncService();

        // Test connection
        $connResult = $service->testConnection();
        $this->assertTrue($connResult['success'], 'Connection test failed: ' . ($connResult['message'] ?? ''));

        // Sync file
        $result = $service->sync($model);
        $this->assertTrue($result['success'], 'Sync failed: ' . json_encode($result['errors']));
        $this->assertNotEmpty($result['synced']);

        // Verify log created
        $this->assertDatabaseHas('cdn_sync_log', [
            'rel_type' => IntegrationTestItem::class,
            'rel_id' => $model->id,
            'is_synced' => true,
        ]);

        // Verify model reports synced
        $this->assertTrue($model->isFullySyncedToCdn());
        $this->assertNotNull($model->getCdnUrl());

        // Cleanup: delete from CDN
        $service->delete($model);

        // Cleanup local
        @unlink($tmpFile);
    }

    public function test_bulk_sync_with_multiple_models(): void
    {
        config([
            'cdn-sync.enabled' => false, // Not configured
        ]);

        $models = [];
        for ($i = 0; $i < 3; $i++) {
            $models[] = IntegrationTestItem::create(['filename' => '/tmp/test' . $i . '.jpg']);
        }

        $service = new CdnSyncService();
        $results = $service->bulkSync($models);

        $this->assertEquals(3, $results['total']);
        $this->assertEquals(0, $results['success']);
        $this->assertEquals(3, $results['failed']);
    }

    public function test_stats_reflect_sync_log(): void
    {
        CdnSyncLog::query()->delete();

        CdnSyncLog::create([
            'rel_type' => IntegrationTestItem::class,
            'rel_id' => 1,
            'local_path' => '/a.jpg',
            'cdn_path' => 'cdn/a.jpg',
            'is_synced' => true,
            'synced_at' => now(),
        ]);
        CdnSyncLog::create([
            'rel_type' => 'OtherModel',
            'rel_id' => 2,
            'local_path' => '/b.jpg',
            'cdn_path' => 'cdn/b.jpg',
            'is_synced' => true,
            'synced_at' => now(),
        ]);

        $service = new CdnSyncService();
        $stats = $service->getStats();

        $this->assertEquals(2, $stats['total_synced']);
        $this->assertArrayHasKey(IntegrationTestItem::class, $stats['by_type']);
        $this->assertArrayHasKey('OtherModel', $stats['by_type']);
    }
}

class IntegrationTestItem extends \Illuminate\Database\Eloquent\Model implements CdnSyncable
{
    use HasCdnSync;

    protected $table = 'integration_test_items';
    protected $guarded = [];

    public function getCdnSyncFiles(): array
    {
        return $this->filename ? [$this->filename] : [];
    }

    public function getCdnRelType(): string
    {
        return static::class;
    }

    public function getCdnRelId(): int|string
    {
        return $this->getKey();
    }
}