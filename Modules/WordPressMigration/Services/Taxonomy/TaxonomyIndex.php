<?php

namespace Modules\WordPressMigration\Services\Taxonomy;

use Illuminate\Support\Facades\DB;
use Modules\Content\Models\Content;

/**
 * Pre-upsert WP categories + tags into Microweber's local tables
 * and probe the users table for matching accounts — producing the
 * {@see TaxonomyLookup} the mapper uses to attach posts by local
 * id on first save.
 *
 * Why a dedicated first pass?
 * ---------------------------
 * Without it the mapper has two unsatisfying options on every
 * post: (a) attach by name and hope the category exists, or (b)
 * create-on-demand for each label the DTO mentions — which runs
 * the category save hooks O(posts × tagsPerPost) times and
 * generates "looks like a typo" rows when the same term appears
 * with different casing across posts. Running the taxonomy sweep
 * once up front collapses that to O(distinct terms) and forces a
 * single canonical form chosen by the origin site itself.
 *
 * Upsert strategy
 * ---------------
 *   - **Categories**: rows in `categories` with `data_type='category'`,
 *     `rel_type = Content::class`, `rel_id = 0` (global). Matched by
 *     (`url` = slug, data_type, rel_type). Missing rows are inserted
 *     with the WP name as `title` and `is_active = 1`.
 *   - **Tags**: rows in `tagging_tags` with (slug, name). Slug is
 *     unique; matched by slug.
 *   - **Users**: we DO NOT auto-create accounts from untrusted
 *     external data — a public WP export could otherwise register
 *     attacker-controlled usernames. Instead we match existing
 *     Microweber users by slug-safe username or email; unmatched
 *     authors fall through (mapper falls back to `source_author`
 *     metadata on the content row).
 */
final class TaxonomyIndex
{
    /**
     * @param list<array<string, mixed>> $wpCategories WP `/wp/v2/categories` rows.
     *        Each row has at least `id`, `name`, `slug`.
     * @param list<array<string, mixed>> $wpTags WP `/wp/v2/tags` rows (same shape).
     * @param list<array<string, mixed>> $wpUsers WP `/wp/v2/users` rows.
     *        Each row has at least `id`, `slug`, `name`, optionally `url`.
     */
    public function prime(array $wpCategories, array $wpTags, array $wpUsers): TaxonomyLookup
    {
        return new TaxonomyLookup(
            categoriesBySlug: $this->primeCategories($wpCategories),
            tagsBySlug: $this->primeTags($wpTags),
            usersBySlug: $this->probeUsers($wpUsers),
        );
    }

    /**
     * @param list<array<string, mixed>> $wpCategories
     * @return array<string, int>
     */
    private function primeCategories(array $wpCategories): array
    {
        $map = [];
        foreach ($wpCategories as $row) {
            $slug = $this->asSlug($row['slug'] ?? null);
            if ($slug === null) {
                continue;
            }
            $title = trim((string)($row['name'] ?? $slug));
            if ($title === '') {
                $title = $slug;
            }

            $existing = DB::table('categories')
                ->where('data_type', 'category')
                ->where('rel_type', Content::class)
                ->where('url', $slug)
                ->value('id');

            if ($existing !== null) {
                $map[$slug] = (int)$existing;
                continue;
            }

            $now = date('Y-m-d H:i:s');
            $id = DB::table('categories')->insertGetId([
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
            $map[$slug] = (int)$id;
        }
        return $map;
    }

    /**
     * @param list<array<string, mixed>> $wpTags
     * @return array<string, int>
     */
    private function primeTags(array $wpTags): array
    {
        $map = [];
        foreach ($wpTags as $row) {
            $slug = $this->asSlug($row['slug'] ?? null);
            if ($slug === null) {
                continue;
            }
            $name = trim((string)($row['name'] ?? $slug));
            if ($name === '') {
                $name = $slug;
            }

            $existing = DB::table('tagging_tags')->where('slug', $slug)->value('id');
            if ($existing !== null) {
                $map[$slug] = (int)$existing;
                continue;
            }

            $id = DB::table('tagging_tags')->insertGetId([
                'slug' => $slug,
                'name' => $name,
                'suggest' => 0,
                'count' => 0,
            ]);
            $map[$slug] = (int)$id;
        }
        return $map;
    }

    /**
     * @param list<array<string, mixed>> $wpUsers
     * @return array<string, int>
     */
    private function probeUsers(array $wpUsers): array
    {
        $map = [];
        foreach ($wpUsers as $row) {
            $slug = $this->asSlug($row['slug'] ?? null);
            if ($slug === null) {
                continue;
            }

            // Try username first, then email when WP surfaced one.
            // Both lookups are case-insensitive via MySQL collation;
            // SQLite tests (if any) will still match the dominant
            // case since we normalize slugs to lowercase on input.
            $query = DB::table('users')->where('username', $slug);
            $id = $query->value('id');

            if ($id === null) {
                $email = isset($row['email']) && is_string($row['email'])
                    ? trim($row['email'])
                    : '';
                if ($email !== '') {
                    $id = DB::table('users')->where('email', $email)->value('id');
                }
            }

            if ($id !== null) {
                $map[$slug] = (int)$id;
            }
        }
        return $map;
    }

    /**
     * Normalize a WP term/user slug into its storage form: trimmed,
     * lowercased. Returns null when the raw value is missing or
     * all-whitespace so callers can skip the row.
     */
    private function asSlug(mixed $raw): ?string
    {
        if (!is_string($raw)) {
            return null;
        }
        $slug = strtolower(trim($raw));
        return $slug === '' ? null : $slug;
    }
}
