<?php

namespace Modules\Media\Tests\Unit\Models;

use Modules\Media\Models\Media;
use PHPUnit\Framework\TestCase;

class MediaTest extends TestCase
{
    public function test_can_instantiate_model()
    {
        $media = new Media();
        $this->assertInstanceOf(Media::class, $media);
    }

    public function test_fillable_attributes()
    {
        $media = new Media();
        $fillable = $media->getFillable();

        $this->assertContains('folder_id', $fillable);
        $this->assertContains('title', $fillable);
        $this->assertContains('description', $fillable);
        $this->assertContains('cdn_url', $fillable);
        $this->assertContains('cdn_provider', $fillable);
        $this->assertContains('is_synced_to_cdn', $fillable);
        $this->assertContains('file_size', $fillable);
        $this->assertContains('file_hash', $fillable);
    }

    public function test_casts_attributes()
    {
        $media = new Media();
        $casts = $media->getCasts();

        $this->assertArrayHasKey('image_options', $casts);
        $this->assertArrayHasKey('metadata', $casts);
        $this->assertArrayHasKey('cdn_metadata', $casts);
        $this->assertArrayHasKey('is_synced_to_cdn', $casts);
        $this->assertArrayHasKey('file_size', $casts);
    }

    public function test_default_attributes()
    {
        $media = new Media();
        $attributes = $media->getAttributes();

        $this->assertEquals('picture', $attributes['media_type'] ?? null);
        $this->assertEquals(false, $attributes['is_synced_to_cdn'] ?? null);
    }

    public function test_model_has_expected_relationships()
    {
        $media = new Media();

        // Test that the methods exist
        $this->assertTrue(method_exists($media, 'folder'));
    }

    public function test_model_has_expected_scopes()
    {
        $media = new Media();

        // Test that the methods exist
        $this->assertTrue(method_exists($media, 'scopeInFolder'));
        $this->assertTrue(method_exists($media, 'scopeOnCdn'));
        $this->assertTrue(method_exists($media, 'scopeByType'));
    }

    public function test_model_has_expected_accessors()
    {
        $media = new Media();

        // Test that the methods exist
        $this->assertTrue(method_exists($media, 'getUrlAttribute'));
        $this->assertTrue(method_exists($media, 'getFileTypeAttribute'));
    }
}
