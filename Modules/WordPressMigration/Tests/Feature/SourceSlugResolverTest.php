<?php

namespace Modules\WordPressMigration\Tests\Feature;

use Illuminate\Support\Facades\DB;
use Modules\WordPressMigration\Services\SourceSlugResolver;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Feature coverage for {@see SourceSlugResolver}.
 *
 * This is a Feature test rather than a pure unit test because the
 * collision path walks the live `content` and `categories` tables
 * via the DB facade — swapping those out with mocks would prove
 * the mock's behavior, not the resolver's, and the resolver's
 * whole point is to stay in sync with `HasSlugTrait::checkSlugExists`
 * which also talks to those exact tables.
 *
 * Pure path-parsing cases use slug bases prefixed with
 * `sslugres-` so they cannot collide with any real-world content
 * already in the fixture DB, keeping the happy-path tests DB-light.
 */
class SourceSlugResolverTest extends TestCase
{
    private SourceSlugResolver $resolver;

    /** @var list<int> */
    private array $insertedContentIds = [];

    /** @var list<int> */
    private array $insertedCategoryIds = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->resolver = new SourceSlugResolver();
    }

    protected function tearDown(): void
    {
        if ($this->insertedContentIds !== []) {
            DB::table('content')->whereIn('id', $this->insertedContentIds)->delete();
        }
        if ($this->insertedCategoryIds !== []) {
            DB::table('categories')->whereIn('id', $this->insertedCategoryIds)->delete();
        }
        parent::tearDown();
    }

    #[Test]
    public function returns_last_path_segment_of_a_simple_url(): void
    {
        $this->assertSame(
            'sslugres-simple',
            $this->resolver->resolve('https://wp.example/sslugres-simple')
        );
    }

    #[Test]
    public function strips_trailing_slash_and_returns_last_real_segment(): void
    {
        $this->assertSame(
            'sslugres-trailing',
            $this->resolver->resolve('https://wp.example/category/sslugres-trailing/')
        );
    }

    #[Test]
    public function category_prefix_is_discarded_since_microweber_stores_only_the_last_segment(): void
    {
        // WP permalinks like /category/foo/bar/ exist; Microweber's
        // permalink resolver only looks at the last segment, so that's
        // what the resolver returns.
        $this->assertSame(
            'sslugres-deep',
            $this->resolver->resolve('https://wp.example/2024/10/category/sub/sslugres-deep/')
        );
    }

    #[Test]
    public function url_encoded_segments_are_decoded_then_normalized(): void
    {
        // `%20` → ` ` → `-`. Mirrors what HasSlugTrait would produce
        // from a titled "my post".
        $this->assertSame(
            'sslugres-encoded-space',
            $this->resolver->resolve('https://wp.example/sslugres-encoded%20space')
        );
    }

    #[Test]
    public function mixed_case_segment_is_lowercased(): void
    {
        $this->assertSame(
            'sslugres-mixedcase',
            $this->resolver->resolve('https://wp.example/SSLUGRES-MixedCase')
        );
    }

    #[Test]
    public function root_only_url_returns_null(): void
    {
        $this->assertNull($this->resolver->resolve('https://wp.example/'));
        $this->assertNull($this->resolver->resolve('https://wp.example'));
    }

    #[Test]
    public function empty_or_whitespace_url_returns_null(): void
    {
        $this->assertNull($this->resolver->resolve(''));
        $this->assertNull($this->resolver->resolve('   '));
    }

    #[Test]
    public function index_html_and_index_php_are_treated_as_non_slugs(): void
    {
        $this->assertNull($this->resolver->resolve('https://wp.example/index.html'));
        $this->assertNull($this->resolver->resolve('https://wp.example/index.php'));
        $this->assertNull($this->resolver->resolve('https://wp.example/index.htm'));
    }

    #[Test]
    public function query_string_and_fragment_are_stripped_from_last_segment(): void
    {
        $this->assertSame(
            'sslugres-withquery',
            $this->resolver->resolve('https://wp.example/sslugres-withquery?foo=bar#frag')
        );
    }

    #[Test]
    public function special_characters_collapse_to_single_hyphen_matching_has_slug_trait(): void
    {
        // The slug normalizer used by HasSlugTrait (via mw()->url_manager->slug)
        // collapses runs of non-letter/digit chars into a single `-`.
        // Resolver mirrors that so the collision check compares apples to apples.
        $this->assertSame(
            'sslugres-special-chars',
            $this->resolver->resolve('https://wp.example/sslugres%20special%20%20--%20%20chars')
        );
    }

    #[Test]
    public function underscores_are_collapsed_to_hyphens_matching_url_manager_slug(): void
    {
        // UrlManager::slug() treats `_` as non-letter/non-digit and
        // turns it into `-`. Resolver must match so its pre-save
        // collision check sees the same final slug the DB stores.
        $this->assertSame(
            'sslugres-with-underscore',
            $this->resolver->resolve('https://wp.example/sslugres_with_underscore')
        );
    }

    #[Test]
    public function unicode_letters_are_preserved(): void
    {
        // Microweber's slug pattern is `\p{L}\d_-`, so accented chars
        // remain in the stored url.
        $this->assertSame(
            'sslugres-café',
            $this->resolver->resolve('https://wp.example/SSLUGRES-Café')
        );
    }

    #[Test]
    public function collision_on_content_url_returns_base_dash_two(): void
    {
        $base = 'sslugres-collide-' . uniqid();
        $this->insertContent($base);

        $resolved = $this->resolver->resolve('https://wp.example/' . $base);

        $this->assertSame($base . '-2', $resolved);
    }

    #[Test]
    public function collision_walks_up_to_the_next_free_suffix(): void
    {
        $base = 'sslugres-walk-' . uniqid();
        $this->insertContent($base);
        $this->insertContent($base . '-2');
        $this->insertContent($base . '-3');

        $resolved = $this->resolver->resolve('https://wp.example/' . $base);

        $this->assertSame($base . '-4', $resolved);
    }

    #[Test]
    public function collision_on_categories_table_is_also_respected(): void
    {
        $base = 'sslugres-catcollide-' . uniqid();
        $this->insertCategory($base);

        $resolved = $this->resolver->resolve('https://wp.example/' . $base);

        $this->assertSame(
            $base . '-2',
            $resolved,
            'A category already owning the slug must push content to -2 — shared permalink namespace'
        );
    }

    #[Test]
    public function exclude_content_id_skips_its_own_row(): void
    {
        $base = 'sslugres-exclude-' . uniqid();
        $id = $this->insertContent($base);

        $resolved = $this->resolver->resolve('https://wp.example/' . $base, excludeContentId: $id);

        $this->assertSame(
            $base,
            $resolved,
            'Re-resolving while editing the same row must not treat its own slug as a collision'
        );
    }

    private function insertContent(string $slug): int
    {
        $id = DB::table('content')->insertGetId([
            'url' => $slug,
            'title' => $slug,
            'content_type' => 'post',
            'subtype' => 'post',
            'is_active' => 1,
            'is_deleted' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $this->insertedContentIds[] = (int)$id;
        return (int)$id;
    }

    private function insertCategory(string $slug): int
    {
        $id = DB::table('categories')->insertGetId([
            'url' => $slug,
            'title' => $slug,
            'rel_type' => 'content',
            'rel_id' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $this->insertedCategoryIds[] = (int)$id;
        return (int)$id;
    }
}
