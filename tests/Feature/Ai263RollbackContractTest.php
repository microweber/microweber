<?php

declare(strict_types=1);

namespace Tests\Feature;

use MicroweberPackages\MetaTags\Entities\ApijsScriptTag;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-188 EMERGENCY ROLLBACK (2026-05-11) — restore jQuery on
 * all public pages per customer request.
 *
 * After AI-263 Phase B5 (cycle-185) dropped `mw_require_jquery()`
 * from the Bootstrap master.blade.php to save 806KB on every
 * public page render, the customer reported that templates still
 * rely on jQuery and need it to function correctly. This overrides
 * the entire AI-263 Phase B1-B5 design goal of conditionally
 * loading jQuery only when admin / opt-in requires it.
 *
 * Cycle-188 rollback:
 *
 *   1. `Templates/Bootstrap/resources/views/layouts/master.blade.php`
 *      — `@php mw_require_jquery() @endphp` block RESTORED at the
 *      top of the file BEFORE `<head>`.
 *
 *   2. `ApijsScriptTag.php` — `$needsJqueryEager` hardcoded to
 *      `true`. The conditional logic (isAdminPath + opt-in flag)
 *      stays in place as documentation + as the future re-removal
 *      path if the customer reconsiders.
 *
 * The adapter infrastructure built across cycles 181-185 is
 * RETAINED — they're additive improvements that work transparently
 * when jQuery is present:
 *   - Swiper / Masonry / Datetimepicker / Chosen / Captcha vanilla
 *     adapters keep working
 *   - mw.$ hybrid wrapper still passes through to real jQuery
 *     when loaded
 *   - Vanilla CSRF fetch interceptor still wraps window.fetch
 *   - collapseNav.js vanilla rewrite still ships
 *   - events.js native addEventListener changes still ship
 *
 * Verification at /, /shop, /admin/login (post-redirect /admin):
 *   - All three: window.jQuery.fn.jquery === '3.7.1' ✓
 *   - All three: zero console errors
 *   - mw.$ hybrid wrapper still loaded (passes through to jQuery)
 *   - CSRF fetch interceptor still wraps window.fetch
 */
class Ai263RollbackContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function source_carries_cycle_188_rollback_anchor(): void
    {
        $master = $this->read('Templates/Bootstrap/resources/views/layouts/master.blade.php');
        $apijs = $this->read('src/MicroweberPackages/MetaTags/Entities/ApijsScriptTag.php');

        $this->assertMatchesRegularExpression('/[Cc]ycle-188/', $master,
            'Bootstrap master.blade.php MUST carry the cycle-188 '
            . 'rollback anchor documenting the jQuery restoration.');
        $this->assertStringContainsString('EMERGENCY ROLLBACK', $master,
            'Bootstrap master.blade.php comment MUST identify this '
            . 'as an emergency rollback (so future maintainers know '
            . 'the jQuery-restore was a customer decision, not a '
            . 'design intent).');

        $this->assertMatchesRegularExpression('/[Cc]ycle-188/', $apijs,
            'ApijsScriptTag.php MUST carry the cycle-188 rollback anchor.');
        $this->assertStringContainsString('EMERGENCY ROLLBACK', $apijs,
            'ApijsScriptTag.php MUST identify the force-emit as the '
            . 'cycle-188 emergency rollback.');
    }

    #[Test]
    public function bootstrap_master_restores_mw_require_jquery_call(): void
    {
        $master = $this->read('Templates/Bootstrap/resources/views/layouts/master.blade.php');

        // The `@php ... mw_require_jquery() ... @endphp` block MUST
        // be present at the top of the file again.
        $this->assertMatchesRegularExpression(
            '/@php[\s\S]{0,2000}mw_require_jquery\(\)[\s\S]{0,500}@endphp/m',
            $master,
            'Bootstrap master.blade.php MUST contain a @php block '
            . 'that calls mw_require_jquery() — cycle-188 rollback '
            . 'restored this from cycle-185\'s drop.'
        );
    }

    #[Test]
    public function apijs_force_emits_jquery_on_all_paths(): void
    {
        $src = $this->read('src/MicroweberPackages/MetaTags/Entities/ApijsScriptTag.php');

        // The needsJqueryEager check should now hardcode to true
        // (per PM's emergency instruction).
        $this->assertMatchesRegularExpression(
            '/\$needsJqueryEager\s*=\s*true\s*;/m',
            $src,
            'ApijsScriptTag MUST hardcode $needsJqueryEager = true '
            . 'so every page (admin OR public) gets the eager jQuery '
            . 'load. Conditional logic kept above as documentation + '
            . 'future re-removal path.'
        );

        // The original isAdminPath + requestRequiresJquery
        // infrastructure MUST still be there (commented out or
        // dormant) — for the future re-removal path.
        $this->assertStringContainsString('isAdminPath', $src,
            'ApijsScriptTag MUST still expose the isAdminPath() method '
            . '— preserved as documentation of the prior Phase B1 '
            . 'design and as the restoration path.');
        $this->assertStringContainsString('requestRequiresJquery', $src,
            'ApijsScriptTag MUST still expose '
            . 'requestRequiresJquery() — same rationale.');
    }

    #[Test]
    public function public_path_now_emits_eager_jquery(): void
    {
        // Cycle-185 test of the inverse — public path emits NO
        // jQuery. After cycle-188 rollback, public path MUST emit
        // jQuery again (the PM-requested customer state).
        $request = \Illuminate\Http\Request::create('/', 'GET');
        $this->app->instance('request', $request);
        $this->app->forgetInstance('mw.requires_jquery');

        $tag = new ApijsScriptTag();
        $html = $tag->toHtml();

        $this->assertStringContainsString('mw-jquery-js-libs-scripts', $html,
            'On public path AFTER cycle-188 rollback, ApijsScriptTag '
            . 'MUST emit the eager jquery.js <script> tag (was absent '
            . 'in cycle-185).');
        $this->assertStringContainsString('mw-jquery-ui-js-libs-scripts', $html,
            'On public path AFTER cycle-188 rollback, ApijsScriptTag '
            . 'MUST emit the eager jquery-ui.js <script> tag.');
        $this->assertStringContainsString('mw-jquery-ui-js-libs-styles', $html,
            'On public path AFTER cycle-188 rollback, ApijsScriptTag '
            . 'MUST emit the jquery-ui.css <link> tag.');
        // The vanilla CSRF interceptor MUST also still be emitted.
        $this->assertStringContainsString('mw-js-csrf-vanilla', $html,
            'ApijsScriptTag MUST still emit the vanilla CSRF fetch '
            . 'interceptor — cycle-181 work is preserved.');
    }

    #[Test]
    public function admin_path_still_emits_eager_jquery(): void
    {
        // Admin path was always emitting jQuery (cycle-181 isAdminPath
        // check). After cycle-188 rollback it MUST still emit jQuery.
        $request = \Illuminate\Http\Request::create('/admin/login', 'GET');
        $this->app->instance('request', $request);

        $tag = new ApijsScriptTag();
        $html = $tag->toHtml();

        $this->assertStringContainsString('mw-jquery-js-libs-scripts', $html,
            'Admin path MUST still emit jquery.js (preserved through '
            . 'every AI-263 phase + rollback).');
        $this->assertStringContainsString('mw-jquery-ui-js-libs-scripts', $html,
            'Admin path MUST still emit jquery-ui.js.');
    }

    #[Test]
    public function adapter_infrastructure_retained(): void
    {
        // All the adapter work from cycles 182-185 MUST still be in
        // place — the rollback is ADDITIVE only (restores jQuery,
        // doesn't remove the adapters).

        // Phase B2 — Slick→Swiper adapter
        $this->assertFileExists(base_path('packages/frontend-assets-libs/resources/local-libs/slick-to-swiper-adapter/slick-to-swiper-adapter.js'),
            'Slick→Swiper adapter file MUST still exist — rollback '
            . 'is ADDITIVE.');

        // Phase B3 — Masonry / Datetimepicker / Chosen adapters
        $this->assertFileExists(base_path('packages/frontend-assets-libs/resources/local-libs/masonry-vanilla-adapter/masonry-vanilla-adapter.js'),
            'Masonry vanilla adapter MUST still exist.');
        $this->assertFileExists(base_path('packages/frontend-assets-libs/resources/local-libs/native-datetimepicker-adapter/native-datetimepicker-adapter.js'),
            'Native datetimepicker adapter MUST still exist.');
        $this->assertFileExists(base_path('packages/frontend-assets-libs/resources/local-libs/native-chosen-adapter/native-chosen-adapter.js'),
            'Native chosen adapter MUST still exist.');

        // Phase B4 — collapseNav vanilla rewrite
        $collapseNav = $this->read('Templates/Bootstrap/resources/assets/js/collapseNav.js');
        $this->assertStringContainsString('MwCollapseNav', $collapseNav,
            'collapseNav.js MUST still expose the MwCollapseNav '
            . 'vanilla function from cycle-184.');

        // Phase B5 — mw.$ hybrid wrapper
        $core = $this->read('packages/frontend-assets/resources/assets/core/@core.js');
        $this->assertStringContainsString('MwDomCollection', $core,
            '@core.js MUST still carry the MwDomCollection vanilla '
            . 'wrapper from cycle-185 — it passes through to real '
            . 'jQuery when present (which it now always is).');

        // Vanilla CSRF interceptor still emitted by ApijsScriptTag
        $apijs = $this->read('src/MicroweberPackages/MetaTags/Entities/ApijsScriptTag.php');
        $this->assertStringContainsString('mw-js-csrf-vanilla', $apijs,
            'ApijsScriptTag MUST still emit the vanilla fetch CSRF '
            . 'interceptor — cycle-181 work retained.');
    }
}
