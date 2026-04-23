<?php

namespace Modules\WordPressMigration\Services\Importers;

use DateTimeImmutable;
use Modules\WordPressMigration\DTOs\MigrationItemDTO;
use Modules\WordPressMigration\Services\Http\CurlHttpProbeFetcher;
use Modules\WordPressMigration\Services\Http\HttpProbeFetcher;
use SimpleXMLElement;

/**
 * Parse a WordPress site's RSS (`<url>/feed/`) and Atom
 * (`<url>/feed/atom/`) feeds into a stream of
 * {@see MigrationItemDTO}.
 *
 * The importer is deliberately narrow for this Phase 3 task:
 * fetch one feed URL, try the Atom fallback if RSS isn't there,
 * parse the response, and yield DTOs. Pagination, dedupe against
 * already-imported guids, HTML asset rehosting, and insertion
 * into Microweber's `content` table are separate Phase 3 tasks
 * and live in their own services — the `import()` return type is
 * `iterable` precisely so a future paginator can be layered on
 * top without touching this class.
 *
 * HTTP is injected via the existing {@see HttpProbeFetcher}
 * contract rather than a second near-identical interface — the
 * probe's return shape (body/http_code/headers/error with no
 * exceptions on 4xx/5xx) is exactly what the importer wants. The
 * bundled {@see \Modules\WordPressMigration\Tests\Support\FakeHttpProbeFetcher}
 * is reused by the importer's unit tests for the same reason.
 */
class RssFeedImporter
{
    private const FETCH_TIMEOUT_SECONDS = 30;
    private const SOURCE = 'rss';

    private HttpProbeFetcher $fetcher;

    public function __construct(?HttpProbeFetcher $fetcher = null)
    {
        $this->fetcher = $fetcher ?? new CurlHttpProbeFetcher();
    }

    /**
     * Fetch and normalize the RSS 2.0 feed at `<baseUrl>/feed/`.
     * If that endpoint is missing or returns a non-RSS payload,
     * retry once against `<baseUrl>/feed/atom/` and parse as Atom.
     *
     * Returns an empty list on total failure — callers that need
     * to distinguish "feed empty" from "feed unreachable" should
     * probe first (see {@see \Modules\WordPressMigration\Services\WordPressSiteProbe}).
     *
     * @return list<MigrationItemDTO>
     */
    public function import(string $baseUrl): array
    {
        $base = self::normalizeBase($baseUrl);
        if ($base === null) {
            return [];
        }
        $host = (string)parse_url($base, PHP_URL_HOST) ?: null;

        $rssUrl = $base . '/feed/';
        $rssResp = $this->fetcher->fetch($rssUrl, self::FETCH_TIMEOUT_SECONDS);

        $items = [];
        if ($this->isSuccess($rssResp) && self::looksLikeRss($rssResp['body'])) {
            $items = $this->parseRss($rssResp['body'], $host);
        }

        if (empty($items)) {
            $atomUrl = $base . '/feed/atom/';
            $atomResp = $this->fetcher->fetch($atomUrl, self::FETCH_TIMEOUT_SECONDS);
            if ($this->isSuccess($atomResp) && self::looksLikeAtom($atomResp['body'])) {
                $items = $this->parseAtom($atomResp['body'], $host);
            }
        }

        return $items;
    }

    /**
     * Parse an RSS 2.0 body directly — the public entrypoint for
     * callers (tests, WXR paths, paginators) that already hold the
     * bytes and don't want another HTTP round-trip.
     *
     * @return list<MigrationItemDTO>
     */
    public function parseRss(string $xml, ?string $sourceHost = null): array
    {
        $root = self::safeLoadXml($xml);
        if ($root === null) {
            return [];
        }

        $channel = $root->channel ?? null;
        if (!$channel instanceof SimpleXMLElement) {
            return [];
        }

        $out = [];
        foreach ($channel->item as $item) {
            $dto = $this->rssItemToDto($item, $sourceHost);
            if ($dto !== null) {
                $out[] = $dto;
            }
        }
        return $out;
    }

    /**
     * Parse an Atom 1.0 body directly.
     *
     * @return list<MigrationItemDTO>
     */
    public function parseAtom(string $xml, ?string $sourceHost = null): array
    {
        $root = self::safeLoadXml($xml);
        if ($root === null) {
            return [];
        }

        $out = [];
        foreach ($root->entry as $entry) {
            $dto = $this->atomEntryToDto($entry, $sourceHost);
            if ($dto !== null) {
                $out[] = $dto;
            }
        }
        return $out;
    }

    /**
     * Map one RSS `<item>` to a DTO, preferring
     * `content:encoded` over the plain `<description>` as the body
     * (WordPress emits the full post HTML in `content:encoded` and
     * only a short excerpt in `<description>`).
     */
    private function rssItemToDto(SimpleXMLElement $item, ?string $sourceHost): ?MigrationItemDTO
    {
        $namespaces = $item->getNamespaces(true);
        $contentNs = $namespaces['content'] ?? 'http://purl.org/rss/1.0/modules/content/';
        $dcNs = $namespaces['dc'] ?? 'http://purl.org/dc/elements/1.1/';
        $wpNs = $namespaces['wp'] ?? 'http://wordpress.org/export/1.2/';

        $contentChildren = $item->children($contentNs);
        $dcChildren = $item->children($dcNs);
        $wpChildren = $item->children($wpNs);

        $encoded = isset($contentChildren->encoded) ? (string)$contentChildren->encoded : '';
        $description = isset($item->description) ? (string)$item->description : '';

        $html = $encoded !== '' ? $encoded : $description;
        // When content:encoded is present WP uses <description> as the
        // excerpt. With only <description> we have no separate excerpt.
        $excerpt = ($encoded !== '' && $description !== '') ? $description : null;

        $title = trim((string)($item->title ?? ''));

        $link = isset($item->link) ? trim((string)$item->link) : null;
        $guidAttr = $item->guid->attributes() ?? null;
        $guidValue = isset($item->guid) ? trim((string)$item->guid) : '';
        $guid = $guidValue !== '' ? $guidValue : $link;

        // WXR-style <wp:post_id> is the most stable id WP offers;
        // prefer it over the URL-shaped <guid> when both are present.
        $wpPostId = isset($wpChildren->post_id) ? trim((string)$wpChildren->post_id) : '';
        if ($wpPostId !== '') {
            $guid = 'wp:' . $wpPostId;
        }

        if ($guid === null || $guid === '') {
            // No stable identity — downstream dedupe can't work safely.
            return null;
        }

        $author = null;
        if (isset($dcChildren->creator)) {
            $author = trim((string)$dcChildren->creator);
        } elseif (isset($item->author)) {
            $author = trim((string)$item->author);
        }
        if ($author === '') {
            $author = null;
        }

        [$categories, $tags] = self::splitCategoriesAndTags($item);

        $pubDate = null;
        if (isset($item->pubDate)) {
            $pubDate = self::parseDate((string)$item->pubDate);
        }
        if ($pubDate === null && isset($dcChildren->date)) {
            $pubDate = self::parseDate((string)$dcChildren->date);
        }

        return new MigrationItemDTO(
            guid: $guid,
            title: $title,
            html: $html,
            excerpt: $excerpt,
            author: $author,
            categories: $categories,
            tags: $tags,
            publishedAt: $pubDate,
            canonicalUrl: $link ?: null,
            source: self::SOURCE,
            sourceHost: $sourceHost,
        );
    }

    private function atomEntryToDto(SimpleXMLElement $entry, ?string $sourceHost): ?MigrationItemDTO
    {
        $id = isset($entry->id) ? trim((string)$entry->id) : '';
        if ($id === '') {
            return null;
        }

        $title = trim((string)($entry->title ?? ''));

        $canonicalUrl = null;
        foreach ($entry->link as $link) {
            $rel = (string)($link['rel'] ?? 'alternate');
            if ($rel === 'alternate' && isset($link['href'])) {
                $canonicalUrl = (string)$link['href'];
                break;
            }
        }
        if ($canonicalUrl === null && isset($entry->link['href'])) {
            $canonicalUrl = (string)$entry->link['href'];
        }

        $html = '';
        if (isset($entry->content)) {
            $html = self::innerXml($entry->content);
        }
        if ($html === '' && isset($entry->summary)) {
            $html = self::innerXml($entry->summary);
        }

        $excerpt = null;
        if (isset($entry->summary) && isset($entry->content)) {
            $excerpt = trim((string)$entry->summary) ?: null;
        }

        $author = null;
        if (isset($entry->author) && isset($entry->author->name)) {
            $author = trim((string)$entry->author->name) ?: null;
        }

        $categories = [];
        foreach ($entry->category as $cat) {
            $term = (string)($cat['term'] ?? $cat['label'] ?? '');
            if ($term !== '') {
                $categories[] = $term;
            }
        }

        $pubDate = null;
        if (isset($entry->published)) {
            $pubDate = self::parseDate((string)$entry->published);
        } elseif (isset($entry->updated)) {
            $pubDate = self::parseDate((string)$entry->updated);
        }

        return new MigrationItemDTO(
            guid: $id,
            title: $title,
            html: $html,
            excerpt: $excerpt,
            author: $author,
            categories: $categories,
            tags: [],
            publishedAt: $pubDate,
            canonicalUrl: $canonicalUrl,
            source: self::SOURCE,
            sourceHost: $sourceHost,
        );
    }

    /**
     * RSS `<category>` doubles as both taxonomy buckets in
     * WordPress. The `domain` attribute discriminates — `category`
     * vs `post_tag`. If the attribute is missing (non-WP feeds,
     * hand-rolled exports) we treat the value as a category, which
     * is the safer default because it preserves browsing hierarchy
     * rather than flattening into tags.
     *
     * @return array{0: list<string>, 1: list<string>}
     */
    private static function splitCategoriesAndTags(SimpleXMLElement $item): array
    {
        $categories = [];
        $tags = [];

        foreach ($item->category as $cat) {
            $value = trim((string)$cat);
            if ($value === '') {
                continue;
            }
            $domain = (string)($cat['domain'] ?? '');
            if ($domain === 'post_tag' || $domain === 'tag') {
                $tags[] = $value;
            } else {
                $categories[] = $value;
            }
        }

        return [array_values(array_unique($categories)), array_values(array_unique($tags))];
    }

    private static function parseDate(string $raw): ?DateTimeImmutable
    {
        $raw = trim($raw);
        if ($raw === '') {
            return null;
        }
        try {
            return new DateTimeImmutable($raw);
        } catch (\Exception) {
            return null;
        }
    }

    /**
     * Return the inside of an Atom `<content>` or `<summary>`
     * element as a string. When `type="html"` the value is text
     * that happens to look like HTML; when `type="xhtml"` the body
     * is wrapped in a `<div>` and we need its children.
     */
    private static function innerXml(SimpleXMLElement $node): string
    {
        $type = (string)($node['type'] ?? 'text');
        if ($type === 'xhtml') {
            $asXml = $node->asXML();
            // Strip the outer <content …> wrapper plus any xhtml <div> wrapper.
            $inner = (string)preg_replace('~^<[^>]+>|</[^>]+>$~', '', $asXml);
            $inner = trim($inner);
            if (preg_match('~^<div[^>]*>(.*)</div>$~s', $inner, $m)) {
                return trim($m[1]);
            }
            return $inner;
        }
        return (string)$node;
    }

    private static function safeLoadXml(string $xml): ?SimpleXMLElement
    {
        $xml = trim($xml);
        if ($xml === '') {
            return null;
        }
        $previous = libxml_use_internal_errors(true);
        try {
            $root = simplexml_load_string(
                $xml,
                SimpleXMLElement::class,
                LIBXML_NOCDATA | LIBXML_NOERROR | LIBXML_NOWARNING | LIBXML_NONET
            );
            return $root === false ? null : $root;
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($previous);
        }
    }

    private static function looksLikeRss(string $body): bool
    {
        $trim = ltrim($body);
        return $trim !== '' && stripos($trim, '<rss') !== false;
    }

    private static function looksLikeAtom(string $body): bool
    {
        $trim = ltrim($body);
        return $trim !== ''
            && stripos($trim, '<feed') !== false
            && stripos($trim, 'http://www.w3.org/2005/Atom') !== false;
    }

    /**
     * @param array{body: string, http_code: int, headers?: array<string, string>, error: string} $resp
     */
    private function isSuccess(array $resp): bool
    {
        return $resp['http_code'] >= 200 && $resp['http_code'] < 400 && $resp['body'] !== '';
    }

    /**
     * Trim the trailing slash so concatenation with `/feed/` yields
     * exactly one separator. Null on un-parseable input.
     */
    private static function normalizeBase(string $raw): ?string
    {
        $raw = trim($raw);
        if ($raw === '') {
            return null;
        }
        if (!preg_match('~^https?://~i', $raw)) {
            $raw = 'https://' . $raw;
        }
        $parsed = parse_url($raw);
        if (!is_array($parsed) || empty($parsed['host'])) {
            return null;
        }
        $scheme = strtolower($parsed['scheme'] ?? 'https');
        if ($scheme !== 'http' && $scheme !== 'https') {
            return null;
        }
        $host = strtolower($parsed['host']);
        $port = isset($parsed['port']) ? ':' . $parsed['port'] : '';
        $path = rtrim($parsed['path'] ?? '', '/');
        return "{$scheme}://{$host}{$port}{$path}";
    }
}
