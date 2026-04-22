<?php

declare(strict_types=1);

namespace Tests\Browser\Support;

use Illuminate\Support\Facades\DB;
use Modules\Content\Models\Content;
use Modules\Page\Models\Page;

/**
 * Cascade-deletes landing-test-* pages and all their satellite rows.
 *
 * Both the LandingPageFactory and the CleansLandingTestPages trait
 * delegate here so the cleanup logic lives in exactly one place.
 * The cascade is intentionally wider than Microweber's production
 * delete path — the goal is "leave zero evidence of the fixture in
 * the test DB", not "simulate the user-facing delete flow".
 */
final class LandingTestContentPurger
{
    public const SLUG_PREFIX = 'landing-test-';

    /**
     * Tables keyed by the `(rel_type='content', rel_id=$id)` morph pair.
     */
    private const SATELLITE_TABLES = [
        'content_data',
        'content_fields',
        'content_fields_drafts',
        'content_revisions_history',
        'content_data_variants',
        'categories',
        'categories_items',
        'custom_fields',
        'media',
    ];

    /**
     * Purge one id, a list of ids, or (when null) every
     * landing-test-* page currently in the DB.
     *
     * @param int|int[]|null $ids
     * @return int[] ids that were purged
     */
    public static function purge(int|array|null $ids = null): array
    {
        $ids = self::resolveIds($ids);
        foreach ($ids as $id) {
            self::purgeOne($id);
        }
        return $ids;
    }

    /**
     * @param int|int[]|null $ids
     * @return int[]
     */
    private static function resolveIds(int|array|null $ids): array
    {
        if ($ids === null) {
            return DB::table('content')
                ->where('url', 'like', self::SLUG_PREFIX . '%')
                ->pluck('id')
                ->map(fn ($v) => (int)$v)
                ->all();
        }

        $ids = is_array($ids) ? $ids : [$ids];
        return array_values(array_unique(array_filter(array_map('intval', $ids))));
    }

    private static function purgeOne(int $id): void
    {
        foreach (self::SATELLITE_TABLES as $table) {
            try {
                DB::table($table)
                    ->where('rel_type', 'content')
                    ->where('rel_id', $id)
                    ->delete();
            } catch (\Throwable) {
                // best-effort: table/column may be absent in some envs
            }
        }

        try {
            DB::table('content_related')
                ->where('content_id', $id)
                ->orWhere('related_content_id', $id)
                ->delete();
        } catch (\Throwable) {
        }

        try {
            DB::table('menus')->where('content_id', $id)->delete();
        } catch (\Throwable) {
        }

        try {
            Page::where('id', $id)->delete();
        } catch (\Throwable) {
        }
        try {
            Content::where('id', $id)->forceDelete();
        } catch (\Throwable) {
            try {
                DB::table('content')->where('id', $id)->delete();
            } catch (\Throwable) {
            }
        }
    }
}
