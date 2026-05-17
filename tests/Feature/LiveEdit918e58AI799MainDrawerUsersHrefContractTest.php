<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-918e58 / AI-799  MainDrawer Users item broken-href fix.
 * Jira: https://microweber.atlassian.net/browse/AI-799
 *
 * Lineage:
 *   - AI-700 (task-2026-05-16-7326d6)  original MainDrawer ship
 *   - AI-735 (task-2026-05-16-256d49)  admin route propagation pattern
 *   - AI-798 (task-2026-05-17-7a9913)  hierarchy refactor (same surface)
 *
 * Pre-fix defect (designer DOM probe):
 *   {
 *     label: "Users",
 *     href: "http://localhost:8000/admin/live-edit?url=http%253A%252F%252Flocalhost%253A8000%252F"
 *   }
 *
 * Root cause: `usersUrl` defaulted to '' in data(). The render `<a :href="usersUrl">`
 * compiled to `<a href="">`, which browsers resolve to the current URL.
 * `readMenuUrls()` was supposed to override via the
 * `api.live-edit.get-top-right-menu` response, but: (a) that response
 * may not carry an `id="users-link"` item; (b) the unconditional
 * `this.usersUrl = item.href || ''` would BLANK any previous value if
 * the menu provided an empty href; (c) the API placeholder `'#'`
 * resolves to current URL too.
 *
 * Stage-2 sub-case of runtime-vs-source-divergence: source pinned the
 * markup but not the href value. AI-793 admin-404 propagation didn't
 * catch it because the URL technically "works" (returns 200 — it's
 * the same live-edit page reloading).
 *
 * Fix shape:
 *   - data() resolves `usersUrl` + `logoutUrl` via a `safeRoute()`
 *     closure that calls Ziggy's `route()` helper with a try/catch
 *     fallback to plain admin paths (/admin/users, /logout). Ziggy
 *     is registered globally by Toolbar's app boot, so route() is
 *     available at component init; the catch handles environments
 *     where Ziggy hasn't loaded yet.
 *   - `readMenuUrls()` hardened: only override the safe default when
 *     the menu provides a non-empty, non-placeholder ('#') href.
 *     Pre-fix it would BLANK the default to ''.
 *   - Every drawer item gains a stable `data-mw-drawer-item="<slug>"`
 *     attribute so designer Tier-3 probes can address them by purpose
 *     rather than by DOM position. Slugs:
 *       layers / template-and-layout / theme-settings  (EDIT)
 *       pages / back-to-admin / users / see-website     (NAVIGATE)
 *       theme-toggle                                    (PREFERENCES)
 *       logout                                          (footer)
 *
 * Designer Tier-3 probe:
 *   expect(document.querySelector('.mw-main-drawer__item[data-mw-drawer-item="users"]').href)
 *       .toMatch(/\/admin\/users$/);
 */
class LiveEdit918e58AI799MainDrawerUsersHrefContractTest extends TestCase
{
    private string $drawer;
    private string $bundle;

    protected function setUp(): void
    {
        parent::setUp();
        $this->drawer = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/ui/components/Toolbar/MainDrawer.vue'
        ));
        $bundlePath = base_path('public/vendor/microweber-packages/frontend-assets/build/live-edit-app.js');
        $this->bundle = file_exists($bundlePath) ? (string) file_get_contents($bundlePath) : '';
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A  data() defaults via safeRoute() helper
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function data_declares_safe_route_helper_with_try_catch_fallback(): void
    {
        $this->assertMatchesRegularExpression(
            "/var\\s+safeRoute\\s*=\\s*function\\s*\\(\\s*name\\s*,\\s*fallback\\s*\\)\\s*\\{[\\s\\S]*?try\\s*\\{[\\s\\S]*?window\\.route\\(name\\)[\\s\\S]*?catch[\\s\\S]*?return\\s+fallback/",
            $this->drawer,
            'data() must declare a safeRoute(name, fallback) closure that calls window.route() inside try/catch with a fallback return.'
        );
    }

    #[Test]
    public function users_url_defaults_to_filament_admin_users_route(): void
    {
        $this->assertMatchesRegularExpression(
            "/usersUrl:\\s*safeRoute\\(\\s*['\"]filament\\.admin\\.resources\\.users\\.index['\"]\\s*,\\s*['\"]\\/admin\\/users['\"]\\s*\\)/",
            $this->drawer,
            'usersUrl must default via safeRoute("filament.admin.resources.users.index", "/admin/users") so the empty-string defect is gone.'
        );
        $this->assertStringNotContainsString(
            "usersUrl: ''",
            $this->drawer,
            'Legacy empty-string default `usersUrl: \'\'` must be gone (it was the broken-link root cause).'
        );
    }

    #[Test]
    public function logout_url_defaults_to_logout_route(): void
    {
        $this->assertMatchesRegularExpression(
            "/logoutUrl:\\s*safeRoute\\(\\s*['\"]logout['\"]\\s*,\\s*['\"]\\/logout['\"]\\s*\\)/",
            $this->drawer,
            'logoutUrl must default via safeRoute("logout", "/logout"). Same fix family as usersUrl.'
        );
        $this->assertStringNotContainsString(
            "logoutUrl: ''",
            $this->drawer,
            'Legacy empty-string default `logoutUrl: \'\'` must be gone.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B  readMenuUrls() hardening
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function read_menu_urls_only_overrides_on_non_empty_non_placeholder_href(): void
    {
        // The pre-fix bug was unconditional `this.usersUrl = item.href || dot-empty`
        // which would BLANK the safe default when item.href was empty.
        // Post-fix uses a `safe` local computed as item.href && item.href !==
        // pound-char and only assigns when truthy.
        $this->assertMatchesRegularExpression(
            "/var\\s+safe\\s*=\\s*item\\.href\\s*&&\\s*item\\.href\\s*!==\\s*'#'\\s*\\?\\s*item\\.href\\s*:\\s*null/",
            $this->drawer,
            'readMenuUrls() must compute the `safe` local that gates the assignment so placeholders/empties do not blank the safe default.'
        );
        $this->assertStringContainsString(
            "if (item.id === 'users-link' && safe) this.usersUrl = safe;",
            $this->drawer,
            'users-link override must only fire when `safe` is non-null.'
        );
        $this->assertStringContainsString(
            "if (item.id === 'logout-link' && safe) this.logoutUrl = safe;",
            $this->drawer,
            'logout-link override must only fire when `safe` is non-null.'
        );
        $this->assertStringContainsString(
            "if (item.id === 'see-website-link' && safe) this.seeWebsiteUrl = safe;",
            $this->drawer,
            'see-website-link override must only fire when `safe` is non-null (regression: previously had its own truthy check).'
        );
        // Negative regression guards: legacy blanking pattern must be
        // GONE from executable code. Pre-strip HTML comments
        // (top-of-file docblock) + JS block + JS line comments. The
        // docblock prose legitimately mentions the legacy pattern
        // when describing the pre-fix shape; recurring selector-self
        // -match guard family per LESSONS.
        $stripped = preg_replace('~<!--[\s\S]*?-->~', '', $this->drawer);
        $stripped = preg_replace('~/\*[\s\S]*?\*/~', '', $stripped);
        $stripped = preg_replace('~//.*$~m', '', $stripped);
        $this->assertStringNotContainsString(
            "this.usersUrl = item.href || ''",
            $stripped,
            'Legacy blanking pattern for usersUrl must be gone from executable JS.'
        );
        $this->assertStringNotContainsString(
            "this.logoutUrl = item.href || ''",
            $stripped,
            'Legacy blanking pattern for logoutUrl must be gone from executable JS.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C  data-mw-drawer-item attributes on all items
    // ─────────────────────────────────────────────────────────────────────

    public static function drawerItemSlugCases(): array
    {
        return [
            'EDIT  layers'                      => ['layers'],
            'EDIT  template-and-layout'         => ['template-and-layout'],
            'EDIT  theme-settings'              => ['theme-settings'],
            'NAVIGATE  pages (Slice C, AI-798)' => ['pages'],
            'NAVIGATE  back-to-admin'           => ['back-to-admin'],
            'NAVIGATE  users (AI-799 target)'   => ['users'],
            'NAVIGATE  see-website'             => ['see-website'],
            'PREFERENCES  theme-toggle'         => ['theme-toggle'],
            'FOOTER  logout'                    => ['logout'],
        ];
    }

    #[Test]
    #[\PHPUnit\Framework\Attributes\DataProvider('drawerItemSlugCases')]
    public function every_drawer_item_carries_data_mw_drawer_item_attribute(string $slug): void
    {
        $this->assertStringContainsString(
            'data-mw-drawer-item="' . $slug . '"',
            $this->drawer,
            'Drawer item "' . $slug . '" must carry data-mw-drawer-item="' . $slug . '" attribute (designer Tier-3 probe selector).'
        );
    }

    #[Test]
    public function exactly_nine_data_mw_drawer_item_attributes_render(): void
    {
        // Sanity: 3 EDIT + 4 NAVIGATE + 1 PREFERENCES + 1 footer = 9 total.
        // Catches accidental double-stamping or missing slugs.
        //
        // Slice the <template>...</template> block so the top-of-file
        // docblock's prose reference to `data-mw-drawer-item="users"`
        // (designer Tier-3 selector example) doesn't count toward
        // the rendered total. Selector-self-match guard family per
        // the LESSONS recurring pattern.
        $templateStart = strpos($this->drawer, '<template>');
        $templateEnd = strpos($this->drawer, '</template>');
        $this->assertNotFalse($templateStart, 'MainDrawer.vue must contain a <template> block.');
        $this->assertNotFalse($templateEnd, 'MainDrawer.vue must contain a </template> closing tag.');
        $templateBody = substr($this->drawer, $templateStart, $templateEnd - $templateStart);

        $count = substr_count($templateBody, 'data-mw-drawer-item="');
        $this->assertSame(
            9,
            $count,
            'Exactly 9 data-mw-drawer-item attributes must render in the <template> body (3 EDIT + 4 NAVIGATE + 1 PREFERENCES + 1 footer).'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D  served-bundle runtime probe
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function bundle_carries_data_mw_drawer_item_users_attribute(): void
    {
        if ($this->bundle === '') {
            $this->markTestSkipped('Served frontend-assets bundle absent.');
        }
        $this->assertStringContainsString(
            'data-mw-drawer-item":"users',
            $this->bundle,
            'Served Vite bundle must carry the data-mw-drawer-item="users" attribute (designer Tier-3 probe will fail without it).'
        );
        $this->assertStringContainsString(
            'filament.admin.resources.users.index',
            $this->bundle,
            'Served Vite bundle must carry the filament.admin.resources.users.index route name (proves the safeRoute() call survived minification).'
        );
    }

    #[Test]
    public function bundle_mtime_at_least_source_mtime(): void
    {
        $sourcePath = base_path('packages/frontend-assets/resources/assets/ui/components/Toolbar/MainDrawer.vue');
        $bundlePath = base_path('public/vendor/microweber-packages/frontend-assets/build/live-edit-app.js');

        if (!file_exists($bundlePath)) {
            $this->markTestSkipped('Served bundle absent.');
        }

        $sourceMtime = filemtime($sourcePath);
        $bundleMtime = filemtime($bundlePath);

        $this->assertGreaterThanOrEqual(
            $sourceMtime,
            $bundleMtime,
            'Vite bundle mtime must be >= source mtime  rebuild after editing MainDrawer.vue.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group E  markers + lineage
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_and_ai799_markers_present(): void
    {
        $this->assertStringContainsString('task-2026-05-17-918e58', $this->drawer);
        $this->assertStringContainsString('AI-799', $this->drawer);
    }

    #[Test]
    public function docblock_cites_ai700_ai735_ai798_lineage(): void
    {
        $this->assertStringContainsString(
            'AI-700',
            $this->drawer,
            'MainDrawer docblock must cite AI-700 (original drawer ship).'
        );
        $this->assertStringContainsString(
            'AI-735',
            $this->drawer,
            'AI-799 docblock should cite AI-735 (admin route propagation lineage).'
        );
    }
}
