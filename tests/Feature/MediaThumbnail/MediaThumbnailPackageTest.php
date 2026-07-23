<?php

namespace Tests\Feature\MediaThumbnail;

use MicroweberPackages\MediaThumbnail\Models\MediaThumbnail;
use MicroweberPackages\MediaThumbnail\Repositories\MediaThumbnailRepository;
use MicroweberPackages\Thumbnailer\ThumbnailGenerator;
use Tests\TestCase;

/**
 * Integration tests for microweber-media-thumbnail package within the CMS.
 */
class MediaThumbnailPackageTest extends TestCase
{
    public function test_service_provider_registers_repository(): void
    {
        $this->assertInstanceOf(
            MediaThumbnailRepository::class,
            app(MediaThumbnailRepository::class)
        );
    }

    public function test_service_provider_registers_thumbnail_generator(): void
    {
        $this->assertInstanceOf(
            ThumbnailGenerator::class,
            app(ThumbnailGenerator::class)
        );
    }

    public function test_media_thumbnails_table_exists(): void
    {
        $this->assertTrue(
            \Illuminate\Support\Facades\Schema::hasTable('media_thumbnails')
        );
    }

    public function test_create_media_thumbnail_via_repository(): void
    {
        $repo = app(MediaThumbnailRepository::class);

        $model = $repo->store('tn-cms-test-' . uniqid(), [
            'src'   => '/images/test.jpg',
            'width' => 200,
        ]);

        $this->assertNotNull($model->id);
        $this->assertNotNull($model->uuid);

        // Cleanup
        $repo->removeByFilename($model->filename);
    }

    public function test_find_by_filename_via_repository(): void
    {
        $repo  = app(MediaThumbnailRepository::class);
        $fname = 'tn-find-cms-' . uniqid();

        $repo->store($fname, ['src' => '/test.jpg', 'width' => 100]);

        $found = $repo->findByFilename($fname);
        $this->assertNotNull($found);
        $this->assertEquals($fname, $found['filename']);

        $repo->removeByFilename($fname);
    }

    public function test_find_by_uuid_via_repository(): void
    {
        $repo = app(MediaThumbnailRepository::class);

        $model = $repo->store('tn-uuid-cms-' . uniqid(), [
            'src' => '/test.jpg',
        ]);

        $found = $repo->findByUuid($model->uuid);
        $this->assertNotNull($found);
        $this->assertEquals($model->id, $found->id);

        $repo->removeByFilename($model->filename);
    }

    public function test_cms_media_thumbnail_model_extends_package(): void
    {
        $cmsModel = new \Modules\Media\Models\MediaThumbnail();

        $this->assertInstanceOf(
            MediaThumbnail::class,
            $cmsModel
        );
    }

    public function test_cms_legacy_query_cached_item_works(): void
    {
        $repo = app(MediaThumbnailRepository::class);
        $fname = 'tn-legacy-' . uniqid();

        $repo->store($fname, ['src' => '/legacy.jpg', 'width' => 150]);

        // Use the CMS legacy method
        $result = \Modules\Media\Models\MediaThumbnail::queryCachedItem($fname);

        $this->assertIsArray($result);
        $this->assertEquals($fname, $result['filename']);

        $repo->removeByFilename($fname);
    }

    public function test_media_repository_get_thumbnail_cached_item(): void
    {
        $repo = app(MediaThumbnailRepository::class);
        $fname = 'tn-media-repo-' . uniqid();

        $repo->store($fname, ['src' => '/repo.jpg', 'width' => 250]);

        // Use the CMS media repository
        $result = app()->media_repository->getThumbnailCachedItem($fname);

        $this->assertIsArray($result);
        $this->assertEquals($fname, $result['filename']);

        $repo->removeByFilename($fname);
    }

    public function test_pixum_route_returns_image(): void
    {
        $response = $this->get(route('media-pixum.serve', ['width' => 10, 'height' => 10]));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'image/png');
    }

    public function test_thumbnail_route_fallback(): void
    {
        $response = $this->get(route('media-thumbnail.thumbnail'));

        $response->assertStatus(200);
    }

    public function test_generate_by_uuid_route_with_missing_uuid(): void
    {
        $response = $this->get(route('media-thumbnail.generate', ['uuid' => 'nonexistent']));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'image/png');
    }

    public function test_prune_old_thumbnails(): void
    {
        $repo = app(MediaThumbnailRepository::class);
        $fname = 'tn-prune-' . uniqid();

        $model = $repo->store($fname, ['src' => '/prune.jpg']);

        MediaThumbnail::where('id', $model->id)->update([
            'created_at' => now()->subDays(100),
        ]);

        $pruned = $repo->pruneOlderThan(now()->subDays(90));
        $this->assertGreaterThanOrEqual(1, $pruned);
    }
}