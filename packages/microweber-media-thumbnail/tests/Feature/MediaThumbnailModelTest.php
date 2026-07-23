<?php

namespace MicroweberPackages\MediaThumbnail\Tests\Feature;

use MicroweberPackages\MediaThumbnail\Models\MediaThumbnail;
use MicroweberPackages\MediaThumbnail\Tests\TestCase;

class MediaThumbnailModelTest extends TestCase
{
    public function test_can_create_media_thumbnail(): void
    {
        $model = MediaThumbnail::create([
            'filename'      => 'tn-test-image-12345',
            'image_options' => ['src' => '/images/test.jpg', 'width' => 200, 'height' => 200],
        ]);

        $this->assertNotNull($model->id);
        $this->assertNotNull($model->uuid);
        $this->assertEquals('tn-test-image-12345', $model->filename);
        $this->assertIsArray($model->image_options);
        $this->assertEquals(200, $model->image_options['width']);
    }

    public function test_uuid_is_auto_generated(): void
    {
        $model = new MediaThumbnail();
        $model->filename = 'tn-auto-uuid-test';
        $model->image_options = ['src' => '/test.jpg'];
        $model->save();

        $this->assertNotNull($model->uuid);
        $this->assertIsString($model->uuid);
    }

    public function test_find_by_filename(): void
    {
        MediaThumbnail::create([
            'filename'      => 'tn-find-test',
            'image_options' => ['src' => '/find.jpg', 'width' => 100],
        ]);

        $result = MediaThumbnail::findByFilename('tn-find-test');

        $this->assertNotNull($result);
        $this->assertIsArray($result);
        $this->assertEquals('tn-find-test', $result['filename']);
        $this->assertIsArray($result['image_options']);
        $this->assertEquals(100, $result['image_options']['width']);
    }

    public function test_find_by_filename_returns_null_when_not_found(): void
    {
        $result = MediaThumbnail::findByFilename('tn-nonexistent');

        $this->assertNull($result);
    }

    public function test_remove_by_filename(): void
    {
        MediaThumbnail::create([
            'filename'      => 'tn-remove-test',
            'image_options' => ['src' => '/remove.jpg'],
        ]);

        $deleted = MediaThumbnail::removeByFilename('tn-remove-test');

        $this->assertEquals(1, $deleted);
        $this->assertNull(MediaThumbnail::findByFilename('tn-remove-test'));
    }

    public function test_image_options_cast_to_json(): void
    {
        $options = [
            'src'    => '/images/cast-test.png',
            'width'  => 300,
            'height' => 200,
            'crop'   => true,
        ];

        $model = MediaThumbnail::create([
            'filename'      => 'tn-cast-test',
            'image_options' => $options,
        ]);

        $fresh = MediaThumbnail::find($model->id);
        $this->assertIsArray($fresh->image_options);
        $this->assertEquals($options, $fresh->image_options);
    }

    public function test_key_type_is_int(): void
    {
        $model = new MediaThumbnail();
        $this->assertEquals('int', $model->getKeyType());
        $this->assertEquals('id', $model->getKeyName());
    }
}