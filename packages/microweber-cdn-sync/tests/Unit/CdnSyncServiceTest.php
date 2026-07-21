<?php

namespace MicroweberPackages\CdnSync\Tests\Unit;

use MicroweberPackages\CdnSync\Models\CdnSyncLog;
use MicroweberPackages\CdnSync\Services\CdnSyncService;
use MicroweberPackages\CdnSync\Tests\TestCase;

class CdnSyncServiceTest extends TestCase
{
    public function test_service_can_be_resolved(): void
    {
        $service = app('cdn_sync');
        $this->assertInstanceOf(CdnSyncService::class, $service);
    }

    public function test_service_is_singleton(): void
    {
        $a = app('cdn_sync');
        $b = app('cdn_sync');
        $this->assertSame($a, $b);
    }

    public function test_is_not_configured_by_default(): void
    {
        $service = app('cdn_sync');
        $this->assertFalse($service->isConfigured());
    }

    public function test_is_configured_when_credentials_set(): void
    {
        config([
            'cdn-sync.enabled' => true,
            'cdn-sync.key' => 'test-key',
            'cdn-sync.secret' => 'test-secret',
            'cdn-sync.bucket' => 'test-bucket',
        ]);

        $service = new CdnSyncService();
        $this->assertTrue($service->isConfigured());
    }

    public function test_is_not_configured_when_disabled(): void
    {
        config([
            'cdn-sync.enabled' => false,
            'cdn-sync.key' => 'test-key',
            'cdn-sync.secret' => 'test-secret',
            'cdn-sync.bucket' => 'test-bucket',
        ]);

        $service = new CdnSyncService();
        $this->assertFalse($service->isConfigured());
    }

    public function test_sync_returns_error_when_not_configured(): void
    {
        $service = app('cdn_sync');
        $model = new TestSyncableModel(['id' => 1, 'filename' => '/tmp/test.jpg']);

        $result = $service->sync($model);
        $this->assertFalse($result['success']);
        $this->assertContains('CDN sync is not configured.', $result['errors']);
    }

    public function test_sync_returns_error_for_empty_files(): void
    {
        config([
            'cdn-sync.enabled' => true,
            'cdn-sync.key' => 'k',
            'cdn-sync.secret' => 's',
            'cdn-sync.bucket' => 'b',
        ]);

        $service = new CdnSyncService();
        $model = new TestSyncableModel(['id' => 1, 'filename' => '']);

        $result = $service->sync($model);
        $this->assertContains('No files to sync.', $result['errors']);
    }

    public function test_get_stats_returns_expected_structure(): void
    {
        CdnSyncLog::query()->delete();

        $service = app('cdn_sync');
        $stats = $service->getStats();

        $this->assertArrayHasKey('total_synced', $stats);
        $this->assertArrayHasKey('configured', $stats);
        $this->assertArrayHasKey('by_type', $stats);
        $this->assertEquals(0, $stats['total_synced']);
    }

    public function test_test_connection_fails_when_not_configured(): void
    {
        $service = app('cdn_sync');
        $result = $service->testConnection();

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('not configured', $result['message']);
    }

    public function test_cdn_sync_log_migration_creates_table(): void
    {
        $this->assertTrue(
            \Illuminate\Support\Facades\Schema::hasTable('cdn_sync_log'),
            'cdn_sync_log table should exist after migration'
        );
    }

    public function test_cdn_sync_log_table_has_expected_columns(): void
    {
        $columns = ['id', 'rel_type', 'rel_id', 'local_path', 'cdn_path', 'cdn_url',
            'disk', 'bucket', 'etag', 'content_type', 'file_size', 'file_hash',
            'is_synced', 'synced_at', 'metadata', 'created_at', 'updated_at'];

        foreach ($columns as $column) {
            $this->assertTrue(
                \Illuminate\Support\Facades\Schema::hasColumn('cdn_sync_log', $column),
                "Column {$column} should exist in cdn_sync_log table"
            );
        }
    }

    public function test_cdn_sync_log_model_can_be_created(): void
    {
        $log = CdnSyncLog::create([
            'rel_type' => 'App\\Models\\Media',
            'rel_id' => 1,
            'local_path' => '/tmp/test.jpg',
            'cdn_path' => 'cdn-sync/media/2026/07/test.jpg',
            'cdn_url' => 'https://bucket.s3.amazonaws.com/cdn-sync/media/2026/07/test.jpg',
            'disk' => 'cdn',
            'bucket' => 'test-bucket',
            'is_synced' => true,
            'synced_at' => now(),
        ]);

        $this->assertDatabaseHas('cdn_sync_log', [
            'id' => $log->id,
            'rel_type' => 'App\\Models\\Media',
            'rel_id' => 1,
            'is_synced' => true,
        ]);
    }

    public function test_cdn_sync_log_scopes(): void
    {
        // Clean up any prior state
        CdnSyncLog::query()->delete();

        CdnSyncLog::create([
            'rel_type' => 'Media', 'rel_id' => 1,
            'local_path' => '/a.jpg', 'cdn_path' => 'a.jpg',
            'is_synced' => true, 'synced_at' => now(),
        ]);
        CdnSyncLog::create([
            'rel_type' => 'Media', 'rel_id' => 2,
            'local_path' => '/b.jpg', 'cdn_path' => 'b.jpg',
            'is_synced' => false,
        ]);
        CdnSyncLog::create([
            'rel_type' => 'Product', 'rel_id' => 3,
            'local_path' => '/c.jpg', 'cdn_path' => 'c.jpg',
            'is_synced' => true, 'synced_at' => now(),
        ]);

        $this->assertEquals(2, CdnSyncLog::synced()->count());
        $this->assertEquals(2, CdnSyncLog::forType('Media')->count());
        $this->assertEquals(1, CdnSyncLog::forModel('Media', 1)->count());
    }

    public function test_get_config_value_returns_config(): void
    {
        // Clear any MW options that might override config
        if (function_exists('save_option')) {
            save_option('cdn_sync_key', '', 'cdn_sync');
        }

        config(['cdn-sync.key' => 'from-config']);

        $service = new CdnSyncService();
        $this->assertEquals('from-config', $service->getConfigValue('key'));
    }

    public function test_bulk_sync_returns_expected_structure(): void
    {
        $service = app('cdn_sync');
        $results = $service->bulkSync([]);

        $this->assertEquals(0, $results['total']);
        $this->assertEquals(0, $results['success']);
        $this->assertEquals(0, $results['failed']);
    }

    public function test_delete_returns_true_when_no_logs(): void
    {
        $service = app('cdn_sync');
        $model = new TestSyncableModel(['id' => 999, 'filename' => '/nope.jpg']);

        $this->assertTrue($service->delete($model));
    }

    public function test_sync_file_returns_false_for_missing_file(): void
    {
        config([
            'cdn-sync.enabled' => true,
            'cdn-sync.key' => 'k',
            'cdn-sync.secret' => 's',
            'cdn-sync.bucket' => 'b',
        ]);

        $service = new CdnSyncService();
        $model = new TestSyncableModel(['id' => 1, 'filename' => '/nonexistent/path/file.jpg']);

        $result = $service->syncFile($model, '/nonexistent/path/file.jpg');
        $this->assertFalse($result);
    }
}

/**
 * A simple test model that implements CdnSyncable.
 */
class TestSyncableModel extends \Illuminate\Database\Eloquent\Model implements \MicroweberPackages\CdnSync\Contracts\CdnSyncable
{
    use \MicroweberPackages\CdnSync\Traits\HasCdnSync;

    protected $guarded = [];
    public $timestamps = false;

    public function getCdnSyncFiles(): array
    {
        if (empty($this->filename)) {
            return [];
        }
        return [$this->filename];
    }

    public function getCdnRelType(): string
    {
        return 'test_model';
    }

    public function getCdnRelId(): int|string
    {
        return $this->id ?? 0;
    }
}