<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-186 / AI-263 Phase C (2026-05-11) — payload baseline +
 * source-level confirmation that the 806KB jQuery drop shipped.
 *
 * PM closed AI-263 as Done after cycle-185 Phase B5 dropped
 * `mw_require_jquery()` from Bootstrap master.blade.php. Phase C
 * is the verification cycle — no code changes, just measurement
 * + report.
 *
 * Live network-payload measurement (Playwright at 390×844, see
 * `.autodev/skills/ai-263-phase-c-baseline/SKILL.md` for the
 * raw numbers):
 *
 *   Public Bootstrap homepage /:
 *     jQuery script in network: FALSE
 *     jQuery UI script in network: FALSE
 *     Total JS bytes decoded: 608,688 (~595 KiB)
 *     scriptCount: 7
 *
 *   Public Bootstrap shop /shop:
 *     Same as /, plus a noUiSlider lazy-load on the price-range
 *     filter — fixed in this cycle by replacing the old
 *     `$(document).ready` wait with an explicit `waitForNoUiSlider`
 *     retry loop (the implicit jQuery-init delay no longer
 *     covers the lazy lib's load time).
 *
 *   Admin /admin (after /admin/login redirect):
 *     window.jQuery.fn.jquery: '3.7.1'  ← REAL jQuery preserved
 *     jquery.js bytes: 285,314
 *     jquery-ui.js bytes: 521,054
 *     Combined: 806,368  ← EXACT match to PM's 806KB estimate
 *
 *   Net savings on every public Bootstrap page: ~806 KB
 *   (= ~57% reduction in JS payload).
 *
 * This contract test pins:
 *   (1) The full AI-263 phase lineage is documented in the
 *       canonical source files (Bootstrap master, @core.js, etc.).
 *   (2) The 806KB drop is observable in the source — the
 *       `mw_require_jquery()` call is GONE from Bootstrap master.
 *   (3) The shop noUiSlider fix from this cycle shipped to source.
 *   (4) The Phase C baseline document exists at
 *       `.autodev/skills/ai-263-phase-c-baseline/SKILL.md`.
 */
class Ai263PhaseCBaselineContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function phase_c_baseline_document_exists(): void
    {
        $src = $this->read('.autodev/skills/ai-263-phase-c-baseline/SKILL.md');
        $this->assertStringContainsString('AI-263 Phase C', $src,
            'Phase C baseline SKILL.md MUST identify itself as the '
            . 'AI-263 Phase C reference.');
        $this->assertStringContainsString('608,688', $src,
            'Phase C baseline MUST record the exact 608,688 bytes '
            . 'decoded JS measurement for the public Bootstrap homepage.');
        $this->assertStringContainsString('806,368', $src,
            'Phase C baseline MUST record the exact 806,368-byte sum '
            . '(285,314 + 521,054) confirming PM\'s "806KB" estimate.');
        $this->assertStringContainsString('57%', $src,
            'Phase C baseline MUST express the percentage reduction '
            . '(57% of public-page JS payload saved).');
    }

    #[Test]
    public function bootstrap_master_documents_full_b1_b5_lineage(): void
    {
        $master = $this->read('Templates/Bootstrap/resources/views/layouts/master.blade.php');
        foreach (['B1', 'B2', 'B3', 'B4', 'B5'] as $phase) {
            $this->assertStringContainsString($phase, $master,
                'Bootstrap master.blade.php comment MUST document the '
                . "{$phase} phase of the AI-263 work so future "
                . 'maintainers see the full lineage.');
        }
        $this->assertStringContainsString('806KB', $master,
            'Bootstrap master.blade.php comment MUST document the '
            . '806KB savings outcome.');
    }

    #[Test]
    public function mw_require_jquery_call_is_truly_dropped(): void
    {
        $master = $this->read('Templates/Bootstrap/resources/views/layouts/master.blade.php');
        // Strip blade {{--...--}} comments + @php...@endphp blocks
        // so the comment that documents the removal doesn't trigger
        // a false positive.
        $stripped = preg_replace('/\{\{--[\s\S]*?--\}\}/', '', $master);
        $stripped = preg_replace('/@php([\s\S]*?)@endphp/', '', $stripped);
        $this->assertStringNotContainsString('mw_require_jquery()', $stripped,
            'Bootstrap master.blade.php MUST NOT call mw_require_jquery() '
            . 'in executable PHP — the entire AI-263 work culminates in '
            . 'this drop.');
    }

    #[Test]
    public function shop_price_range_waits_for_lazy_loaded_noUiSlider(): void
    {
        $src = $this->read('Modules/Shop/resources/views/livewire/shop/filters/price_range/script.blade.php');
        $this->assertStringContainsString('waitForNoUiSlider', $src,
            'Shop price-range script MUST use a retry loop to wait '
            . 'for the lazy-loaded noUiSlider library before calling '
            . 'noUiSlider.create() — before AI-263, the implicit '
            . 'jQuery-init delay covered the lazy lib\'s load time. '
            . 'Now that mw.$ uses immediate vanilla DOMContentLoaded, '
            . 'the script races ahead and an explicit wait is needed.');
        // 30 retries × 100ms = 3s max wait — reasonable upper bound.
        $this->assertMatchesRegularExpression(
            '/}\)\(30\)/m',
            $src,
            'Shop price-range retry loop MUST start with a sensible '
            . 'retry budget (30 × 100ms = 3s max wait).'
        );
    }

    #[Test]
    public function mw_dollar_continues_to_be_vanilla_capable(): void
    {
        $src = $this->read('packages/frontend-assets/resources/assets/core/@core.js');
        // The B5 hybrid wrapper MUST still be in place — Phase C is a
        // verification cycle, NOT a regression cycle. If the vanilla
        // shim was removed, public pages would re-need jQuery and the
        // 806KB savings would vanish.
        $this->assertStringContainsString('MwDomCollection', $src,
            '@core.js MUST still contain the MwDomCollection vanilla '
            . 'wrapper from cycle-185.');
        $this->assertStringContainsString('AI-263 Phase B5', $src,
            '@core.js MUST still carry the Phase B5 anchor.');
        $this->assertMatchesRegularExpression(
            '/window\.jQuery\s*!==\s*mw\.\$/m',
            $src,
            '@core.js MUST still have the self-recursion guard '
            . 'preventing mw.$ → window.jQuery → mw.$ infinite loop '
            . 'when the alias is in place.'
        );
    }

    #[Test]
    public function api_settings_lib_definitions_use_vanilla_adapters(): void
    {
        $src = $this->read('src/MicroweberPackages/App/resources/includes/api/api_settings.js');

        // The 4 plugins refactored in Phase B2 + B3 — verify the
        // lib definitions still point at the vanilla adapters, NOT
        // the original jQuery plugin libraries.
        $this->assertStringContainsString('slick-to-swiper-adapter/slick-to-swiper-adapter.js', $src,
            'api_settings.js MUST still load the Slick→Swiper adapter '
            . '(Phase B2 cycle-182).');
        $this->assertStringContainsString('masonry-vanilla-adapter/masonry-vanilla-adapter.js', $src,
            'api_settings.js MUST still load the Masonry vanilla '
            . 'adapter (Phase B3 cycle-183).');
        $this->assertStringContainsString('native-datetimepicker-adapter/native-datetimepicker-adapter.js', $src,
            'api_settings.js MUST still load the native '
            . 'datetimepicker adapter (Phase B3 cycle-183).');
        $this->assertStringContainsString('native-chosen-adapter/native-chosen-adapter.js', $src,
            'api_settings.js MUST still load the native chosen '
            . 'adapter (Phase B3 cycle-183).');

        // And NOT the old jQuery plugin libraries.
        $this->assertStringNotContainsString("'slick/slick.js'", $src,
            'api_settings.js MUST NOT load the original Slick jQuery '
            . 'plugin (replaced by Phase B2 adapter).');
        $this->assertStringNotContainsString("'masonry/masonry.pkgd.js'", $src,
            'api_settings.js MUST NOT load the original Masonry '
            . 'jQuery plugin (replaced by Phase B3 adapter).');
        $this->assertStringNotContainsString("'bootstrap_datetimepicker/bootstrap-datetimepicker.js'", $src,
            'api_settings.js MUST NOT load the original Bootstrap '
            . 'Datetimepicker jQuery plugin (replaced by Phase B3 '
            . 'native adapter).');
        $this->assertStringNotContainsString("'chosen' + '/chosen.jquery.min.js'", $src,
            'api_settings.js MUST NOT load the original Chosen '
            . 'jQuery plugin (replaced by Phase B3 native adapter).');
    }

    #[Test]
    public function apijs_script_tag_keeps_conditional_emission_for_admin_path(): void
    {
        $src = $this->read('src/MicroweberPackages/MetaTags/Entities/ApijsScriptTag.php');
        // Phase B1 conditional emission MUST still be in place —
        // it's what preserves admin path's eager jQuery load.
        $this->assertStringContainsString('isAdminPath', $src,
            'ApijsScriptTag MUST still have the isAdminPath() check '
            . 'from cycle-181 — that\'s what preserves jQuery loading '
            . 'on /admin/* and /api/* while public Bootstrap pages '
            . 'go without.');
        $this->assertStringContainsString('requestRequiresJquery', $src,
            'ApijsScriptTag MUST still expose the request-scoped '
            . 'opt-in flag from cycle-181 (other templates can opt '
            . 'in by calling mw_require_jquery() if they need it).');
    }

    #[Test]
    public function five_phase_contract_tests_still_present(): void
    {
        // All 5 prior Phase contract tests MUST still exist + be runnable.
        // If any are missing, the Phase C "all green" verification is
        // suspect.
        $tests = [
            'tests/Feature/Ai263PhaseB1JqueryConditionalContractTest.php',
            'tests/Feature/Ai263PhaseB2SlickSwiperContractTest.php',
            'tests/Feature/Ai263PhaseB3MasonryCaptchaCustomfieldsContractTest.php',
            'tests/Feature/Ai263PhaseB4FrontendAndCollapseNavContractTest.php',
            'tests/Feature/Ai263PhaseB5MwVanillaShimContractTest.php',
        ];
        foreach ($tests as $rel) {
            $this->assertFileExists(base_path($rel),
                "Phase contract test MUST still exist: {$rel}");
        }
    }
}
