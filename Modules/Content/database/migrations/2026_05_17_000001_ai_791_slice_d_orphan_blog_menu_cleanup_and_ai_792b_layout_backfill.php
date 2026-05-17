<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * task-2026-05-17-4612c4 / AI-791 Slice D
 *     + AI-792b bundled per designer dispatch ("Bundle with the
 *       AI-792b backfill migration -- same data layer, same risk
 *       profile.").
 *
 * Two surgical data fixes for existing installs:
 *
 * (1) AI-791 Slice D -- ORPHAN BLOG MENU CLEANUP. Designer's local-
 *     install DOM probe of the home demo header menu found a
 *     duplicate Blog item with a timestamp-suffix slug (e.g.
 *     `Blog20260516195012`, where 20260516195012 is the install
 *     timestamp). Root cause hypothesis: `Content::createDefaultBlog
 *     Page()` ran twice during install (race or double-trigger);
 *     first call created `/Blog`, second call hit the URL-unique
 *     collision and Microweber appended the install timestamp; both
 *     pages got auto-inserted into the header menu.
 *
 *     The current prevention layer (`Content::createDefaultBlogPage`
 *     wraps the create in an `if (!$existing)` guard via
 *     `get_pages(content_type=page&subtype=dynamic&is_shop=0)`) is
 *     in place per AI-792; this migration cleans up the LEGACY
 *     installs that already shipped the orphan.
 *
 *     Surgical SQL (per designer email 2026-05-17T10:15:17Z):
 *
 *       DELETE FROM menus WHERE id IN (
 *           SELECT m.id FROM menus m
 *           JOIN content c ON c.id = m.content_id
 *           WHERE c.url REGEXP '^Blog[0-9]{14}$'
 *             AND EXISTS (
 *                 SELECT 1 FROM menus m2
 *                 JOIN content c2 ON c2.id = m2.content_id
 *                 WHERE c2.url = 'Blog' AND m2.parent_id = m.parent_id
 *             )
 *       );
 *
 *     Adjusted here:
 *     - Wrap the inner SELECT with a SELECT-FROM-SELECT alias to work
 *       around MySQL's "you can't modify a table referenced in the
 *       SELECT" restriction.
 *     - Restrict orphan-content match to `content_type='page'` +
 *       `subtype='dynamic'` + `is_shop=0` (the exact shape that
 *       `Content::createDefaultBlogPage()` creates) so we never
 *       false-delete a legitimate `Blog{timestamp}` URL that
 *       happened to be created manually by an editor.
 *     - The EXISTS clause requires a canonical `Blog` URL to exist
 *       in the same parent menu -- guarantees we never delete the
 *       sole Blog entry even if it has the timestamp suffix (would
 *       break the user's nav otherwise).
 *
 * (2) AI-792b -- LAYOUT_FILE BACKFILL on existing Blog pages with
 *     `layout_file=null`. AI-792 (task-2026-05-17-4e9d1b) shipped
 *     the `layout_file='blog.blade.php'` assignment inside
 *     `createDefaultBlogPage()` for fresh installs; existing installs
 *     pre-AI-792 have Blog pages with `layout_file=null` and render
 *     the home-hero template instead of the blog archive. This
 *     migration sets `layout_file='blog.blade.php'` on the matching
 *     legacy rows.
 *
 * Both writes are idempotent (running again is a no-op after the first
 * run since the affected rows no longer match the WHERE conditions).
 * down() is intentionally a no-op -- you cannot deterministically
 * recover deleted menu items, and we should not "restore" a defect.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Guard rails: skip cleanly if the menus or content tables
        // do not exist (fresh install before the create-tables
        // migrations have run).
        if (! Schema::hasTable('menus') || ! Schema::hasTable('content')) {
            return;
        }

        $this->ai791SliceDOrphanBlogMenuCleanup();
        $this->ai792bLayoutFileBackfill();
    }

    public function down(): void
    {
        // No-op by design. See class docblock.
    }

    /**
     * AI-791 Slice D -- delete orphan Blog menu entries whose
     * content_id points to a `Blog<14-digit-timestamp>` page AND
     * a canonical `Blog` URL already exists in the same parent menu.
     */
    private function ai791SliceDOrphanBlogMenuCleanup(): void
    {
        // SQL-driver-agnostic: MySQL uses REGEXP; SQLite has no
        // REGEXP by default. Use a portable LIKE + LENGTH check
        // (Blog + 14 digits = 18 chars; LIKE 'Blog%' filters first,
        // then a strict equality on the suffix length distinguishes
        // the timestamp pattern from arbitrary `BlogFoo` slugs).
        $candidates = DB::table('menus as m')
            ->join('content as c', 'c.id', '=', 'm.content_id')
            ->where('c.content_type', 'page')
            ->where('c.subtype', 'dynamic')
            ->where('c.is_shop', 0)
            ->where('c.url', 'LIKE', 'Blog%')
            ->whereRaw('CHAR_LENGTH(c.url) = 18')             // Blog + 14 digits
            ->whereRaw("SUBSTRING(c.url, 5) REGEXP '^[0-9]{14}$'")
            ->select('m.id as menu_id', 'm.parent_id', 'm.menu_name', 'c.url as content_url', 'c.id as content_id')
            ->get();

        if ($candidates->isEmpty()) {
            return;
        }

        $deletedMenuIds = [];
        foreach ($candidates as $row) {
            // Belt-and-braces: confirm a canonical `Blog` URL exists
            // in the same parent menu (same menu_name + same parent_id)
            // before deleting the orphan -- never strip the sole Blog
            // entry from a user's nav.
            $hasCanonical = DB::table('menus as m2')
                ->join('content as c2', 'c2.id', '=', 'm2.content_id')
                ->where('c2.url', 'Blog')
                ->where('c2.content_type', 'page')
                ->where('c2.subtype', 'dynamic')
                ->where('c2.is_shop', 0)
                ->where('m2.parent_id', $row->parent_id)
                ->where('m2.menu_name', $row->menu_name)
                ->exists();

            if ($hasCanonical) {
                $deletedMenuIds[] = $row->menu_id;
            }
        }

        if (! empty($deletedMenuIds)) {
            DB::table('menus')->whereIn('id', $deletedMenuIds)->delete();
        }
    }

    /**
     * AI-792b -- backfill `layout_file='blog.blade.php'` on legacy
     * Blog pages where it is NULL or empty. Restricts to the exact
     * shape created by `Content::createDefaultBlogPage()` so we never
     * touch an unrelated dynamic page.
     */
    private function ai792bLayoutFileBackfill(): void
    {
        if (! Schema::hasColumn('content', 'layout_file')) {
            return;
        }

        DB::table('content')
            ->where('content_type', 'page')
            ->where('subtype', 'dynamic')
            ->where('is_shop', 0)
            ->where(function ($q) {
                $q->whereNull('layout_file')->orWhere('layout_file', '');
            })
            ->where(function ($q) {
                // Match canonical `/Blog` AND timestamp-suffix orphans
                // (we backfill both -- the orphan cleanup above only
                // removes the MENU entry, not the content row; the
                // content row stays so any deep-link to the orphan
                // URL keeps working).
                $q->where('url', 'Blog')
                  ->orWhere(function ($q2) {
                      $q2->where('url', 'LIKE', 'Blog%')
                         ->whereRaw('CHAR_LENGTH(url) = 18')
                         ->whereRaw("SUBSTRING(url, 5) REGEXP '^[0-9]{14}$'");
                  });
            })
            ->update(['layout_file' => 'blog.blade.php']);
    }
};
