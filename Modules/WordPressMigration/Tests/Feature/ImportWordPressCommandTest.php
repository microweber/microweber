<?php

namespace Modules\WordPressMigration\Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\WordPressMigration\Models\StagingContent;
use Modules\WordPressMigration\Models\WordPressMigrationJob;
use Modules\WordPressMigration\Services\Http\HttpProbeFetcher;
use Modules\WordPressMigration\Services\WordPressContentMapper;
use Modules\WordPressMigration\Tests\Support\FakeHttpProbeFetcher;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Phase 10 CLI coverage for the headless driver.
 *
 * Exercises the argument parsing, probe → mode resolution, dry-run
 * vs live dispatch, and WXR offline path. Network is faked via the
 * same {@see FakeHttpProbeFetcher} the Filament-page tests use so
 * probe outcomes are deterministic.
 */
class ImportWordPressCommandTest extends TestCase
{
    private const SOURCE_URL = 'https://cli-test.example.invalid';

    private const SOURCE_HOST = 'cli-test.example.invalid';

    protected function setUp(): void
    {
        parent::setUp();
        if (! Schema::hasTable('wp_migration_jobs')) {
            $this->artisan('module:migrate', ['module' => 'WordPressMigration']);
        }
        DB::table('wp_migration_jobs')->delete();
        DB::table('wp_migration_staging_content')->delete();
        DB::table('wp_migration_staging_media')->delete();
        $this->purgeTestContent();
    }

    protected function tearDown(): void
    {
        $this->purgeTestContent();
        parent::tearDown();
    }

    private function bindFakeFetcher(FakeHttpProbeFetcher $fetcher): void
    {
        $this->app->instance(HttpProbeFetcher::class, $fetcher);
    }

    #[Test]
    public function rejects_a_url_that_does_not_start_with_http(): void
    {
        $exit = Artisan::call('microweber:import:wordpress', [
            'url' => 'not-a-url',
            '--yes' => true,
        ]);

        $this->assertSame(2, $exit);
    }

    #[Test]
    public function rejects_an_unknown_mode(): void
    {
        $exit = Artisan::call('microweber:import:wordpress', [
            'url' => self::SOURCE_URL,
            '--mode' => 'ftp',
            '--yes' => true,
        ]);

        $this->assertSame(2, $exit);
    }

    #[Test]
    public function rejects_non_positive_max(): void
    {
        $exit = Artisan::call('microweber:import:wordpress', [
            'url' => self::SOURCE_URL,
            '--max' => 0,
            '--yes' => true,
        ]);

        $this->assertSame(2, $exit);
    }

    #[Test]
    public function exits_cleanly_when_probe_finds_no_capabilities(): void
    {
        $this->bindFakeFetcher(FakeHttpProbeFetcher::unreachable(self::SOURCE_URL));

        $exit = Artisan::call('microweber:import:wordpress', [
            'url' => self::SOURCE_URL,
            '--yes' => true,
        ]);

        $this->assertSame(1, $exit);
    }

    #[Test]
    public function probes_and_persists_a_job_row_on_rest_capable_sources(): void
    {
        $this->bindFakeFetcher(FakeHttpProbeFetcher::rest(self::SOURCE_URL, posts: 3, pages: 1));

        $exit = Artisan::call('microweber:import:wordpress', [
            'url' => self::SOURCE_URL,
            '--dry-run' => true,
            '--yes' => true,
            '--max' => 1,
        ]);

        $this->assertSame(0, $exit);

        $job = WordPressMigrationJob::query()
            ->where('source_url', self::SOURCE_URL)
            ->first();
        $this->assertNotNull($job);
        $this->assertSame('rest', $job->mode);
        $this->assertSame(WordPressMigrationJob::STATUS_FINISHED, $job->status);
    }

    #[Test]
    public function dry_run_writes_to_staging_not_to_live_content(): void
    {
        $this->bindFakeFetcher(FakeHttpProbeFetcher::rest(self::SOURCE_URL, posts: 0, pages: 0));

        $exit = Artisan::call('microweber:import:wordpress', [
            'url' => self::SOURCE_URL,
            '--mode' => 'rss',
            '--dry-run' => true,
            '--yes' => true,
            '--max' => 5,
        ]);

        // Depending on the fake the walker may have nothing to fetch
        // (REST fake returns no RSS); the critical assertion is that
        // zero rows landed on live content.
        $this->assertSame(0, $exit);

        $liveRows = DB::table('content_data')
            ->where('field_name', WordPressContentMapper::META_SOURCE_GUID)
            ->where('field_value', 'like', '%' . self::SOURCE_HOST . '%')
            ->count();
        $this->assertSame(0, $liveRows);
    }

    #[Test]
    public function wxr_mode_reads_a_local_xml_file(): void
    {
        $path = base_path('tests/fixtures/wp/wxr-sample.xml');
        $this->assertFileExists($path, 'WXR fixture must exist for this test');

        $exit = Artisan::call('microweber:import:wordpress', [
            'url' => $path,
            '--mode' => 'wxr',
            '--dry-run' => true,
            '--yes' => true,
            '--max' => 50,
        ]);

        $this->assertSame(0, $exit);

        // The sample file has at least one item; we don't care about
        // count here, only that staging received rows for a wxr job.
        $job = WordPressMigrationJob::query()->where('mode', 'wxr')->first();
        $this->assertNotNull($job);

        $staged = StagingContent::where('job_id', $job->id)->count();
        $this->assertGreaterThan(0, $staged, 'WXR dry-run should have produced staging rows');
    }

    #[Test]
    public function wxr_mode_rejects_a_missing_file_path(): void
    {
        $exit = Artisan::call('microweber:import:wordpress', [
            'url' => '/tmp/this-file-does-not-exist-xyz.xml',
            '--mode' => 'wxr',
            '--yes' => true,
        ]);

        $this->assertSame(2, $exit);
    }

    private function purgeTestContent(): void
    {
        $contentIds = DB::table('content_data')
            ->where('field_name', WordPressContentMapper::META_SOURCE_GUID)
            ->where('field_value', 'like', '%' . self::SOURCE_HOST . '%')
            ->pluck('rel_id')
            ->all();

        if (! empty($contentIds)) {
            DB::table('content_data')->whereIn('rel_id', $contentIds)->delete();
            DB::table('content')->whereIn('id', $contentIds)->delete();
        }
    }
}
