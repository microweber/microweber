<?php

namespace Modules\WordPressMigration\Tests\Feature;

use DateTimeImmutable;
use Illuminate\Support\Facades\DB;
use Modules\Content\Models\Content;
use Modules\WordPressMigration\DTOs\MigrationItemDTO;
use Modules\WordPressMigration\Services\Taxonomy\TaxonomyLookup;
use Modules\WordPressMigration\Services\WordPressContentMapper;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Feature coverage for the mapper's taxonomy-attach behavior.
 *
 * The sibling {@see WordPressContentMapperTest} pins the bare
 * "DTO → content row" contract. This file zooms in on what
 * happens when the mapper is constructed with a {@see TaxonomyLookup}:
 *
 *   - `categories_items` rows appear for every matched category slug.
 *   - `tagging_tagged` rows appear for every matched tag slug.
 *   - `content.created_by` is set when the author slug resolves; left
 *     alone otherwise (we NEVER auto-create users from external data).
 *   - Re-mapping the same DTO does not duplicate attachment rows.
 *
 * We create the lookup in-memory (never through `TaxonomyIndex` so
 * this file doesn't depend on the categories/tagging_tags upsert
 * path — that's covered in TaxonomyIndexTest), and we feed it local
 * ids we've seeded directly via the DB facade so the assertions
 * read as "the mapper wrote exactly these ids into the join tables".
 */
class WordPressContentMapperTaxonomyTest extends TestCase
{
    /** @var list<int> */
    private array $insertedContentIds = [];
    /** @var list<int> */
    private array $insertedCategoryIds = [];
    /** @var list<int> */
    private array $insertedTagIds = [];
    /** @var list<int> */
    private array $insertedUserIds = [];

    protected function setUp(): void
    {
        parent::setUp();

        // Same cleanup pattern as WordPressContentMapperTest: drop
        // any prior WP-migrated rows so a previous failing run can't
        // leak attachment rows into this one.
        $priorContentIds = DB::table('content_data')
            ->where('field_name', WordPressContentMapper::META_IMPORT_SOURCE)
            ->where('field_value', WordPressContentMapper::IMPORT_SOURCE_WORDPRESS_RSS)
            ->pluck('rel_id');

        if ($priorContentIds->isNotEmpty()) {
            DB::table('content_data')->whereIn('rel_id', $priorContentIds)->delete();
            DB::table('categories_items')->whereIn('rel_id', $priorContentIds)->delete();
            DB::table('tagging_tagged')->whereIn('taggable_id', $priorContentIds)->delete();
            DB::table('content')->whereIn('id', $priorContentIds)->delete();
        }
    }

    protected function tearDown(): void
    {
        if ($this->insertedContentIds !== []) {
            DB::table('categories_items')->whereIn('rel_id', $this->insertedContentIds)->delete();
            DB::table('tagging_tagged')->whereIn('taggable_id', $this->insertedContentIds)->delete();
            DB::table('content_data')->whereIn('rel_id', $this->insertedContentIds)->delete();
            DB::table('content')->whereIn('id', $this->insertedContentIds)->delete();
        }
        if ($this->insertedCategoryIds !== []) {
            DB::table('categories')->whereIn('id', $this->insertedCategoryIds)->delete();
        }
        if ($this->insertedTagIds !== []) {
            DB::table('tagging_tags')->whereIn('id', $this->insertedTagIds)->delete();
        }
        if ($this->insertedUserIds !== []) {
            DB::table('users')->whereIn('id', $this->insertedUserIds)->delete();
        }
        parent::tearDown();
    }

    #[Test]
    public function matched_category_slugs_create_categories_items_rows_pointing_at_local_ids(): void
    {
        $newsSlug = 'wpmig-map-cat-news-' . uniqid();
        $techSlug = 'wpmig-map-cat-tech-' . uniqid();
        $newsId = $this->seedCategory($newsSlug, 'News');
        $techId = $this->seedCategory($techSlug, 'Tech');

        $lookup = new TaxonomyLookup(
            categoriesBySlug: [$newsSlug => $newsId, $techSlug => $techId],
        );
        $mapper = new WordPressContentMapper(taxonomy: $lookup);

        $content = $mapper->map($this->dto([
            'guid' => 'wp:cat-attach',
            'title' => 'Attach cats',
            'categories' => ['News', 'Tech'],
            'categorySlugs' => [$newsSlug, $techSlug],
        ]));
        $this->insertedContentIds[] = (int)$content->id;

        $rows = DB::table('categories_items')
            ->where('rel_id', $content->id)
            ->orderBy('parent_id')
            ->get();
        $this->assertCount(2, $rows);
        $parentIds = $rows->pluck('parent_id')->map(fn($v) => (int)$v)->all();
        sort($parentIds);
        $expected = [$newsId, $techId];
        sort($expected);
        $this->assertSame($expected, $parentIds);
        foreach ($rows as $row) {
            $this->assertSame(Content::class, $row->rel_type);
        }
    }

    #[Test]
    public function unmatched_category_slugs_are_silently_skipped(): void
    {
        $knownSlug = 'wpmig-map-cat-known-' . uniqid();
        $knownId = $this->seedCategory($knownSlug, 'Known');

        $lookup = new TaxonomyLookup(
            categoriesBySlug: [$knownSlug => $knownId],
        );
        $mapper = new WordPressContentMapper(taxonomy: $lookup);

        $content = $mapper->map($this->dto([
            'guid' => 'wp:cat-partial',
            'title' => 'Partial match',
            'categories' => ['Known', 'Ghost'],
            'categorySlugs' => [$knownSlug, 'wpmig-map-cat-missing-' . uniqid()],
        ]));
        $this->insertedContentIds[] = (int)$content->id;

        $rows = DB::table('categories_items')->where('rel_id', $content->id)->get();
        $this->assertCount(1, $rows, 'Missing slugs must NOT create stray rows against fabricated ids');
        $this->assertSame($knownId, (int)$rows->first()->parent_id);
    }

    #[Test]
    public function matched_tag_slugs_create_tagging_tagged_rows_with_preserved_names(): void
    {
        $featuredSlug = 'wpmig-map-tag-featured-' . uniqid();
        $pinnedSlug = 'wpmig-map-tag-pinned-' . uniqid();
        $featuredId = $this->seedTag($featuredSlug, 'Featured');
        $pinnedId = $this->seedTag($pinnedSlug, 'Pinned');

        $lookup = new TaxonomyLookup(
            tagsBySlug: [$featuredSlug => $featuredId, $pinnedSlug => $pinnedId],
        );
        $mapper = new WordPressContentMapper(taxonomy: $lookup);

        $content = $mapper->map($this->dto([
            'guid' => 'wp:tag-attach',
            'title' => 'Attach tags',
            'tags' => ['Featured', 'Pinned'],
            'tagSlugs' => [$featuredSlug, $pinnedSlug],
        ]));
        $this->insertedContentIds[] = (int)$content->id;

        $rows = DB::table('tagging_tagged')
            ->where('taggable_id', $content->id)
            ->orderBy('tag_slug')
            ->get();
        $this->assertCount(2, $rows);
        $bySlug = $rows->keyBy('tag_slug');
        $this->assertSame('Featured', $bySlug[$featuredSlug]->tag_name);
        $this->assertSame('Pinned', $bySlug[$pinnedSlug]->tag_name);
        foreach ($rows as $row) {
            $this->assertSame(Content::class, $row->taggable_type);
        }
    }

    #[Test]
    public function matching_author_slug_sets_created_by_to_local_user_id(): void
    {
        $slug = 'wpmig-map-author-' . uniqid();
        $userId = $this->seedUser($slug, $slug . '@test.invalid');

        $lookup = new TaxonomyLookup(usersBySlug: [$slug => $userId]);
        $mapper = new WordPressContentMapper(taxonomy: $lookup);

        $content = $mapper->map($this->dto([
            'guid' => 'wp:author-hit',
            'title' => 'By known author',
            'author' => 'Display Name',
            'authorSlug' => $slug,
        ]));
        $this->insertedContentIds[] = (int)$content->id;

        $this->assertSame($userId, (int)$content->created_by);
    }

    #[Test]
    public function unmatched_author_slug_does_not_create_a_local_user(): void
    {
        // No lookup entry for this slug — mapper MUST NOT auto-create
        // a user and MUST NOT fabricate a created_by value from the
        // unmatched slug. The only safe behavior is to leave the row
        // with whatever default the CreatedByObserver produced (null
        // or 0 depending on the test auth state).
        $ghostSlug = 'wpmig-map-ghost-' . uniqid();
        $lookup = new TaxonomyLookup(usersBySlug: []);
        $mapper = new WordPressContentMapper(taxonomy: $lookup);

        $content = $mapper->map($this->dto([
            'guid' => 'wp:author-miss',
            'title' => 'By ghost author',
            'author' => 'Ghost',
            'authorSlug' => $ghostSlug,
        ]));
        $this->insertedContentIds[] = (int)$content->id;

        // No user row was silently created for the ghost slug.
        $this->assertSame(
            0,
            DB::table('users')->where('username', $ghostSlug)->count(),
            'An unmatched author slug must never cause a users row to be created'
        );
        // The lookup still reports null for the ghost — sanity check
        // that the mapper's skip-branch was the one exercised.
        $this->assertNull($lookup->userLocalId($ghostSlug));
    }

    #[Test]
    public function remapping_the_same_dto_does_not_duplicate_attachment_rows(): void
    {
        $catSlug = 'wpmig-map-idem-cat-' . uniqid();
        $tagSlug = 'wpmig-map-idem-tag-' . uniqid();
        $catId = $this->seedCategory($catSlug, 'Idem Cat');
        $tagId = $this->seedTag($tagSlug, 'Idem Tag');

        $lookup = new TaxonomyLookup(
            categoriesBySlug: [$catSlug => $catId],
            tagsBySlug: [$tagSlug => $tagId],
        );
        $mapper = new WordPressContentMapper(taxonomy: $lookup);

        $dto = $this->dto([
            'guid' => 'wp:idem-attach',
            'title' => 'Idempotent attach',
            'categories' => ['Idem Cat'],
            'categorySlugs' => [$catSlug],
            'tags' => ['Idem Tag'],
            'tagSlugs' => [$tagSlug],
        ]);

        $first = $mapper->map($dto);
        $this->insertedContentIds[] = (int)$first->id;
        $second = $mapper->map($dto);

        $this->assertSame($first->id, $second->id);
        $this->assertSame(
            1,
            DB::table('categories_items')->where('rel_id', $first->id)->count(),
            'Second map() pass must not insert a duplicate categories_items row'
        );
        $this->assertSame(
            1,
            DB::table('tagging_tagged')->where('taggable_id', $first->id)->count(),
            'Second map() pass must not insert a duplicate tagging_tagged row'
        );
    }

    #[Test]
    public function without_taxonomy_lookup_no_attachments_are_written(): void
    {
        // The RSS path constructs the mapper without a lookup — that
        // path should still work and just skip the join-table writes.
        $mapper = new WordPressContentMapper();

        $content = $mapper->map($this->dto([
            'guid' => 'wp:no-lookup',
            'title' => 'Plain',
            'categories' => ['Anything'],
            'categorySlugs' => ['wpmig-map-nolookup-' . uniqid()],
        ]));
        $this->insertedContentIds[] = (int)$content->id;

        $this->assertSame(
            0,
            DB::table('categories_items')->where('rel_id', $content->id)->count()
        );
        $this->assertSame(
            0,
            DB::table('tagging_tagged')->where('taggable_id', $content->id)->count()
        );
    }

    private function seedCategory(string $slug, string $title): int
    {
        $now = date('Y-m-d H:i:s');
        $id = (int)DB::table('categories')->insertGetId([
            'data_type' => 'category',
            'rel_type' => Content::class,
            'rel_id' => 0,
            'title' => $title,
            'url' => $slug,
            'parent_id' => 0,
            'is_active' => 1,
            'is_deleted' => 0,
            'is_hidden' => 0,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $this->insertedCategoryIds[] = $id;
        return $id;
    }

    private function seedTag(string $slug, string $name): int
    {
        $id = (int)DB::table('tagging_tags')->insertGetId([
            'slug' => $slug,
            'name' => $name,
            'suggest' => 0,
            'count' => 0,
        ]);
        $this->insertedTagIds[] = $id;
        return $id;
    }

    private function seedUser(string $username, string $email): int
    {
        $now = date('Y-m-d H:i:s');
        $id = (int)DB::table('users')->insertGetId([
            'username' => $username,
            'email' => $email,
            'password' => 'seeded-for-test',
            'is_active' => 1,
            'is_admin' => 0,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $this->insertedUserIds[] = $id;
        return $id;
    }

    /**
     * @param array<string, mixed> $overrides
     */
    private function dto(array $overrides): MigrationItemDTO
    {
        $defaults = [
            'guid' => 'wp:tax-default',
            'title' => 'Default title',
            'html' => '<p>default body</p>',
            'excerpt' => null,
            'author' => null,
            'categories' => [],
            'tags' => [],
            'publishedAt' => new DateTimeImmutable('2026-01-01T00:00:00+00:00'),
            'canonicalUrl' => null,
            'source' => 'rest',
            'sourceHost' => 'example.com',
            'featuredImageUrl' => null,
            'categorySlugs' => [],
            'tagSlugs' => [],
            'authorSlug' => null,
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
            featuredImageUrl: $merged['featuredImageUrl'],
            categorySlugs: $merged['categorySlugs'],
            tagSlugs: $merged['tagSlugs'],
            authorSlug: $merged['authorSlug'],
        );
    }
}
