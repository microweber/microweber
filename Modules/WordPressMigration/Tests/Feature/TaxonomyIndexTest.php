<?php

namespace Modules\WordPressMigration\Tests\Feature;

use Illuminate\Support\Facades\DB;
use Modules\Content\Models\Content;
use Modules\WordPressMigration\Services\Taxonomy\TaxonomyIndex;
use Modules\WordPressMigration\Services\Taxonomy\TaxonomyLookup;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * DB-hitting coverage for the taxonomy-first pass.
 *
 * `TaxonomyIndex::prime()` is the seam that turns WP `/wp/v2/*`
 * term and user rows into a {@see TaxonomyLookup} the mapper uses
 * on every post save. The tests below exercise three invariants
 * the importer pipeline relies on:
 *
 *   1. Missing categories/tags are inserted into the local tables
 *      with the slug as the natural key; existing rows are reused
 *      rather than duplicated.
 *   2. Users are NEVER auto-created — only probed by username or
 *      email. Unmatched authors drop out of the lookup so the mapper
 *      leaves `content.created_by` untouched.
 *   3. The operation is idempotent: re-running `prime()` with the
 *      same payload returns the same local ids and does not grow
 *      either table.
 *
 * We pick slugs prefixed with `wpmig-tax-` plus a per-test uniqid so
 * a reusable fixture DB won't collide with other suites' category
 * rows, and we clean up the ids we created in tearDown().
 */
class TaxonomyIndexTest extends TestCase
{
    private TaxonomyIndex $index;

    /** @var list<int> */
    private array $insertedCategoryIds = [];

    /** @var list<int> */
    private array $insertedTagIds = [];

    /** @var list<int> */
    private array $insertedUserIds = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->index = new TaxonomyIndex();
    }

    protected function tearDown(): void
    {
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
    public function categories_are_upserted_and_lookup_returns_local_ids(): void
    {
        $newSlug = 'wpmig-tax-new-' . uniqid();
        $existingSlug = 'wpmig-tax-existing-' . uniqid();

        // Seed one slug directly so we can prove prime() reuses
        // rather than blindly inserts a duplicate.
        $existingId = $this->seedCategory($existingSlug, 'Preexisting');

        $lookup = $this->index->prime(
            wpCategories: [
                ['id' => 11, 'slug' => $newSlug, 'name' => 'Brand New'],
                ['id' => 22, 'slug' => $existingSlug, 'name' => 'Reused'],
            ],
            wpTags: [],
            wpUsers: [],
        );

        $this->assertInstanceOf(TaxonomyLookup::class, $lookup);
        $this->assertSame($existingId, $lookup->categoryLocalId($existingSlug));

        $newLocalId = $lookup->categoryLocalId($newSlug);
        $this->assertNotNull($newLocalId);
        $row = DB::table('categories')->where('id', $newLocalId)->first();
        $this->assertNotNull($row);
        $this->assertSame($newSlug, $row->url);
        $this->assertSame('Brand New', $row->title);
        $this->assertSame('category', $row->data_type);
        $this->assertSame(Content::class, $row->rel_type);
        $this->assertSame(1, (int)$row->is_active);
        $this->insertedCategoryIds[] = (int)$newLocalId;
    }

    #[Test]
    public function tags_are_upserted_and_lookup_returns_local_ids(): void
    {
        $newSlug = 'wpmig-tax-tag-new-' . uniqid();
        $existingSlug = 'wpmig-tax-tag-existing-' . uniqid();

        $existingId = $this->seedTag($existingSlug, 'Already there');

        $lookup = $this->index->prime(
            wpCategories: [],
            wpTags: [
                ['id' => 33, 'slug' => $newSlug, 'name' => 'Fresh Tag'],
                ['id' => 44, 'slug' => $existingSlug, 'name' => 'Should reuse'],
            ],
            wpUsers: [],
        );

        $this->assertSame($existingId, $lookup->tagLocalId($existingSlug));

        $newLocalId = $lookup->tagLocalId($newSlug);
        $this->assertNotNull($newLocalId);
        $row = DB::table('tagging_tags')->where('id', $newLocalId)->first();
        $this->assertNotNull($row);
        $this->assertSame($newSlug, $row->slug);
        $this->assertSame('Fresh Tag', $row->name);
        $this->insertedTagIds[] = (int)$newLocalId;
    }

    #[Test]
    public function users_are_matched_by_username_or_email_and_never_created(): void
    {
        $matchSlug = 'wpmig-tax-user-' . uniqid();
        $emailSlug = 'wpmig-tax-email-' . uniqid();
        $unknownSlug = 'wpmig-tax-unknown-' . uniqid();
        $email = $emailSlug . '@example.com';

        $usernameMatchId = $this->seedUser($matchSlug, $matchSlug . '@test.invalid');
        $emailMatchId = $this->seedUser('some-other-username-' . uniqid(), $email);

        $lookup = $this->index->prime(
            wpCategories: [],
            wpTags: [],
            wpUsers: [
                ['id' => 1, 'slug' => $matchSlug, 'name' => 'Matched by username'],
                ['id' => 2, 'slug' => $emailSlug, 'name' => 'Matched via email', 'email' => $email],
                ['id' => 3, 'slug' => $unknownSlug, 'name' => 'Has no local match'],
            ],
        );

        $this->assertSame($usernameMatchId, $lookup->userLocalId($matchSlug));
        $this->assertSame($emailMatchId, $lookup->userLocalId($emailSlug));
        $this->assertNull(
            $lookup->userLocalId($unknownSlug),
            'Unmatched authors must NOT be auto-created — lookup must return null'
        );

        // Belt-and-suspenders: confirm no new user row was created
        // for the unknown slug under any plausible column.
        $this->assertSame(
            0,
            DB::table('users')
                ->where('username', $unknownSlug)
                ->orWhere('email', 'like', $unknownSlug . '%')
                ->count()
        );
    }

    #[Test]
    public function priming_twice_is_idempotent_and_does_not_duplicate_rows(): void
    {
        $catSlug = 'wpmig-tax-idem-cat-' . uniqid();
        $tagSlug = 'wpmig-tax-idem-tag-' . uniqid();

        $first = $this->index->prime(
            wpCategories: [['id' => 1, 'slug' => $catSlug, 'name' => 'Idempotent cat']],
            wpTags: [['id' => 2, 'slug' => $tagSlug, 'name' => 'Idempotent tag']],
            wpUsers: [],
        );
        $this->insertedCategoryIds[] = (int)$first->categoryLocalId($catSlug);
        $this->insertedTagIds[] = (int)$first->tagLocalId($tagSlug);

        $second = $this->index->prime(
            wpCategories: [['id' => 1, 'slug' => $catSlug, 'name' => 'Idempotent cat']],
            wpTags: [['id' => 2, 'slug' => $tagSlug, 'name' => 'Idempotent tag']],
            wpUsers: [],
        );

        $this->assertSame($first->categoryLocalId($catSlug), $second->categoryLocalId($catSlug));
        $this->assertSame($first->tagLocalId($tagSlug), $second->tagLocalId($tagSlug));

        $this->assertSame(
            1,
            DB::table('categories')
                ->where('url', $catSlug)
                ->where('data_type', 'category')
                ->where('rel_type', Content::class)
                ->count()
        );
        $this->assertSame(1, DB::table('tagging_tags')->where('slug', $tagSlug)->count());
    }

    #[Test]
    public function empty_or_whitespace_slug_rows_are_skipped_silently(): void
    {
        $lookup = $this->index->prime(
            wpCategories: [
                ['id' => 1, 'slug' => '', 'name' => 'No slug'],
                ['id' => 2, 'slug' => '   ', 'name' => 'Only whitespace'],
                ['id' => 3, 'name' => 'Missing slug key altogether'],
            ],
            wpTags: [['id' => 4, 'slug' => null, 'name' => 'Null slug']],
            wpUsers: [['id' => 5, 'slug' => '', 'name' => 'No slug']],
        );

        $this->assertSame([], $lookup->categoriesBySlug);
        $this->assertSame([], $lookup->tagsBySlug);
        $this->assertSame([], $lookup->usersBySlug);
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
}
