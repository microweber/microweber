<?php

namespace MicroweberPackages\CdnSync\Tests\Unit;

use Illuminate\Support\Facades\Schema;
use MicroweberPackages\CdnSync\Contracts\CdnSyncable;
use MicroweberPackages\CdnSync\Models\CdnSyncLog;
use MicroweberPackages\CdnSync\Tests\TestCase;
use MicroweberPackages\CdnSync\Traits\HasCdnSync;

class HasCdnSyncTraitTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Create a test table for the trait model if it doesn't exist
        if (!Schema::hasTable('trait_test_models')) {
            Schema::create('trait_test_models', function ($table) {
                $table->id();
                $table->string('filename')->nullable();
                $table->timestamps();
            });
        }

        // Clean up prior data
        CdnSyncLog::query()->delete();
        \Illuminate\Support\Facades\DB::table('trait_test_models')->truncate();
    }

    protected function tearDown(): void
    {
        CdnSyncLog::query()->delete();
        Schema::dropIfExists('trait_test_models');
        parent::tearDown();
    }

    public function test_trait_provides_cdn_sync_logs_relation(): void
    {
        $model = TraitTestModel::create(['filename' => '/test.jpg']);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\MorphMany::class, $model->cdnSyncLogs());
    }

    public function test_is_not_fully_synced_when_no_logs(): void
    {
        $model = TraitTestModel::create(['filename' => '/test.jpg']);
        $this->assertFalse($model->isFullySyncedToCdn());
    }

    public function test_is_fully_synced_when_all_files_logged(): void
    {
        $model = TraitTestModel::create(['filename' => '/test.jpg']);

        CdnSyncLog::create([
            'rel_type' => TraitTestModel::class,
            'rel_id' => $model->id,
            'local_path' => '/test.jpg',
            'cdn_path' => 'cdn/test.jpg',
            'cdn_url' => 'https://cdn.test/test.jpg',
            'is_synced' => true,
            'synced_at' => now(),
        ]);

        $this->assertTrue($model->isFullySyncedToCdn());
    }

    public function test_has_any_cdn_sync(): void
    {
        $model = TraitTestModel::create(['filename' => '/test.jpg']);
        $this->assertFalse($model->hasAnyCdnSync());

        CdnSyncLog::create([
            'rel_type' => TraitTestModel::class,
            'rel_id' => $model->id,
            'local_path' => '/test.jpg',
            'cdn_path' => 'cdn/test.jpg',
            'is_synced' => true,
            'synced_at' => now(),
        ]);

        $this->assertTrue($model->hasAnyCdnSync());
    }

    public function test_get_cdn_url_returns_null_when_not_synced(): void
    {
        $model = TraitTestModel::create(['filename' => '/test.jpg']);
        $this->assertNull($model->getCdnUrl());
    }

    public function test_get_cdn_url_returns_url_when_synced(): void
    {
        $model = TraitTestModel::create(['filename' => '/test.jpg']);

        CdnSyncLog::create([
            'rel_type' => TraitTestModel::class,
            'rel_id' => $model->id,
            'local_path' => '/test.jpg',
            'cdn_path' => 'cdn/test.jpg',
            'cdn_url' => 'https://cdn.test/test.jpg',
            'is_synced' => true,
            'synced_at' => now(),
        ]);

        $this->assertEquals('https://cdn.test/test.jpg', $model->getCdnUrl());
    }

    public function test_get_cdn_url_for_specific_path(): void
    {
        $model = TraitTestModel::create(['filename' => '/test.jpg']);

        CdnSyncLog::create([
            'rel_type' => TraitTestModel::class,
            'rel_id' => $model->id,
            'local_path' => '/test.jpg',
            'cdn_path' => 'cdn/test.jpg',
            'cdn_url' => 'https://cdn.test/test.jpg',
            'is_synced' => true,
            'synced_at' => now(),
        ]);

        $this->assertEquals('https://cdn.test/test.jpg', $model->getCdnUrl('/test.jpg'));
        $this->assertNull($model->getCdnUrl('/other.jpg'));
    }

    public function test_scope_synced_to_cdn(): void
    {
        $synced = TraitTestModel::create(['filename' => '/a.jpg']);
        TraitTestModel::create(['filename' => '/b.jpg']);

        CdnSyncLog::create([
            'rel_type' => TraitTestModel::class,
            'rel_id' => $synced->id,
            'local_path' => '/a.jpg',
            'cdn_path' => 'cdn/a.jpg',
            'is_synced' => true,
            'synced_at' => now(),
        ]);

        $this->assertEquals(1, TraitTestModel::syncedToCdn()->count());
        $this->assertEquals(1, TraitTestModel::notSyncedToCdn()->count());
    }

    public function test_default_get_cdn_rel_type(): void
    {
        $model = new TraitTestModel();
        $this->assertEquals(TraitTestModel::class, $model->getCdnRelType());
    }

    public function test_default_get_cdn_sync_files_uses_filename(): void
    {
        $model = new TraitTestModel(['filename' => '/test.jpg']);
        $this->assertEquals(['/test.jpg'], $model->getCdnSyncFiles());
    }

    public function test_default_get_cdn_sync_files_returns_empty_when_no_file(): void
    {
        $model = new TraitTestModel();
        $this->assertEquals([], $model->getCdnSyncFiles());
    }
}

class TraitTestModel extends \Illuminate\Database\Eloquent\Model implements CdnSyncable
{
    use HasCdnSync;

    protected $table = 'trait_test_models';
    protected $guarded = [];
}