<?php

namespace Modules\WordPressMigration\Tests\Unit;

use DateTimeImmutable;
use Modules\WordPressMigration\DTOs\MigrationItemDTO;
use Modules\WordPressMigration\DTOs\SitemapCrawlResult;
use Modules\WordPressMigration\Services\Importers\SitemapPageImporter;
use Modules\WordPressMigration\Tests\Support\FakeHttpProbeFetcher;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Unit coverage for the "crawl sitemap → fetch each page → extract"
 * driver. Because the crawl and extraction pieces have their own
 * dedicated suites, these tests focus on the glue: URL-as-GUID,
 * per-page failure handling, seenGuids short-circuit, and
 * propagation of the underlying sitemap stop_reason.
 */
class SitemapPageImporterTest extends TestCase
{
    private function minimalSitemap(array $locs): string
    {
        $entries = implode("\n", array_map(
            fn (string $loc) => "  <url><loc>{$loc}</loc></url>",
            $locs,
        ));
        return <<<XML
<?xml version="1.0"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
{$entries}
</urlset>
XML;
    }

    private function articleHtml(string $title, string $body, array $metas = []): string
    {
        $metaTags = '';
        foreach ($metas as $name => $content) {
            $attr = str_starts_with($name, 'og:') || str_starts_with($name, 'article:')
                ? 'property'
                : 'name';
            $metaTags .= '    <meta ' . $attr . '="' . $name . '" content="' . htmlspecialchars($content) . '">' . "\n";
        }
        return <<<HTML
<!doctype html>
<html>
  <head>
    <title>{$title}</title>
{$metaTags}
  </head>
  <body>
    <article>
      <p>{$body}</p>
    </article>
  </body>
</html>
HTML;
    }

    #[Test]
    public function crawl_and_extract_yields_migration_items_with_url_as_guid(): void
    {
        $sitemap = $this->minimalSitemap([
            'https://wp.example/post-a',
            'https://wp.example/post-b',
        ]);

        $fetcher = new FakeHttpProbeFetcher([
            'https://wp.example/sitemap.xml' => ['body' => $sitemap, 'http_code' => 200, 'error' => ''],
            'https://wp.example/post-a' => [
                'body' => $this->articleHtml(
                    'Post A title',
                    'Body content for post A long enough to clear the extractor minimum body length and be accepted as a real article.',
                    ['og:image' => 'https://wp.example/a.jpg', 'article:published_time' => '2026-04-10T12:00:00+00:00'],
                ),
                'http_code' => 200,
                'error' => '',
            ],
            'https://wp.example/post-b' => [
                'body' => $this->articleHtml(
                    'Post B title',
                    'Body content for post B long enough to clear the extractor minimum body length and be accepted as a real article.',
                ),
                'http_code' => 200,
                'error' => '',
            ],
        ]);

        $result = (new SitemapPageImporter(fetcher: $fetcher))->walk('https://wp.example/sitemap.xml');

        $this->assertSame(SitemapCrawlResult::STOP_COMPLETE, $result->stopReason);
        $this->assertCount(2, $result->items);
        $this->assertSame(2, $result->pagesFetched);
        $this->assertSame(0, $result->pagesSkipped);

        [$a, $b] = $result->items;
        $this->assertSame('https://wp.example/post-a', $a->guid);
        $this->assertSame('https://wp.example/post-a', $a->canonicalUrl);
        $this->assertSame('Post A title', $a->title);
        $this->assertSame('sitemap', $a->source);
        $this->assertSame('wp.example', $a->sourceHost);
        $this->assertNotNull($a->publishedAt);
        $this->assertSame('2026-04-10T12:00:00+00:00', $a->publishedAt->format(DATE_ATOM));

        $this->assertSame('https://wp.example/post-b', $b->guid);
        $this->assertSame('Post B title', $b->title);
    }

    #[Test]
    public function og_image_is_prepended_to_body_when_not_already_present(): void
    {
        $sitemap = $this->minimalSitemap(['https://ex.com/x']);

        $fetcher = new FakeHttpProbeFetcher([
            'https://ex.com/sitemap.xml' => ['body' => $sitemap, 'http_code' => 200, 'error' => ''],
            'https://ex.com/x' => [
                'body' => $this->articleHtml(
                    'Hero-only post',
                    'Body that does not reference the hero image file by name, long enough to clear the minimum body length gate.',
                    ['og:image' => 'https://ex.com/hero.jpg'],
                ),
                'http_code' => 200,
                'error' => '',
            ],
        ]);

        $result = (new SitemapPageImporter(fetcher: $fetcher))->walk('https://ex.com/sitemap.xml');

        $this->assertCount(1, $result->items);
        $this->assertStringContainsString(
            '<img src="https://ex.com/hero.jpg"',
            $result->items[0]->html,
            'og:image should be injected at the top of the body HTML'
        );
    }

    #[Test]
    public function og_image_is_not_duplicated_when_body_already_contains_it(): void
    {
        $sitemap = $this->minimalSitemap(['https://ex.com/y']);

        $fetcher = new FakeHttpProbeFetcher([
            'https://ex.com/sitemap.xml' => ['body' => $sitemap, 'http_code' => 200, 'error' => ''],
            'https://ex.com/y' => [
                'body' => '<html><head><title>Inline hero</title><meta property="og:image" content="https://ex.com/hero.jpg"></head><body><article><p>Body already has its hero image inline below; long enough to clear the min body length gate the extractor enforces.</p><img src="https://ex.com/hero.jpg"></article></body></html>',
                'http_code' => 200,
                'error' => '',
            ],
        ]);

        $result = (new SitemapPageImporter(fetcher: $fetcher))->walk('https://ex.com/sitemap.xml');

        $this->assertCount(1, $result->items);
        $occurrences = substr_count($result->items[0]->html, 'https://ex.com/hero.jpg');
        $this->assertSame(1, $occurrences, 'og:image must not be injected when body already references it');
    }

    #[Test]
    public function page_fetch_failure_skips_the_url_without_aborting_walk(): void
    {
        $sitemap = $this->minimalSitemap([
            'https://ex.com/good',
            'https://ex.com/404',
        ]);

        $fetcher = new FakeHttpProbeFetcher([
            'https://ex.com/sitemap.xml' => ['body' => $sitemap, 'http_code' => 200, 'error' => ''],
            'https://ex.com/good' => [
                'body' => $this->articleHtml('Good', 'Body text long enough to clear the extractor minimum body length gate and be accepted.'),
                'http_code' => 200, 'error' => '',
            ],
            'https://ex.com/404' => ['body' => '', 'http_code' => 404, 'error' => ''],
        ]);

        $result = (new SitemapPageImporter(fetcher: $fetcher))->walk('https://ex.com/sitemap.xml');

        $this->assertCount(1, $result->items);
        $this->assertSame(1, $result->pagesFetched);
        $this->assertSame(1, $result->pagesSkipped);
        $this->assertSame(['https://ex.com/404'], $result->skippedUrls);
    }

    #[Test]
    public function unparseable_page_is_counted_as_skipped(): void
    {
        $sitemap = $this->minimalSitemap(['https://ex.com/thin']);

        $fetcher = new FakeHttpProbeFetcher([
            'https://ex.com/sitemap.xml' => ['body' => $sitemap, 'http_code' => 200, 'error' => ''],
            'https://ex.com/thin' => [
                'body' => '<html><head><title>Thin</title></head><body><p>Hi.</p></body></html>',
                'http_code' => 200, 'error' => '',
            ],
        ]);

        $result = (new SitemapPageImporter(fetcher: $fetcher))->walk('https://ex.com/sitemap.xml');

        $this->assertCount(0, $result->items);
        $this->assertSame(1, $result->pagesFetched);
        $this->assertSame(1, $result->pagesSkipped);
        $this->assertSame(['https://ex.com/thin'], $result->skippedUrls);
    }

    #[Test]
    public function seen_guids_short_circuit_does_not_fetch_the_page(): void
    {
        $sitemap = $this->minimalSitemap([
            'https://ex.com/already-imported',
            'https://ex.com/fresh',
        ]);

        $fetcher = new FakeHttpProbeFetcher([
            'https://ex.com/sitemap.xml' => ['body' => $sitemap, 'http_code' => 200, 'error' => ''],
            // No entry for /already-imported — if the importer fetches
            // it the FakeFetcher surfaces the unknown-URL transport
            // error, which would get counted as a skip, not a silent pass.
            'https://ex.com/fresh' => [
                'body' => $this->articleHtml('Fresh', 'Body long enough to clear the extractor minimum body length gate; lorem ipsum dolor sit amet.'),
                'http_code' => 200, 'error' => '',
            ],
        ]);

        $result = (new SitemapPageImporter(fetcher: $fetcher))->walk(
            'https://ex.com/sitemap.xml',
            seenGuids: ['https://ex.com/already-imported'],
        );

        $this->assertCount(1, $result->items);
        $this->assertSame('https://ex.com/fresh', $result->items[0]->guid);
        $this->assertNotContains('https://ex.com/already-imported', $fetcher->fetched,
            'Already-imported URLs must be skipped before any HTTP call');
    }

    #[Test]
    public function max_items_halts_walk_before_processing_remaining_urls(): void
    {
        $sitemap = $this->minimalSitemap([
            'https://ex.com/1',
            'https://ex.com/2',
            'https://ex.com/3',
        ]);

        $fetcher = new FakeHttpProbeFetcher([
            'https://ex.com/sitemap.xml' => ['body' => $sitemap, 'http_code' => 200, 'error' => ''],
            'https://ex.com/1' => ['body' => $this->articleHtml('One', 'Body long enough to clear the extractor min body length gate; lorem ipsum dolor sit amet.'), 'http_code' => 200, 'error' => ''],
            'https://ex.com/2' => ['body' => $this->articleHtml('Two', 'Body long enough to clear the extractor min body length gate; lorem ipsum dolor sit amet.'), 'http_code' => 200, 'error' => ''],
        ]);

        $result = (new SitemapPageImporter(fetcher: $fetcher))->walk(
            'https://ex.com/sitemap.xml',
            maxItems: 2,
        );

        $this->assertCount(2, $result->items);
        $this->assertNotContains('https://ex.com/3', $fetcher->fetched,
            'Third URL must not be fetched after the cap is hit');
    }

    #[Test]
    public function sitemap_unreachable_returns_empty_result_with_unreachable_stop(): void
    {
        $fetcher = new FakeHttpProbeFetcher([
            'https://offline.example/sitemap.xml' => ['body' => '', 'http_code' => 0, 'error' => 'connection refused'],
        ]);

        $result = (new SitemapPageImporter(fetcher: $fetcher))->walk('https://offline.example/sitemap.xml');

        $this->assertSame(SitemapCrawlResult::STOP_UNREACHABLE, $result->stopReason);
        $this->assertSame([], $result->items);
        $this->assertSame(0, $result->pagesFetched);
    }

    #[Test]
    public function rss_fallback_fills_missing_author_and_taxonomy_fields(): void
    {
        // The sitemap page HTML exposes the article body/title/hero
        // but doesn't render the author byline or category/tag chips
        // — exactly the gap RSS enrichment is meant to close.
        $sitemap = $this->minimalSitemap(['https://ex.com/post-x']);

        $fetcher = new FakeHttpProbeFetcher([
            'https://ex.com/sitemap.xml' => ['body' => $sitemap, 'http_code' => 200, 'error' => ''],
            'https://ex.com/post-x' => [
                'body' => $this->articleHtml(
                    'Post X title',
                    'Body long enough to clear the extractor minimum body length gate; lorem ipsum dolor sit amet enim.',
                ),
                'http_code' => 200,
                'error' => '',
            ],
        ]);

        $rss = [$this->rssDto(
            canonicalUrl: 'https://ex.com/post-x',
            author: 'Jane Doe',
            categories: ['News', 'Updates'],
            tags: ['launch', 'featured'],
        )];

        $result = (new SitemapPageImporter(fetcher: $fetcher))->walk(
            'https://ex.com/sitemap.xml',
            rssFallback: $rss,
        );

        $this->assertCount(1, $result->items);
        $item = $result->items[0];
        $this->assertSame('Jane Doe', $item->author);
        $this->assertSame(['News', 'Updates'], $item->categories);
        $this->assertSame(['launch', 'featured'], $item->tags);
    }

    #[Test]
    public function page_extracted_author_beats_rss_fallback_author(): void
    {
        // When both signals exist, the page's own meta wins — it's
        // what the publisher showed readers, which is the
        // authoritative value. RSS is a fallback, not an override.
        $sitemap = $this->minimalSitemap(['https://ex.com/post-a']);

        $fetcher = new FakeHttpProbeFetcher([
            'https://ex.com/sitemap.xml' => ['body' => $sitemap, 'http_code' => 200, 'error' => ''],
            'https://ex.com/post-a' => [
                'body' => $this->articleHtml(
                    'Post A',
                    'Body long enough to clear the extractor min body length gate; lorem ipsum dolor sit amet enim.',
                    ['author' => 'Page Author'],
                ),
                'http_code' => 200,
                'error' => '',
            ],
        ]);

        $rss = [$this->rssDto(
            canonicalUrl: 'https://ex.com/post-a',
            author: 'RSS Author',
            categories: ['Tech'],
            tags: [],
        )];

        $result = (new SitemapPageImporter(fetcher: $fetcher))->walk(
            'https://ex.com/sitemap.xml',
            rssFallback: $rss,
        );

        $this->assertCount(1, $result->items);
        $this->assertSame(
            'Page Author',
            $result->items[0]->author,
            'Page-extracted author must not be overridden by RSS data'
        );
        // But RSS categories still fill in — the page has none to defend.
        $this->assertSame(['Tech'], $result->items[0]->categories);
    }

    #[Test]
    public function rss_fallback_matches_across_trailing_slash_and_host_case_variants(): void
    {
        // Real publisher feeds frequently differ from sitemap entries
        // by a trailing slash or mixed-case hostname. The importer
        // normalizes both sides before the lookup so the match works.
        $sitemap = $this->minimalSitemap(['https://ex.com/post-match']);

        $fetcher = new FakeHttpProbeFetcher([
            'https://ex.com/sitemap.xml' => ['body' => $sitemap, 'http_code' => 200, 'error' => ''],
            'https://ex.com/post-match' => [
                'body' => $this->articleHtml(
                    'Post Match',
                    'Body long enough to clear the extractor min body length gate; lorem ipsum dolor sit amet enim.',
                ),
                'http_code' => 200,
                'error' => '',
            ],
        ]);

        $rss = [$this->rssDto(
            canonicalUrl: 'https://EX.com/post-match/',
            author: 'Normalized Match',
            categories: [],
            tags: ['hit'],
        )];

        $result = (new SitemapPageImporter(fetcher: $fetcher))->walk(
            'https://ex.com/sitemap.xml',
            rssFallback: $rss,
        );

        $this->assertCount(1, $result->items);
        $this->assertSame('Normalized Match', $result->items[0]->author);
        $this->assertSame(['hit'], $result->items[0]->tags);
    }

    #[Test]
    public function rss_fallback_without_a_matching_url_is_a_noop(): void
    {
        // RSS carries posts the sitemap doesn't list (or vice versa)
        // — unmatched entries must leave the sitemap-produced item
        // untouched, not bleed their metadata into a different post.
        $sitemap = $this->minimalSitemap(['https://ex.com/only-in-sitemap']);

        $fetcher = new FakeHttpProbeFetcher([
            'https://ex.com/sitemap.xml' => ['body' => $sitemap, 'http_code' => 200, 'error' => ''],
            'https://ex.com/only-in-sitemap' => [
                'body' => $this->articleHtml(
                    'Only in sitemap',
                    'Body long enough to clear the extractor min body length gate; lorem ipsum dolor sit amet enim.',
                ),
                'http_code' => 200,
                'error' => '',
            ],
        ]);

        $rss = [$this->rssDto(
            canonicalUrl: 'https://ex.com/different-post',
            author: 'Unrelated Author',
            categories: ['Unrelated'],
            tags: ['bleed'],
        )];

        $result = (new SitemapPageImporter(fetcher: $fetcher))->walk(
            'https://ex.com/sitemap.xml',
            rssFallback: $rss,
        );

        $this->assertCount(1, $result->items);
        $this->assertNull($result->items[0]->author);
        $this->assertSame([], $result->items[0]->categories);
        $this->assertSame([], $result->items[0]->tags);
    }

    private function rssDto(
        string $canonicalUrl,
        ?string $author,
        array $categories,
        array $tags,
    ): MigrationItemDTO {
        return new MigrationItemDTO(
            guid: $canonicalUrl,
            title: 'RSS title',
            html: '<p>RSS body</p>',
            excerpt: null,
            author: $author,
            categories: $categories,
            tags: $tags,
            publishedAt: new DateTimeImmutable('2026-04-15T12:00:00+00:00'),
            canonicalUrl: $canonicalUrl,
            source: 'rss',
            sourceHost: (string)parse_url($canonicalUrl, PHP_URL_HOST) ?: null,
        );
    }
}
