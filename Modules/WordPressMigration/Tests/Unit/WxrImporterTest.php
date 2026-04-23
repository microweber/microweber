<?php

namespace Modules\WordPressMigration\Tests\Unit;

use Modules\WordPressMigration\DTOs\MigrationItemDTO;
use Modules\WordPressMigration\DTOs\WxrImportResult;
use Modules\WordPressMigration\Services\Importers\WxrImporter;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Unit coverage for {@see WxrImporter} — the offline WordPress
 * eXtended RSS file importer.
 *
 * Split into two halves: fixture-level tests that feed the whole
 * `tests/fixtures/wp/wxr-sample.xml` through {@see WxrImporter::import()}
 * (the real production path, including the XMLReader streaming
 * open), and narrow tests that use `importString()` with inline XML
 * for edge cases that would bloat the shared fixture if added there.
 */
class WxrImporterTest extends TestCase
{
    private function fixturePath(): string
    {
        // Same fixture the Filament Dusk test will upload once Phase 6
        // wires the form field — keeping both pointed at one file
        // means the fixture's shape is covered by unit tests before
        // a browser test can flake on a size change.
        return __DIR__ . '/../../../../tests/fixtures/wp/wxr-sample.xml';
    }

    #[Test]
    public function importing_the_sample_wxr_yields_published_posts_and_pages(): void
    {
        $result = (new WxrImporter())->import($this->fixturePath());

        $this->assertInstanceOf(WxrImportResult::class, $result);
        $this->assertSame(WxrImportResult::STOP_COMPLETE, $result->stopReason);

        // Three publishable items: wp:1001, wp:1002 (posts) + wp:2001 (page).
        // Draft / revision / nav_menu_item / attachment items must not
        // land in $items — their presence would be a regression.
        $guids = array_map(fn (MigrationItemDTO $d) => $d->guid, $result->items);
        sort($guids);
        $this->assertSame(['wp:1001', 'wp:1002', 'wp:2001'], $guids);

        // itemsSeen counts EVERY <item> in the file, including the
        // four skipped ones, so the operator can reason about why
        // the emitted count is smaller than the source WXR.
        $this->assertGreaterThanOrEqual(count($result->items), $result->itemsSeen);
        $this->assertSame(7, $result->itemsSeen);
    }

    #[Test]
    public function post_dto_preserves_gutenberg_body_and_author_and_terms(): void
    {
        $result = (new WxrImporter())->import($this->fixturePath());

        $launch = $this->findGuid($result->items, 'wp:1001');

        $this->assertSame('Launch day: what shipped', $launch->title);
        $this->assertStringContainsString('<!-- wp:paragraph -->', $launch->html,
            'Gutenberg block comments must pass through WXR import untouched'
        );
        $this->assertSame('A rundown of everything that went live.', $launch->excerpt);
        $this->assertSame('jane', $launch->author);
        $this->assertSame('jane', $launch->authorSlug);
        $this->assertSame('wxr', $launch->source);
        $this->assertSame('wxr-fixture.example', $launch->sourceHost);
        $this->assertSame('https://wxr-fixture.example/2026/04/launch-day/', $launch->canonicalUrl);

        $this->assertSame(['News', 'Tech'], $launch->categories);
        $this->assertSame(['Launch', 'Featured'], $launch->tags);
        $this->assertSame(['news', 'tech'], $launch->categorySlugs);
        $this->assertSame(['launch', 'featured'], $launch->tagSlugs);

        $this->assertNotNull($launch->publishedAt);
        $this->assertSame('2026-04-10T12:00:00+00:00', $launch->publishedAt->format(DATE_ATOM));

        // _thumbnail_id meta is lifted into featuredImageUrl as a
        // `wp-attachment:{id}` reference so the mapper can later
        // resolve it against the collected media side-channel.
        $this->assertSame('wp-attachment:3001', $launch->featuredImageUrl);
    }

    #[Test]
    public function item_without_item_level_nicename_backfills_slug_from_channel_index(): void
    {
        // wp:1002's <category domain="category">Tech</category> has no
        // `nicename` attribute. The importer should fall back to the
        // channel-level <wp:category> block to resolve "Tech" → "tech".
        $result = (new WxrImporter())->import($this->fixturePath());

        $chart = $this->findGuid($result->items, 'wp:1002');

        $this->assertSame(['Tech'], $chart->categories);
        $this->assertSame(['tech'], $chart->categorySlugs,
            'Channel-level <wp:category> block should backfill the missing nicename'
        );
        $this->assertSame(['Launch'], $chart->tags);
        $this->assertSame(['launch'], $chart->tagSlugs);
    }

    #[Test]
    public function attachments_go_to_media_side_channel_not_items(): void
    {
        $result = (new WxrImporter())->import($this->fixturePath());

        $this->assertCount(1, $result->media,
            'wp:3001 is an attachment item and must surface on ->media, not ->items'
        );
        $attachment = $result->media[0];
        $this->assertSame(3001, $attachment['id']);
        $this->assertSame(
            'https://wxr-fixture.example/wp-content/uploads/2026/04/hero.jpg',
            $attachment['source_url']
        );
        $this->assertSame('image/jpeg', $attachment['mime_type']);
        $this->assertSame(1001, $attachment['post'],
            'Attachment should keep its wp:post_parent so the mapper can link it to wp:1001'
        );
    }

    #[Test]
    public function channel_authors_and_terms_are_exposed_for_taxonomy_priming(): void
    {
        $result = (new WxrImporter())->import($this->fixturePath());

        $this->assertCount(2, $result->users);
        $jane = $this->findFirst($result->users, fn ($u) => ($u['login'] ?? '') === 'jane');
        $this->assertSame('Jane Doe', $jane['display_name']);
        $this->assertSame('Jane Doe', $jane['name'],
            'Shape-compatible with TaxonomyIndex::primeUsers — keyed by `name` not `display_name`'
        );
        $this->assertSame('jane', $jane['slug'],
            'Shape-compatible with TaxonomyIndex::primeUsers — keyed by `slug` not `login`'
        );
        $this->assertSame('jane@example.com', $jane['email']);

        $this->assertCount(2, $result->categories);
        $news = $this->findFirst($result->categories, fn ($c) => ($c['slug'] ?? '') === 'news');
        $this->assertSame('News', $news['name']);
        $this->assertSame('11', $news['id']);

        $this->assertCount(2, $result->tags);
    }

    #[Test]
    public function drafts_and_revisions_and_nav_menu_items_are_skipped(): void
    {
        $result = (new WxrImporter())->import($this->fixturePath());

        $guids = array_map(fn (MigrationItemDTO $d) => $d->guid, $result->items);
        $this->assertNotContains('wp:9001', $guids, 'Draft post MUST be skipped');
        $this->assertNotContains('wp:8001', $guids, 'Revision item MUST be skipped');
        $this->assertNotContains('wp:7001', $guids, 'nav_menu_item MUST be skipped');
        $this->assertNotContains('wp:3001', $guids, 'Attachment MUST NOT appear in ->items (it goes to ->media)');
    }

    #[Test]
    public function seen_guids_prevent_rewriting_already_imported_items(): void
    {
        // Re-runs against a WXR that shares ids with a prior REST
        // import must not re-emit those items. Downstream dedupe
        // would also catch it but short-circuiting here saves a
        // mapper call per item on large re-runs.
        $result = (new WxrImporter())->import(
            $this->fixturePath(),
            seenGuids: ['wp:1001'],
        );

        $guids = array_map(fn (MigrationItemDTO $d) => $d->guid, $result->items);
        $this->assertNotContains('wp:1001', $guids);
        $this->assertCount(2, $result->items);
    }

    #[Test]
    public function max_items_stops_collection_mid_stream(): void
    {
        $result = (new WxrImporter())->import($this->fixturePath(), maxItems: 1);

        $this->assertCount(1, $result->items);
        $this->assertSame(WxrImportResult::STOP_MAX_ITEMS, $result->stopReason,
            'Hitting maxItems must report the capped stop reason so the UI can tell the operator'
        );
    }

    #[Test]
    public function missing_file_returns_unreachable_with_a_warning(): void
    {
        $result = (new WxrImporter())->import('/no/such/file.xml');

        $this->assertSame([], $result->items);
        $this->assertSame(WxrImportResult::STOP_UNREACHABLE, $result->stopReason);
        $this->assertNotEmpty($result->warnings);
        $this->assertStringContainsString('/no/such/file.xml', $result->warnings[0]);
    }

    #[Test]
    public function malformed_xml_string_reports_unreachable_without_throwing(): void
    {
        // libxml would otherwise spew onto stderr — the importer must
        // silence that and surface the failure as a typed result.
        $result = (new WxrImporter())->importString('<this is not valid XML');

        $this->assertSame([], $result->items);
        $this->assertSame(WxrImportResult::STOP_UNREACHABLE, $result->stopReason);
    }

    #[Test]
    public function inline_string_path_handles_minimal_single_item_wxr(): void
    {
        // A hand-rolled minimal export — the smallest thing that
        // still parses — proves the string path works without a
        // file and without relying on the shared fixture.
        $xml = <<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0"
     xmlns:content="http://purl.org/rss/1.0/modules/content/"
     xmlns:dc="http://purl.org/dc/elements/1.1/"
     xmlns:wp="http://wordpress.org/export/1.2/">
  <channel>
    <title>Minimal</title>
    <link>https://minimal.example</link>
    <wp:wxr_version>1.2</wp:wxr_version>
    <item>
      <title>Solo</title>
      <link>https://minimal.example/solo</link>
      <dc:creator>admin</dc:creator>
      <content:encoded><![CDATA[<p>Lone item.</p>]]></content:encoded>
      <wp:post_id>42</wp:post_id>
      <wp:post_date_gmt>2026-02-01 08:00:00</wp:post_date_gmt>
      <wp:status>publish</wp:status>
      <wp:post_type>post</wp:post_type>
    </item>
  </channel>
</rss>
XML;

        $result = (new WxrImporter())->importString($xml);

        $this->assertCount(1, $result->items);
        $dto = $result->items[0];
        $this->assertSame('wp:42', $dto->guid);
        $this->assertSame('Solo', $dto->title);
        $this->assertSame('<p>Lone item.</p>', $dto->html);
        $this->assertSame('admin', $dto->author);
        $this->assertSame('minimal.example', $dto->sourceHost);

        // No <wp:category>/<wp:tag>/<wp:author> blocks — the
        // importer must surface warnings so the operator knows
        // author/term enrichment will be degraded.
        $this->assertNotEmpty($result->warnings);
    }

    /**
     * @param list<MigrationItemDTO> $items
     */
    private function findGuid(array $items, string $guid): MigrationItemDTO
    {
        foreach ($items as $item) {
            if ($item->guid === $guid) {
                return $item;
            }
        }
        $this->fail("Item {$guid} not present in result set");
    }

    /**
     * @template T
     * @param list<T> $rows
     * @param callable(T): bool $matcher
     * @return T
     */
    private function findFirst(array $rows, callable $matcher)
    {
        foreach ($rows as $row) {
            if ($matcher($row)) {
                return $row;
            }
        }
        $this->fail('No row in the result set matched the predicate');
    }
}
