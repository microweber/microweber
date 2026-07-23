<?php

namespace Tests\Feature\MediaThumbnail;

use Illuminate\Support\Facades\Route;
use MicroweberPackages\MediaThumbnail\Models\MediaThumbnail;
use MicroweberPackages\MediaThumbnail\Repositories\MediaThumbnailRepository;
use Tests\TestCase;

/**
 * Smoke tests for all media-thumbnail related routes.
 *
 * Verifies that each endpoint returns an HTTP 200 and the expected
 * content type (image or fallback placeholder).
 */
class MediaThumbnailLivewireTest extends TestCase
{
    public function test_all_media_thumbnail_routes_are_named(): void
    {
        $expectedRoutes = [
            'media-thumbnail.pixum',
            'media-thumbnail.thumbnail',
            'media-thumbnail.generate',
        ];

        foreach ($expectedRoutes as $name) {
            $this->assertTrue(
                Route::has($name),
                "Route [{$name}] should be registered"
            );
        }
    }

    public function test_pixum_img_smoke(): void
    {
        $response = $this->get(route('media-thumbnail.pixum', [
            'width'  => 20,
            'height' => 20,
        ]));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'image/png');
    }

    public function test_thumbnail_img_smoke_without_params(): void
    {
        $response = $this->get(route('media-thumbnail.thumbnail'));

        $response->assertStatus(200);
    }

    public function test_thumbnail_img_smoke_with_invalid_src(): void
    {
        $response = $this->get(route('media-thumbnail.thumbnail', [
            'src'   => '/nonexistent/image.jpg',
            'width' => 100,
        ]));

        $response->assertStatus(200);
    }

    public function test_generate_uuid_smoke_with_valid_record(): void
    {
        // Create a test image
        $dir = storage_path('app/public');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $testImage = $dir . '/tn-livewire-test.png';
        $img = imagecreatetruecolor(50, 50);
        $c = imagecolorallocate($img, 200, 200, 200);
        imagefill($img, 0, 0, $c);
        imagepng($img, $testImage);
        imagedestroy($img);

        $model = MediaThumbnail::create([
            'filename'      => 'tn-livewire-test',
            'image_options' => [
                'src'    => $testImage,
                'width'  => 30,
                'height' => 30,
            ],
        ]);

        $response = $this->get(route('media-thumbnail.generate', ['uuid' => $model->uuid]));
        $response->assertStatus(200);

        // Cleanup
        @unlink($testImage);
        $model->delete();
    }

    public function test_generate_uuid_smoke_with_nonexistent_uuid(): void
    {
        $response = $this->get(route('media-thumbnail.generate', [
            'uuid' => '00000000-0000-0000-0000-000000000000',
        ]));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'image/png');
    }

    public function test_old_tn_request_route_is_registered(): void
    {
        // Verify the CMS backward-compatibility route exists
        $this->assertTrue(
            \Illuminate\Support\Facades\Route::has('api.image-generate-tn-request'),
            'Legacy image-generate-tn-request route should be registered'
        );
    }

    public function test_repository_is_singleton(): void
    {
        $a = app(MediaThumbnailRepository::class);
        $b = app(MediaThumbnailRepository::class);

        $this->assertSame($a, $b);
    }
}