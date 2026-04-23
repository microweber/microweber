<?php

namespace Modules\WordPressMigration\Tests\Unit;

use Modules\WordPressMigration\DTOs\FeedWalkResult;
use Modules\WordPressMigration\Services\Importers\RssFeedImporter;
use Modules\WordPressMigration\Tests\Support\FakeHttpProbeFetcher;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Paginated-walk behavior for {@see RssFeedImporter::walk()}.
 *
 * These tests cover the stop conditions the job runner actually
 * branches on: empty page, already-seen guid, items cap, and
 * unreachable page-1. The plain per-page parser is already
 * covered by {@see RssFeedImporterTest} — here we only script
 * HTTP pages and assert the walker's orchestration.
 */
class RssFeedWalkerTest extends TestCase
{
    #[Test]
    public function walker_stops_on_empty_second_page_and_reports_empty_page(): void
    {
        $fetcher = new FakeHttpProbeFetcher([
            'https://wp.example/feed/' => self::rss([
                ['guid' => 'a', 'title' => 'A'],
                ['guid' => 'b', 'title' => 'B'],
            ]),
            'https://wp.example/feed/?paged=2' => self::rss([]),
        ]);

        $result = (new RssFeedImporter($fetcher))->walk('https://wp.example');

        $this->assertSame(FeedWalkResult::STOP_EMPTY_PAGE, $result->stopReason);
        $this->assertCount(2, $result->items);
        $this->assertSame(['a', 'b'], array_map(fn ($d) => $d->guid, $result->items));
        $this->assertSame(2, $result->pagesFetched);
        $this->assertSame([
            'https://wp.example/feed/',
            'https://wp.example/feed/?paged=2',
        ], $result->fetchedUrls);
    }

    #[Test]
    public function walker_short_circuits_on_already_seen_guid_without_including_it(): void
    {
        $fetcher = new FakeHttpProbeFetcher([
            'https://wp.example/feed/' => self::rss([
                ['guid' => 'c', 'title' => 'C'],
                ['guid' => 'b', 'title' => 'B'],  // caller already has this
                ['guid' => 'a', 'title' => 'A'],
            ]),
        ]);

        $result = (new RssFeedImporter($fetcher))->walk(
            'https://wp.example',
            seenGuids: ['b']
        );

        $this->assertSame(FeedWalkResult::STOP_SEEN_GUID, $result->stopReason);
        $this->assertCount(1, $result->items);
        $this->assertSame('c', $result->items[0]->guid);
        // Walker must NOT request page 2 once it has seen a known guid.
        $this->assertSame(['https://wp.example/feed/'], $result->fetchedUrls);
    }

    #[Test]
    public function walker_stops_mid_page_when_max_items_is_reached(): void
    {
        $fetcher = new FakeHttpProbeFetcher([
            'https://wp.example/feed/' => self::rss([
                ['guid' => 'a', 'title' => 'A'],
                ['guid' => 'b', 'title' => 'B'],
                ['guid' => 'c', 'title' => 'C'],
            ]),
        ]);

        $result = (new RssFeedImporter($fetcher))->walk('https://wp.example', maxItems: 2);

        $this->assertSame(FeedWalkResult::STOP_MAX_ITEMS, $result->stopReason);
        $this->assertCount(2, $result->items);
        $this->assertSame(['a', 'b'], array_map(fn ($d) => $d->guid, $result->items));
        // No ?paged=2 fetch because the cap was hit on page 1.
        $this->assertSame(['https://wp.example/feed/'], $result->fetchedUrls);
    }

    #[Test]
    public function walker_paginates_across_multiple_pages_up_to_max_items(): void
    {
        $fetcher = new FakeHttpProbeFetcher([
            'https://wp.example/feed/' => self::rss([
                ['guid' => 'p1-a', 'title' => 'P1A'],
                ['guid' => 'p1-b', 'title' => 'P1B'],
            ]),
            'https://wp.example/feed/?paged=2' => self::rss([
                ['guid' => 'p2-a', 'title' => 'P2A'],
                ['guid' => 'p2-b', 'title' => 'P2B'],
            ]),
            'https://wp.example/feed/?paged=3' => self::rss([
                ['guid' => 'p3-a', 'title' => 'P3A'],
                ['guid' => 'p3-b', 'title' => 'P3B'],
            ]),
        ]);

        $result = (new RssFeedImporter($fetcher))->walk('https://wp.example', maxItems: 5);

        $this->assertSame(FeedWalkResult::STOP_MAX_ITEMS, $result->stopReason);
        $this->assertCount(5, $result->items);
        $this->assertSame(
            ['p1-a', 'p1-b', 'p2-a', 'p2-b', 'p3-a'],
            array_map(fn ($d) => $d->guid, $result->items)
        );
        $this->assertSame(3, $result->pagesFetched);
    }

    #[Test]
    public function walker_treats_404_after_page_1_as_empty_page(): void
    {
        $fetcher = new FakeHttpProbeFetcher([
            'https://wp.example/feed/' => self::rss([
                ['guid' => 'a', 'title' => 'A'],
            ]),
            'https://wp.example/feed/?paged=2' => [
                'body' => '', 'http_code' => 404, 'error' => '',
            ],
        ]);

        $result = (new RssFeedImporter($fetcher))->walk('https://wp.example');

        $this->assertSame(FeedWalkResult::STOP_EMPTY_PAGE, $result->stopReason);
        $this->assertCount(1, $result->items);
    }

    #[Test]
    public function walker_reports_unreachable_when_page_1_rss_and_atom_both_fail(): void
    {
        $fetcher = new FakeHttpProbeFetcher([
            'https://wp.example/feed/' => [
                'body' => '', 'http_code' => 500, 'error' => '',
            ],
            'https://wp.example/feed/atom/' => [
                'body' => '', 'http_code' => 500, 'error' => '',
            ],
        ]);

        $result = (new RssFeedImporter($fetcher))->walk('https://wp.example');

        $this->assertSame(FeedWalkResult::STOP_UNREACHABLE, $result->stopReason);
        $this->assertSame([], $result->items);
        $this->assertSame(0, $result->pagesFetched);
        $this->assertSame([
            'https://wp.example/feed/',
            'https://wp.example/feed/atom/',
        ], $result->fetchedUrls);
    }

    #[Test]
    public function walker_falls_back_to_atom_and_paginates_the_atom_url(): void
    {
        $fetcher = new FakeHttpProbeFetcher([
            'https://wp.example/feed/' => ['body' => '<html>nope</html>', 'http_code' => 200, 'error' => ''],
            'https://wp.example/feed/atom/' => self::atom([
                ['id' => 'tag:1', 'title' => 'A'],
                ['id' => 'tag:2', 'title' => 'B'],
            ]),
            'https://wp.example/feed/atom/?paged=2' => self::atom([]),
        ]);

        $result = (new RssFeedImporter($fetcher))->walk('https://wp.example');

        $this->assertSame(FeedWalkResult::STOP_EMPTY_PAGE, $result->stopReason);
        $this->assertSame(['tag:1', 'tag:2'], array_map(fn ($d) => $d->guid, $result->items));
        // Crucially: page 2 must use the Atom URL, not the RSS URL.
        $this->assertSame([
            'https://wp.example/feed/',
            'https://wp.example/feed/atom/',
            'https://wp.example/feed/atom/?paged=2',
        ], $result->fetchedUrls);
    }

    #[Test]
    public function walker_detects_within_run_duplicate_and_stops(): void
    {
        // Misconfigured feed where page 2 repeats page 1 — infinite
        // loop hazard if we weren't also tracking per-run guids.
        $fetcher = new FakeHttpProbeFetcher([
            'https://wp.example/feed/' => self::rss([
                ['guid' => 'x', 'title' => 'X'],
                ['guid' => 'y', 'title' => 'Y'],
            ]),
            'https://wp.example/feed/?paged=2' => self::rss([
                ['guid' => 'x', 'title' => 'X again'],
            ]),
        ]);

        $result = (new RssFeedImporter($fetcher))->walk('https://wp.example');

        $this->assertSame(FeedWalkResult::STOP_SEEN_GUID, $result->stopReason);
        $this->assertSame(['x', 'y'], array_map(fn ($d) => $d->guid, $result->items));
        $this->assertSame(2, $result->pagesFetched);
    }

    #[Test]
    public function walker_with_max_items_zero_returns_empty_without_fetching(): void
    {
        $fetcher = new FakeHttpProbeFetcher([]);
        $result = (new RssFeedImporter($fetcher))->walk('https://wp.example', maxItems: 0);

        $this->assertSame(FeedWalkResult::STOP_MAX_ITEMS, $result->stopReason);
        $this->assertSame([], $result->items);
        $this->assertSame([], $result->fetchedUrls);
    }

    #[Test]
    public function walker_with_invalid_base_url_reports_max_items_without_fetching(): void
    {
        // normalizeBase rejects the URL, so we never reach the
        // fetcher. STOP_MAX_ITEMS is the "no work done" bucket
        // (could also have been unreachable — documented in the
        // DTO — but max_items is what the existing import() style
        // returns for invalid input).
        $fetcher = new FakeHttpProbeFetcher([]);
        $result = (new RssFeedImporter($fetcher))->walk('   ', maxItems: 10);

        $this->assertSame([], $result->items);
        $this->assertSame([], $result->fetchedUrls);
    }

    /**
     * @param list<array{guid: string, title: string}> $items
     * @return array{body: string, http_code: int, error: string}
     */
    private static function rss(array $items): array
    {
        $itemsXml = '';
        foreach ($items as $i) {
            $itemsXml .= sprintf(
                "<item><title>%s</title><link>https://wp.example/%s</link><guid>%s</guid></item>",
                htmlspecialchars($i['title']),
                rawurlencode($i['guid']),
                htmlspecialchars($i['guid'])
            );
        }
        $body = '<?xml version="1.0"?><rss version="2.0"><channel>'
            . '<title>Ex</title><link>https://wp.example/</link><description>d</description>'
            . $itemsXml . '</channel></rss>';
        return ['body' => $body, 'http_code' => 200, 'error' => ''];
    }

    /**
     * @param list<array{id: string, title: string}> $entries
     * @return array{body: string, http_code: int, error: string}
     */
    private static function atom(array $entries): array
    {
        $entriesXml = '';
        foreach ($entries as $e) {
            $entriesXml .= sprintf(
                '<entry><id>%s</id><title>%s</title><link href="https://wp.example/x"/><content type="html">body</content></entry>',
                htmlspecialchars($e['id']),
                htmlspecialchars($e['title'])
            );
        }
        $body = '<?xml version="1.0"?><feed xmlns="http://www.w3.org/2005/Atom"><title>Ex</title>'
            . $entriesXml . '</feed>';
        return ['body' => $body, 'http_code' => 200, 'error' => ''];
    }
}
