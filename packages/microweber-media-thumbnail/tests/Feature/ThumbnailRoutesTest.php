<?php

namespace MicroweberPackages\MediaThumbnail\Tests\Feature;

use MicroweberPackages\MediaThumbnail\Models\MediaThumbnail;
use MicroweberPackages\MediaThumbnail\Tests\TestCase;

class ThumbnailRoutesTest extends TestCase
{
    public function test_pixum_route_via_pixum_package(): void
    {
        // The pixum route is now served by the media-pixum package
        $response = $this->get(route('media-pixum.serve', ['width' => 10, 'height' => 10]));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'image/png');
    }

    public function test_thumbnail_img_route_returns_pixum_when_no_src(): void
    {
        $response = $this->get(route('media-thumbnail.thumbnail'));

        // When no src provided, should serve a pixum placeholder
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'image/png');
    }

    public function test_generate_by_uuid_returns_pixum_for_missing_uuid(): void
    {
        $response = $this->get(route('media-thumbnail.generate', ['uuid' => 'nonexistent-uuid']));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'image/png');
    }

    public function test_generate_by_uuid_with_valid_record(): void
    {
        // Create a test image file
        $testImageDir = storage_path('app/public');
        if (!is_dir($testImageDir)) {
            mkdir($testImageDir, 0755, true);
        }

        $testImage = $testImageDir . '/test-thumbnail.png';
        $img = imagecreatetruecolor(100, 100);
        $white = imagecolorallocate($img, 255, 255, 255);
        imagefill($img, 0, 0, $white);
        imagepng($img, $testImage);
        imagedestroy($img);

        $model = MediaThumbnail::create([
            'filename'      => 'tn-route-test',
            'image_options' => [
                'src'    => $testImage,
                'width'  => 50,
                'height' => 50,
            ],
        ]);

        $response = $this->get(route('media-thumbnail.generate', ['uuid' => $model->uuid]));

        $response->assertStatus(200);

        // Clean up
        @unlink($testImage);
    }

    public function test_thumbnail_route_names_exist(): void
    {
        // pixum route is now in the media-pixum package
        $this->assertTrue(app('router')->has('media-pixum.serve'));
        $this->assertTrue(app('router')->has('media-thumbnail.thumbnail'));
        $this->assertTrue(app('router')->has('media-thumbnail.generate'));
    }
}