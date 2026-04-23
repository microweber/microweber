<?php

namespace Modules\WordPressMigration\Tests\Unit;

use Modules\WordPressMigration\DTOs\SitemapCrawlResult;
use Modules\WordPressMigration\DTOs\SitemapUrlEntry;
use Modules\WordPressMigration\Services\Importers\SitemapImporter;
use Modules\WordPressMigration\Tests\Support\FakeHttpProbeFetcher;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Unit coverage for the Phase 4 sitemap crawler. The behaviour
 * matrix pinned here:
 *
 *  - Flat `<urlset>` is parsed with lastmod/changefreq/priority
 *    exposed on each entry.
 *  - `<sitemapindex>` is recursively resolved. Sub-sitemap URLs
 *    following the Yoast/RankMath/AIOSEO `<type>-sitemap.xml`
 *    naming convention carry an inferred content type through to
 *    every URL they contain.
 *  - Nesting is bounded by MAX_DEPTH; circular indexes short-circuit
 *    via the seen-sitemap set.
 *  - `max_urls` stops the crawl mid-urlset with `STOP_MAX_URLS`.
 *  - Root unreachable → `STOP_UNREACHABLE` with zero collected
 *    items. Sub-sitemap 404 AFTER a valid root is tolerated.
 *  - Duplicate `<loc>` entries across sub-sitemaps are deduped in
 *    the output.
 *  - Malformed XML at any level is dropped cleanly without throwing.
 */
class SitemapImporterTest extends TestCase
{
    #[Test]
    public function flat_urlset_is_parsed_into_entries_with_sitemap_metadata(): void
    {
        $xml = <<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url>
    <loc>https://example.com/hello</loc>
    <lastmod>2026-04-15T10:30:00+00:00</lastmod>
    <changefreq>WEEKLY</changefreq>
    <priority>0.8</priority>
  </url>
  <url>
    <loc>https://example.com/no-meta</loc>
  </url>
</urlset>
XML;

        $entries = (new SitemapImporter())->parseUrlset($xml);

        $this->assertCount(2, $entries);
        $this->assertSame('https://example.com/hello', $entries[0]->loc);
        $this->assertSame('2026-04-15T10:30:00+00:00', $entries[0]->lastmod?->format(DATE_ATOM));
        $this->assertSame('weekly', $entries[0]->changefreq, 'changefreq is normalized to lowercase');
        $this->assertSame(0.8, $entries[0]->priority);
        $this->assertNull($entries[0]->contentType);

        $this->assertSame('https://example.com/no-meta', $entries[1]->loc);
        $this->assertNull($entries[1]->lastmod);
        $this->assertNull($entries[1]->changefreq);
        $this->assertNull($entries[1]->priority);
    }

    #[Test]
    public function entries_missing_loc_are_dropped_silently(): void
    {
        $xml = <<<'XML'
<?xml version="1.0"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url><lastmod>2026-04-01</lastmod></url>
  <url><loc>https://example.com/kept</loc></url>
</urlset>
XML;

        $entries = (new SitemapImporter())->parseUrlset($xml);

        $this->assertCount(1, $entries);
        $this->assertSame('https://example.com/kept', $entries[0]->loc);
    }

    #[Test]
    public function unparseable_lastmod_is_ignored_without_dropping_the_entry(): void
    {
        $xml = <<<'XML'
<?xml version="1.0"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url>
    <loc>https://example.com/x</loc>
    <lastmod>not a real date</lastmod>
    <priority>garbage</priority>
  </url>
</urlset>
XML;

        $entries = (new SitemapImporter())->parseUrlset($xml);

        $this->assertCount(1, $entries);
        $this->assertSame('https://example.com/x', $entries[0]->loc);
        $this->assertNull($entries[0]->lastmod);
        $this->assertNull($entries[0]->priority);
    }

    #[Test]
    public function parse_index_yields_sub_sitemap_locations_with_inferred_content_types(): void
    {
        $xml = <<<'XML'
<?xml version="1.0"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <sitemap><loc>https://example.com/post-sitemap.xml</loc></sitemap>
  <sitemap><loc>https://example.com/page-sitemap.xml</loc></sitemap>
  <sitemap><loc>https://example.com/category-sitemap.xml</loc></sitemap>
  <sitemap><loc>https://example.com/post-sitemap2.xml</loc></sitemap>
  <sitemap><loc>https://example.com/sitemap.xml</loc></sitemap>
</sitemapindex>
XML;

        $list = (new SitemapImporter())->parseIndex($xml);

        $this->assertSame([
            ['loc' => 'https://example.com/post-sitemap.xml', 'content_type' => 'post'],
            ['loc' => 'https://example.com/page-sitemap.xml', 'content_type' => 'page'],
            ['loc' => 'https://example.com/category-sitemap.xml', 'content_type' => 'category'],
            ['loc' => 'https://example.com/post-sitemap2.xml', 'content_type' => 'post'],
            ['loc' => 'https://example.com/sitemap.xml', 'content_type' => null],
        ], $list);
    }

    #[Test]
    public function infer_content_type_handles_rankmath_aioseo_style_filenames(): void
    {
        $this->assertSame('post', SitemapImporter::inferContentType('https://ex.com/post-sitemap.xml'));
        $this->assertSame('product', SitemapImporter::inferContentType('https://ex.com/product-sitemap1.xml'));
        $this->assertSame('post_tag', SitemapImporter::inferContentType('https://ex.com/post_tag-sitemap.xml'));
        $this->assertSame('author', SitemapImporter::inferContentType('https://ex.com/author-sitemap.xml'));
        $this->assertNull(SitemapImporter::inferContentType('https://ex.com/sitemap.xml'));
        $this->assertNull(SitemapImporter::inferContentType('https://ex.com/sitemap_index.xml'));
        $this->assertNull(SitemapImporter::inferContentType('https://ex.com/feed'));
        $this->assertNull(SitemapImporter::inferContentType(''));
    }

    #[Test]
    public function crawl_of_flat_urlset_collects_entries_in_document_order(): void
    {
        $urlset = <<<'XML'
<?xml version="1.0"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url><loc>https://example.com/a</loc><lastmod>2026-04-01</lastmod></url>
  <url><loc>https://example.com/b</loc></url>
  <url><loc>https://example.com/c</loc></url>
</urlset>
XML;

        $fetcher = new FakeHttpProbeFetcher([
            'https://example.com/sitemap.xml' => ['body' => $urlset, 'http_code' => 200, 'error' => ''],
        ]);

        $result = (new SitemapImporter($fetcher))->crawl('https://example.com/sitemap.xml');

        $this->assertSame(SitemapCrawlResult::STOP_COMPLETE, $result->stopReason);
        $this->assertSame(1, $result->sitemapsFetched);
        $this->assertCount(3, $result->urls);
        $this->assertSame(
            ['https://example.com/a', 'https://example.com/b', 'https://example.com/c'],
            array_map(fn (SitemapUrlEntry $e) => $e->loc, $result->urls),
        );
        $this->assertSame(['https://example.com/sitemap.xml'], $result->fetchedUrls);
    }

    #[Test]
    public function crawl_resolves_yoast_style_index_and_carries_type_to_child_urls(): void
    {
        $index = <<<'XML'
<?xml version="1.0"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <sitemap><loc>https://wp.example/post-sitemap.xml</loc></sitemap>
  <sitemap><loc>https://wp.example/page-sitemap.xml</loc></sitemap>
  <sitemap><loc>https://wp.example/category-sitemap.xml</loc></sitemap>
</sitemapindex>
XML;
        $postSm = <<<'XML'
<?xml version="1.0"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url><loc>https://wp.example/2026/04/hello</loc></url>
  <url><loc>https://wp.example/2026/03/world</loc></url>
</urlset>
XML;
        $pageSm = <<<'XML'
<?xml version="1.0"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url><loc>https://wp.example/about</loc></url>
</urlset>
XML;
        $catSm = <<<'XML'
<?xml version="1.0"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url><loc>https://wp.example/category/news</loc></url>
</urlset>
XML;

        $fetcher = new FakeHttpProbeFetcher([
            'https://wp.example/sitemap_index.xml' => ['body' => $index, 'http_code' => 200, 'error' => ''],
            'https://wp.example/post-sitemap.xml' => ['body' => $postSm, 'http_code' => 200, 'error' => ''],
            'https://wp.example/page-sitemap.xml' => ['body' => $pageSm, 'http_code' => 200, 'error' => ''],
            'https://wp.example/category-sitemap.xml' => ['body' => $catSm, 'http_code' => 200, 'error' => ''],
        ]);

        $result = (new SitemapImporter($fetcher))->crawl('https://wp.example/sitemap_index.xml');

        $this->assertSame(SitemapCrawlResult::STOP_COMPLETE, $result->stopReason);
        $this->assertSame(4, $result->sitemapsFetched, 'Index + 3 sub-sitemaps');
        $this->assertCount(4, $result->urls);

        $byLoc = [];
        foreach ($result->urls as $entry) {
            $byLoc[$entry->loc] = $entry;
        }
        $this->assertSame('post', $byLoc['https://wp.example/2026/04/hello']->contentType);
        $this->assertSame('post', $byLoc['https://wp.example/2026/03/world']->contentType);
        $this->assertSame('page', $byLoc['https://wp.example/about']->contentType);
        $this->assertSame('category', $byLoc['https://wp.example/category/news']->contentType);
    }

    #[Test]
    public function crawl_resolves_nested_index_within_max_depth(): void
    {
        $outerIndex = <<<'XML'
<?xml version="1.0"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <sitemap><loc>https://example.com/inner-index.xml</loc></sitemap>
</sitemapindex>
XML;
        $innerIndex = <<<'XML'
<?xml version="1.0"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <sitemap><loc>https://example.com/post-sitemap.xml</loc></sitemap>
</sitemapindex>
XML;
        $postSm = <<<'XML'
<?xml version="1.0"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url><loc>https://example.com/deep-post</loc></url>
</urlset>
XML;

        $fetcher = new FakeHttpProbeFetcher([
            'https://example.com/sitemap_index.xml' => ['body' => $outerIndex, 'http_code' => 200, 'error' => ''],
            'https://example.com/inner-index.xml' => ['body' => $innerIndex, 'http_code' => 200, 'error' => ''],
            'https://example.com/post-sitemap.xml' => ['body' => $postSm, 'http_code' => 200, 'error' => ''],
        ]);

        $result = (new SitemapImporter($fetcher))->crawl('https://example.com/sitemap_index.xml');

        $this->assertSame(SitemapCrawlResult::STOP_COMPLETE, $result->stopReason);
        $this->assertSame(3, $result->sitemapsFetched);
        $this->assertCount(1, $result->urls);
        $this->assertSame('https://example.com/deep-post', $result->urls[0]->loc);
        $this->assertSame('post', $result->urls[0]->contentType,
            'Type inferred at the deepest index level must reach the urlset');
    }

    #[Test]
    public function crawl_breaks_circular_index_references_via_seen_set(): void
    {
        // Index A points at B which points back at A. The crawler
        // must not fetch either sitemap twice and must terminate.
        $indexA = <<<'XML'
<?xml version="1.0"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <sitemap><loc>https://example.com/b.xml</loc></sitemap>
</sitemapindex>
XML;
        $indexB = <<<'XML'
<?xml version="1.0"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <sitemap><loc>https://example.com/a.xml</loc></sitemap>
</sitemapindex>
XML;

        $fetcher = new FakeHttpProbeFetcher([
            'https://example.com/a.xml' => ['body' => $indexA, 'http_code' => 200, 'error' => ''],
            'https://example.com/b.xml' => ['body' => $indexB, 'http_code' => 200, 'error' => ''],
        ]);

        $result = (new SitemapImporter($fetcher))->crawl('https://example.com/a.xml');

        $this->assertSame(SitemapCrawlResult::STOP_COMPLETE, $result->stopReason);
        $this->assertSame(2, $result->sitemapsFetched,
            'Each circular sitemap fetched exactly once — no infinite loop');
        $this->assertCount(2, $fetcher->fetched);
    }

    #[Test]
    public function crawl_stops_with_max_depth_when_nesting_exceeds_cap(): void
    {
        // 5-level nested index: depth 0 → 1 → 2 → 3 → 4. MAX_DEPTH
        // is 3, so level 4 must be skipped and max_depth reported.
        $level = fn (string $loc) => <<<XML
<?xml version="1.0"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <sitemap><loc>{$loc}</loc></sitemap>
</sitemapindex>
XML;

        $fetcher = new FakeHttpProbeFetcher([
            'https://ex.com/s0.xml' => ['body' => $level('https://ex.com/s1.xml'), 'http_code' => 200, 'error' => ''],
            'https://ex.com/s1.xml' => ['body' => $level('https://ex.com/s2.xml'), 'http_code' => 200, 'error' => ''],
            'https://ex.com/s2.xml' => ['body' => $level('https://ex.com/s3.xml'), 'http_code' => 200, 'error' => ''],
            'https://ex.com/s3.xml' => ['body' => $level('https://ex.com/s4.xml'), 'http_code' => 200, 'error' => ''],
            'https://ex.com/s4.xml' => ['body' => '<?xml version="1.0"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"><url><loc>https://ex.com/lost</loc></url></urlset>', 'http_code' => 200, 'error' => ''],
        ]);

        $result = (new SitemapImporter($fetcher))->crawl('https://ex.com/s0.xml');

        $this->assertSame(SitemapCrawlResult::STOP_MAX_DEPTH, $result->stopReason);
        $this->assertSame([], $result->urls, 'Deepest urlset is beyond the cap and is dropped');
        $this->assertNotContains('https://ex.com/s4.xml', $fetcher->fetched);
    }

    #[Test]
    public function crawl_stops_at_max_urls_midway_and_reports_partial_result(): void
    {
        $urlset = <<<'XML'
<?xml version="1.0"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url><loc>https://example.com/1</loc></url>
  <url><loc>https://example.com/2</loc></url>
  <url><loc>https://example.com/3</loc></url>
  <url><loc>https://example.com/4</loc></url>
  <url><loc>https://example.com/5</loc></url>
</urlset>
XML;
        $fetcher = new FakeHttpProbeFetcher([
            'https://example.com/sitemap.xml' => ['body' => $urlset, 'http_code' => 200, 'error' => ''],
        ]);

        $result = (new SitemapImporter($fetcher))->crawl('https://example.com/sitemap.xml', maxUrls: 2);

        $this->assertSame(SitemapCrawlResult::STOP_MAX_URLS, $result->stopReason);
        $this->assertCount(2, $result->urls);
        $this->assertSame('https://example.com/1', $result->urls[0]->loc);
        $this->assertSame('https://example.com/2', $result->urls[1]->loc);
    }

    #[Test]
    public function crawl_with_non_positive_max_urls_is_an_immediate_unreachable(): void
    {
        $fetcher = new FakeHttpProbeFetcher([]);
        $result = (new SitemapImporter($fetcher))->crawl('https://example.com/sitemap.xml', maxUrls: 0);

        $this->assertSame(SitemapCrawlResult::STOP_UNREACHABLE, $result->stopReason);
        $this->assertSame(0, $result->sitemapsFetched);
        $this->assertSame([], $fetcher->fetched, 'No fetch is attempted with maxUrls <= 0');
    }

    #[Test]
    public function crawl_root_unreachable_returns_stop_unreachable_with_no_data(): void
    {
        $fetcher = new FakeHttpProbeFetcher([
            'https://offline.example/sitemap.xml' => ['body' => '', 'http_code' => 0, 'error' => 'connection refused'],
        ]);

        $result = (new SitemapImporter($fetcher))->crawl('https://offline.example/sitemap.xml');

        $this->assertSame(SitemapCrawlResult::STOP_UNREACHABLE, $result->stopReason);
        $this->assertSame(0, $result->sitemapsFetched);
        $this->assertSame([], $result->urls);
    }

    #[Test]
    public function crawl_tolerates_sub_sitemap_404_after_valid_root(): void
    {
        $index = <<<'XML'
<?xml version="1.0"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <sitemap><loc>https://example.com/post-sitemap.xml</loc></sitemap>
  <sitemap><loc>https://example.com/page-sitemap.xml</loc></sitemap>
</sitemapindex>
XML;
        $postSm = <<<'XML'
<?xml version="1.0"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url><loc>https://example.com/hello</loc></url>
</urlset>
XML;

        $fetcher = new FakeHttpProbeFetcher([
            'https://example.com/sitemap_index.xml' => ['body' => $index, 'http_code' => 200, 'error' => ''],
            'https://example.com/post-sitemap.xml' => ['body' => $postSm, 'http_code' => 200, 'error' => ''],
            'https://example.com/page-sitemap.xml' => ['body' => '', 'http_code' => 404, 'error' => ''],
        ]);

        $result = (new SitemapImporter($fetcher))->crawl('https://example.com/sitemap_index.xml');

        $this->assertSame(SitemapCrawlResult::STOP_COMPLETE, $result->stopReason);
        $this->assertCount(1, $result->urls);
        $this->assertSame('https://example.com/hello', $result->urls[0]->loc);
    }

    #[Test]
    public function crawl_dedupes_urls_that_appear_in_multiple_sub_sitemaps(): void
    {
        $index = <<<'XML'
<?xml version="1.0"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <sitemap><loc>https://example.com/post-sitemap.xml</loc></sitemap>
  <sitemap><loc>https://example.com/post-sitemap2.xml</loc></sitemap>
</sitemapindex>
XML;
        $first = <<<'XML'
<?xml version="1.0"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url><loc>https://example.com/a</loc></url>
  <url><loc>https://example.com/b</loc></url>
</urlset>
XML;
        $second = <<<'XML'
<?xml version="1.0"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url><loc>https://example.com/b</loc></url>
  <url><loc>https://example.com/c</loc></url>
</urlset>
XML;

        $fetcher = new FakeHttpProbeFetcher([
            'https://example.com/sitemap_index.xml' => ['body' => $index, 'http_code' => 200, 'error' => ''],
            'https://example.com/post-sitemap.xml' => ['body' => $first, 'http_code' => 200, 'error' => ''],
            'https://example.com/post-sitemap2.xml' => ['body' => $second, 'http_code' => 200, 'error' => ''],
        ]);

        $result = (new SitemapImporter($fetcher))->crawl('https://example.com/sitemap_index.xml');

        $locs = array_map(fn (SitemapUrlEntry $e) => $e->loc, $result->urls);
        $this->assertSame([
            'https://example.com/a',
            'https://example.com/b',
            'https://example.com/c',
        ], $locs);
    }

    #[Test]
    public function broken_xml_root_is_treated_as_unreachable(): void
    {
        $fetcher = new FakeHttpProbeFetcher([
            'https://example.com/sitemap.xml' => ['body' => '<<<garbage>>>', 'http_code' => 200, 'error' => ''],
        ]);

        $result = (new SitemapImporter($fetcher))->crawl('https://example.com/sitemap.xml');

        $this->assertSame(SitemapCrawlResult::STOP_UNREACHABLE, $result->stopReason);
        $this->assertSame(0, $result->sitemapsFetched);
    }

    #[Test]
    public function parse_urlset_rejects_an_index_document(): void
    {
        $index = <<<'XML'
<?xml version="1.0"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <sitemap><loc>https://ex.com/sub.xml</loc></sitemap>
</sitemapindex>
XML;

        $this->assertSame([], (new SitemapImporter())->parseUrlset($index));
    }

    #[Test]
    public function parse_index_rejects_a_urlset_document(): void
    {
        $urlset = <<<'XML'
<?xml version="1.0"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url><loc>https://ex.com/x</loc></url>
</urlset>
XML;

        $this->assertSame([], (new SitemapImporter())->parseIndex($urlset));
    }

    #[Test]
    public function dto_toArray_round_trip_keeps_all_fields(): void
    {
        $xml = <<<'XML'
<?xml version="1.0"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url>
    <loc>https://ex.com/a</loc>
    <lastmod>2026-04-01T00:00:00+00:00</lastmod>
    <changefreq>Daily</changefreq>
    <priority>0.7</priority>
  </url>
</urlset>
XML;
        [$dto] = (new SitemapImporter())->parseUrlset($xml, contentType: 'post');

        $this->assertSame([
            'loc' => 'https://ex.com/a',
            'lastmod' => '2026-04-01T00:00:00+00:00',
            'changefreq' => 'daily',
            'priority' => 0.7,
            'content_type' => 'post',
        ], $dto->toArray());
    }

    #[Test]
    public function normalize_rejects_non_http_input(): void
    {
        $fetcher = new FakeHttpProbeFetcher([]);

        $result = (new SitemapImporter($fetcher))->crawl('   ');
        $this->assertSame(SitemapCrawlResult::STOP_UNREACHABLE, $result->stopReason);
        $this->assertSame([], $fetcher->fetched);

        $result2 = (new SitemapImporter($fetcher))->crawl('ftp://example.com/sitemap.xml');
        $this->assertSame(SitemapCrawlResult::STOP_UNREACHABLE, $result2->stopReason);
        $this->assertSame([], $fetcher->fetched);
    }
}
