<?php

namespace Modules\WordPressMigration\Tests\Feature;

use DateTimeImmutable;
use Illuminate\Support\Facades\DB;
use Modules\WordPressMigration\DTOs\MigrationItemDTO;
use Modules\WordPressMigration\Models\StagingContent;
use Modules\WordPressMigration\Models\StagingMedia;
use Modules\WordPressMigration\Services\StagingWriter;
use Modules\WordPressMigration\Services\WordPressContentMapper;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Dry-run tests for Phase 8 "preview before commit".
 *
 * The headline guarantee is: a full staging pass must produce zero
 * writes to the live `content` and `media` tables. We assert this
 * by snapshotting the row counts before calling `stage()` and
 * comparing afterwards — the staging rows accumulate, the live
 * counts don't move.
 *
 * Uses the same DB-integration style as WordPressContentMapperTest
 * because the staging path also relies on real Eloquent upserts
 * and the uniqueness index on (job_id, source_guid).
 */
class StagingWriterTest extends TestCase
{
    private const JOB_ID = 77;

    protected function setUp(): void
    {
        parent::setUp();

        // Clean slate — staging tables are tiny, a full delete is
        // fine, and it prevents a prior failing run from leaving
        // rows that upsert-hit here.
        DB::table('wp_migration_staging_media')->where('job_id', self::JOB_ID)->delete();
        DB::table('wp_migration_staging_content')->where('job_id', self::JOB_ID)->delete();

        // Same defensive cleanup on the live side for any WP rows
        // a flaky earlier suite might have left behind with the
        // guids this test uses — otherwise the "zero live writes"
        // assertion could falsely pass by counting residue.
        $this->purgeLiveContentForGuids(['dryrun:a', 'dryrun:b']);
    }

    #[Test]
    public function staging_a_dto_writes_zero_rows_to_live_content_and_media(): void
    {
        $contentBefore = DB::table('content')->count();
        $mediaBefore = DB::table('media')->count();

        $writer = new StagingWriter();
        $row = $writer->stage(self::JOB_ID, $this->dto([
            'guid' => 'dryrun:a',
            'title' => 'Draft',
            'html' => '<p><img src="https://example.com/wp-content/uploads/hero.jpg"></p>',
            'featuredImageUrl' => 'https://example.com/wp-content/uploads/hero.jpg',
        ]));

        $this->assertInstanceOf(StagingContent::class, $row);
        $this->assertGreaterThan(0, $row->id);

        $this->assertSame($contentBefore, DB::table('content')->count(),
            'Dry-run must not insert into live content'
        );
        $this->assertSame($mediaBefore, DB::table('media')->count(),
            'Dry-run must not insert into live media'
        );

        // And the staged row is reachable.
        $this->assertSame(1, StagingContent::where('job_id', self::JOB_ID)->count());
        $this->assertGreaterThan(0, StagingMedia::where('job_id', self::JOB_ID)->count());
    }

    #[Test]
    public function re_staging_same_guid_upserts_rather_than_duplicates(): void
    {
        $writer = new StagingWriter();

        $first = $writer->stage(self::JOB_ID, $this->dto([
            'guid' => 'dryrun:a',
            'title' => 'Original',
        ]));

        $second = $writer->stage(self::JOB_ID, $this->dto([
            'guid' => 'dryrun:a',
            'title' => 'Edited',
        ]));

        $this->assertSame($first->id, $second->id, '(job_id, source_guid) upsert must hit the same row');
        $this->assertSame(1, StagingContent::where('job_id', self::JOB_ID)->count());
        $this->assertSame('Edited', StagingContent::find($first->id)->title);
    }

    #[Test]
    public function re_staging_preserves_operator_exclusion(): void
    {
        // Operator unchecks a row in the preview. The importer then
        // re-runs the staging pass (for whatever reason — paused
        // and resumed, added a late-page batch). The exclusion must
        // stick; silently re-including it would be a data-loss
        // footgun at Commit time.
        $writer = new StagingWriter();
        $first = $writer->stage(self::JOB_ID, $this->dto(['guid' => 'dryrun:a']));
        DB::table('wp_migration_staging_content')->where('id', $first->id)->update(['excluded' => true]);

        $writer->stage(self::JOB_ID, $this->dto(['guid' => 'dryrun:a', 'title' => 'Second pass']));

        $after = StagingContent::find($first->id);
        $this->assertTrue((bool)$after->excluded, 'Exclusion must survive a re-stage');
    }

    #[Test]
    public function featured_and_inline_using_same_url_dedupe_to_one_media_row(): void
    {
        $writer = new StagingWriter();
        $url = 'https://example.com/wp-content/uploads/shared.jpg';

        $writer->stage(self::JOB_ID, $this->dto([
            'guid' => 'dryrun:a',
            'html' => '<p><img src="' . $url . '"></p>',
            'featuredImageUrl' => $url,
        ]));

        $rows = StagingMedia::where('job_id', self::JOB_ID)->get();
        $this->assertCount(1, $rows,
            'Same URL in featured + inline must stage as one row — dedupe on sha256(url)'
        );
        $this->assertSame('featured', $rows[0]->role,
            'Featured wins the role slot when both are present'
        );
    }

    #[Test]
    public function inline_urls_from_img_and_anchor_are_captured(): void
    {
        $writer = new StagingWriter();
        $writer->stage(self::JOB_ID, $this->dto([
            'guid' => 'dryrun:b',
            'html' => '<p><img src="https://example.com/wp-content/uploads/a.jpg">'
                . '<a href="https://example.com/wp-content/uploads/b.pdf">PDF</a></p>',
        ]));

        $urls = StagingMedia::where('job_id', self::JOB_ID)
            ->orderBy('source_url')
            ->pluck('source_url')
            ->all();

        $this->assertSame([
            'https://example.com/wp-content/uploads/a.jpg',
            'https://example.com/wp-content/uploads/b.pdf',
        ], $urls);
    }

    #[Test]
    public function taxonomies_are_stored_as_json_names_for_commit_time_resolution(): void
    {
        $writer = new StagingWriter();
        $row = $writer->stage(self::JOB_ID, $this->dto([
            'guid' => 'dryrun:a',
            'categories' => ['News', 'Tech'],
            'tags' => ['launch', 'featured'],
        ]));

        $this->assertSame(['News', 'Tech'], $row->fresh()->categories);
        $this->assertSame(['launch', 'featured'], $row->fresh()->tags);
    }

    private function dto(array $overrides): MigrationItemDTO
    {
        $defaults = [
            'guid' => 'dryrun:default',
            'title' => 'Default title',
            'html' => '<p>default body</p>',
            'excerpt' => null,
            'author' => null,
            'categories' => [],
            'tags' => [],
            'publishedAt' => new DateTimeImmutable('2026-01-01T00:00:00+00:00'),
            'canonicalUrl' => null,
            'source' => 'wxr',
            'sourceHost' => 'example.com',
            'featuredImageUrl' => null,
            'categorySlugs' => [],
            'tagSlugs' => [],
            'authorSlug' => null,
        ];
        $merged = array_replace($defaults, $overrides);

        return new MigrationItemDTO(
            guid: $merged['guid'],
            title: $merged['title'],
            html: $merged['html'],
            excerpt: $merged['excerpt'],
            author: $merged['author'],
            categories: $merged['categories'],
            tags: $merged['tags'],
            publishedAt: $merged['publishedAt'],
            canonicalUrl: $merged['canonicalUrl'],
            source: $merged['source'],
            sourceHost: $merged['sourceHost'],
            featuredImageUrl: $merged['featuredImageUrl'],
            categorySlugs: $merged['categorySlugs'],
            tagSlugs: $merged['tagSlugs'],
            authorSlug: $merged['authorSlug'],
        );
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
