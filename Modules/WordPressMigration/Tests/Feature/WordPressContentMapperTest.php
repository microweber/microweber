<?php

namespace Modules\WordPressMigration\Tests\Feature;

use DateTimeImmutable;
use Illuminate\Support\Facades\DB;
use Modules\Content\Models\Content;
use Modules\WordPressMigration\DTOs\MigrationItemDTO;
use Modules\WordPressMigration\Services\WordPressContentMapper;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Feature coverage for the DTO → `content` row + `content_data`
 * meta mapping.
 *
 * Integration scope on purpose: the class under test leans on the
 * ContentDataTrait save hooks which only fire against a real
 * Eloquent/DB stack, and the idempotency contract relies on the
 * `whereContentData` scope issuing a real join. Mocking the DB
 * here would only prove that the mock behaves the way we told it
 * to — not that `content_data` actually identifies the prior row.
 */
class WordPressContentMapperTest extends TestCase
{
    private WordPressContentMapper $mapper;

    protected function setUp(): void
    {
        parent::setUp();

        // Isolate test data — drop any prior WP-migrated rows plus
        // their content_data entries so a previous failing run
        // can't spoof idempotency on a later run.
        $priorContentIds = DB::table('content_data')
            ->where('field_name', WordPressContentMapper::META_IMPORT_SOURCE)
            ->where('field_value', WordPressContentMapper::IMPORT_SOURCE_WORDPRESS_RSS)
            ->pluck('rel_id');

        if ($priorContentIds->isNotEmpty()) {
            DB::table('content_data')->whereIn('rel_id', $priorContentIds)->delete();
            DB::table('content')->whereIn('id', $priorContentIds)->delete();
        }

        $this->mapper = new WordPressContentMapper();
    }

    #[Test]
    public function mapping_a_dto_inserts_a_content_row_plus_meta(): void
    {
        $dto = $this->dto([
            'guid' => 'https://example.com/?p=101',
            'title' => 'Hello world',
            'html' => '<p>Body</p>',
            'excerpt' => 'Teaser',
            'canonicalUrl' => 'https://example.com/hello-world',
            'sourceHost' => 'example.com',
            'publishedAt' => new DateTimeImmutable('2026-04-15T09:00:00+00:00'),
        ]);

        $content = $this->mapper->map($dto);

        $this->assertNotNull($content->id);
        $this->assertSame('Hello world', $content->title);
        $this->assertSame('<p>Body</p>', $content->content);
        $this->assertSame('Teaser', $content->description);
        $this->assertSame('post', $content->content_type);
        $this->assertSame('post', $content->subtype);
        $this->assertSame('https://example.com/hello-world', $content->original_link);
        $this->assertSame(1, (int)$content->is_active);

        $meta = $content->getContentData();
        $this->assertSame('wordpress_rss', $meta[WordPressContentMapper::META_IMPORT_SOURCE]);
        $this->assertSame('https://example.com/?p=101', $meta[WordPressContentMapper::META_SOURCE_GUID]);
        $this->assertSame('example.com', $meta[WordPressContentMapper::META_SOURCE_HOST]);
    }

    #[Test]
    public function mapping_the_same_guid_twice_returns_the_same_content_row(): void
    {
        $dto = $this->dto(['guid' => 'wp:42', 'title' => 'First pass']);
        $first = $this->mapper->map($dto);

        $updated = $this->dto(['guid' => 'wp:42', 'title' => 'Second pass', 'html' => '<p>Updated body</p>']);
        $second = $this->mapper->map($updated);

        $this->assertSame($first->id, $second->id, 'Re-running must upsert onto the same row');
        $this->assertSame('Second pass', $second->title);
        $this->assertSame('<p>Updated body</p>', $second->content);

        // Exactly one content row and exactly one guid content_data row.
        $this->assertSame(1, DB::table('content_data')
            ->where('rel_id', $first->id)
            ->where('field_name', WordPressContentMapper::META_SOURCE_GUID)
            ->count()
        );
    }

    #[Test]
    public function different_guids_get_different_content_rows(): void
    {
        $a = $this->mapper->map($this->dto(['guid' => 'wp:1', 'title' => 'A']));
        $b = $this->mapper->map($this->dto(['guid' => 'wp:2', 'title' => 'B']));

        $this->assertNotSame($a->id, $b->id);
    }

    #[Test]
    public function find_existing_returns_null_for_unknown_guid_and_the_row_for_known_one(): void
    {
        $this->assertNull($this->mapper->findExisting('wp:does-not-exist'));

        $created = $this->mapper->map($this->dto(['guid' => 'wp:7', 'title' => 'Known']));
        $found = $this->mapper->findExisting('wp:7');

        $this->assertNotNull($found);
        $this->assertSame($created->id, $found->id);
    }

    #[Test]
    public function different_import_sources_with_same_guid_do_not_collide(): void
    {
        // The same feed guid appearing under both wordpress_rss and
        // wordpress_rest (operator migrated modes mid-run) must
        // resolve to distinct content rows — otherwise switching
        // modes would silently overwrite the prior import.
        $rssMapper = new WordPressContentMapper(importSource: 'wordpress_rss');
        $restMapper = new WordPressContentMapper(importSource: 'wordpress_rest');

        $rss = $rssMapper->map($this->dto(['guid' => 'shared-id', 'title' => 'Via RSS']));
        $rest = $restMapper->map($this->dto(['guid' => 'shared-id', 'title' => 'Via REST']));

        $this->assertNotSame($rss->id, $rest->id);
        $this->assertSame('Via RSS', Content::find($rss->id)->title);
        $this->assertSame('Via REST', Content::find($rest->id)->title);
    }

    #[Test]
    public function page_subtype_is_respected_when_configured(): void
    {
        $pageMapper = new WordPressContentMapper(contentType: 'page', subtype: 'static');
        $page = $pageMapper->map($this->dto(['guid' => 'wp-page:1', 'title' => 'About']));

        $this->assertSame('page', $page->content_type);
        $this->assertSame('static', $page->subtype);
    }

    #[Test]
    public function posted_at_is_null_when_dto_has_no_published_date(): void
    {
        $dto = $this->dto(['guid' => 'wp:nopub', 'title' => 'No date', 'publishedAt' => null]);
        $content = $this->mapper->map($dto);

        $this->assertNull($content->posted_at, 'Null publishedAt must not invent a posted_at');
    }

    #[Test]
    public function empty_source_host_is_not_stored_as_meta(): void
    {
        $dto = $this->dto(['guid' => 'wp:hostless', 'title' => 'Hostless', 'sourceHost' => null]);
        $content = $this->mapper->map($dto);

        $meta = $content->getContentData();
        $this->assertArrayNotHasKey(
            WordPressContentMapper::META_SOURCE_HOST,
            $meta,
            'Absent host must not write an empty meta row'
        );
        // But the two required keys are still present.
        $this->assertArrayHasKey(WordPressContentMapper::META_IMPORT_SOURCE, $meta);
        $this->assertArrayHasKey(WordPressContentMapper::META_SOURCE_GUID, $meta);
    }

    #[Test]
    public function canonical_url_last_segment_is_preserved_as_the_content_slug(): void
    {
        // Microweber's permalink resolver keys off the last URL
        // segment, so an imported WP link like
        // `<a href="/category/my-post/">` still lands on this row
        // once the host is rewritten to the destination site.
        $slug = 'wp-slug-' . uniqid();
        $dto = $this->dto([
            'guid' => 'wp:slug-preserved',
            'title' => 'Totally unrelated title',
            'canonicalUrl' => 'https://wp.example/category/' . $slug . '/',
        ]);

        $content = $this->mapper->map($dto);

        $this->assertSame(
            $slug,
            $content->url,
            'The slug from the origin URL must win over the title-based default'
        );
    }

    #[Test]
    public function colliding_slugs_across_sources_get_deterministic_dash_suffix(): void
    {
        // Two different origin sites publish posts that happen to
        // share the same last-segment slug. The second import must
        // disambiguate deterministically with a `-2` suffix rather
        // than getting the generic `YmdHis` timestamp HasSlugTrait
        // would otherwise fall back to.
        $slug = 'clash-' . uniqid();
        $first = $this->mapper->map($this->dto([
            'guid' => 'wp:clash-a',
            'title' => 'First',
            'canonicalUrl' => 'https://siteA.example/' . $slug,
            'sourceHost' => 'siteA.example',
        ]));

        $second = $this->mapper->map($this->dto([
            'guid' => 'wp:clash-b',
            'title' => 'Second',
            'canonicalUrl' => 'https://siteB.example/' . $slug,
            'sourceHost' => 'siteB.example',
        ]));

        $this->assertSame($slug, $first->url);
        $this->assertSame($slug . '-2', $second->url);
        $this->assertNotSame($first->id, $second->id);
    }

    #[Test]
    public function null_canonical_url_falls_back_to_title_based_slug_generation(): void
    {
        // Nothing to preserve — let HasSlugTrait derive from the
        // title as it always did pre-import-pipeline. This is the
        // regression guard for DTOs that never had a permalink
        // (e.g. hand-built fixtures or a feed that omits the link).
        $dto = $this->dto([
            'guid' => 'wp:no-canonical',
            'title' => 'Title Only Slug',
            'canonicalUrl' => null,
        ]);

        $content = $this->mapper->map($dto);

        $this->assertNotEmpty($content->url);
        // HasSlugTrait preserves the title's case (it has the
        // strtolower step commented out) and may append a timestamp
        // disambiguator if another row already owns this slug, so
        // assert only that the url starts with the expected stem
        // rather than pinning the exact value.
        $this->assertStringStartsWith('Title-Only-Slug', $content->url);
    }

    #[Test]
    public function re_importing_the_same_guid_does_not_change_the_existing_slug(): void
    {
        // Idempotency contract: the URL is pinned on first insert.
        // A later re-import with the same guid (whether the canonical
        // URL has moved on the origin or not) must leave `content.url`
        // alone so external/internal links stay valid.
        $slug = 'keep-' . uniqid();
        $first = $this->mapper->map($this->dto([
            'guid' => 'wp:idem-slug',
            'title' => 'Original',
            'canonicalUrl' => 'https://wp.example/' . $slug,
        ]));
        $this->assertSame($slug, $first->url);

        $second = $this->mapper->map($this->dto([
            'guid' => 'wp:idem-slug',
            'title' => 'Origin renamed on the upstream site',
            'canonicalUrl' => 'https://wp.example/some-other-path',
        ]));

        $this->assertSame($first->id, $second->id);
        $this->assertSame(
            $slug,
            $second->url,
            'Re-import must not rewrite the pinned slug even if the upstream URL changed'
        );
    }

    /**
     * @param array<string, mixed> $overrides
     */
    private function dto(array $overrides): MigrationItemDTO
    {
        $defaults = [
            'guid' => 'wp:default',
            'title' => 'Default title',
            'html' => '<p>default body</p>',
            'excerpt' => null,
            'author' => null,
            'categories' => [],
            'tags' => [],
            'publishedAt' => new DateTimeImmutable('2026-01-01T00:00:00+00:00'),
            'canonicalUrl' => null,
            'source' => 'rss',
            'sourceHost' => 'example.com',
        ];
        $merged = array_replace($defaults, $overrides);

        return new MigrationItemDTO(
            guid: $merged['guid'],
            title: $merged['title'],
            html: $merged['html'],
            excerpt: $merged['excerpt'],
            author: $merged['author'],
            categories: $merged['categories'],
            tags: $merged['tags'],
            publishedAt: $merged['publishedAt'],
            canonicalUrl: $merged['canonicalUrl'],
            source: $merged['source'],
            sourceHost: $merged['sourceHost'],
        );
    }
}
