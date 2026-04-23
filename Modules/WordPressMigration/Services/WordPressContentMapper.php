<?php

namespace Modules\WordPressMigration\Services;

use Modules\Content\Models\Content;
use Modules\WordPressMigration\DTOs\MigrationItemDTO;
use Modules\WordPressMigration\Services\Media\HtmlMediaRewriter;
use Modules\WordPressMigration\Services\Media\MediaRehoster;

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
 * Media handling
 * --------------
 * When a {@see MediaRehoster} is supplied, the mapper rewrites
 * every `<img src>` and `<a href>` in the DTO's HTML *before*
 * the content row is finalized, so imported posts render with
 * locally-hosted assets rather than hotlinks to the origin site.
 * See {@see HtmlMediaRewriter} for the substitution rules.
 *
 * Because the rehoster's media record needs `rel_id` pointing at
 * the new content row, we save a placeholder row first, then
 * rewrite with that id in scope, then update. This two-step save
 * is intentional: rehosting before a row exists would orphan the
 * media rows if the content insert later failed, and a single
 * monolithic save would force the rehoster to guess the id.
 *
 * What this class does NOT do
 * ---------------------------
 * It does not attach categories/tags/author as taxonomies
 * (Phase 3 has a separate task for the taxonomy pass). Those
 * enrichments layer on top of the content row this class
 * produces.
 */
class WordPressContentMapper
{
    public const META_IMPORT_SOURCE = 'import_source';
    public const META_SOURCE_GUID = 'source_guid';
    public const META_SOURCE_HOST = 'source_host';

    public const IMPORT_SOURCE_WORDPRESS_RSS = 'wordpress_rss';

    private readonly HtmlMediaRewriter $rewriter;
    private readonly SourceSlugResolver $slugResolver;

    public function __construct(
        private readonly string $importSource = self::IMPORT_SOURCE_WORDPRESS_RSS,
        private readonly string $contentType = 'post',
        private readonly string $subtype = 'post',
        private readonly ?MediaRehoster $rehoster = null,
        ?HtmlMediaRewriter $rewriter = null,
        ?SourceSlugResolver $slugResolver = null,
    ) {
        $this->rewriter = $rewriter ?? new HtmlMediaRewriter();
        $this->slugResolver = $slugResolver ?? new SourceSlugResolver();
    }

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
            // Featured image first so the rehoster sees an empty
            // media attachment set for this content row; the HTML
            // rewriter runs second and will dedupe on content hash
            // if the featured image also appears inline.
            $this->rehostFeaturedImage($existing, $dto);
            $this->rewriteMediaInto($existing, $dto);
            return $existing->refresh();
        }

        $content = new Content($fields);
        // Preserve the source slug (last path segment of the
        // canonical URL) BEFORE save so that HasSlugTrait's
        // `creating` hook sees our value as the pre-assigned url and
        // skips the title-based generator. We resolve collisions
        // ourselves so the hook's `checkSlugExists` check passes
        // without triggering its `YmdHis` timestamp fallback — see
        // SourceSlugResolver for the -2/-3 strategy.
        $preservedSlug = $dto->canonicalUrl !== null
            ? $this->slugResolver->resolve((string)$dto->canonicalUrl)
            : null;
        if ($preservedSlug !== null) {
            $content->url = $preservedSlug;
        }
        $content->setContentData($this->metaFields($dto));
        $content->save();
        $this->rehostFeaturedImage($content, $dto);
        $this->rewriteMediaInto($content, $dto);
        return $content->refresh();
    }

    /**
     * Rehost the WP `featured_media` URL (if the importer found one)
     * so a `media` row is attached to the newly-saved content with
     * `rel_type` = Content::class and `rel_id` = the content's id.
     * That row is what `content_picture($id)` reaches for when a
     * frontend template renders the thumbnail — effectively Microweber's
     * "featured image" slot.
     *
     * We do this as a separate rehoster call (not via the HTML
     * rewriter) because the featured image may not appear in the
     * post body at all — many WP themes render it from the
     * featured slot only. Skipping it would leave the thumbnail empty
     * even though WP had one configured.
     */
    private function rehostFeaturedImage(Content $content, MigrationItemDTO $dto): void
    {
        if ($this->rehoster === null) {
            return;
        }
        $url = $dto->featuredImageUrl;
        if ($url === null || $url === '') {
            return;
        }
        $context = [
            'rel_type' => Content::class,
            'rel_id' => (int)$content->id,
            'role' => 'featured',
        ];
        if ($dto->sourceHost !== null && $dto->sourceHost !== '') {
            $context['host'] = $dto->sourceHost;
        }
        // Rehoster's return value is the new URL; we don't write it
        // back onto the content row because Microweber's thumbnail
        // lookup goes through the media table, not a dedicated column.
        $this->rehoster->rehost($url, $context);
    }

    /**
     * Run the media rewriter against the content's freshly-saved
     * HTML. No-op when no rehoster was supplied, or when the
     * rewritten HTML is byte-identical to the original (so we
     * don't gratuitously bump `updated_at`).
     */
    private function rewriteMediaInto(Content $content, MigrationItemDTO $dto): void
    {
        if ($this->rehoster === null) {
            return;
        }
        $original = (string)$content->content;
        if ($original === '') {
            return;
        }

        $context = [
            'rel_type' => Content::class,
            'rel_id' => (int)$content->id,
        ];
        if ($dto->sourceHost !== null && $dto->sourceHost !== '') {
            $context['host'] = $dto->sourceHost;
        }

        $rewritten = $this->rewriter->rewrite($original, $this->rehoster, $context);
        if ($rewritten === $original) {
            return;
        }

        $content->content = $rewritten;
        $content->save();
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
