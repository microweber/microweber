<?php

namespace Modules\WordPressMigration\Services;

use Modules\Content\Models\Content;
use Modules\WordPressMigration\DTOs\MigrationItemDTO;

/**
 * Turn a normalized {@see MigrationItemDTO} into a row on the
 * Microweber `content` table, annotating it with `content_data`
 * rows that identify the import source and the remote object id.
 *
 * Idempotency contract
 * --------------------
 * The pair (`import_source`, `source_guid`) in `content_data` is
 * the stable identity key across runs. Calling {@see map()} twice
 * for the same DTO MUST produce the same `content.id` — we do a
 * lookup by those two meta values before creating a new row, and
 * on a hit we refresh the content fields (title, html, etc.) so
 * upstream edits propagate while the local id and URL stay pinned.
 *
 * The source key we write is literally `wordpress_rss` (Phase 3
 * task language). When the REST and WXR importers land they'll
 * reuse this mapper and pass their own source string — keep the
 * value free-form here, the constants live on the importers that
 * own them.
 *
 * Why content_data rather than a new column?
 * ------------------------------------------
 * Microweber already uses `content_data` for arbitrary per-row
 * key/value extensions (see {@see \Modules\ContentData\Traits\ContentDataTrait}),
 * so we avoid a new migration and inherit deletion-cascade for
 * free. The two keys we care about — `import_source` and
 * `source_guid` — are queried together via `whereContentData`,
 * which already indexes on (field_name, field_value).
 *
 * What this class does NOT do
 * ---------------------------
 * It does not download or rewrite embedded media (that's the
 * Phase 7 MediaRehoster + HtmlMediaRewriter), and it does not
 * attach categories/tags/author as taxonomies (Phase 3 has a
 * separate task for the taxonomy pass). Those enrichments layer
 * on top of the content row this class produces.
 */
class WordPressContentMapper
{
    public const META_IMPORT_SOURCE = 'import_source';
    public const META_SOURCE_GUID = 'source_guid';
    public const META_SOURCE_HOST = 'source_host';

    public const IMPORT_SOURCE_WORDPRESS_RSS = 'wordpress_rss';

    public function __construct(
        private readonly string $importSource = self::IMPORT_SOURCE_WORDPRESS_RSS,
        private readonly string $contentType = 'post',
        private readonly string $subtype = 'post',
    ) {}

    /**
     * Upsert the DTO onto `content`. Returns the persisted row so
     * the caller can attach taxonomies / log the insert-vs-update
     * outcome.
     *
     * @param MigrationItemDTO $dto
     */
    public function map(MigrationItemDTO $dto): Content
    {
        $existing = $this->findExisting($dto->guid);

        $fields = $this->contentFields($dto);

        if ($existing !== null) {
            $existing->fill($fields);
            // Re-apply meta each time so a change of importSource
            // (e.g. switching a job from rss → rest) overwrites
            // rather than leaves stale provenance behind.
            $existing->setContentData($this->metaFields($dto));
            $existing->save();
            return $existing->refresh();
        }

        $content = new Content($fields);
        $content->setContentData($this->metaFields($dto));
        $content->save();
        return $content->refresh();
    }

    /**
     * Locate a previously-imported content row by its
     * (import_source, source_guid) content-data pair. Returns null
     * if no such row exists.
     *
     * Exposed (rather than kept private) so the Phase 3 job
     * runner can cheaply decide "skip — already imported" without
     * hydrating the full content row twice.
     */
    public function findExisting(string $guid): ?Content
    {
        if ($guid === '') {
            return null;
        }

        return Content::query()
            ->whereContentData([
                self::META_IMPORT_SOURCE => $this->importSource,
                self::META_SOURCE_GUID => $guid,
            ])
            ->first();
    }

    /**
     * @return array<string, mixed>
     */
    private function contentFields(MigrationItemDTO $dto): array
    {
        return [
            'title' => $dto->title,
            'content' => $dto->html,
            'description' => $dto->excerpt,
            'content_type' => $this->contentType,
            'subtype' => $this->subtype,
            'original_link' => $dto->canonicalUrl,
            'is_active' => 1,
            'is_deleted' => 0,
            'posted_at' => $dto->publishedAt?->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * @return array<string, string>
     */
    private function metaFields(MigrationItemDTO $dto): array
    {
        $meta = [
            self::META_IMPORT_SOURCE => $this->importSource,
            self::META_SOURCE_GUID => $dto->guid,
        ];
        if ($dto->sourceHost !== null && $dto->sourceHost !== '') {
            $meta[self::META_SOURCE_HOST] = $dto->sourceHost;
        }
        return $meta;
    }
}
