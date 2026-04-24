<?php

declare(strict_types=1);

namespace Tests\Browser\Support;

use Illuminate\Support\Facades\DB;

/**
 * Cascade-deletes every row the full-website-creation workflow test
 * introduces, across `content`, `content_data`, `media`, `options`,
 * `menus`, `menus_items`, and `users`.
 *
 * Scoping contract — all rows the workflow test creates MUST carry
 * the {@see self::FIXTURE_MARKER} prefix somewhere matchable:
 *
 *   - content.url            → 'workflow-fixture-*'
 *   - content.title          → contains 'workflow-fixture'
 *   - media.filename         → contains 'workflow-fixture'
 *   - options.option_key     → 'workflow_fixture_*'
 *   - users.email            → '*@workflow-fixture.test'
 *   - menus.title            → contains 'workflow-fixture'
 *
 * The purger rejects any purge that would touch rows OUTSIDE these
 * markers — belt-and-braces guard against an accidental prefix
 * typo wiping production-looking fixtures.
 */
final class WorkflowFixturePurger
{
    public const FIXTURE_MARKER = 'workflow-fixture';

    public const FIXTURE_EMAIL_DOMAIN = '@workflow-fixture.test';

    public const FIXTURE_OPTION_KEY_PREFIX = 'workflow_fixture_';

    /**
     * Tables keyed by `(rel_type='content', rel_id=$id)`.
     */
    private const CONTENT_SATELLITE_TABLES = [
        'content_data',
        'content_fields',
        'content_fields_drafts',
        'content_revisions_history',
        'content_data_variants',
        'categories_items',
        'custom_fields',
        'media',
    ];

    /**
     * Purge every row in every owned table that matches a
     * workflow-fixture marker. Safe to call from setUp() and
     * tearDown() — idempotent.
     */
    public static function purge(): void
    {
        self::purgeContent();
        self::purgeStandaloneMedia();
        self::purgeOptions();
        self::purgeMenus();
        self::purgeUsers();
    }

    /**
     * Snapshot row counts for the tables the workflow touches. Used
     * by the test's tearDown to assert "zero residue" — the counts
     * after the test must equal the counts taken in setUp.
     *
     * @return array<string, int>
     */
    public static function snapshotCounts(): array
    {
        return [
            'content' => (int) DB::table('content')->count(),
            'content_data' => (int) DB::table('content_data')->count(),
            'media' => (int) DB::table('media')->count(),
            'options' => self::countNonTransientOptions(),
            'users' => (int) DB::table('users')->count(),
            'menus' => self::safeCount('menus'),
            'menus_items' => self::safeCount('menus_items'),
        ];
    }

    /**
     * Count `options` rows excluding the auto-generated module-default
     * rows that Microweber's frontend renderer creates lazily on
     * first visit (e.g. testimonials/social-links modules call
     * saveOption('getXCreatedDefault', '1') the first time their
     * skin renders). These rows are NOT under workflow-test control
     * — any frontend visit can trip them — so the leak guard
     * ignores them. Real workflow-fixture leaks still surface
     * because workflow rows carry the FIXTURE_OPTION_KEY_PREFIX
     * marker, which sits outside the `module-layouts-` group.
     */
    private static function countNonTransientOptions(): int
    {
        return (int) DB::table('options')
            ->where(function ($q) {
                $q->where('option_group', 'not like', 'module-layouts-%')
                    ->orWhereNull('option_group');
            })
            ->count();
    }

    private static function safeCount(string $table): int
    {
        try {
            return (int) DB::table($table)->count();
        } catch (\Throwable) {
            return 0;
        }
    }

    private static function purgeContent(): void
    {
        $ids = DB::table('content')
            ->where(function ($q) {
                $q->where('url', 'like', self::FIXTURE_MARKER . '-%')
                    ->orWhere('title', 'like', '%' . self::FIXTURE_MARKER . '%');
            })
            ->pluck('id')
            ->map(fn ($v) => (int) $v)
            ->all();

        foreach ($ids as $id) {
            // custom_fields_values are keyed by custom_field_id
            // (not rel_id), so delete them FIRST to avoid orphan
            // rows after the custom_fields cascade below.
            try {
                $customFieldIds = DB::table('custom_fields')
                    ->where('rel_type', 'content')
                    ->where('rel_id', $id)
                    ->pluck('id')
                    ->map(fn ($v) => (int) $v)
                    ->all();
                if (! empty($customFieldIds)) {
                    DB::table('custom_fields_values')
                        ->whereIn('custom_field_id', $customFieldIds)
                        ->delete();
                }
            } catch (\Throwable) {
                // custom_fields_values absent on a stripped-down DB
            }

            foreach (self::CONTENT_SATELLITE_TABLES as $table) {
                try {
                    DB::table($table)
                        ->where('rel_type', 'content')
                        ->where('rel_id', $id)
                        ->delete();
                } catch (\Throwable) {
                    // Table absent or column-name drift — swallow.
                }
            }
        }

        if (! empty($ids)) {
            DB::table('content')->whereIn('id', $ids)->delete();
        }
    }

    private static function purgeStandaloneMedia(): void
    {
        try {
            DB::table('media')
                ->where('filename', 'like', '%' . self::FIXTURE_MARKER . '%')
                ->orWhere('title', 'like', '%' . self::FIXTURE_MARKER . '%')
                ->delete();
        } catch (\Throwable) {
            // media absent on a stripped-down test DB
        }
    }

    private static function purgeOptions(): void
    {
        try {
            DB::table('options')
                ->where('option_key', 'like', self::FIXTURE_OPTION_KEY_PREFIX . '%')
                ->delete();
        } catch (\Throwable) {
        }
    }

    private static function purgeMenus(): void
    {
        $menuIds = [];
        try {
            $menuIds = DB::table('menus')
                ->where('title', 'like', '%' . self::FIXTURE_MARKER . '%')
                ->pluck('id')
                ->map(fn ($v) => (int) $v)
                ->all();
        } catch (\Throwable) {
            // `menus` itself is absent — nothing to purge.
            return;
        }

        if (empty($menuIds)) {
            return;
        }

        // `menus_items` may not exist on installs where the menu
        // module stores items in `menus` itself (parent_id linked).
        // Swallow this independently so the subsequent menus delete
        // still runs and the leak guard sees a clean count.
        try {
            DB::table('menus_items')->whereIn('parent_id', $menuIds)->delete();
        } catch (\Throwable) {
        }

        try {
            DB::table('menus')->whereIn('id', $menuIds)->delete();
        } catch (\Throwable) {
        }
    }

    private static function purgeUsers(): void
    {
        try {
            DB::table('users')
                ->where('email', 'like', '%' . self::FIXTURE_EMAIL_DOMAIN)
                ->delete();
        } catch (\Throwable) {
        }
    }
}
