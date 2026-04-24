<?php

namespace Modules\WordPressMigration\Tests\Feature;

use DateTimeImmutable;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\WordPressMigration\DTOs\MigrationItemDTO;
use Modules\WordPressMigration\Models\StagingContent;
use Modules\WordPressMigration\Models\WordPressMigrationJob;
use Modules\WordPressMigration\Services\StagingWriter;
use Modules\WordPressMigration\Services\WordPressContentMapper;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ImportWordPressCommitCommandTest extends TestCase
{
    private const SOURCE_URL = 'https://commit-cli-test.example.invalid';

    private const SOURCE_HOST = 'commit-cli-test.example.invalid';

    protected function setUp(): void
    {
        parent::setUp();
        if (! Schema::hasTable('wp_migration_jobs')) {
            $this->artisan('module:migrate', ['module' => 'WordPressMigration']);
        }
        DB::table('wp_migration_staging_content')->delete();
        DB::table('wp_migration_staging_media')->delete();
        DB::table('wp_migration_jobs')->delete();
        $this->purgeTestContent();
    }

    protected function tearDown(): void
    {
        $this->purgeTestContent();
        parent::tearDown();
    }

    private function purgeTestContent(): void
    {
        $contentIds = DB::table('content_data')
            ->where('field_name', WordPressContentMapper::META_SOURCE_GUID)
            ->where('field_value', 'like', 'commit-cli:%')
            ->pluck('rel_id')
            ->all();

        if (! empty($contentIds)) {
            DB::table('content_data')->whereIn('rel_id', $contentIds)->delete();
            DB::table('content')->whereIn('id', $contentIds)->delete();
        }
    }

    private function seedJobAndStaging(int $count = 2): WordPressMigrationJob
    {
        $job = WordPressMigrationJob::create([
            'source_url' => self::SOURCE_URL,
            'source_url_hash' => hash('sha256', self::SOURCE_URL),
            'source_host' => self::SOURCE_HOST,
            'status' => WordPressMigrationJob::STATUS_READY,
            'mode' => 'rest',
        ]);

        $writer = new StagingWriter();
        for ($i = 1; $i <= $count; $i++) {
            $writer->stage($job->id, new MigrationItemDTO(
                guid: "commit-cli:g{$i}",
                title: "Commit CLI post {$i}",
                html: "<p>Body {$i}</p>",
                excerpt: null,
                author: null,
                categories: [],
                tags: [],
                publishedAt: new DateTimeImmutable('2026-01-01T00:00:00+00:00'),
                canonicalUrl: "https://{$this->host()}/g{$i}",
                source: 'rest',
                sourceHost: self::SOURCE_HOST,
            ));
        }

        return $job;
    }

    private function host(): string
    {
        return self::SOURCE_HOST;
    }

    #[Test]
    public function rejects_a_non_numeric_job_id(): void
    {
        $exit = Artisan::call('microweber:import:wordpress:commit', [
            'job' => 'abc',
            '--yes' => true,
        ]);
        $this->assertSame(2, $exit);
    }

    #[Test]
    public function returns_not_found_when_job_missing(): void
    {
        $exit = Artisan::call('microweber:import:wordpress:commit', [
            'job' => 999999,
            '--yes' => true,
        ]);
        $this->assertSame(4, $exit);
    }

    #[Test]
    public function a_successful_commit_promotes_staging_rows_to_live_content(): void
    {
        $job = $this->seedJobAndStaging(2);

        $exit = Artisan::call('microweber:import:wordpress:commit', [
            'job' => $job->id,
            '--yes' => true,
        ]);

        $this->assertSame(0, $exit);

        foreach (['commit-cli:g1', 'commit-cli:g2'] as $guid) {
            $this->assertTrue(
                DB::table('content_data')
                    ->where('field_name', WordPressContentMapper::META_SOURCE_GUID)
                    ->where('field_value', $guid)
                    ->exists(),
                "Guid {$guid} should be promoted to live content"
            );
        }

        $this->assertSame(0, StagingContent::where('job_id', $job->id)->count(),
            'Committed staging rows should be deleted');
    }

    #[Test]
    public function returns_zero_when_there_are_no_eligible_staging_rows(): void
    {
        $job = WordPressMigrationJob::create([
            'source_url' => self::SOURCE_URL,
            'source_url_hash' => hash('sha256', self::SOURCE_URL),
            'source_host' => self::SOURCE_HOST,
            'status' => WordPressMigrationJob::STATUS_READY,
            'mode' => 'rest',
        ]);

        $exit = Artisan::call('microweber:import:wordpress:commit', [
            'job' => $job->id,
            '--yes' => true,
        ]);
        $this->assertSame(0, $exit);
        $this->assertStringContainsString('No staging rows eligible', Artisan::output());
    }

    #[Test]
    public function retry_failed_only_targets_rows_flagged_with_last_commit_error(): void
    {
        $job = $this->seedJobAndStaging(2);

        // Flag exactly one row as previously-failed.
        $failed = StagingContent::query()
            ->where('job_id', $job->id)
            ->orderBy('id')
            ->first();
        $failed->last_commit_error = 'taxonomy mismatch';
        $failed->save();

        $exit = Artisan::call('microweber:import:wordpress:commit', [
            'job' => $job->id,
            '--retry-failed' => true,
            '--yes' => true,
        ]);
        $this->assertSame(0, $exit);

        $this->assertTrue(
            DB::table('content_data')
                ->where('field_name', WordPressContentMapper::META_SOURCE_GUID)
                ->where('field_value', $failed->source_guid)
                ->exists(),
            'Retried row should land on live content'
        );

        // The other row, which had no prior failure, is untouched.
        $remaining = StagingContent::query()->where('job_id', $job->id)->count();
        $this->assertSame(1, $remaining,
            'retry-failed must NOT touch rows without a persisted error');
    }

    #[Test]
    public function retry_failed_reports_no_work_when_nothing_is_flagged(): void
    {
        $job = $this->seedJobAndStaging(1);

        $exit = Artisan::call('microweber:import:wordpress:commit', [
            'job' => $job->id,
            '--retry-failed' => true,
            '--yes' => true,
        ]);
        $this->assertSame(0, $exit);
        $this->assertStringContainsString('No staging rows flagged as failed', Artisan::output());
    }
}
