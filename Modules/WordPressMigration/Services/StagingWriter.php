<?php

namespace Modules\WordPressMigration\Services;

use Illuminate\Support\Carbon;
use Modules\WordPressMigration\DTOs\MigrationItemDTO;
use Modules\WordPressMigration\Models\StagingContent;
use Modules\WordPressMigration\Models\StagingMedia;

/**
 * The dry-run equivalent of {@see WordPressContentMapper}.
 *
 * Accepts the same {@see MigrationItemDTO} but writes to
 * `wp_migration_staging_content` / `wp_migration_staging_media`
 * instead of `content` / `media`. Zero writes to live tables is
 * the core guarantee — the operator can stage a full 5000-post
 * import, flip through the preview UI, exclude what they don't
 * want, and only then promote the remaining rows with a Commit
 * batch.
 *
 * Idempotency: re-staging the same DTO within one job upserts onto
 * the same row via (job_id, source_guid). This matches the live
 * side's (import_source, source_guid) contract, just scoped to
 * the owning job so preview data can be truncated without
 * affecting other jobs' staging snapshots.
 *
 * What this class does NOT do
 * ---------------------------
 * - Rehost media files. The preview UI wants a list of URLs the
 *   Commit step will fetch, not copies of the bytes. Running the
 *   real rehoster in dry-run would defeat the zero-write goal
 *   because it writes physical files under `userfiles/media/`.
 * - Resolve taxonomies to local ids. Names are stored verbatim
 *   and the promoter does the lookup at Commit time against the
 *   then-current local taxonomy state.
 * - Touch `content.created_by`. Author resolution is a Commit-time
 *   concern for the same reason.
 */
class StagingWriter
{
    public function __construct(
        private readonly string $importSource = WordPressContentMapper::IMPORT_SOURCE_WORDPRESS_RSS,
    ) {}

    /**
     * Stage `$dto` for `$jobId`. Returns the persisted row so the
     * Commit step has the id to walk.
     */
    public function stage(int $jobId, MigrationItemDTO $dto): StagingContent
    {
        $row = StagingContent::query()
            ->where('job_id', $jobId)
            ->where('source_guid', $dto->guid)
            ->first();

        $payload = [
            'job_id' => $jobId,
            'import_source' => $this->importSource,
            'source_guid' => $dto->guid,
            'title' => $dto->title,
            'content_html' => $dto->html,
            'excerpt' => $dto->excerpt,
            'canonical_url' => $dto->canonicalUrl,
            'source_host' => $dto->sourceHost,
            'featured_image_url' => $dto->featuredImageUrl,
            'author_slug' => $dto->authorSlug,
            'posted_at' => $dto->publishedAt?->format('Y-m-d H:i:s'),
            'categories' => $dto->categories,
            'tags' => $dto->tags,
        ];

        if ($row === null) {
            $row = StagingContent::create($payload);
        } else {
            // Re-staging never flips exclusion back on — operators
            // may have unchecked this row in the preview between
            // stage passes, and silently re-including it would be
            // a data-loss footgun.
            $row->fill($payload);
            $row->save();
        }

        $this->stageMediaRowsFor($row, $dto);

        return $row;
    }

    /**
     * Record the URLs this DTO would rehost in Commit. Dedupe is
     * via sha256 of the URL so the same hero image referenced
     * from both featured_media and inline HTML stages as one row.
     */
    private function stageMediaRowsFor(StagingContent $row, MigrationItemDTO $dto): void
    {
        $seen = [];

        if ($dto->featuredImageUrl !== null && $dto->featuredImageUrl !== '') {
            $this->upsertMedia(
                jobId: (int)$row->job_id,
                stagingContentId: (int)$row->id,
                url: $dto->featuredImageUrl,
                role: 'featured',
            );
            $seen[hash('sha256', $dto->featuredImageUrl)] = true;
        }

        foreach ($this->extractInlineMediaUrls($dto->html) as $url) {
            $hash = hash('sha256', $url);
            if (isset($seen[$hash])) {
                continue;
            }
            $seen[$hash] = true;
            $this->upsertMedia(
                jobId: (int)$row->job_id,
                stagingContentId: (int)$row->id,
                url: $url,
                role: 'inline',
            );
        }
    }

    private function upsertMedia(int $jobId, int $stagingContentId, string $url, string $role): void
    {
        $hash = hash('sha256', $url);
        StagingMedia::query()->updateOrCreate(
            ['job_id' => $jobId, 'source_url_hash' => $hash],
            [
                'staging_content_id' => $stagingContentId,
                'source_url' => $url,
                'role' => $role,
            ]
        );
    }

    /**
     * Pull `<img src>` and `<a href>` URLs out of the body HTML.
     * We don't filter by extension here — the Commit step's
     * rehoster is the authority on what's an asset.
     *
     * @return list<string>
     */
    private function extractInlineMediaUrls(string $html): array
    {
        if ($html === '' || !str_contains($html, '<')) {
            return [];
        }

        $urls = [];
        $patterns = [
            '/<img\b[^>]*?\bsrc=["\']([^"\']+)["\']/i',
            '/<a\b[^>]*?\bhref=["\']([^"\']+)["\']/i',
        ];
        foreach ($patterns as $pattern) {
            if (preg_match_all($pattern, $html, $matches) && isset($matches[1])) {
                foreach ($matches[1] as $url) {
                    $url = trim($url);
                    if ($url === '') {
                        continue;
                    }
                    $urls[] = $url;
                }
            }
        }
        return array_values(array_unique($urls));
    }
}
