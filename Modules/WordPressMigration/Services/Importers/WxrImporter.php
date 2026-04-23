<?php

namespace Modules\WordPressMigration\Services\Importers;

use DateTimeImmutable;
use Modules\WordPressMigration\DTOs\MigrationItemDTO;
use Modules\WordPressMigration\DTOs\WxrImportResult;
use SimpleXMLElement;
use XMLReader;

/**
 * Offline WordPress eXtended RSS (`.xml`) importer.
 *
 * Consumes a WXR file the operator uploaded through the Filament
 * form and produces the same {@see MigrationItemDTO} stream the
 * {@see WpRestImporter} emits — so everything downstream (
 * {@see \Modules\WordPressMigration\Services\Taxonomy\TaxonomyIndex},
 * {@see \Modules\WordPressMigration\Services\WordPressContentMapper},
 * the media rehoster, the Filament preview) is reused as-is. No
 * HTTP fetcher, no credentials, no pagination: a WXR file is
 * entirely self-contained and the parser's only job is to lift its
 * `<item>` elements into DTOs.
 *
 * Two-pass streaming parse
 * ------------------------
 * WXR files for a busy blog regularly run into the hundreds of
 * megabytes, so the parser MUST NOT load the whole document into
 * memory. It runs two passes over the file:
 *
 *   1. **Channel pre-scan**: walks the document with
 *      {@see XMLReader} and lifts the channel-level `<wp:category>`,
 *      `<wp:tag>`, and `<wp:author>` blocks into id/slug lookup
 *      tables. These blocks are cheap (a handful of KB on even a
 *      huge blog) and reading them once up front lets the item
 *      pass resolve author + term references without a second
 *      traversal.
 *   2. **Item stream**: continues the same XMLReader past the
 *      channel preamble, expanding one `<item>` at a time via
 *      {@see XMLReader::expand()} into a tiny SimpleXMLElement.
 *      Memory stays bounded by the size of one item rather than
 *      the whole file.
 *
 * The two passes share one XMLReader, advancing forward through
 * the file — we never rewind, which is what lets the parser
 * tolerate files that are too big to `simplexml_load_file`.
 *
 * Idempotency key parity with REST
 * --------------------------------
 * Each item's guid is `wp:{wp:post_id}`, same shape the REST
 * importer emits, so a WXR import followed by a REST re-sync
 * over the same site wires up to the same `content_data`
 * identity rows — no duplicates, no orphaned re-imports. The
 * DTO's `source` field is `wxr` instead of `rest` so warnings
 * and progress messages can disambiguate, but dedupe keys off
 * `(import_source, source_guid)` on the content_data side and
 * that pair is the mapper's concern, not the importer's.
 *
 * Filtered item types
 * -------------------
 * WordPress's `<wp:post_type>` vocabulary includes the obvious
 * `post`, `page`, `attachment` plus a handful of housekeeping
 * types (`nav_menu_item`, `revision`, `oembed_cache`, …). The
 * importer lifts posts + pages into MigrationItemDTOs and
 * attachments into the `media` side-channel; every other
 * `post_type` is discarded with a debug counter so the operator
 * can see "500 items in file, 420 emitted" when the difference
 * matters.
 */
class WxrImporter
{
    private const SOURCE = 'wxr';

    /** Namespace WP uses on WXR export fields. */
    private const NS_WP = 'http://wordpress.org/export/1.2/';
    /** Content:encoded (the post body) — same namespace RSS 2 uses. */
    private const NS_CONTENT = 'http://purl.org/rss/1.0/modules/content/';
    /** dc:creator (author login, not display name). */
    private const NS_DC = 'http://purl.org/dc/elements/1.1/';
    /** excerpt:encoded — the WP-specific post excerpt field. */
    private const NS_EXCERPT = 'http://wordpress.org/export/1.2/excerpt/';

    /**
     * `<wp:post_status>` values the importer treats as publishable.
     * Anything else (draft, auto-draft, trash, pending, inherit on
     * non-attachment rows, …) is skipped so the mapper only ever
     * sees content the operator actually intended to ship.
     */
    private const PUBLISHED_STATUSES = ['publish', 'private'];

    /**
     * Parse a WXR file at `$path` into a {@see WxrImportResult}.
     *
     * Missing files, files that are not WP WXR exports, and files
     * the caller's cap says to skip (`$maxItems <= 0`) all short-
     * circuit to `STOP_UNREACHABLE` with an empty result — callers
     * that need to distinguish those reasons should check the
     * `warnings` list.
     *
     * @param list<string> $seenGuids Guids already imported in previous runs (`wp:{post_id}` shape — matches WpRestImporter)
     * @param int $maxItems Cap on collected posts+pages; attachments count separately toward the media bucket
     */
    public function import(string $path, array $seenGuids = [], int $maxItems = 10000): WxrImportResult
    {
        $path = trim($path);
        if ($path === '' || !is_file($path) || !is_readable($path)) {
            return new WxrImportResult(
                items: [],
                stopReason: WxrImportResult::STOP_UNREACHABLE,
                warnings: ["WXR file not found or unreadable: {$path}"],
            );
        }
        if ($maxItems <= 0) {
            return new WxrImportResult(
                items: [],
                stopReason: WxrImportResult::STOP_MAX_ITEMS,
            );
        }

        return $this->parse($path, $seenGuids, $maxItems);
    }

    /**
     * Same as {@see import()} but reads from a pre-loaded string.
     * Primarily used by unit tests so fixtures can live inline; the
     * production import path always goes through {@see import()}
     * because multi-hundred-MB strings are exactly what we're trying
     * to avoid.
     */
    public function importString(string $xml, array $seenGuids = [], int $maxItems = 10000): WxrImportResult
    {
        $xml = trim($xml);
        if ($xml === '') {
            return new WxrImportResult(
                items: [],
                stopReason: WxrImportResult::STOP_UNREACHABLE,
                warnings: ['WXR payload is empty'],
            );
        }
        if ($maxItems <= 0) {
            return new WxrImportResult(
                items: [],
                stopReason: WxrImportResult::STOP_MAX_ITEMS,
            );
        }

        // XMLReader::xml() consumes the string in the same streaming
        // mode it would a file — we just have to hand it a buffer
        // instead of a path. Suppressed so malformed XML surfaces as
        // a typed result rather than a PHP warning.
        return $this->parseWith(
            fn (XMLReader $r) => @$r->xml($xml, 'UTF-8', LIBXML_NOCDATA | LIBXML_NONET),
            $seenGuids,
            $maxItems,
            $xml,
        );
    }

    /**
     * @param list<string> $seenGuids
     */
    private function parse(string $path, array $seenGuids, int $maxItems): WxrImportResult
    {
        return $this->parseWith(
            fn (XMLReader $r) => @$r->open($path, 'UTF-8', LIBXML_NOCDATA | LIBXML_NONET),
            $seenGuids,
            $maxItems,
            $path,
        );
    }

    /**
     * Shared driver for both the file and string parse paths.
     *
     * @param callable(XMLReader): bool $opener Binds the reader to its source
     * @param list<string> $seenGuids
     */
    private function parseWith(
        callable $opener,
        array $seenGuids,
        int $maxItems,
        string $sourceLabel,
    ): WxrImportResult {
        $reader = new XMLReader();
        // XMLReader::expand() returns a DOMNode detached from any
        // document unless we hand it a DOMDocument to own the node.
        // simplexml_import_dom() silently fails on a detached node,
        // so one document owned by the parser is mandatory.
        $expansionDoc = new \DOMDocument();
        if ($opener($reader) === false) {
            return new WxrImportResult(
                items: [],
                stopReason: WxrImportResult::STOP_UNREACHABLE,
                warnings: ["Unable to open WXR source: {$sourceLabel}"],
            );
        }

        $warnings = [];
        $categories = [];
        $tags = [];
        $users = [];
        $media = [];
        $items = [];
        $seen = self::normalizeSeen($seenGuids);
        $sourceHost = null;
        $itemsSeen = 0;
        $stop = WxrImportResult::STOP_COMPLETE;

        // libxml warnings on unknown entities in hostile WXRs (e.g.
        // raw &nbsp; in a description) would spew onto stderr without
        // `libxml_use_internal_errors(true)` — keep them buffered and
        // discard, since the reader already emits a parsed value for
        // anything the libxml loader could salvage.
        $previousLibXml = libxml_use_internal_errors(true);

        try {
            while ($reader->read()) {
                if ($reader->nodeType !== XMLReader::ELEMENT) {
                    continue;
                }
                switch ($reader->name) {
                    case 'wp:base_site_url':
                    case 'wp:base_blog_url':
                        if ($sourceHost === null) {
                            $sourceHost = self::extractHost((string)$reader->readString());
                        }
                        break;

                    case 'link':
                        // Channel-level <link> at RSS 2.0 top level —
                        // only capture it before we've entered an item
                        // (items also carry a <link>).
                        if ($sourceHost === null && $reader->depth === 2) {
                            $sourceHost = self::extractHost((string)$reader->readString());
                        }
                        break;

                    case 'wp:category':
                        $category = $this->readChannelChildren($reader, [
                            'term_id' => 'id',
                            'category_nicename' => 'slug',
                            'cat_name' => 'name',
                            'category_parent' => 'parent',
                            'category_description' => 'description',
                        ]);
                        if ($category !== []) {
                            $categories[] = $category;
                        }
                        break;

                    case 'wp:tag':
                        $tag = $this->readChannelChildren($reader, [
                            'term_id' => 'id',
                            'tag_slug' => 'slug',
                            'tag_name' => 'name',
                            'tag_description' => 'description',
                        ]);
                        if ($tag !== []) {
                            $tags[] = $tag;
                        }
                        break;

                    case 'wp:author':
                        $author = $this->readChannelChildren($reader, [
                            'author_id' => 'id',
                            'author_login' => 'login',
                            'author_email' => 'email',
                            'author_display_name' => 'display_name',
                            'author_first_name' => 'first_name',
                            'author_last_name' => 'last_name',
                        ]);
                        if ($author !== []) {
                            // TaxonomyIndex keys users by `slug`;
                            // author_login is WP's durable username
                            // and matches what `/wp-json/wp/v2/users`
                            // emits as `slug`.
                            if (isset($author['login'])) {
                                $author['slug'] = $author['login'];
                            }
                            if (isset($author['display_name']) && $author['display_name'] !== '') {
                                $author['name'] = $author['display_name'];
                            } elseif (isset($author['login'])) {
                                $author['name'] = $author['login'];
                            }
                            $users[] = $author;
                        }
                        break;

                    case 'item':
                        $itemsSeen++;
                        $node = $reader->expand($expansionDoc);
                        if (!($node instanceof \DOMNode)) {
                            break;
                        }
                        $sx = @simplexml_import_dom($node);
                        if (!($sx instanceof SimpleXMLElement)) {
                            break;
                        }
                        $classified = $this->classifyItem($sx);
                        if ($classified === 'attachment') {
                            $row = $this->readAttachment($sx);
                            if ($row !== null) {
                                $media[] = $row;
                            }
                            // Still count toward itemsSeen, but don't
                            // bump the content cap — media is tracked
                            // separately.
                            break;
                        }
                        if ($classified === null) {
                            // Skipped post type (revision, nav_menu_item,
                            // draft, …). Don't warn per-item — that
                            // would spam on big exports — but the
                            // itemsSeen vs items size gap makes the
                            // skip visible at the result level.
                            break;
                        }
                        $dto = $this->itemToDto($sx, $sourceHost);
                        if ($dto === null) {
                            break;
                        }
                        if (isset($seen[$dto->guid])) {
                            break;
                        }
                        $items[] = $dto;
                        $seen[$dto->guid] = true;
                        if (count($items) >= $maxItems) {
                            $stop = WxrImportResult::STOP_MAX_ITEMS;
                            break 2;
                        }
                        break;
                }
            }
            // A fatal libxml error (unclosed tag, bad entity, truncated
            // file) leaves the reader wedged at its last successful
            // node — the while() above simply exits. We surface that
            // as UNREACHABLE so the UI doesn't report "0 items found"
            // for what was really a broken upload.
            foreach (libxml_get_errors() as $err) {
                if ($err->level === LIBXML_ERR_FATAL) {
                    $stop = WxrImportResult::STOP_UNREACHABLE;
                    $warnings[] = 'WXR source is not well-formed XML: ' . trim($err->message);
                    break;
                }
            }
        } finally {
            $reader->close();
            libxml_clear_errors();
            libxml_use_internal_errors($previousLibXml);
        }

        // Resolve term slugs per-item now that we have the channel
        // index. The parser above left dto.categorySlugs/tagSlugs
        // empty because we can't guarantee the category block comes
        // before the first item in a WXR; we walk the collected
        // items once more in memory here (cheap — the DTO list is
        // already bounded by maxItems).
        $items = $this->enrichTermSlugs($items, $categories, $tags);

        if ($categories === []) {
            $warnings[] = 'WXR file has no <wp:category> blocks — category slugs will be derived from item category nicenames';
        }
        if ($users === []) {
            $warnings[] = 'WXR file has no <wp:author> blocks — author attribution will fall back to dc:creator';
        }

        return new WxrImportResult(
            items: $items,
            stopReason: $stop,
            media: $media,
            menus: null,
            warnings: $warnings,
            categories: $categories,
            tags: $tags,
            users: $users,
            itemsSeen: $itemsSeen,
        );
    }

    /**
     * Read a channel-level WXR block (`<wp:category>`, `<wp:tag>`,
     * `<wp:author>`) by advancing through its direct children and
     * collecting the ones listed in `$keyMap`. Advancing the reader
     * forward is what makes this O(1) in memory — we never expand
     * the whole block into a DOM tree.
     *
     * @param array<string, string> $keyMap WP element local name → result key (e.g. `term_id` → `id`)
     * @return array<string, string>
     */
    private function readChannelChildren(XMLReader $reader, array $keyMap): array
    {
        $out = [];
        if ($reader->isEmptyElement) {
            return $out;
        }
        $initialDepth = $reader->depth;
        while ($reader->read()) {
            if ($reader->nodeType === XMLReader::END_ELEMENT && $reader->depth === $initialDepth) {
                break;
            }
            if ($reader->nodeType !== XMLReader::ELEMENT) {
                continue;
            }
            $local = $reader->localName;
            if (!isset($keyMap[$local])) {
                continue;
            }
            $value = trim((string)$reader->readString());
            if ($value !== '') {
                $out[$keyMap[$local]] = $value;
            }
        }
        return $out;
    }

    /**
     * Return `post`, `page`, `attachment`, or null (skip).
     */
    private function classifyItem(SimpleXMLElement $item): ?string
    {
        $wp = $item->children(self::NS_WP);
        $type = trim((string)($wp->post_type ?? ''));
        $status = strtolower(trim((string)($wp->status ?? '')));

        if ($type === 'attachment') {
            // Attachments legitimately have status=inherit. Accept
            // any status here; the caller filters by URL.
            return 'attachment';
        }
        if ($type !== 'post' && $type !== 'page') {
            return null;
        }
        if ($status !== '' && !in_array($status, self::PUBLISHED_STATUSES, true)) {
            return null;
        }
        return $type;
    }

    /**
     * Lift an attachment <item> into a flat media row — shape matches
     * the WpRestImportResult::media entries so downstream code can
     * consume both side-by-side.
     *
     * @return array<string, mixed>|null
     */
    private function readAttachment(SimpleXMLElement $item): ?array
    {
        $wp = $item->children(self::NS_WP);

        $sourceUrl = trim((string)($wp->attachment_url ?? ''));
        if ($sourceUrl === '') {
            return null;
        }
        $id = (int)trim((string)($wp->post_id ?? 0));
        $title = trim((string)($item->title ?? ''));
        $postParent = (int)trim((string)($wp->post_parent ?? 0));

        return [
            'id' => $id,
            'title' => ['rendered' => $title],
            'slug' => trim((string)($wp->post_name ?? '')),
            'source_url' => $sourceUrl,
            'post' => $postParent > 0 ? $postParent : null,
            'mime_type' => self::sniffMimeFromUrl($sourceUrl),
            'media_type' => 'attachment',
        ];
    }

    /**
     * Map one published post/page `<item>` to a DTO. Returns null
     * when the item lacks a usable guid (no wp:post_id AND no link).
     */
    private function itemToDto(SimpleXMLElement $item, ?string $sourceHost): ?MigrationItemDTO
    {
        $wp = $item->children(self::NS_WP);
        $content = $item->children(self::NS_CONTENT);
        $dc = $item->children(self::NS_DC);
        $excerpt = $item->children(self::NS_EXCERPT);

        $postId = trim((string)($wp->post_id ?? ''));
        $link = trim((string)($item->link ?? ''));
        if ($postId === '' && $link === '') {
            return null;
        }
        $guid = $postId !== '' ? 'wp:' . $postId : $link;

        $title = trim((string)($item->title ?? ''));
        $html = (string)($content->encoded ?? '');
        $excerptValue = trim((string)($excerpt->encoded ?? ''));
        $description = isset($item->description) ? trim((string)$item->description) : '';
        if ($excerptValue === '' && $description !== '') {
            // WP occasionally fills <description> instead of
            // excerpt:encoded (older exports). Prefer the explicit
            // excerpt when both are present, but accept the fallback.
            $excerptValue = $description;
        }

        $publishedAt = null;
        $postDateGmt = trim((string)($wp->post_date_gmt ?? ''));
        if ($postDateGmt !== '' && $postDateGmt !== '0000-00-00 00:00:00') {
            $publishedAt = self::parseDate($postDateGmt . ' UTC');
        }
        if ($publishedAt === null) {
            $postDate = trim((string)($wp->post_date ?? ''));
            if ($postDate !== '' && $postDate !== '0000-00-00 00:00:00') {
                $publishedAt = self::parseDate($postDate);
            }
        }
        if ($publishedAt === null && isset($item->pubDate)) {
            $publishedAt = self::parseDate((string)$item->pubDate);
        }

        $authorLogin = trim((string)($dc->creator ?? ''));
        $author = $authorLogin !== '' ? $authorLogin : null;

        [$categories, $tags, $categorySlugs, $tagSlugs] = self::readItemTerms($item);

        return new MigrationItemDTO(
            guid: $guid,
            title: $title !== '' ? $title : '(untitled)',
            html: $html,
            excerpt: $excerptValue !== '' ? $excerptValue : null,
            author: $author,
            categories: $categories,
            tags: $tags,
            publishedAt: $publishedAt,
            canonicalUrl: $link !== '' ? $link : null,
            source: self::SOURCE,
            sourceHost: $sourceHost,
            featuredImageUrl: self::featuredImageFromMeta($item),
            categorySlugs: $categorySlugs,
            tagSlugs: $tagSlugs,
            authorSlug: $authorLogin !== '' ? $authorLogin : null,
        );
    }

    /**
     * Walk item-level `<category>` elements and split by `domain`
     * attribute, same logic the RSS importer uses — WXR emits
     * category taxonomy as `domain="category"` and tags as
     * `domain="post_tag"`, with the term slug in the `nicename`
     * attribute.
     *
     * @return array{0: list<string>, 1: list<string>, 2: list<string>, 3: list<string>}
     */
    private static function readItemTerms(SimpleXMLElement $item): array
    {
        $categoryNames = [];
        $tagNames = [];
        $categorySlugs = [];
        $tagSlugs = [];
        foreach ($item->category as $cat) {
            $value = trim((string)$cat);
            $domain = (string)($cat['domain'] ?? '');
            $nicename = trim((string)($cat['nicename'] ?? ''));
            if ($value === '' && $nicename === '') {
                continue;
            }
            $displayName = $value !== '' ? $value : $nicename;
            if ($domain === 'post_tag') {
                $tagNames[] = $displayName;
                if ($nicename !== '') {
                    $tagSlugs[] = $nicename;
                }
            } else {
                $categoryNames[] = $displayName;
                if ($nicename !== '') {
                    $categorySlugs[] = $nicename;
                }
            }
        }
        return [
            array_values(array_unique($categoryNames)),
            array_values(array_unique($tagNames)),
            array_values(array_unique($categorySlugs)),
            array_values(array_unique($tagSlugs)),
        ];
    }

    /**
     * `_thumbnail_id` is WP's meta key for the featured-image
     * attachment ID. WXR includes it as `<wp:postmeta>` inside the
     * item. We only have enough info to record the attachment id
     * here; resolving it to a URL is the mapper's job once the
     * media side-channel has been indexed.
     */
    private static function featuredImageFromMeta(SimpleXMLElement $item): ?string
    {
        $wp = $item->children(self::NS_WP);
        foreach ($wp->postmeta as $meta) {
            $metaChildren = $meta->children(self::NS_WP);
            $key = trim((string)($metaChildren->meta_key ?? ''));
            if ($key !== '_thumbnail_id') {
                continue;
            }
            $value = trim((string)($metaChildren->meta_value ?? ''));
            if ($value !== '' && ctype_digit($value)) {
                // Prefix keeps this distinguishable from a real URL
                // so the mapper can tell it still needs resolving.
                return 'wp-attachment:' . $value;
            }
        }
        return null;
    }

    /**
     * Backfill dto.categorySlugs/tagSlugs from the channel index
     * when the item's own `<category nicename="…">` attribute was
     * empty — older exporters sometimes omit nicename and rely on
     * the channel block to canonicalize.
     *
     * @param list<MigrationItemDTO> $items
     * @param list<array<string, mixed>> $categories
     * @param list<array<string, mixed>> $tags
     * @return list<MigrationItemDTO>
     */
    private function enrichTermSlugs(array $items, array $categories, array $tags): array
    {
        if ($items === []) {
            return $items;
        }

        $catByName = [];
        foreach ($categories as $row) {
            $name = isset($row['name']) ? strtolower((string)$row['name']) : '';
            $slug = isset($row['slug']) ? (string)$row['slug'] : '';
            if ($name !== '' && $slug !== '') {
                $catByName[$name] = $slug;
            }
        }
        $tagByName = [];
        foreach ($tags as $row) {
            $name = isset($row['name']) ? strtolower((string)$row['name']) : '';
            $slug = isset($row['slug']) ? (string)$row['slug'] : '';
            if ($name !== '' && $slug !== '') {
                $tagByName[$name] = $slug;
            }
        }

        if ($catByName === [] && $tagByName === []) {
            return $items;
        }

        $out = [];
        foreach ($items as $dto) {
            $catSlugs = $dto->categorySlugs;
            $tagSlugs = $dto->tagSlugs;

            if ($catSlugs === [] && $dto->categories !== []) {
                foreach ($dto->categories as $name) {
                    $resolved = $catByName[strtolower($name)] ?? null;
                    if ($resolved !== null) {
                        $catSlugs[] = $resolved;
                    }
                }
            }
            if ($tagSlugs === [] && $dto->tags !== []) {
                foreach ($dto->tags as $name) {
                    $resolved = $tagByName[strtolower($name)] ?? null;
                    if ($resolved !== null) {
                        $tagSlugs[] = $resolved;
                    }
                }
            }

            if ($catSlugs === $dto->categorySlugs && $tagSlugs === $dto->tagSlugs) {
                $out[] = $dto;
                continue;
            }

            $out[] = new MigrationItemDTO(
                guid: $dto->guid,
                title: $dto->title,
                html: $dto->html,
                excerpt: $dto->excerpt,
                author: $dto->author,
                categories: $dto->categories,
                tags: $dto->tags,
                publishedAt: $dto->publishedAt,
                canonicalUrl: $dto->canonicalUrl,
                source: $dto->source,
                sourceHost: $dto->sourceHost,
                featuredImageUrl: $dto->featuredImageUrl,
                categorySlugs: array_values(array_unique($catSlugs)),
                tagSlugs: array_values(array_unique($tagSlugs)),
                authorSlug: $dto->authorSlug,
            );
        }
        return $out;
    }

    /**
     * @param list<string> $seenGuids
     * @return array<string, true>
     */
    private static function normalizeSeen(array $seenGuids): array
    {
        $map = [];
        foreach ($seenGuids as $g) {
            if (is_string($g) && $g !== '') {
                $map[$g] = true;
            }
        }
        return $map;
    }

    private static function parseDate(string $raw): ?DateTimeImmutable
    {
        $raw = trim($raw);
        if ($raw === '') {
            return null;
        }
        try {
            return new DateTimeImmutable($raw);
        } catch (\Throwable) {
            return null;
        }
    }

    private static function extractHost(string $raw): ?string
    {
        $raw = trim($raw);
        if ($raw === '') {
            return null;
        }
        $host = parse_url($raw, PHP_URL_HOST);
        if (!is_string($host) || $host === '') {
            return null;
        }
        return strtolower($host);
    }

    /**
     * Very small file-extension → MIME lookup. WXR doesn't carry the
     * MIME type on `<item>` rows, so the best we can do without
     * fetching the asset is guess from the extension. The media
     * rehoster does a HEAD-based sniff of its own for canonicalization,
     * this is just a sensible default.
     */
    private static function sniffMimeFromUrl(string $url): ?string
    {
        $path = parse_url($url, PHP_URL_PATH);
        if (!is_string($path)) {
            return null;
        }
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        return match ($ext) {
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'svg' => 'image/svg+xml',
            'mp4' => 'video/mp4',
            'pdf' => 'application/pdf',
            default => null,
        };
    }
}
