<?php

namespace Modules\WordPressMigration\Tests\Feature;

use DateTimeImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Content\Models\Content;
use Modules\WordPressMigration\DTOs\MigrationItemDTO;
use Modules\WordPressMigration\Models\StagingContent;
use Modules\WordPressMigration\Models\StagingMedia;
use Modules\WordPressMigration\Services\StagingCommitter;
use Modules\WordPressMigration\Services\StagingWriter;
use Modules\WordPressMigration\Services\WordPressContentMapper;
use PHPUnit\Framework\Attributes\Test;
use RuntimeException;
use Tests\TestCase;

/**
 * Integration tests for the Phase 8 "Commit" step.
 *
 * Every test here exercises the real DB-level write path so the
 * transactional guarantee ("failed batches roll back cleanly") is
 * actually verified — a mocked database would just prove the mock
 * behaves the way we told it to. We snapshot `content` counts
 * before and after to prove the rollback actually undid the inserts.
 */
class StagingCommitterTest extends TestCase
{
    private const JOB_ID = 91;

    protected function setUp(): void
    {
        parent::setUp();

        if (!Schema::hasTable('wp_migration_staging_content')) {
            $this->artisan('module:migrate', ['module' => 'WordPressMigration']);
        }

        DB::table('wp_migration_staging_media')->where('job_id', self::JOB_ID)->delete();
        DB::table('wp_migration_staging_content')->where('job_id', self::JOB_ID)->delete();

        $this->purgeLiveContentForGuids([
            'commit:a', 'commit:b', 'commit:c', 'commit:boom', 'commit:skip',
            'commit:b1', 'commit:b2', 'commit:b3', 'commit:b4', 'commit:b5',
        ]);
    }

    #[Test]
    public function commit_promotes_every_non_excluded_row_to_live_content(): void
    {
        $this->stage('commit:a', 'Alpha post', '<p>A</p>');
        $this->stage('commit:b', 'Beta post', '<p>B</p>');

        $report = $this->committer()->commit(self::JOB_ID);

        $this->assertSame(2, $report->committedCount());
        $this->assertSame(0, $report->failedCount());
        $this->assertTrue($report->isSuccessful());

        $titles = Content::query()
            ->whereContentData([
                WordPressContentMapper::META_IMPORT_SOURCE => WordPressContentMapper::IMPORT_SOURCE_WORDPRESS_RSS,
            ])
            ->whereIn((new Content())->getTable() . '.id', array_values($report->committed))
            ->pluck('title')
            ->all();

        sort($titles);
        $this->assertSame(['Alpha post', 'Beta post'], $titles);
    }

    #[Test]
    public function commit_skips_excluded_rows_and_leaves_them_in_staging(): void
    {
        $a = $this->stage('commit:a', 'Alpha post');
        $skip = $this->stage('commit:skip', 'Skipped post');
        DB::table('wp_migration_staging_content')->where('id', $skip->id)->update(['excluded' => true]);

        $liveBefore = DB::table('content')->count();

        $report = $this->committer()->commit(self::JOB_ID);

        $this->assertSame(1, $report->committedCount());
        $this->assertSame(1, $report->skipped);

        // Skipped row is still in staging as a receipt.
        $this->assertNotNull(StagingContent::find($skip->id));
        // Committed row is gone.
        $this->assertNull(StagingContent::find($a->id));

        $this->assertSame($liveBefore + 1, DB::table('content')->count(),
            'Only the non-excluded staged row should have been promoted');
    }

    #[Test]
    public function committed_staging_rows_are_deleted_from_staging_tables(): void
    {
        $row = $this->stage('commit:a', 'Alpha post', '<p><img src="https://example.com/wp-content/uploads/x.jpg"></p>');
        $mediaBefore = StagingMedia::where('job_id', self::JOB_ID)->count();
        $this->assertGreaterThan(0, $mediaBefore);

        $this->committer()->commit(self::JOB_ID);

        $this->assertNull(StagingContent::find($row->id));
        $this->assertSame(0, StagingMedia::where('staging_content_id', $row->id)->count(),
            'staging_media rows must be cleaned up alongside their content row');
    }

    #[Test]
    public function a_throwing_mapper_rolls_back_the_entire_batch(): void
    {
        $this->stage('commit:a', 'Alpha post');
        $this->stage('commit:boom', 'BOOM post');
        $this->stage('commit:b', 'Beta post');

        $contentBefore = DB::table('content')->count();
        $stagingBefore = StagingContent::where('job_id', self::JOB_ID)->count();

        $report = $this->throwingCommitter('BOOM')->commit(self::JOB_ID);

        $this->assertSame(0, $report->committedCount());
        $this->assertSame(3, $report->failedCount(),
            'Every row in a failed batch must be reported, not just the row that threw');
        $this->assertFalse($report->isSuccessful());

        $this->assertSame($contentBefore, DB::table('content')->count(),
            'Transactional rollback must undo all inserts from the failing batch');
        $this->assertSame($stagingBefore, StagingContent::where('job_id', self::JOB_ID)->count(),
            'A failed batch leaves its staging rows untouched so the operator can retry');
    }

    #[Test]
    public function a_failed_batch_does_not_block_subsequent_batches(): void
    {
        // batchSize=2 means 5 rows → 3 batches: [b1,b2] ok, [boom,b3] fails, [b4] ok.
        $this->stage('commit:b1', 'Row 1');
        $this->stage('commit:b2', 'Row 2');
        $this->stage('commit:boom', 'BOOM Row');
        $this->stage('commit:b3', 'Row 3');
        $this->stage('commit:b4', 'Row 4');

        $report = $this->throwingCommitter('BOOM', batchSize: 2)->commit(self::JOB_ID);

        $this->assertSame(3, $report->committedCount(),
            'First and third batches should commit despite the middle one failing');
        $this->assertSame(2, $report->failedCount(),
            'Both rows in the middle batch are reported as failed (whole-batch rollback)');

        // Only the failed batch's rows remain in staging.
        $remainingGuids = StagingContent::where('job_id', self::JOB_ID)
            ->orderBy('source_guid')
            ->pluck('source_guid')
            ->all();
        $this->assertSame(['commit:b3', 'commit:boom'], $remainingGuids);
    }

    #[Test]
    public function re_committing_the_same_staged_dto_upserts_on_the_live_row(): void
    {
        // Prove idempotency: if a prior Commit left a content row,
        // re-staging and re-committing should update that same row
        // rather than duplicating it — this rides on the mapper's
        // (import_source, source_guid) contract.
        $this->stage('commit:a', 'First title');
        $this->committer()->commit(self::JOB_ID);

        $firstId = Content::query()
            ->whereContentData([
                WordPressContentMapper::META_IMPORT_SOURCE => WordPressContentMapper::IMPORT_SOURCE_WORDPRESS_RSS,
                WordPressContentMapper::META_SOURCE_GUID => 'commit:a',
            ])
            ->value('id');
        $this->assertNotNull($firstId);

        // Second stage + commit with a changed title.
        $this->stage('commit:a', 'Second title');
        $this->committer()->commit(self::JOB_ID);

        $hits = Content::query()
            ->whereContentData([
                WordPressContentMapper::META_IMPORT_SOURCE => WordPressContentMapper::IMPORT_SOURCE_WORDPRESS_RSS,
                WordPressContentMapper::META_SOURCE_GUID => 'commit:a',
            ])
            ->get();

        $this->assertCount(1, $hits, 'Mapper upsert must keep the row count at one');
        $this->assertSame((int)$firstId, (int)$hits->first()->id);
        $this->assertSame('Second title', $hits->first()->title);
    }

    private function committer(): StagingCommitter
    {
        return new StagingCommitter(new WordPressContentMapper());
    }

    private function throwingCommitter(string $titleMarker, int $batchSize = 50): StagingCommitter
    {
        $mapper = new class($titleMarker) extends WordPressContentMapper {
            public function __construct(private readonly string $marker)
            {
                parent::__construct();
            }

            public function map(MigrationItemDTO $dto): Content
            {
                if (str_contains($dto->title, $this->marker)) {
                    throw new RuntimeException("intentional failure on {$dto->title}");
                }
                return parent::map($dto);
            }
        };
        return new StagingCommitter($mapper, $batchSize);
    }

    private function stage(string $guid, string $title, string $html = '<p>body</p>'): StagingContent
    {
        $writer = new StagingWriter();
        return $writer->stage(self::JOB_ID, new MigrationItemDTO(
            guid: $guid,
            title: $title,
            html: $html,
            excerpt: null,
            author: null,
            categories: [],
            tags: [],
            publishedAt: new DateTimeImmutable('2026-01-01T00:00:00+00:00'),
            canonicalUrl: "https://example.com/{$guid}",
            source: 'wxr',
            sourceHost: 'example.com',
        ));
    }

    /**
     * @param list<string> $guids
     */
    private function purgeLiveContentForGuids(array $guids): void
    {
        $contentIds = DB::table('content_data')
            ->where('field_name', WordPressContentMapper::META_SOURCE_GUID)
            ->whereIn('field_value', $guids)
            ->pluck('rel_id')
            ->all();

        if (empty($contentIds)) {
            return;
        }
        DB::table('content_data')->whereIn('rel_id', $contentIds)->delete();
        DB::table('content')->whereIn('id', $contentIds)->delete();
        DB::table('media')
            ->where('rel_type', 'Modules\\Content\\Models\\Content')
            ->whereIn('rel_id', $contentIds)
            ->delete();
    }
}
