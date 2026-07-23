<?php

namespace MicroweberPackages\MediaThumbnail\Tests\Feature;

use MicroweberPackages\MediaThumbnail\Models\MediaThumbnail;
use MicroweberPackages\MediaThumbnail\Repositories\MediaThumbnailRepository;
use MicroweberPackages\MediaThumbnail\Tests\TestCase;

class MediaThumbnailRepositoryTest extends TestCase
{
    protected MediaThumbnailRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = app(MediaThumbnailRepository::class);
    }

    public function test_store_creates_model_and_returns_it(): void
    {
        $model = $this->repository->store('tn-store-test', [
            'src'   => '/images/store.jpg',
            'width' => 200,
        ]);

        $this->assertInstanceOf(MediaThumbnail::class, $model);
        $this->assertEquals('tn-store-test', $model->filename);
        $this->assertNotNull($model->uuid);
    }

    public function test_find_by_filename_returns_cached_result(): void
    {
        $this->repository->store('tn-cached-find', [
            'src' => '/cached.jpg',
        ]);

        $result = $this->repository->findByFilename('tn-cached-find');
        $this->assertNotNull($result);
        $this->assertEquals('tn-cached-find', $result['filename']);

        // Second call should come from cache
        $result2 = $this->repository->findByFilename('tn-cached-find');
        $this->assertEquals($result, $result2);
    }

    public function test_find_by_uuid(): void
    {
        $model = $this->repository->store('tn-uuid-find', [
            'src' => '/uuid.jpg',
        ]);

        $found = $this->repository->findByUuid($model->uuid);
        $this->assertNotNull($found);
        $this->assertEquals($model->id, $found->id);
    }

    public function test_remove_by_filename(): void
    {
        $this->repository->store('tn-remove-repo', [
            'src' => '/remove.jpg',
        ]);

        $deleted = $this->repository->removeByFilename('tn-remove-repo');
        $this->assertEquals(1, $deleted);

        $result = $this->repository->findByFilename('tn-remove-repo');
        $this->assertNull($result);
    }

    public function test_prune_older_than(): void
    {
        $model = $this->repository->store('tn-prune-test', [
            'src' => '/prune.jpg',
        ]);

        // Set created_at to 100 days ago
        MediaThumbnail::where('id', $model->id)->update([
            'created_at' => now()->subDays(100),
        ]);

        $pruned = $this->repository->pruneOlderThan(now()->subDays(90));
        $this->assertEquals(1, $pruned);
    }
}