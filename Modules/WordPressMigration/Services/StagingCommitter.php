<?php

namespace Modules\WordPressMigration\Services;

use DateTimeImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\WordPressMigration\DTOs\MigrationItemDTO;
use Modules\WordPressMigration\Models\StagingContent;
use Modules\WordPressMigration\Models\StagingMedia;
use Throwable;

/**
 * Promote staged rows from `wp_migration_staging_content` onto the
 * live `content` table. Phase 8 counterpart to {@see StagingWriter}.
 *
 * Batching + transactions
 * -----------------------
 * Rows are walked in chunks of {@see $batchSize} and each batch runs
 * inside a single {@see DB::transaction()}. The semantics that follow
 * from that:
 *
 *   - All-or-nothing per batch. If any row inside a batch throws
 *     during `$mapper->map()`, the transaction rolls back and none
 *     of the rows in that batch land on `content`. The staging
 *     snapshot is untouched so the operator can retry after the
 *     root cause is fixed.
 *   - A failure in one batch does NOT block later batches. The
 *     committer logs the failure to the {@see CommitReport} and
 *     advances to the next chunk. This matches the task's "failed
 *     batches roll back cleanly" — we isolate the failure to the
 *     offending batch rather than aborting the whole commit.
 *   - Excluded rows are never loaded. They stay in staging as a
 *     receipt of "what the operator chose to drop".
 *
 * Success path cleanup
 * --------------------
 * After a batch's transaction commits, the committer deletes the
 * source staging rows (and any `wp_migration_staging_media` rows
 * tied to them). The authoritative record is now the
 * `(import_source, source_guid)` pair on `content_data` — that's
 * what the mapper uses to upsert on a re-import. Keeping a second
 * "committed" copy in staging would drift as the content is edited
 * live, and operators have no reason to page through a commit
 * audit that duplicates the live table.
 *
 * Why re-hydrate a DTO rather than insert directly?
 * -------------------------------------------------
 * The staging snapshot is schema-narrow — just the fields the
 * preview UI renders. The live-side write path has subtleties the
 * mapper already encodes (idempotency via content_data, slug
 * preservation, media rehosting, taxonomy attach, author apply).
 * Going through {@see WordPressContentMapper::map()} reuses every
 * one of those without duplication.
 *
 * Taxonomy slugs
 * --------------
 * Staging stores category/tag *names* (the DTO's `categories`/`tags`
 * arrays), not slugs. The DTO still needs slug-keyed arrays for the
 * taxonomy lookup pass, so we slugify on the way out — matching the
 * RSS importer's default behavior for sources that ship names only.
 */
final class StagingCommitter
{
    public function __construct(
        private readonly WordPressContentMapper $mapper,
        private readonly int $batchSize = 50,
    ) {}

    /**
     * Commit every non-excluded staging row for `$jobId`. Returns a
     * per-row report so the caller can render a summary notification.
     */
    public function commit(int $jobId): CommitReport
    {
        return $this->commitInternal($jobId, onlyFailed: false);
    }

    /**
     * Retry-only variant of {@see commit()}: walks only the staging
     * rows where `last_commit_error` is set from a prior failed pass.
     *
     * Rows that succeeded previously were deleted by the committer on
     * batch success, so they can't be re-processed — `commitFailedOnly`
     * is strictly narrower than `commit`. Before processing, the
     * stored error is cleared; if the retry fails again, the new
     * message overwrites it, giving the operator fresh context.
     */
    public function commitFailedOnly(int $jobId): CommitReport
    {
        return $this->commitInternal($jobId, onlyFailed: true);
    }

    private function commitInternal(int $jobId, bool $onlyFailed): CommitReport
    {
        $report = new CommitReport();

        $report->skipped = StagingContent::query()
            ->where('job_id', $jobId)
            ->where('excluded', true)
            ->when($onlyFailed, fn ($q) => $q->whereNotNull('last_commit_error'))
            ->count();

        $lastId = 0;
        while (true) {
            $rows = StagingContent::query()
                ->where('job_id', $jobId)
                ->where('excluded', false)
                ->where('id', '>', $lastId)
                ->when($onlyFailed, fn ($q) => $q->whereNotNull('last_commit_error'))
                ->orderBy('id')
                ->limit($this->batchSize)
                ->get();

            if ($rows->isEmpty()) {
                break;
            }
            $lastId = (int)$rows->last()->id;

            $this->commitBatch($rows->all(), $report);
        }

        return $report;
    }

    /**
     * @param list<StagingContent> $rows
     */
    private function commitBatch(array $rows, CommitReport $report): void
    {
        // Clear any stale per-row error from a previous failed pass
        // so a retry starts from a clean slate — otherwise a
        // successful retry would land on `content` but the staging
        // row (which we delete anyway on success) would still carry
        // the old error for the brief moment before deletion.
        $batchIds = array_map(static fn (StagingContent $r) => (int)$r->id, $rows);
        StagingContent::query()
            ->whereIn('id', $batchIds)
            ->update(['last_commit_error' => null]);

        try {
            $idMap = DB::transaction(function () use ($rows): array {
                $map = [];
                foreach ($rows as $row) {
                    $content = $this->mapper->map($this->hydrateDto($row));
                    $map[(int)$row->id] = (int)$content->id;
                }
                return $map;
            });
        } catch (Throwable $e) {
            // Whole-batch rollback — everything this batch wrote is
            // gone, including partial content rows. Persist the
            // error on every row in the batch so a future "Retry
            // failed items" action can target exactly these rows
            // without having to re-walk the whole staging set.
            StagingContent::query()
                ->whereIn('id', $batchIds)
                ->update(['last_commit_error' => $e->getMessage()]);

            foreach ($rows as $row) {
                $report->fail((int)$row->id, $e->getMessage());
            }
            return;
        }

        $stagingIds = array_keys($idMap);
        StagingMedia::query()->whereIn('staging_content_id', $stagingIds)->delete();
        StagingContent::query()->whereIn('id', $stagingIds)->delete();

        foreach ($idMap as $stagingId => $contentId) {
            $report->commit((int)$stagingId, (int)$contentId);
        }
    }

    private function hydrateDto(StagingContent $row): MigrationItemDTO
    {
        $categories = array_values(array_filter(
            (array)($row->categories ?? []),
            static fn ($v) => is_string($v) && $v !== ''
        ));
        $tags = array_values(array_filter(
            (array)($row->tags ?? []),
            static fn ($v) => is_string($v) && $v !== ''
        ));

        $publishedAt = null;
        if ($row->posted_at !== null) {
            // posted_at is cast to Carbon by the model; the DTO wants
            // an immutable. Go through ATOM to avoid timezone drift.
            $publishedAt = new DateTimeImmutable($row->posted_at->format(DATE_ATOM));
        }

        return new MigrationItemDTO(
            guid: (string)$row->source_guid,
            title: (string)($row->title ?? ''),
            html: (string)($row->content_html ?? ''),
            excerpt: $row->excerpt,
            categories: $categories,
            tags: $tags,
            publishedAt: $publishedAt,
            canonicalUrl: $row->canonical_url,
            source: 'staged',
            sourceHost: $row->source_host,
            featuredImageUrl: $row->featured_image_url,
            categorySlugs: array_map(static fn ($c) => Str::slug($c), $categories),
            tagSlugs: array_map(static fn ($t) => Str::slug($t), $tags),
            authorSlug: $row->author_slug,
        );
    }
}
