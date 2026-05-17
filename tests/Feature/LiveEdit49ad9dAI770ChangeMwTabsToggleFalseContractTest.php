<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-49ad9d / AI-770 v2 CHANGE — mw.tabs toggle:false
 * + direct $firstOpen dispatch. Jira:
 *   https://microweber.atlassian.net/browse/AI-770
 *
 * Designer verified AI-770 v1 (`564cfb9c32`) — Tier 1+2 clean
 * but Tier 3 runtime probe FAILED: desktop Media library body
 * remained empty post-ship.
 *
 * Root cause confirmed empirically by designer:
 *   1. mw.tabs() init sets .active on first tab (library)
 *   2. AI-770 v1 setTimeout → trigger("click") on library
 *   3. mw.tabs click handler sees hasClass("active") → enters
 *      the toggle-OFF branch (else clause at tools/tabs.js:97)
 *   4. The toggle-OFF branch only calls our onclick when
 *      `obj.toggle === true` (default). With toggle truthy,
 *      onclick fires WITH toggle-off side-effects (content
 *      hidden). Either way, $firstOpen-related state was
 *      not correctly mounted.
 *   5. Library iframe never mounted.
 *
 * Fix per designer's Option C + Option B combined:
 *   - Option C: mw.tabs `toggle: false` so re-clicking the
 *     active tab no longer toggles it off (closes the latent
 *     UX bug — for a 1-of-N picker, accidentally hiding the
 *     active tab content by re-clicking is wrong by design).
 *   - Option B: bypass the click; fire $firstOpen DIRECTLY in
 *     the else-if fallback branch for the default first tab.
 *     With toggle:false in place, clicking an already-active
 *     tab is a no-op (else branch at tools/tabs.js:97 doesn't
 *     call onclick when toggle is not true). So we can't rely
 *     on a programmatic click anymore — direct $firstOpen
 *     dispatch is the only path that wakes the library
 *     iframe mount on initial paint.
 *
 * The __navigation_first index push prevents a subsequent
 * real click on the library tab from double-firing $firstOpen
 * (same guard the mw.tabs.onclick callback uses at line ~838).
 */
class LiveEdit49ad9dAI770ChangeMwTabsToggleFalseContractTest extends TestCase
{
    private string $filepicker;
    private string $bundle;

    protected function setUp(): void
    {
        parent::setUp();
        $this->filepicker = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/components/filepicker.js'
        ));
        $bundlePath = base_path(
            'packages/frontend-assets/resources/dist/build/admin.js'
        );
        $this->bundle = file_exists($bundlePath)
            ? (string) file_get_contents($bundlePath)
            : '';
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — Option C: mw.tabs toggle:false
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function mw_tabs_init_carries_toggle_false(): void
    {
        // Pin `toggle: false` inside the mw.tabs() init block at
        // line ~833. Walk from "mw.tabs({" to the matching
        // closing `});` and assert the toggle declaration is
        // present.
        $start = strpos($this->filepicker, 'scope._tabs = mw.tabs(');
        $this->assertNotFalse($start, 'mw.tabs() init for picker tabs must be present.');
        // Find the matching closing "});" for this call.
        $end = strpos($this->filepicker, '            });', $start);
        $this->assertNotFalse($end);
        $slice = substr($this->filepicker, $start, $end - $start);

        $this->assertMatchesRegularExpression(
            '/toggle:\s*false/',
            $slice,
            'mw.tabs() init must include `toggle: false` per AI-770 v2 Option C.'
        );
    }

    #[Test]
    public function active_class_remains_active_post_change(): void
    {
        // Defensive — the activeClass setting must remain so
        // the first tab still gets a visual active state on
        // init even though toggle is off.
        $start = strpos($this->filepicker, 'scope._tabs = mw.tabs(');
        $end = strpos($this->filepicker, '            });', $start);
        $slice = substr($this->filepicker, $start, $end - $start);
        $this->assertMatchesRegularExpression(
            "/activeClass:\\s*['\"]active['\"]/",
            $slice,
            'activeClass: "active" must remain — toggle: false does not affect visual active marking.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — Option B: direct $firstOpen dispatch
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function else_if_fires_first_open_directly(): void
    {
        $this->assertMatchesRegularExpression(
            "/\\\$\\(scope\\)\\.trigger\\(\\s*['\"]\\\$firstOpen['\"]\\s*,\\s*\\[/",
            $this->filepicker,
            'AI-770 v2 else-if must fire $firstOpen directly via $(scope).trigger("$firstOpen", [...]).'
        );
    }

    #[Test]
    public function navigation_first_guard_prevents_double_fire(): void
    {
        // The __navigation_first array tracks which tab indexes
        // have been "first-opened". The AI-770 v2 fix pushes
        // index 0 into it BEFORE firing $firstOpen so a real
        // user click on the library tab later doesn't double-fire.
        $this->assertMatchesRegularExpression(
            '/scope\.__navigation_first\.indexOf\(\s*0\s*\)\s*===\s*-1[\s\S]{0,200}?scope\.__navigation_first\.push\(\s*0\s*\)/',
            $this->filepicker,
            'AI-770 v2 must guard direct $firstOpen with __navigation_first.indexOf(0) === -1 + push(0) so a subsequent real click does not double-fire.'
        );
    }

    #[Test]
    public function fires_with_first_component_section_element(): void
    {
        // Per the mw.tabs onclick contract at line ~840:
        //   $(scope).trigger("$firstOpen", [el, this.dataset.type]);
        // where el = mw.$(obj.tabs).eq(i)[0]. The direct-fire
        // path must pass the equivalent — first section element
        // + first component type string.
        $this->assertMatchesRegularExpression(
            "/['\"]\\.mw-filepicker-component-section['\"][\\s\\S]{0,200}?scope\\.\\\$root[\\s\\S]{0,80}?\\.eq\\(\\s*0\\s*\\)/",
            $this->filepicker,
            'AI-770 v2 must select the first .mw-filepicker-component-section under scope.$root to pass as the el argument to $firstOpen.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — non-regression on adjacent paths
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function ai117_first_active_component_override_preserved(): void
    {
        // AI-117 / cycle-108 firstActiveComponent override path
        // (designer-recommended for empty MediaLibrary →
        // desktop hand-off) must remain — different code path,
        // not affected by the AI-770 v2 CHANGE.
        $this->assertMatchesRegularExpression(
            '/if\s*\(\s*scope\.settings\.firstActiveComponent\s*\)\s*\{[\s\S]*?\$target\.trigger\(\s*[\'"]click[\'"]\s*\)/',
            $this->filepicker,
            'AI-117 firstActiveComponent override path must be preserved (still uses trigger("click") — that path clicks an INACTIVE tab so the toggle-OFF bug does not apply).'
        );
    }

    #[Test]
    public function library_iframe_mount_handler_intact(): void
    {
        $this->assertStringContainsString(
            'library: function () {',
            $this->filepicker,
            'library tab render function must still exist.'
        );
        $this->assertStringContainsString(
            'filament.admin.pages.media-library',
            $this->filepicker,
            'library tab must still mount the filament.admin.pages.media-library iframe.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — bundle runtime probe + markers
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function bundle_carries_toggle_false_setting(): void
    {
        if ($this->bundle === '') {
            $this->markTestSkipped('Vite admin.js bundle not present.');
        }
        // Probe for the structural pattern that survives Vite
        // minification: a `toggle:!1` (false) or `toggle:false`
        // declaration near mw.tabs init markers.
        $this->assertMatchesRegularExpression(
            '/toggle:\s*(!1|false)/i',
            $this->bundle,
            'Vite admin.js bundle must carry the `toggle: false` (or minified `toggle:!1`) mw.tabs setting.'
        );
    }

    #[Test]
    public function v2_change_markers_pinned(): void
    {
        $this->assertStringContainsString('task-2026-05-17-49ad9d', $this->filepicker);
        $this->assertStringContainsString('AI-770 v2 CHANGE', $this->filepicker);
        // Original AI-770 v1 task-id (task-00bdf0) preserved
        // for audit-chain continuity.
        $this->assertStringContainsString(
            'task-2026-05-17-00bdf0',
            $this->filepicker,
            'Original AI-770 v1 task-id (task-00bdf0) must remain in the comment for audit-chain continuity.'
        );
    }
}
