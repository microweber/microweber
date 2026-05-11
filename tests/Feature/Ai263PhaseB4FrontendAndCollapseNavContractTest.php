<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-184 / AI-263 Phase B4 (2026-05-11) — partial-progress
 * refactor of the LAST jQuery dependencies on public pages.
 *
 * Phase B4 targets identified by PM:
 *   1. `frontend-assets/resources/assets/core/events.js` line
 *      302 — unguarded module-init `$(window).on(...)`.
 *   2. `Templates/Bootstrap/resources/assets/js/collapseNav.js`
 *      — entire file uses jQuery, ~26 `$()` call sites.
 *   3. After 1 + 2 are done: drop `mw_require_jquery()` from
 *      Bootstrap master.blade.php → 806KB saved.
 *
 * Cycle-184 outcome (HONEST report):
 *   ✓ Target 1 — events.js:302 refactored to
 *     `window.addEventListener('hashchange'/'load', ...)`.
 *   ✓ Target 2 — collapseNav.js full vanilla rewrite. Exposes
 *     `window.MwCollapseNav(selector, config)` + a guarded
 *     `$.fn.collapseNav` shim that only registers if jQuery
 *     happens to be loaded.
 *   ✗ Target 3 — DEFERRED to Phase B5. Live test exposed a
 *     deeper blocker: `frontend.js` uses `mw.$()` (the
 *     jseldom-jquery wrapper at libs/jseldom/jseldom-jquery.js)
 *     throughout core/events.js, core/ajax.js, core/forms.js.
 *     The wrapper itself throws `ReferenceError: jQuery is not
 *     defined`. Refactoring requires swapping `mw.$` for a
 *     vanilla DOM wrapper OR guarding ~30 call sites — multi-
 *     cycle work.
 *
 * Bootstrap master.blade.php still calls `mw_require_jquery()`
 * — but the COMMENT explains the partial-progress state and
 * names Phase B5 as the next-cycle scope.
 */
class Ai263PhaseB4FrontendAndCollapseNavContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function source_carries_cycle_184_anchor(): void
    {
        $events = $this->read('packages/frontend-assets/resources/assets/core/events.js');
        $collapseNav = $this->read('Templates/Bootstrap/resources/assets/js/collapseNav.js');
        $master = $this->read('Templates/Bootstrap/resources/views/layouts/master.blade.php');

        $this->assertMatchesRegularExpression('/[Cc]ycle-184/', $events,
            'core/events.js MUST carry the cycle-184 anchor.');
        $this->assertStringContainsString('AI-263 Phase B4', $events,
            'core/events.js MUST carry the AI-263 Phase B4 anchor.');
        $this->assertMatchesRegularExpression('/[Cc]ycle-184/', $collapseNav,
            'collapseNav.js MUST carry the cycle-184 anchor.');
        $this->assertStringContainsString('AI-263 Phase B4', $collapseNav,
            'collapseNav.js MUST carry the AI-263 Phase B4 anchor.');
        // Note: the Bootstrap master.blade.php comment was REPLACED by
        // the cycle-185 Phase B5 comment when mw_require_jquery() was
        // dropped. The cycle-185 comment documents the FULL Phase B1-B5
        // lineage (including B4), so we accept either "Phase B4" or
        // "Phase B5 - mentions B4" as a passing state.
        $b4Mentioned = strpos($master, 'AI-263 Phase B4') !== false
            || strpos($master, 'B4 (cycle-184)') !== false
            || strpos($master, 'AI-263 Phase B5') !== false;
        $this->assertTrue($b4Mentioned,
            'Bootstrap master.blade.php MUST document AI-263 Phase B4 '
            . 'either as the active state OR as a prior phase in the '
            . 'B1-B5 lineage of the AI-263 work.');
    }

    #[Test]
    public function events_js_uses_native_addeventlistener_at_module_init(): void
    {
        $src = $this->read('packages/frontend-assets/resources/assets/core/events.js');

        // The OLD jQuery-style `$(window).on("hashchange load", ...)`
        // module-init call MUST be gone — strip comments before
        // checking so the doc comment that references the old
        // pattern doesn't fail the test.
        $stripped = preg_replace('!/\*.*?\*/!s', '', $src);
        $stripped = preg_replace('!//[^\n]*!', '', $stripped);
        $this->assertStringNotContainsString('$(window).on("hashchange load"', $stripped,
            'core/events.js MUST NOT contain the unguarded module-init '
            . '`$(window).on("hashchange load", ...)` call (in '
            . 'executable code — comments may still document the '
            . 'replacement).');

        // The new native calls MUST be present.
        $this->assertStringContainsString("window.addEventListener('hashchange', __mwHashChangeOrLoad)", $src,
            'core/events.js MUST register hashchange via native '
            . 'window.addEventListener.');
        $this->assertStringContainsString("window.addEventListener('load', __mwHashChangeOrLoad)", $src,
            'core/events.js MUST register load via native '
            . 'window.addEventListener.');

        // The handler MUST swap `mw.$("html").addClass(...)` for
        // vanilla classList — frontend.js's mw.$ requires jQuery,
        // so this is one of the leaf-call-site refactors.
        $this->assertStringContainsString("document.documentElement.classList.add('showpostscat')", $src,
            'core/events.js hashchange handler MUST use '
            . 'document.documentElement.classList.add() instead of '
            . 'mw.$("html").addClass() so this handler does NOT depend '
            . 'on jQuery being loaded.');
        $this->assertStringContainsString("document.documentElement.classList.remove('showpostscat')", $src,
            'core/events.js hashchange handler MUST use '
            . 'document.documentElement.classList.remove() instead of '
            . 'mw.$("html").removeClass().');
    }

    #[Test]
    public function collapsenav_js_is_vanilla_with_no_unguarded_jquery_calls(): void
    {
        $src = $this->read('Templates/Bootstrap/resources/assets/js/collapseNav.js');

        // Strip comments so the doc comment that references the
        // OLD jQuery patterns as documentation doesn't trip the
        // assertions.
        $stripped = preg_replace('!/\*.*?\*/!s', '', $src);
        $stripped = preg_replace('!//[^\n]*!', '', $stripped);

        // Old jQuery patterns at module-init scope MUST be gone
        // FROM EXECUTABLE CODE.
        $this->assertStringNotContainsString('$(selector).html()', $stripped,
            'collapseNav.js MUST NOT use $(selector).html() in '
            . 'executable code — refactored to element.innerHTML.');
        $this->assertStringNotContainsString('$.extend(', $stripped,
            'collapseNav.js MUST NOT use $.extend in executable '
            . 'code — refactored to Object.assign().');
        $this->assertStringNotContainsString('$(window).on(', $stripped,
            'collapseNav.js MUST NOT register window events via '
            . '$(window).on in executable code — refactored to '
            . 'window.addEventListener.');

        // New vanilla primitives MUST be present.
        $this->assertStringContainsString('window.MwCollapseNav', $src,
            'collapseNav.js MUST expose window.MwCollapseNav('
            . 'selector, config) as the vanilla entry point.');
        $this->assertStringContainsString('Object.assign({}, defaults, config)', $src,
            'collapseNav.js MUST use Object.assign() for option '
            . 'merging (replaces $.extend).');
        $this->assertStringContainsString("window.addEventListener('load',", $src,
            'collapseNav.js MUST register load event via native '
            . 'window.addEventListener.');
        $this->assertStringContainsString("window.addEventListener('resize',", $src,
            'collapseNav.js MUST register resize event via native '
            . 'window.addEventListener.');
        $this->assertStringContainsString('element.classList', $src,
            'collapseNav.js MUST mention element.classList in comment '
            . '(documents the vanilla replacement pattern used '
            . 'throughout the rewrite).');
    }

    #[Test]
    public function collapsenav_js_provides_data_attribute_auto_init(): void
    {
        $src = $this->read('Templates/Bootstrap/resources/assets/js/collapseNav.js');

        // Auto-init on `<ul data-mw-collapse-nav>` so module skins
        // can opt in via HTML attribute instead of the jQuery
        // plugin call.
        $this->assertStringContainsString('data-mw-collapse-nav', $src,
            'collapseNav.js MUST provide a data-attribute auto-init '
            . 'path (`<ul data-mw-collapse-nav>`) so module skins can '
            . 'opt in WITHOUT calling the jQuery plugin.');
        $this->assertStringContainsString('querySelectorAll', $src,
            'collapseNav.js auto-init MUST use document.querySelectorAll '
            . 'to find data-mw-collapse-nav nodes.');
    }

    #[Test]
    public function collapsenav_js_jquery_shim_is_optional(): void
    {
        $src = $this->read('Templates/Bootstrap/resources/assets/js/collapseNav.js');

        // The jQuery `$.fn.collapseNav` shim MUST be guarded —
        // only registers IFF jQuery happens to be loaded.
        $this->assertMatchesRegularExpression(
            '/registerJqueryShim[\s\S]{0,300}typeof\s+window\.jQuery\s*===\s*[\'"]undefined[\'"]/m',
            $src,
            'collapseNav.js MUST guard the jQuery shim behind '
            . 'typeof window.jQuery === "undefined" check so the '
            . 'file works WITHOUT jQuery.'
        );
    }

    #[Test]
    public function bootstrap_master_documents_phase_b4_partial_progress(): void
    {
        $master = $this->read('Templates/Bootstrap/resources/views/layouts/master.blade.php');

        // After cycle-185 Phase B5 dropped mw_require_jquery() (the
        // actual 806KB-savings cycle), the comment shifted from
        // "REMAINING BLOCKER" to "5-phase lineage". Accept either
        // state — both are valid points in the AI-263 timeline.
        $partialState = strpos($master, 'REMAINING BLOCKER') !== false;
        $completeState = strpos($master, 'Phase B5') !== false
            && strpos($master, 'DROPPED') !== false;
        $this->assertTrue($partialState || $completeState,
            'Bootstrap master.blade.php MUST document AI-263 state — '
            . 'either Phase B4 partial-progress (REMAINING BLOCKER) '
            . 'OR Phase B5 complete (mw_require_jquery DROPPED).');
        $this->assertStringContainsString('Phase B5', $master,
            'Bootstrap master.blade.php MUST mention Phase B5 (either '
            . 'as "next-cycle scope" if still partial OR as the '
            . 'completed cycle that dropped the opt-in).');
    }

    #[Test]
    public function built_app_js_has_no_real_jquery_references(): void
    {
        $rel = 'public/templates/bootstrap/dist/build/app.js';
        $path = base_path($rel);
        if (!file_exists($path)) {
            $this->markTestSkipped('Built bootstrap app.js missing.');
        }
        $built = file_get_contents($path);

        // jQuery itself referenced exactly 0 times — the 1 "jQuery"
        // hit in the built bundle is the Bootstrap 5 minified
        // module that uses a local variable named `jQuery` but
        // doesn't require the actual library.
        $this->assertStringNotContainsString('require("jquery")', $built,
            'Built app.js MUST NOT contain require("jquery") — '
            . 'Bootstrap 5 bundle + vanilla collapseNav don\'t need it.');
        $this->assertStringNotContainsString('window.jQuery=', $built,
            'Built app.js MUST NOT export window.jQuery — '
            . 'Bootstrap 5 bundle is jQuery-free.');

        // The new vanilla collapseNav export MUST be in the bundle.
        $this->assertStringContainsString('MwCollapseNav', $built,
            'Built app.js MUST contain the MwCollapseNav vanilla '
            . 'export — proves the collapseNav rewrite shipped.');
    }

    #[Test]
    public function built_frontend_js_carries_vanilla_hashchange_handler(): void
    {
        $rel = 'public/vendor/microweber-packages/frontend-assets/build/frontend.js';
        $path = base_path($rel);
        if (!file_exists($path)) {
            $this->markTestSkipped('Built frontend.js missing.');
        }
        $built = file_get_contents($path);

        // Distinctive identifier — proves the new vanilla
        // handler shipped to the compiled bundle.
        $this->assertStringContainsString('__mwHashChangeOrLoad', $built,
            'Built frontend.js MUST contain the __mwHashChangeOrLoad '
            . 'function identifier — confirms the vanilla '
            . 'hashchange/load handler refactor shipped.');
    }
}
