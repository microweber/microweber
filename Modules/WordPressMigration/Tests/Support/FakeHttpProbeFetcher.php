<?php

namespace Modules\WordPressMigration\Tests\Support;

use Modules\WordPressMigration\Services\Http\HttpProbeFetcher;

/**
 * Scripted HttpProbeFetcher for page-level tests. Responses are
 * keyed by the exact URL the probe requests; unscripted URLs
 * return a transport error so accidental URL drift surfaces as
 * a test failure rather than a silent "site is down" outcome.
 *
 * Static factories cover the probe outcomes the page actually
 * branches on (REST-ready vs. unreachable). Keep new factories
 * narrow — if a test needs a bespoke response table, instantiate
 * FakeHttpProbeFetcher directly with an explicit `$table`.
 */
final class FakeHttpProbeFetcher implements HttpProbeFetcher
{
    /** @var list<string> */
    public array $fetched = [];

    /**
     * Per-call Authorization header, indexed by the position in
     * `$fetched`. null means "the caller did not pass an auth header"
     * (anon mode). Exposed so tests can assert that authed importers
     * actually attach the header on every request rather than just
     * the first.
     *
     * @var list<?string>
     */
    public array $fetchedAuth = [];

    /**
     * Per-URL response queues. When a URL has entries here, each
     * `fetch()` call dequeues the next one; callers use this to
     * script transient-failure-then-success sequences (retry tests).
     * Once the queue is empty the static `$table` takes over so a
     * test doesn't have to enumerate every follow-on request.
     *
     * @var array<string, list<array{body: string, http_code: int, error: string, headers?: array<string, string>}>>
     */
    public array $queues = [];

    /**
     * @param array<string, array{body: string, http_code: int, error: string}> $table
     */
    public function __construct(private array $table) {}

    public function fetch(string $url, int $timeout, ?string $authorization = null): array
    {
        $this->fetched[] = $url;
        $this->fetchedAuth[] = $authorization;
        if (isset($this->queues[$url]) && $this->queues[$url] !== []) {
            $resp = array_shift($this->queues[$url]);
            $resp['headers'] = $resp['headers'] ?? [];
            return $resp;
        }
        if (!array_key_exists($url, $this->table)) {
            return [
                'body' => '',
                'http_code' => 0,
                'headers' => [],
                'error' => "FakeHttpProbeFetcher: no scripted response for URL '{$url}'",
            ];
        }
        $resp = $this->table[$url];
        $resp['headers'] = $resp['headers'] ?? [];
        return $resp;
    }

    /**
     * Queue a sequence of responses for the given URL; the first
     * response in the list is returned on the first call, the second
     * on the second call, and so on. Useful for modeling a 429 →
     * 200 retry flow in a single test without fussing with counters.
     *
     * @param list<array{body: string, http_code: int, error: string, headers?: array<string, string>}> $responses
     */
    public function queue(string $url, array $responses): void
    {
        $this->queues[$url] = array_values($responses);
    }

    public static function rest(string $url, int $posts, int $pages): self
    {
        $wpJsonBody = json_encode([
            'name' => 'Test WP Site',
            'namespaces' => ['wp/v2'],
        ]);

        return new self([
            "{$url}/wp-json" => self::ok($wpJsonBody),
            "{$url}/wp-json/wp/v2/posts?per_page=1" => self::okWithWpTotal($posts, '[]'),
            "{$url}/wp-json/wp/v2/pages?per_page=1" => self::okWithWpTotal($pages, '[]'),
            "{$url}/feed" => self::notFound(),
            "{$url}/sitemap.xml" => self::notFound(),
            "{$url}/sitemap_index.xml" => self::notFound(),
            "{$url}/robots.txt" => self::notFound(),
        ]);
    }

    public static function unreachable(string $url): self
    {
        $err = fn (string $msg) => ['body' => '', 'http_code' => 0, 'error' => $msg];

        return new self([
            "{$url}/wp-json" => $err('connection refused'),
            "{$url}/wp-json/wp/v2/posts?per_page=1" => $err('connection refused'),
            "{$url}/wp-json/wp/v2/pages?per_page=1" => $err('connection refused'),
            "{$url}/feed" => $err('connection refused'),
            "{$url}/sitemap.xml" => $err('connection refused'),
            "{$url}/sitemap_index.xml" => $err('connection refused'),
            "{$url}/robots.txt" => $err('connection refused'),
        ]);
    }

    private static function ok(string $body): array
    {
        return ['body' => $body, 'http_code' => 200, 'error' => ''];
    }

    private static function okWithWpTotal(int $total, string $body): array
    {
        return [
            'body' => $body,
            'http_code' => 200,
            'headers' => ['x-wp-total' => (string)$total],
            'error' => '',
        ];
    }

    private static function notFound(): array
    {
        return ['body' => '', 'http_code' => 404, 'error' => ''];
    }
}
