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
     * @param array<string, array{body: string, http_code: int, error: string}> $table
     */
    public function __construct(private array $table) {}

    public function fetch(string $url, int $timeout): array
    {
        $this->fetched[] = $url;
        if (!array_key_exists($url, $this->table)) {
            return [
                'body' => '',
                'http_code' => 0,
                'error' => "FakeHttpProbeFetcher: no scripted response for URL '{$url}'",
            ];
        }
        return $this->table[$url];
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
            'body' => "HTTP/1.1 200 OK\r\nContent-Type: application/json\r\nX-WP-Total: {$total}\r\n\r\n{$body}",
            'http_code' => 200,
            'error' => '',
        ];
    }

    private static function notFound(): array
    {
        return ['body' => '', 'http_code' => 404, 'error' => ''];
    }
}
