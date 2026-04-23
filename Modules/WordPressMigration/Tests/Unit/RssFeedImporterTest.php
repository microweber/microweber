<?php

namespace Modules\WordPressMigration\Tests\Unit;

use Modules\WordPressMigration\DTOs\MigrationItemDTO;
use Modules\WordPressMigration\Services\Importers\RssFeedImporter;
use Modules\WordPressMigration\Tests\Support\FakeHttpProbeFetcher;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Unit coverage for the RSS/Atom → MigrationItemDTO normalizer.
 *
 * The fuller fixture-driven suite (see TODO Phase 3:
 * `RssFeedImporterTest` with recorded `tests/fixtures/wp/feed-*.xml`)
 * is a separate follow-up task. These tests cover the
 * implementation's branching logic with inline XML so a change to
 * the parser surfaces as a localized failure rather than a
 * filesystem diff.
 */
class RssFeedImporterTest extends TestCase
{
    #[Test]
    public function rss_2_feed_is_parsed_into_dtos_with_description_as_html(): void
    {
        $xml = <<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/">
  <channel>
    <title>Example Site</title>
    <link>https://example.com</link>
    <item>
      <title>First post</title>
      <link>https://example.com/first</link>
      <guid isPermaLink="true">https://example.com/?p=1</guid>
      <pubDate>Wed, 15 Apr 2026 10:30:00 +0000</pubDate>
      <dc:creator>Alice</dc:creator>
      <description>Short teaser for the first post.</description>
      <category>News</category>
      <category domain="post_tag">announcement</category>
    </item>
  </channel>
</rss>
XML;

        $items = (new RssFeedImporter())->parseRss($xml, 'example.com');

        $this->assertCount(1, $items);
        $dto = $items[0];
        $this->assertInstanceOf(MigrationItemDTO::class, $dto);
        $this->assertSame('https://example.com/?p=1', $dto->guid);
        $this->assertSame('First post', $dto->title);
        $this->assertSame('Short teaser for the first post.', $dto->html);
        $this->assertNull($dto->excerpt);
        $this->assertSame('Alice', $dto->author);
        $this->assertSame(['News'], $dto->categories);
        $this->assertSame(['announcement'], $dto->tags);
        $this->assertSame('https://example.com/first', $dto->canonicalUrl);
        $this->assertNotNull($dto->publishedAt);
        $this->assertSame('2026-04-15T10:30:00+00:00', $dto->publishedAt->format(DATE_ATOM));
        $this->assertSame('rss', $dto->source);
        $this->assertSame('example.com', $dto->sourceHost);
    }

    #[Test]
    public function content_encoded_wins_over_description_and_description_becomes_excerpt(): void
    {
        $xml = <<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0"
     xmlns:content="http://purl.org/rss/1.0/modules/content/"
     xmlns:wp="http://wordpress.org/export/1.2/">
  <channel>
    <title>Ex</title>
    <item>
      <title>Full article</title>
      <link>https://example.com/full</link>
      <guid>https://example.com/?p=9</guid>
      <wp:post_id>42</wp:post_id>
      <description>This is the short excerpt.</description>
      <content:encoded><![CDATA[<p>The <em>full</em> article body with <img src="https://example.com/a.png"/>.</p>]]></content:encoded>
    </item>
  </channel>
</rss>
XML;

        $items = (new RssFeedImporter())->parseRss($xml);

        $this->assertCount(1, $items);
        $dto = $items[0];
        $this->assertSame('wp:42', $dto->guid, 'wp:post_id wins as stable identity');
        $this->assertSame(
            '<p>The <em>full</em> article body with <img src="https://example.com/a.png"/>.</p>',
            $dto->html
        );
        $this->assertSame('This is the short excerpt.', $dto->excerpt);
    }

    #[Test]
    public function atom_entries_are_parsed_with_content_and_alternate_link(): void
    {
        $xml = <<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<feed xmlns="http://www.w3.org/2005/Atom">
  <title>Ex</title>
  <entry>
    <id>tag:example.com,2026:post-7</id>
    <title>Atom post</title>
    <link rel="self" href="https://example.com/feed/atom/7"/>
    <link rel="alternate" href="https://example.com/atom-post"/>
    <published>2026-03-05T08:15:00+00:00</published>
    <author><name>Bob</name></author>
    <category term="Essays" label="essays"/>
    <summary>Short Atom summary.</summary>
    <content type="html"><![CDATA[<p>Atom body <strong>content</strong>.</p>]]></content>
  </entry>
</feed>
XML;

        $items = (new RssFeedImporter())->parseAtom($xml, 'example.com');

        $this->assertCount(1, $items);
        $dto = $items[0];
        $this->assertSame('tag:example.com,2026:post-7', $dto->guid);
        $this->assertSame('Atom post', $dto->title);
        $this->assertSame('<p>Atom body <strong>content</strong>.</p>', $dto->html);
        $this->assertSame('Short Atom summary.', $dto->excerpt);
        $this->assertSame('Bob', $dto->author);
        $this->assertSame(['Essays'], $dto->categories);
        $this->assertSame([], $dto->tags, 'Atom has no tag/category split on its own');
        $this->assertSame('https://example.com/atom-post', $dto->canonicalUrl);
        $this->assertSame('2026-03-05T08:15:00+00:00', $dto->publishedAt?->format(DATE_ATOM));
        $this->assertSame('rss', $dto->source);
    }

    #[Test]
    public function import_hits_feed_slash_and_maps_items(): void
    {
        $rss = <<<'XML'
<?xml version="1.0"?>
<rss version="2.0">
  <channel>
    <item><title>A</title><link>https://wp.example/a</link><guid>https://wp.example/a</guid></item>
    <item><title>B</title><link>https://wp.example/b</link><guid>https://wp.example/b</guid></item>
  </channel>
</rss>
XML;
        $fetcher = new FakeHttpProbeFetcher([
            'https://wp.example/feed/' => ['body' => $rss, 'http_code' => 200, 'error' => ''],
        ]);

        $items = (new RssFeedImporter($fetcher))->import('https://wp.example');

        $this->assertCount(2, $items);
        $this->assertSame(['https://wp.example/feed/'], $fetcher->fetched);
        $this->assertSame('A', $items[0]->title);
        $this->assertSame('B', $items[1]->title);
        $this->assertSame('wp.example', $items[0]->sourceHost);
    }

    #[Test]
    public function import_falls_back_to_atom_when_rss_is_missing_or_broken(): void
    {
        $atom = <<<'XML'
<?xml version="1.0"?>
<feed xmlns="http://www.w3.org/2005/Atom">
  <entry>
    <id>tag:wp.example,2026:fallback-1</id>
    <title>Fallback entry</title>
    <link href="https://wp.example/fallback-1"/>
    <content type="html">fallback body</content>
  </entry>
</feed>
XML;
        $fetcher = new FakeHttpProbeFetcher([
            'https://wp.example/feed/' => ['body' => '<html>nope</html>', 'http_code' => 200, 'error' => ''],
            'https://wp.example/feed/atom/' => ['body' => $atom, 'http_code' => 200, 'error' => ''],
        ]);

        $items = (new RssFeedImporter($fetcher))->import('https://wp.example');

        $this->assertCount(1, $items);
        $this->assertSame('tag:wp.example,2026:fallback-1', $items[0]->guid);
        $this->assertSame('Fallback entry', $items[0]->title);
        $this->assertSame('https://wp.example/fallback-1', $items[0]->canonicalUrl);
        $this->assertSame(
            ['https://wp.example/feed/', 'https://wp.example/feed/atom/'],
            $fetcher->fetched,
            'RSS must be tried first, Atom only as fallback'
        );
    }

    #[Test]
    public function import_returns_empty_list_when_both_endpoints_fail(): void
    {
        $fetcher = new FakeHttpProbeFetcher([
            'https://wp.example/feed/' => ['body' => '', 'http_code' => 404, 'error' => ''],
            'https://wp.example/feed/atom/' => ['body' => '', 'http_code' => 404, 'error' => ''],
        ]);

        $items = (new RssFeedImporter($fetcher))->import('https://wp.example');

        $this->assertSame([], $items);
    }

    #[Test]
    public function broken_xml_yields_empty_list_without_throwing(): void
    {
        // Truncated RSS: channel is open but item is cut mid-way.
        $broken = '<?xml version="1.0"?><rss version="2.0"><channel><item><title>Half';
        $items = (new RssFeedImporter())->parseRss($broken);
        $this->assertSame([], $items);
    }

    #[Test]
    public function items_without_any_guid_or_link_are_dropped(): void
    {
        $xml = <<<'XML'
<?xml version="1.0"?>
<rss version="2.0">
  <channel>
    <item><title>Anonymous</title><description>no guid, no link</description></item>
    <item><title>Legit</title><link>https://example.com/x</link><guid>https://example.com/x</guid></item>
  </channel>
</rss>
XML;
        $items = (new RssFeedImporter())->parseRss($xml);
        $this->assertCount(1, $items, 'Item without any stable id is dropped');
        $this->assertSame('Legit', $items[0]->title);
    }

    #[Test]
    public function dto_toArray_serializes_dates_and_list_fields(): void
    {
        $xml = <<<'XML'
<?xml version="1.0"?>
<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/">
  <channel>
    <item>
      <title>Round-trip</title>
      <link>https://example.com/r</link>
      <guid>https://example.com/r</guid>
      <pubDate>Thu, 01 Jan 2026 00:00:00 +0000</pubDate>
      <dc:creator>Carol</dc:creator>
      <description>body</description>
      <category>News</category>
      <category domain="post_tag">pinned</category>
    </item>
  </channel>
</rss>
XML;
        [$dto] = (new RssFeedImporter())->parseRss($xml, 'example.com');

        $this->assertSame([
            'guid' => 'https://example.com/r',
            'title' => 'Round-trip',
            'html' => 'body',
            'excerpt' => null,
            'author' => 'Carol',
            'categories' => ['News'],
            'tags' => ['pinned'],
            'published_at' => '2026-01-01T00:00:00+00:00',
            'canonical_url' => 'https://example.com/r',
            'source' => 'rss',
            'source_host' => 'example.com',
        ], $dto->toArray());
    }

    #[Test]
    public function invalid_base_url_returns_empty_without_hitting_fetcher(): void
    {
        $fetcher = new FakeHttpProbeFetcher([]);
        $items = (new RssFeedImporter($fetcher))->import('   ');
        $this->assertSame([], $items);
        $this->assertSame([], $fetcher->fetched);
    }

    #[Test]
    public function trailing_slash_in_base_url_does_not_double_up_feed_path(): void
    {
        $fetcher = new FakeHttpProbeFetcher([
            'https://wp.example/feed/' => [
                'body' => '<?xml version="1.0"?><rss version="2.0"><channel></channel></rss>',
                'http_code' => 200,
                'error' => '',
            ],
            'https://wp.example/feed/atom/' => [
                'body' => '', 'http_code' => 404, 'error' => '',
            ],
        ]);

        (new RssFeedImporter($fetcher))->import('https://wp.example/');

        // If the base wasn't trimmed we would have seen https://wp.example//feed/.
        $this->assertSame('https://wp.example/feed/', $fetcher->fetched[0]);
    }
}
