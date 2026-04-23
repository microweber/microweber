<?php

namespace Modules\WordPressMigration\Services\Importers;

use DateTimeImmutable;
use Modules\WordPressMigration\DTOs\MigrationItemDTO;
use Modules\WordPressMigration\DTOs\WpRestImportResult;
use Modules\WordPressMigration\Services\Http\CurlHttpProbeFetcher;
use Modules\WordPressMigration\Services\Http\HttpProbeFetcher;
use Modules\WordPressMigration\Services\Http\WpAppPasswordCredential;

/**
 * WordPress REST API importer — the "authoritative" Phase 3 source.
 *
 * When `/wp-json/wp/v2/*` is exposed, it carries strictly more
 * information than RSS (term slugs, media ids, comment bodies,
 * accurate author display names) and it paginates cleanly via the
 * `X-WP-Total` / `X-WP-TotalPages` headers WordPress emits on every
 * list endpoint.
 *
 * Why a single importer for eight endpoints?
 * ------------------------------------------
 * Posts and pages are the *only* two collections that map directly to
 * Microweber content rows. The other six are enrichers:
 *
 *   - `users`       → author display names (posts carry only author id)
 *   - `categories`  → term ids → human-readable names on the DTO
 *   - `tags`        → same, for `<tag>` taxonomies
 *   - `media`       → raw rows handed to the media rehoster (Phase 7)
 *   - `comments`    → raw rows surfaced to the Filament preview
 *   - `menus`       → raw rows surfaced to the Filament preview
 *
 * Each of those endpoints is cheap (`per_page=100`, a handful of
 * pages for a typical WP site) so we slurp the full set up front,
 * build lookup tables, and then stream posts+pages with the lookups
 * pre-resolved. Splitting them into six sibling importers would
 * force each consumer to repeat the pagination boilerplate.
 *
 * Endpoint fallbacks
 * ------------------
 * WordPress can expose or hide each collection independently:
 *
 *   - `/users` returns 401/403 when the site disables the public
 *     author index (default on hardened sites). The importer
 *     records a warning and the DTO falls back to the author slug
 *     in `post._embedded.author[0].slug`.
 *   - `/comments` is disabled entirely on blogs that have turned
 *     commenting off (200 with empty array is common too).
 *   - `/menus` is a WP 5.9+ endpoint — older sites return 404,
 *     which we surface as `menus = null` rather than `[]` so
 *     the Filament UI can distinguish "missing" from "empty".
 *
 * None of those is a hard failure; a 401 on users doesn't abort
 * the walk, it just removes one enrichment signal.
 */
class WpRestImporter
{
    private const FETCH_TIMEOUT_SECONDS = 30;
    private const PER_PAGE = 100;

    /** Safety cap — stops pagination runaway on a broken source. */
    private const WALKER_MAX_PAGES = 200;

    /** Total attempts per URL (1 initial + up to MAX_RETRIES - 1 retries). */
    private const MAX_RETRIES = 3;

    /** Exponential backoff base; doubled each retry (500, 1000, 2000 ms). */
    private const RETRY_BASE_DELAY_MS = 500;

    /** Upper clamp on server-provided Retry-After values — protects against pathological replies. */
    private const RETRY_AFTER_CAP_SECONDS = 30;

    private const SOURCE = 'rest';

    private HttpProbeFetcher $fetcher;
    private ?WpAppPasswordCredential $credential;
    /** @var \Closure(int): void */
    private \Closure $sleeper;

    /**
     * @param WpAppPasswordCredential|null $credential When supplied,
     *        every REST request gets the credential's Authorization
     *        header. null = anon mode, which is correct for sites
     *        that expose the REST API publicly (the 5.6-era default).
     *        The importer never inspects the credential beyond asking
     *        it for its header — storage and decryption live on the
     *        repository side.
     * @param \Closure(int): void|null $sleeper Hook for retry backoff
     *        delays, parameter is milliseconds to sleep. Defaulted to
     *        a real `usleep()` so production behaves naturally;
     *        tests inject a no-op / recorder so the suite doesn't
     *        actually block on retries.
     */
    public function __construct(
        ?HttpProbeFetcher $fetcher = null,
        ?WpAppPasswordCredential $credential = null,
        ?\Closure $sleeper = null,
    ) {
        $this->fetcher = $fetcher ?? new CurlHttpProbeFetcher();
        $this->credential = $credential;
        $this->sleeper = $sleeper ?? static function (int $ms): void {
            if ($ms > 0) {
                usleep($ms * 1000);
            }
        };
    }

    /**
     * Walk the REST API at `$baseUrl/wp-json` and return every
     * importable row, with ancillary collections side-loaded for
     * downstream consumers.
     *
     * @param list<string> $seenGuids Content guids already imported (format: `wp:{post_id}`)
     * @param int $maxItems Cap on collected posts+pages combined
     */
    public function walk(string $baseUrl, array $seenGuids = [], int $maxItems = 1000): WpRestImportResult
    {
        $base = self::normalizeBase($baseUrl);
        if ($base === null || $maxItems <= 0) {
            return new WpRestImportResult([], 0, WpRestImportResult::STOP_UNREACHABLE);
        }
        $host = (string)parse_url($base, PHP_URL_HOST) ?: null;

        $fetched = [];
        $warnings = [];
        $pagesFetched = 0;

        // A failed root probe is the only condition that aborts the
        // whole walk — if /wp-json itself isn't JSON there's nothing
        // to enrich, let alone stream.
        $root = $this->fetchJson($base . '/wp-json', $fetched);
        if ($root === null) {
            return new WpRestImportResult([], 0, WpRestImportResult::STOP_UNREACHABLE, fetchedUrls: $fetched);
        }

        // Enrichers first; posts + pages rely on the lookup tables
        // they populate. Cost is bounded by PER_PAGE × WALKER_MAX_PAGES,
        // a budget most WP installs don't come close to exhausting.
        [$categories, $pCat] = $this->collectTermsIndexed($base . '/wp-json/wp/v2/categories', $fetched, $warnings, 'categories');
        [$tags, $pTag] = $this->collectTermsIndexed($base . '/wp-json/wp/v2/tags', $fetched, $warnings, 'tags');
        [$users, $pUser] = $this->collectUsersIndexed($base . '/wp-json/wp/v2/users', $fetched, $warnings);
        [$media, $pMedia] = $this->collectList($base . '/wp-json/wp/v2/media', $fetched, $warnings, 'media');
        [$comments, $pCmt] = $this->collectList($base . '/wp-json/wp/v2/comments', $fetched, $warnings, 'comments');
        $menus = $this->collectMenus($base . '/wp-json/wp/v2/menus', $fetched, $warnings, $pagesFetched);

        $pagesFetched += $pCat + $pTag + $pUser + $pMedia + $pCmt;

        // Now the two collections that actually produce MigrationItemDTOs.
        $seen = [];
        foreach ($seenGuids as $g) {
            if (is_string($g) && $g !== '') {
                $seen[$g] = true;
            }
        }

        $collected = [];

        $stop = $this->collectContent(
            url: $base . '/wp-json/wp/v2/posts',
            contentType: 'post',
            host: $host,
            categoriesIdx: $categories,
            tagsIdx: $tags,
            usersIdx: $users,
            seen: $seen,
            collected: $collected,
            maxItems: $maxItems,
            fetched: $fetched,
            pagesFetched: $pagesFetched,
        );
        if ($stop === null) {
            $stop = $this->collectContent(
                url: $base . '/wp-json/wp/v2/pages',
                contentType: 'page',
                host: $host,
                categoriesIdx: $categories,
                tagsIdx: $tags,
                usersIdx: $users,
                seen: $seen,
                collected: $collected,
                maxItems: $maxItems,
                fetched: $fetched,
                pagesFetched: $pagesFetched,
            );
        }
        $stop ??= WpRestImportResult::STOP_COMPLETE;

        return new WpRestImportResult(
            items: $collected,
            pagesFetched: $pagesFetched,
            stopReason: $stop,
            media: array_values($media),
            comments: array_values($comments),
            menus: $menus,
            fetchedUrls: $fetched,
            warnings: $warnings,
        );
    }

    /**
     * Stream one content collection (posts OR pages) into $collected.
     * Mutates $pagesFetched + $fetched as it walks. Returns a STOP_*
     * value on short-circuit, or null to mean "keep going to the next
     * content collection".
     *
     * @param array<int, array{name: string, slug: string}> $categoriesIdx
     * @param array<int, array{name: string, slug: string}> $tagsIdx
     * @param array<int, string> $usersIdx
     * @param array<string, true> $seen
     * @param list<MigrationItemDTO> $collected
     * @param list<string> $fetched
     */
    private function collectContent(
        string $url,
        string $contentType,
        ?string $host,
        array $categoriesIdx,
        array $tagsIdx,
        array $usersIdx,
        array &$seen,
        array &$collected,
        int $maxItems,
        array &$fetched,
        int &$pagesFetched,
    ): ?string {
        $totalPages = null;
        for ($page = 1; $page <= self::WALKER_MAX_PAGES; $page++) {
            $pagedUrl = $url . '?per_page=' . self::PER_PAGE . '&page=' . $page;
            $meta = $this->fetchJsonWithMeta($pagedUrl, $fetched);
            // Page 1 missing → the collection isn't exposed. Skip
            // silently; posts-or-pages can legitimately be empty.
            if ($meta === null) {
                return null;
            }
            $body = $meta['data'];
            if ($body === []) {
                return null;
            }
            $pagesFetched++;
            if ($page === 1) {
                $totalPages = self::totalPagesFromHeaders($meta['headers']);
            }

            foreach ($body as $row) {
                if (!is_array($row)) {
                    continue;
                }
                $dto = $this->toMigrationItem($row, $contentType, $host, $categoriesIdx, $tagsIdx, $usersIdx);
                if ($dto === null) {
                    continue;
                }
                if (isset($seen[$dto->guid])) {
                    continue;
                }
                $collected[] = $dto;
                $seen[$dto->guid] = true;
                if (count($collected) >= $maxItems) {
                    return WpRestImportResult::STOP_MAX_ITEMS;
                }
            }

            // Prefer the server-reported total when present — it lets
            // the walker stop at the exact final page instead of
            // discovering the end by fetching a short/empty one.
            if ($totalPages !== null && $page >= $totalPages) {
                return null;
            }

            if (count($body) < self::PER_PAGE) {
                return null;
            }
        }
        return WpRestImportResult::STOP_MAX_PAGES;
    }

    /**
     * @param array<string, mixed> $row
     * @param array<int, array{name: string, slug: string}> $categoriesIdx
     * @param array<int, array{name: string, slug: string}> $tagsIdx
     * @param array<int, string> $usersIdx
     */
    private function toMigrationItem(
        array $row,
        string $contentType,
        ?string $host,
        array $categoriesIdx,
        array $tagsIdx,
        array $usersIdx,
    ): ?MigrationItemDTO {
        $postId = isset($row['id']) ? (int)$row['id'] : 0;
        if ($postId <= 0) {
            return null;
        }

        $title = self::renderedString($row['title'] ?? null);
        $html = self::renderedString($row['content'] ?? null);
        $excerpt = self::renderedString($row['excerpt'] ?? null);
        if ($excerpt === '') {
            $excerpt = null;
        }

        $publishedAt = self::parseDate((string)($row['date_gmt'] ?? $row['date'] ?? ''));

        $authorId = (int)($row['author'] ?? 0);
        $author = $usersIdx[$authorId] ?? null;

        $categories = self::namesForIds($row['categories'] ?? [], $categoriesIdx);
        $tags = self::namesForIds($row['tags'] ?? [], $tagsIdx);

        $link = is_string($row['link'] ?? null) && $row['link'] !== '' ? (string)$row['link'] : null;

        // The mapper's idempotency key is (import_source, source_guid).
        // `wp:{post_id}` is stable across re-runs and distinct from the
        // RSS importer's feed-guid format so a later RSS re-probe of the
        // same site cannot collide.
        return new MigrationItemDTO(
            guid: 'wp:' . $postId,
            title: $title !== '' ? $title : '(untitled)',
            html: $html,
            excerpt: $excerpt,
            author: $author,
            categories: $categories,
            tags: $tags,
            publishedAt: $publishedAt,
            canonicalUrl: $link,
            source: self::SOURCE,
            sourceHost: $host,
        );
    }

    /**
     * Walk a paginated REST list endpoint and return all rows.
     *
     * @param list<string> $fetched
     * @param list<string> $warnings
     * @return array{0: list<array<string, mixed>>, 1: int}
     */
    private function collectList(string $url, array &$fetched, array &$warnings, string $label): array
    {
        $rows = [];
        $pagesFetched = 0;
        $totalPages = null;
        for ($page = 1; $page <= self::WALKER_MAX_PAGES; $page++) {
            $pagedUrl = $url . '?per_page=' . self::PER_PAGE . '&page=' . $page;
            $meta = $this->fetchJsonWithMeta($pagedUrl, $fetched);
            if ($meta === null) {
                if ($page === 1) {
                    $warnings[] = "REST {$label} endpoint unavailable — skipping enrichment";
                }
                break;
            }
            $body = $meta['data'];
            if ($body === []) {
                break;
            }
            $pagesFetched++;
            if ($page === 1) {
                $totalPages = self::totalPagesFromHeaders($meta['headers']);
            }
            foreach ($body as $row) {
                if (is_array($row)) {
                    $rows[] = $row;
                }
            }
            if ($totalPages !== null && $page >= $totalPages) {
                break;
            }
            if (count($body) < self::PER_PAGE) {
                break;
            }
        }
        return [$rows, $pagesFetched];
    }

    /**
     * @param list<string> $fetched
     * @param list<string> $warnings
     * @return array{0: array<int, array{name: string, slug: string}>, 1: int}
     */
    private function collectTermsIndexed(string $url, array &$fetched, array &$warnings, string $label): array
    {
        [$rows, $pages] = $this->collectList($url, $fetched, $warnings, $label);
        $index = [];
        foreach ($rows as $row) {
            $id = isset($row['id']) ? (int)$row['id'] : 0;
            if ($id <= 0) {
                continue;
            }
            $index[$id] = [
                'name' => is_string($row['name'] ?? null) ? (string)$row['name'] : '',
                'slug' => is_string($row['slug'] ?? null) ? (string)$row['slug'] : '',
            ];
        }
        return [$index, $pages];
    }

    /**
     * @param list<string> $fetched
     * @param list<string> $warnings
     * @return array{0: array<int, string>, 1: int}
     */
    private function collectUsersIndexed(string $url, array &$fetched, array &$warnings): array
    {
        [$rows, $pages] = $this->collectList($url, $fetched, $warnings, 'users');
        $index = [];
        foreach ($rows as $row) {
            $id = isset($row['id']) ? (int)$row['id'] : 0;
            if ($id <= 0) {
                continue;
            }
            // `name` is the display name; fall back to slug for
            // sites that sanitize the public index down to slug-only.
            $name = is_string($row['name'] ?? null) && $row['name'] !== ''
                ? (string)$row['name']
                : (string)($row['slug'] ?? '');
            if ($name !== '') {
                $index[$id] = $name;
            }
        }
        return [$index, $pages];
    }

    /**
     * `wp/v2/menus` is optional (WP 5.9+ / plugin-provided). Surface
     * null for "endpoint not exposed" vs [] for "exposed but empty"
     * so the Filament UI can render the right message.
     *
     * @param list<string> $fetched
     * @param list<string> $warnings
     */
    private function collectMenus(string $url, array &$fetched, array &$warnings, int &$pagesFetched): ?array
    {
        $body = $this->fetchJson($url . '?per_page=' . self::PER_PAGE, $fetched);
        if ($body === null) {
            return null;
        }
        $pagesFetched++;
        if (!is_array($body)) {
            return null;
        }
        $rows = [];
        foreach ($body as $row) {
            if (is_array($row)) {
                $rows[] = $row;
            }
        }
        return $rows;
    }

    /**
     * Fetch a URL and decode a JSON body. Returns null for transport
     * failure, non-2xx after retries, or invalid JSON — the caller
     * distinguishes those cases by context (page 1 vs paging past
     * the end). Discards response headers; callers that care about
     * pagination metadata use {@see fetchJsonWithMeta()}.
     *
     * @param list<string> $fetched
     */
    private function fetchJson(string $url, array &$fetched): ?array
    {
        $meta = $this->fetchJsonWithMeta($url, $fetched);
        return $meta === null ? null : $meta['data'];
    }

    /**
     * Like {@see fetchJson()} but also surfaces the response headers
     * so callers can read `x-wp-totalpages` without a second request.
     *
     * Retry policy: 429 and any 5xx response is retried with
     * exponential backoff up to {@see MAX_RETRIES} attempts total.
     * Transport failures (http_code 0 with a non-empty error) are
     * retried too — DNS hiccups and TLS resets are almost always
     * transient. A 429 whose `Retry-After` header is a positive
     * integer uses that value (clamped at
     * {@see RETRY_AFTER_CAP_SECONDS}) instead of the exponential
     * schedule, because WP rate limiters expect their own pacing.
     *
     * 4xx responses other than 429 are NOT retried — 401/403/404
     * are permanent for the duration of a walk and extra requests
     * would just dig a deeper auth hole.
     *
     * @param list<string> $fetched
     * @return array{data: array<mixed>, headers: array<string, string>}|null
     */
    private function fetchJsonWithMeta(string $url, array &$fetched): ?array
    {
        // Log the URL once per logical request, not once per retry —
        // `$fetched` is a tracing signal, not a traffic counter.
        $fetched[] = $url;

        for ($attempt = 1; $attempt <= self::MAX_RETRIES; $attempt++) {
            $resp = $this->fetcher->fetch($url, self::FETCH_TIMEOUT_SECONDS, $this->credential?->authorizationHeader());
            $code = (int)($resp['http_code'] ?? 0);
            $headers = $resp['headers'] ?? [];

            if ($this->isSuccess($resp)) {
                $decoded = json_decode((string)$resp['body'], true);
                if (!is_array($decoded)) {
                    return null;
                }
                return ['data' => $decoded, 'headers' => $headers];
            }

            if ($attempt < self::MAX_RETRIES && $this->shouldRetry($resp)) {
                $delayMs = $this->backoffDelayMs($attempt, $headers, $code);
                ($this->sleeper)($delayMs);
                continue;
            }

            return null;
        }

        return null;
    }

    /**
     * @param array{body: string, http_code: int, headers?: array<string, string>, error: string} $resp
     */
    private function shouldRetry(array $resp): bool
    {
        $code = (int)($resp['http_code'] ?? 0);
        // Transport failure (connection reset, DNS, TLS handshake) —
        // the fetcher reports http_code=0 and populates `error`.
        if ($code === 0 && ($resp['error'] ?? '') !== '') {
            return true;
        }
        return $code === 429 || $code >= 500;
    }

    /**
     * @param array<string, string> $headers
     */
    private function backoffDelayMs(int $attempt, array $headers, int $code): int
    {
        if ($code === 429) {
            $retryAfter = $this->parseRetryAfter($headers['retry-after'] ?? null);
            if ($retryAfter !== null) {
                return $retryAfter * 1000;
            }
        }
        // Exponential: attempt 1 → 500ms, attempt 2 → 1000ms, …
        return self::RETRY_BASE_DELAY_MS * (1 << ($attempt - 1));
    }

    private function parseRetryAfter(?string $raw): ?int
    {
        if ($raw === null) {
            return null;
        }
        $raw = trim($raw);
        if ($raw === '' || !ctype_digit($raw)) {
            return null;
        }
        $seconds = (int)$raw;
        if ($seconds <= 0) {
            return null;
        }
        return min($seconds, self::RETRY_AFTER_CAP_SECONDS);
    }

    /**
     * `X-WP-TotalPages` on a list endpoint tells us the final page up
     * front — using it lets the walker stop at page N instead of
     * making an extra "past the end" request to discover it's empty.
     * Returns null when the header is missing or non-numeric so
     * callers know to fall back to the short-page heuristic.
     *
     * @param array<string, string> $headers
     */
    private static function totalPagesFromHeaders(array $headers): ?int
    {
        $raw = $headers['x-wp-totalpages'] ?? null;
        if ($raw === null) {
            return null;
        }
        $raw = trim($raw);
        if ($raw === '' || !ctype_digit($raw)) {
            return null;
        }
        $total = (int)$raw;
        return $total > 0 ? $total : null;
    }

    /**
     * @param array<int, array{name: string, slug: string}> $index
     * @param mixed $ids
     * @return list<string>
     */
    private static function namesForIds(mixed $ids, array $index): array
    {
        if (!is_array($ids)) {
            return [];
        }
        $out = [];
        foreach ($ids as $id) {
            $key = (int)$id;
            if ($key <= 0) {
                continue;
            }
            if (isset($index[$key])) {
                $name = $index[$key]['name'];
                if ($name !== '') {
                    $out[] = $name;
                }
            }
        }
        return $out;
    }

    /**
     * The REST API wraps title/content/excerpt in
     * `{"rendered": "...", "protected": false}`. Tolerate either the
     * wrapped or the raw-string shape so fixture variants don't break.
     */
    private static function renderedString(mixed $field): string
    {
        if (is_string($field)) {
            return trim($field);
        }
        if (is_array($field) && isset($field['rendered']) && is_string($field['rendered'])) {
            return trim($field['rendered']);
        }
        return '';
    }

    private static function parseDate(string $raw): ?DateTimeImmutable
    {
        $raw = trim($raw);
        if ($raw === '') {
            return null;
        }
        // WordPress REST emits ISO 8601 without a trailing Z when it
        // reports `date_gmt` — DateTimeImmutable handles both shapes.
        try {
            return new DateTimeImmutable($raw);
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * @param array{body: string, http_code: int, headers?: array<string, string>, error: string} $resp
     */
    private function isSuccess(array $resp): bool
    {
        return $resp['http_code'] >= 200 && $resp['http_code'] < 400 && $resp['body'] !== '';
    }

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
