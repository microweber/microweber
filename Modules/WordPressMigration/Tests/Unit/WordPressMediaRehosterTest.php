<?php

namespace Modules\WordPressMigration\Tests\Unit;

use Modules\WordPressMigration\Services\Media\RehostReceipt;
use Modules\WordPressMigration\Services\Media\WordPressMediaRehoster;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Unit coverage for {@see WordPressMediaRehoster}.
 *
 * These tests inject fake downloader + saver closures so nothing
 * hits the network or the database. The comprehensive line-1303
 * cases (redirect following, mime-sniff fallback, CDN variants)
 * live in their own fixture test; this file just nails down the
 * core contract: download once, hash, dedupe, return receipt.
 */
class WordPressMediaRehosterTest extends TestCase
{
    private string $tmpRoot;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tmpRoot = sys_get_temp_dir() . DIRECTORY_SEPARATOR
            . 'mw-rehost-' . bin2hex(random_bytes(6)) . DIRECTORY_SEPARATOR;
        mkdir($this->tmpRoot, 0775, true);
    }

    protected function tearDown(): void
    {
        $this->rmrf($this->tmpRoot);
        parent::tearDown();
    }

    #[Test]
    public function protocol_relative_url_is_rejected(): void
    {
        $rehoster = $this->makeRehoster();

        $this->assertNull($rehoster->fetch('//wp.example/hero.jpg'));
        $this->assertNull($rehoster->rehost('//wp.example/hero.jpg'));
    }

    #[Test]
    public function non_http_schemes_are_rejected(): void
    {
        $rehoster = $this->makeRehoster();

        foreach (['mailto:a@b.com', 'tel:+1', 'javascript:void(0)', 'data:image/png;base64,AAA'] as $url) {
            $this->assertNull($rehoster->fetch($url), "scheme should be rejected: {$url}");
        }
    }

    #[Test]
    public function urls_without_a_recognised_extension_are_rejected(): void
    {
        $rehoster = $this->makeRehoster();

        $this->assertNull($rehoster->fetch('https://wp.example/category/news/'));
        $this->assertNull($rehoster->fetch('https://wp.example/wp-json/wp/v2/posts'));
    }

    #[Test]
    public function successful_fetch_returns_media_id_and_public_url(): void
    {
        $rehoster = $this->makeRehoster(
            downloader: $this->fakeDownloader(['https://wp.example/a.jpg' => 'PAYLOAD-A']),
        );

        $receipt = $rehoster->fetch('https://wp.example/a.jpg', [
            'rel_type' => \Modules\Content\Models\Content::class,
            'rel_id' => 42,
        ]);

        $this->assertInstanceOf(RehostReceipt::class, $receipt);
        $this->assertGreaterThan(0, $receipt->mediaId);
        $this->assertStringContainsString('/userfiles/media/imported/wordpress/job-7/', $receipt->url);
        $this->assertStringEndsWith('.jpg', $receipt->url);
    }

    #[Test]
    public function two_urls_with_identical_bytes_dedupe_to_one_file_and_one_media_row(): void
    {
        $saves = [];
        $rehoster = $this->makeRehoster(
            downloader: $this->fakeDownloader([
                'https://wp.example/a.jpg'   => 'SHARED-BYTES',
                'https://cdn.example/a2.jpg' => 'SHARED-BYTES',
            ]),
            saver: function (array $data) use (&$saves): int {
                $saves[] = $data;
                return 1000 + count($saves);
            },
        );

        $first = $rehoster->fetch('https://wp.example/a.jpg');
        $second = $rehoster->fetch('https://cdn.example/a2.jpg');

        $this->assertNotNull($first);
        $this->assertNotNull($second);
        $this->assertSame($first->mediaId, $second->mediaId,
            'Same bytes must reuse the first media row — no duplicate insert'
        );
        $this->assertSame($first->url, $second->url);
        $this->assertCount(1, $saves,
            'save_media must fire exactly once per unique content hash'
        );

        // Exactly one physical file lives in the job directory.
        $jobDir = $this->tmpRoot . 'imported/wordpress/job-7/';
        $files = glob($jobDir . '*.jpg') ?: [];
        $this->assertCount(1, $files,
            'Only one file should be on disk — dedupe drops the second copy'
        );
    }

    #[Test]
    public function same_url_twice_is_downloaded_once(): void
    {
        $downloadCount = 0;
        $rehoster = $this->makeRehoster(
            downloader: function (string $url, string $target) use (&$downloadCount): bool {
                $downloadCount++;
                file_put_contents($target, 'HERO-BYTES');
                return true;
            },
        );

        $first = $rehoster->fetch('https://wp.example/hero.jpg');
        $second = $rehoster->fetch('https://wp.example/hero.jpg');

        $this->assertNotNull($first);
        $this->assertSame($first->mediaId, $second?->mediaId);
        $this->assertSame(1, $downloadCount,
            'Per-URL cache must short-circuit the second call without another download'
        );
    }

    #[Test]
    public function storage_path_includes_job_id_and_hash_prefix_and_extension(): void
    {
        $rehoster = $this->makeRehoster(
            jobId: 'wxr-fixture.example',
            downloader: $this->fakeDownloader(['https://wp.example/doc.pdf' => 'PDF-BYTES']),
        );

        $receipt = $rehoster->fetch('https://wp.example/doc.pdf');
        $this->assertNotNull($receipt);

        $this->assertMatchesRegularExpression(
            '#^/userfiles/media/imported/wordpress/wxr-fixture_example/[0-9a-f]{12}\.pdf$#',
            $receipt->url,
            'URL must encode the sanitized job id, the 12-char sha256 prefix, and the source extension'
        );
    }

    #[Test]
    public function download_failure_returns_null_and_leaves_no_file(): void
    {
        $rehoster = $this->makeRehoster(
            downloader: fn (): bool => false,
        );

        $result = $rehoster->fetch('https://wp.example/broken.jpg');
        $this->assertNull($result);

        $jobDir = $this->tmpRoot . 'imported/wordpress/job-7/';
        $files = is_dir($jobDir) ? glob($jobDir . '*') : [];
        $this->assertSame([], $files, 'No half-written file should be left behind on failure');
    }

    #[Test]
    public function zero_byte_download_is_treated_as_failure(): void
    {
        $rehoster = $this->makeRehoster(
            downloader: function (string $url, string $target): bool {
                file_put_contents($target, '');
                return true;
            },
        );

        $this->assertNull($rehoster->fetch('https://wp.example/empty.jpg'));
    }

    #[Test]
    public function rehost_adapter_returns_url_string(): void
    {
        $rehoster = $this->makeRehoster(
            downloader: $this->fakeDownloader(['https://wp.example/pic.png' => 'PNG-BYTES']),
        );

        $url = $rehoster->rehost('https://wp.example/pic.png');
        $this->assertIsString($url);
        $this->assertStringEndsWith('.png', $url);
    }

    #[Test]
    public function media_type_matches_extension_family(): void
    {
        $captured = [];
        $rehoster = $this->makeRehoster(
            downloader: $this->fakeDownloader([
                'https://wp.example/a.jpg'  => 'IMG',
                'https://wp.example/a.mp4'  => 'VID',
                'https://wp.example/a.mp3'  => 'AUD',
                'https://wp.example/a.pdf'  => 'DOC',
            ]),
            saver: function (array $data) use (&$captured): int {
                $captured[pathinfo($data['title'], PATHINFO_EXTENSION)] = $data['media_type'];
                return count($captured) + 100;
            },
        );

        $rehoster->fetch('https://wp.example/a.jpg');
        $rehoster->fetch('https://wp.example/a.mp4');
        $rehoster->fetch('https://wp.example/a.mp3');
        $rehoster->fetch('https://wp.example/a.pdf');

        $this->assertSame('picture', $captured['jpg']);
        $this->assertSame('video',   $captured['mp4']);
        $this->assertSame('audio',   $captured['mp3']);
        $this->assertSame('file',    $captured['pdf']);
    }

    /**
     * @param array<string, string> $table URL → body bytes
     */
    private function fakeDownloader(array $table): \Closure
    {
        return function (string $url, string $target) use ($table): bool {
            if (!isset($table[$url])) {
                return false;
            }
            file_put_contents($target, $table[$url]);
            return true;
        };
    }

    private function makeRehoster(
        int|string $jobId = 'job-7',
        ?\Closure $downloader = null,
        ?\Closure $saver = null,
    ): WordPressMediaRehoster {
        return new WordPressMediaRehoster(
            jobId: $jobId,
            downloader: $downloader ?? $this->fakeDownloader([]),
            saver: $saver ?? fn (array $data): int => random_int(1000, 9999),
            storageRoot: $this->tmpRoot,
        );
    }

    private function rmrf(string $path): void
    {
        if (!is_dir($path)) {
            @unlink($path);
            return;
        }
        foreach (scandir($path) ?: [] as $entry) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }
            $this->rmrf($path . DIRECTORY_SEPARATOR . $entry);
        }
        @rmdir($path);
    }
}
