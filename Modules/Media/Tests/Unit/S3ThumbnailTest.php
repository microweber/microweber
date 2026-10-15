<?php

namespace Modules\Media\Tests\Unit;

use Illuminate\Support\Facades\Storage;
use MicroweberPackages\MediaThumbnail\Models\MediaThumbnail;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Covers the S3 / remote-disk thumbnail feature on
 * Modules\Media\Repositories\MediaManager::thumbnail():
 *
 *  - When userfiles live on a remote disk (userfiles_disk_is_remote() true,
 *    i.e. media.upload_disk = s3 with a configured bucket) AND $src is a URL
 *    served by that bucket, thumbnail() now streams the source object from the
 *    disk, resizes it, uploads the thumbnail under
 *    <media_base_path_relative()>cache/thumbnails/<width>/tn-<crc>.<ext>, caches
 *    the mapping in the media_thumbnails DB table and returns the bucket URL.
 *  - A pixum placeholder (url contains 'pixum') is only returned when no
 *    thumbnail can be produced.
 *  - A truly external URL (not served by the bucket) is NOT treated as ours.
 *
 * No real S3 is touched: Storage::fake('s3') backs the "s3" disk with a local
 * temp dir. We force a non-empty bucket + url into the s3 disk config BEFORE
 * faking so the fake disk's url() resolves to a bucket link, then point the
 * media upload disk at s3 so userfiles_disk_is_remote() is true.
 *
 * The media_thumbnails row created here is asserted by its own unique filename
 * (cache) key — shared tables are never truncated.
 */
class S3ThumbnailTest extends TestCase
{
    /** @var string Bucket base URL the fake s3 disk resolves url()s against. */
    private $bucketBase;

    /**
     * Give the s3 disk a non-empty bucket + url, fake only the s3 disk, and
     * point the media upload disk at s3 (so userfiles_disk_is_remote() == true).
     * Mirrors Modules/Media/Tests/Unit/S3MediaUploadTest::forceS3UploadDisk().
     */
    private function forceS3UploadDisk(): void
    {
        config(['filesystems.disks.s3.url' => 'https://bucket.example.test']);
        config(['filesystems.disks.s3.bucket' => 'testbucket']);
        Storage::fake('s3');
        config(['media.upload_disk' => 's3']);

        // Derive the bucket base the same way MediaManager::_remote_thumbnail
        // does, so our assertions never hardcode the url() formatting.
        $probe = Storage::disk('s3')->url('__MW_TN_PROBE__');
        $this->bucketBase = substr($probe, 0, strpos($probe, '__MW_TN_PROBE__'));
    }

    /**
     * Put a REAL 400x300 JPEG onto the fake s3 disk and return its disk-relative
     * key. A 1x1 image will not survive the resizer — this is a genuine raster.
     */
    private function seedRemoteImage(string $relKey): void
    {
        $img = imagecreatetruecolor(400, 300);
        $bg = imagecolorallocate($img, 10, 120, 200);
        imagefilledrectangle($img, 0, 0, 400, 300, $bg);
        // A contrasting block so it is unmistakably a real raster image.
        $fg = imagecolorallocate($img, 240, 200, 20);
        imagefilledrectangle($img, 40, 40, 200, 160, $fg);

        $tmp = tempnam(sys_get_temp_dir(), 'mw_tn_src_') . '.jpg';
        imagejpeg($img, $tmp, 90);
        imagedestroy($img);

        Storage::disk('s3')->put($relKey, file_get_contents($tmp), ['visibility' => 'public']);
        @unlink($tmp);
    }

    #[Test]
    public function thumbnail_generates_a_bucket_thumbnail_for_a_remote_src(): void
    {
        $this->forceS3UploadDisk();
        $this->assertTrue(userfiles_disk_is_remote(), 'precondition: userfiles disk must be remote');

        $relKey = media_base_path_relative() . 'uploaded/t.jpg';
        $this->seedRemoteImage($relKey);
        Storage::disk('s3')->assertExists($relKey);

        $src = Storage::disk('s3')->url($relKey);

        $result = app()->media_manager->thumbnail($src, 120, 90);

        // 1) It is a real thumbnail, not a placeholder.
        $this->assertIsString($result);
        $this->assertStringNotContainsString('pixum', $result, 'remote thumbnail must not fall back to pixum');

        // 2) It is served from the bucket, under cache/thumbnails/120/.
        $this->assertStringStartsWith($this->bucketBase, $result, 'thumbnail url must be on the bucket base');
        $this->assertStringContainsString('cache/thumbnails/120/', $result);

        // 3) The thumbnail object actually exists on the disk at the key the
        //    returned URL points to.
        $thumbRel = ltrim(strtok(substr($result, strlen($this->bucketBase)), '?'), '/');
        $this->assertStringContainsString('cache/thumbnails/120/', $thumbRel);
        Storage::disk('s3')->assertExists($thumbRel);

        // 4) A media_thumbnails DB row was cached, keyed by the thumb filename,
        //    carrying the remote_url mapping back to this exact URL.
        $cacheKey = pathinfo($thumbRel, PATHINFO_FILENAME); // tn-<crc>
        $row = MediaThumbnail::findByFilename($cacheKey);
        $this->assertIsArray($row, 'a media_thumbnails row must be cached for the thumbnail');
        $this->assertSame($cacheKey, $row['filename']);
        $this->assertSame($result, $row['image_options']['remote_url'] ?? null);

        // 5) A second call returns the identical URL (served from the DB cache).
        $result2 = app()->media_manager->thumbnail($src, 120, 90);
        $this->assertSame($result, $result2, 'second call must return the cached bucket url');

        // Only one row should back this key (no duplicate on the cache hit).
        $this->assertSame(1, MediaThumbnail::where('filename', $cacheKey)->count());

        // Cleanup our specific row so the shared table stays tidy.
        MediaThumbnail::removeByFilename($cacheKey);
    }

    #[Test]
    public function thumbnail_does_not_bucket_thumbnail_a_truly_external_url(): void
    {
        $this->forceS3UploadDisk();
        $this->assertTrue(userfiles_disk_is_remote(), 'precondition: userfiles disk must be remote');

        // Not served by our bucket base -> _remote_thumbnail must bail (return
        // null) and the normal (non-bucket) flow handles it. Lenient: just make
        // sure nothing blows up and we did not mint a bucket cache thumbnail.
        $external = 'https://example.com/x.jpg';

        $result = app()->media_manager->thumbnail($external, 120, 90);

        $this->assertIsString($result);
        $this->assertStringNotContainsString('cache/thumbnails/', $result, 'external url must not become a bucket thumbnail');
    }
}
