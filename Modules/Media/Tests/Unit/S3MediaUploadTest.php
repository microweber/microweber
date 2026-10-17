<?php

namespace Modules\Media\Tests\Unit;

use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Covers the S3 / configurable-disk userfiles feature:
 *  - the global disk-selection helpers in
 *    packages/microweber-app/src/functions/paths.php, and
 *  - Modules\Media\Repositories\MediaManager::upload() now streaming through
 *    Laravel's Storage (no move_uploaded_file).
 *
 * No real S3 is touched — Storage::fake('s3') backs the "s3" disk with a local
 * temp dir. We force a non-empty bucket + url into the s3 disk config so that
 * userfiles_disk() resolves to "s3" and the remote url() branch has a URL.
 */
class S3MediaUploadTest extends TestCase
{
    /** @var array Snapshot of the real $_FILES so tearDown can restore it. */
    private $filesBackup;

    protected function setUp(): void
    {
        parent::setUp();
        $this->filesBackup = $_FILES ?? [];
    }

    protected function tearDown(): void
    {
        $_FILES = $this->filesBackup;
        parent::tearDown();
    }

    /**
     * Give the s3 disk a non-empty bucket + a url, then fake only the s3 disk
     * (so the "public" disk the app needs at boot is left untouched), and point
     * the media upload disk config at s3.
     */
    private function forceS3UploadDisk(): void
    {
        config(['filesystems.disks.s3.bucket' => 'microweber-testing-bucket']);
        config(['filesystems.disks.s3.url' => 'https://s3.example.com/microweber-testing-bucket']);
        Storage::fake('s3');
        config(['media.upload_disk' => 's3']);
    }

    #[Test]
    public function userfiles_disk_defaults_to_public(): void
    {
        // Simulate a clean environment: no media.upload_disk config AND no
        // MEDIA_UPLOAD_DISK env var (the dev .env may set one). env() reads the
        // repository live, so clearing all three adapters neutralises it.
        $original = getenv('MEDIA_UPLOAD_DISK');
        putenv('MEDIA_UPLOAD_DISK');
        unset($_ENV['MEDIA_UPLOAD_DISK'], $_SERVER['MEDIA_UPLOAD_DISK']);
        config(['media.upload_disk' => null]);

        try {
            $this->assertSame('public', userfiles_disk());
            $this->assertFalse(userfiles_disk_is_remote());
            // media_upload_disk() delegates to userfiles_disk().
            $this->assertSame('public', media_upload_disk());
        } finally {
            if ($original !== false) {
                putenv('MEDIA_UPLOAD_DISK=' . $original);
                $_ENV['MEDIA_UPLOAD_DISK'] = $original;
                $_SERVER['MEDIA_UPLOAD_DISK'] = $original;
            }
        }
    }

    #[Test]
    public function userfiles_disk_is_s3_when_configured_with_a_bucket(): void
    {
        config(['filesystems.disks.s3.bucket' => 'microweber-testing-bucket']);
        config(['media.upload_disk' => 's3']);

        $this->assertSame('s3', userfiles_disk());
        $this->assertTrue(userfiles_disk_is_remote());
        $this->assertSame('s3', media_upload_disk());
    }

    #[Test]
    public function userfiles_disk_falls_back_to_public_when_s3_bucket_is_empty(): void
    {
        // s3 selected but not actually configured -> never silently drop writes.
        config(['media.upload_disk' => 's3']);
        config(['filesystems.disks.s3.bucket' => '']);

        $this->assertSame('public', userfiles_disk());
        $this->assertFalse(userfiles_disk_is_remote());
    }

    #[Test]
    public function userfiles_base_relpath_builds_per_site_subpaths(): void
    {
        $this->assertSame(MW_USERFILES_FOLDER_NAME, userfiles_base_relpath());
        $this->assertSame(MW_USERFILES_FOLDER_NAME . '/css', userfiles_base_relpath('css'));
        // Backslashes are normalised to forward slashes.
        $this->assertStringNotContainsString('\\', userfiles_base_relpath('a\\b'));

        // Media base path is relative to the public disk root and ends in /media/.
        $this->assertStringStartsWith(MW_USERFILES_FOLDER_NAME . '/', media_base_path_relative());
        $this->assertStringEndsWith('/media/', media_base_path_relative());
    }

    /**
     * The key test: a PLAIN temp file (exactly what move_uploaded_file() would
     * reject) is uploaded successfully onto the faked s3 disk. Success proves
     * upload() goes through Laravel Storage, not move_uploaded_file.
     */
    #[Test]
    public function upload_streams_a_plain_temp_file_to_the_s3_disk(): void
    {
        $this->forceS3UploadDisk();
        $this->assertSame('s3', media_upload_disk(), 'precondition: upload disk must be s3');

        $name = 'plain-upload-' . uniqid() . '.png';

        // A plain temp file — NOT created by an HTTP upload. move_uploaded_file()
        // / is_uploaded_file() would reject this; Storage does not care.
        $tmp = tempnam(sys_get_temp_dir(), 'mw_media_');
        file_put_contents($tmp, random_bytes(64));

        $_FILES = [
            'file' => [
                'name' => $name,
                'type' => 'image/png',
                'tmp_name' => $tmp,
                'error' => 0,
                'size' => filesize($tmp),
            ],
        ];

        $result = app()->media_manager->upload(['name' => $name]);
        $decoded = json_decode($result, true);

        $this->assertIsArray($decoded, 'upload() should return JSON');
        $this->assertNotEmpty($decoded['src'] ?? null, 'returned src must be non-empty');

        // The object landed at <media_base_path_relative()>uploaded/<name> on s3.
        $expectedKey = media_base_path_relative() . 'uploaded/' . $name;
        Storage::disk('s3')->assertExists($expectedKey);

        @unlink($tmp);
    }
}
