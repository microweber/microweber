<?php

namespace Tests\Browser\Traits;

use Illuminate\Support\Facades\DB;
use Tests\Browser\Factories\ColorPaletteFactory;
use Tests\Browser\Factories\ColorPaletteSkinMatrixFactory;
use Tests\DuskTestCase;

/**
 * Auto-cleanup for color-palette Dusk fixtures so reruns and
 * cross-test mutations can't leak into each other.
 *
 * Compose on top of any test class that drives the Bootstrap
 * color-pack picker (via {@see LiveEditColorPaletteTrait} and
 * {@see ColorPaletteFactory}). After every test method, the
 * `tearDownCleansColorPaletteTestFixtures` hook:
 *
 *   1. Cascade-deletes every `color-palette-test-*` page and its
 *      satellite rows (content_data, content_fields, revisions, …)
 *      so orphan fixtures never accumulate.
 *   2. Resets `options.current_template` to `Bootstrap` and drops any
 *      ad-hoc rows the test inserted into the `template` option group
 *      (palette apply/save writes customize-styles options that the
 *      factory doesn't own — we scrub them so the next test starts
 *      from the ground-truth template config).
 *   3. Invalidates `app()->option_repository->clearCache()` and
 *      `app()->cache_manager->delete('options')` — the artisan-serve
 *      worker caches option reads through the repository's
 *      `cacheCallback()`, so a raw DB fix without a cache kill is
 *      invisible to the next request the browser makes.
 *   4. Flips `DuskTestCase::$adminLoggedIn` back to false if the test
 *      logged out to guest during a save→public-render parity check —
 *      AdminLoginTrait::loginAsAdmin() early-returns on that flag, and
 *      a stale true would make the next test skip login while its
 *      cookies are gone.
 *
 * Hook naming: Laravel's `InteractsWithTestCaseLifecycle` auto-
 * registers any `tearDown{TraitBasename}` method on a used trait as
 * a `beforeApplicationDestroyed` callback. That fires with the
 * container still alive (before `Application::flush()`) — critical
 * here because step 3 calls `app()`-resolved services.
 *
 * Safe to double-apply: both the trait and any local `tearDown()` in
 * the using class can call the same cleanup without side effects.
 */
trait CleansColorPaletteTestFixtures
{
    /**
     * Option keys under the `template` group that belong to the
     * palette/template config and should be reset between tests.
     * Extend this list (not the cleanup logic) when new palette-
     * mutating options are introduced.
     */
    private const OWNED_OPTION_KEYS = [
        'current_template',
    ];

    protected function tearDownCleansColorPaletteTestFixtures(): void
    {
        $this->purgeColorPaletteTestPages();
        $this->resetTemplateOptionsGroup();
        $this->invalidateOptionsCache();
        $this->resetAdminLoggedInCache();
    }

    /**
     * Sweep every `color-palette-test-*` and `color-palette-skin-test-*`
     * page row (plus satellites). Delegates to each factory's own
     * `cleanupAll()` so the deletion cascade lives in exactly one place
     * per fixture class.
     */
    protected function purgeColorPaletteTestPages(): void
    {
        try {
            ColorPaletteFactory::cleanupAll();
        } catch (\Throwable) {
            // best-effort: teardown must never mask a test failure
        }
        try {
            ColorPaletteSkinMatrixFactory::cleanupAll();
        } catch (\Throwable) {
            // best-effort
        }
    }

    /**
     * Reset `options.current_template` to Bootstrap and delete any
     * non-owned rows the palette-save pipeline inserted into the
     * `template` group during the test. We deliberately keep the
     * whitelist tight — tests that genuinely own a custom option key
     * should clean that key themselves in tearDown().
     */
    protected function resetTemplateOptionsGroup(): void
    {
        try {
            $row = DB::table('options')
                ->where('option_key', 'current_template')
                ->where('option_group', 'template')
                ->first();

            if ($row && $row->option_value !== 'Bootstrap') {
                DB::table('options')
                    ->where('id', $row->id)
                    ->update([
                        'option_value' => 'Bootstrap',
                        'updated_at' => now(),
                    ]);
            }

            DB::table('options')
                ->where('option_group', 'template')
                ->whereNotIn('option_key', self::OWNED_OPTION_KEYS)
                ->delete();
        } catch (\Throwable) {
        }
    }

    /**
     * Nuke the file-backed options cache and the repository's in-memory
     * cache so the next request served by artisan-serve reads the
     * freshly-reset rows. Matches the belt-and-suspenders pattern used
     * by the template-switch tests.
     */
    protected function invalidateOptionsCache(): void
    {
        try {
            $app = app();
            if (isset($app->cache_manager) && method_exists($app->cache_manager, 'delete')) {
                $app->cache_manager->delete('options');
                $app->cache_manager->delete('options/template');
            }
            if (isset($app->option_repository) && method_exists($app->option_repository, 'clearCache')) {
                $app->option_repository->clearCache();
            }
        } catch (\Throwable) {
        }
    }

    /**
     * Defensively drop the shared admin-login cache. The flag is
     * static on DuskTestCase, so a previous test's logoutToGuest
     * would otherwise bleed into the next class's loginAsAdmin().
     */
    protected function resetAdminLoggedInCache(): void
    {
        DuskTestCase::$adminLoggedIn = false;
    }
}
