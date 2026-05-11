<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-183 / AI-263 Phase B3 (2026-05-11) — drop-in vanilla
 * adapters for Masonry, Bootstrap Datetimepicker, Chosen +
 * captcha jQuery cleanup.
 *
 * Continues the Phase B2 (cycle-182) drop-in adapter strategy
 * for the remaining jQuery plugin dependencies identified in
 * the Phase A audit (cycle-180). Module skins continue to call
 * the jQuery-plugin APIs unchanged but get vanilla
 * implementations.
 *
 *   1. Masonry  (Pictures skin-18 / skin-20 / masonry.blade.php,
 *      Content masonry.blade.php). `.masonry({itemSelector,
 *      gutter})` → CSS Grid masonry (Firefox/Safari) with CSS
 *      multi-column fallback (every browser since 2014). No
 *      `setInterval(reLayout, 500)` needed — CSS auto-reflows.
 *
 *   2. Bootstrap Datetimepicker  (Modules/CustomFields
 *      *time.blade.php across all 6 form template families).
 *      `.datetimepicker({pickDate:false, minuteStep:15})` →
 *      swap `<input type="text">` for HTML5 `<input
 *      type="time">` (or `date` / `datetime-local` based on
 *      options). Browser provides the native picker.
 *
 *   3. Chosen  (Modules/CustomFields *dropdown.blade.php
 *      when `settings.multiple == true`).
 *      `.chosen({width: '100%'})` → leave native `<select
 *      multiple>` as-is, apply `mw-chosen-native` class with
 *      44×44 touch-target floor + 4px design-system radius +
 *      Inter font.
 *
 *   4. Captcha recaptcha.blade.php direct edit:
 *      - `$('#x').val(response)` → getElementById + .value
 *      - `$(document).ready(...)` → DOMContentLoaded
 *      - `$().find('iframe').length` → querySelector
 *      - `$().first().remove()` → parentNode.removeChild
 *      - `$.getScript(...)` → dynamic <script> element
 *      - `$('#x')[0]` → getElementById direct ref
 */
class Ai263PhaseB3MasonryCaptchaCustomfieldsContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function source_carries_cycle_183_anchor(): void
    {
        $masonryA = $this->read('packages/frontend-assets-libs/resources/local-libs/masonry-vanilla-adapter/masonry-vanilla-adapter.js');
        $dtpA = $this->read('packages/frontend-assets-libs/resources/local-libs/native-datetimepicker-adapter/native-datetimepicker-adapter.js');
        $chosenA = $this->read('packages/frontend-assets-libs/resources/local-libs/native-chosen-adapter/native-chosen-adapter.js');
        $captcha = $this->read('Modules/Captcha/resources/views/templates/recaptcha.blade.php');
        $settings = $this->read('src/MicroweberPackages/App/resources/includes/api/api_settings.js');

        foreach (['masonry-vanilla-adapter.js' => $masonryA, 'native-datetimepicker-adapter.js' => $dtpA, 'native-chosen-adapter.js' => $chosenA] as $name => $src) {
            $this->assertMatchesRegularExpression('/[Cc]ycle-183/', $src,
                $name . ' MUST carry the cycle-183 anchor.');
            $this->assertStringContainsString('AI-263 Phase B3', $src,
                $name . ' MUST carry the AI-263 Phase B3 anchor.');
        }
        $this->assertStringContainsString('AI-263 Phase B3', $captcha,
            'Modules/Captcha/.../recaptcha.blade.php MUST carry the '
            . 'AI-263 Phase B3 anchor.');
        $this->assertStringContainsString('AI-263 Phase B3', $settings,
            'api_settings.js MUST carry the AI-263 Phase B3 anchor.');
    }

    #[Test]
    public function api_settings_masonry_swaps_to_adapter(): void
    {
        $src = $this->read('src/MicroweberPackages/App/resources/includes/api/api_settings.js');
        $this->assertStringNotContainsString("'masonry/masonry.pkgd.js'", $src,
            'api_settings.js MUST NOT load masonry/masonry.pkgd.js '
            . '— the vanilla CSS-Grid / column-layout adapter '
            . 'replaces the jQuery plugin.');
        $this->assertStringContainsString('masonry-vanilla-adapter/masonry-vanilla-adapter.js', $src,
            'api_settings.js MUST load the masonry-vanilla-adapter.');
    }

    #[Test]
    public function api_settings_datetimepicker_swaps_to_adapter(): void
    {
        $src = $this->read('src/MicroweberPackages/App/resources/includes/api/api_settings.js');
        $this->assertStringNotContainsString("'bootstrap_datetimepicker/bootstrap-datetimepicker.js'", $src,
            'api_settings.js MUST NOT load bootstrap_datetimepicker/'
            . 'bootstrap-datetimepicker.js — the native '
            . '<input type="datetime-local"> adapter replaces it.');
        $this->assertStringContainsString('native-datetimepicker-adapter/native-datetimepicker-adapter.js', $src,
            'api_settings.js MUST load the native-datetimepicker-adapter.');
    }

    #[Test]
    public function api_settings_chosen_swaps_to_adapter(): void
    {
        $src = $this->read('src/MicroweberPackages/App/resources/includes/api/api_settings.js');
        $this->assertStringNotContainsString("'chosen' + '/chosen.jquery.min.js'", $src,
            'api_settings.js MUST NOT load chosen/chosen.jquery.min.js '
            . '— the vanilla native <select multiple> adapter '
            . 'replaces it.');
        $this->assertStringContainsString('native-chosen-adapter/native-chosen-adapter.js', $src,
            'api_settings.js MUST load the native-chosen-adapter.');
    }

    #[Test]
    public function masonry_adapter_uses_css_grid_or_column_fallback(): void
    {
        $src = $this->read('packages/frontend-assets-libs/resources/local-libs/masonry-vanilla-adapter/masonry-vanilla-adapter.js');
        // CSS.supports detection for native grid masonry.
        $this->assertMatchesRegularExpression(
            '/CSS\.supports\([\s\S]{0,200}grid-template-rows[\s\S]{0,100}masonry/m',
            $src,
            'Masonry adapter MUST feature-detect '
            . 'CSS.supports("grid-template-rows", "masonry") so '
            . 'modern browsers get native CSS Grid masonry.'
        );
        // Column-count fallback path.
        $this->assertStringContainsString('columnCount', $src,
            'Masonry adapter MUST fall back to CSS multi-column '
            . 'layout (columnCount + columnGap + breakInside:avoid) '
            . 'when CSS Grid masonry is unsupported.');
        $this->assertStringContainsString("breakInside = 'avoid'", $src,
            'Multi-column fallback MUST set breakInside:avoid on '
            . 'items so each figure stays whole inside a column.');
        // Responsive column count.
        $this->assertStringContainsString('getResponsiveColumns', $src,
            'Masonry adapter MUST recompute column count by viewport '
            . 'width (1/2/3/4 at <480/<768/<1200/≥1200).');
    }

    #[Test]
    public function masonry_adapter_handles_imperative_api(): void
    {
        $src = $this->read('packages/frontend-assets-libs/resources/local-libs/masonry-vanilla-adapter/masonry-vanilla-adapter.js');
        // String API → silent no-op so legacy `.masonry("reloadItems")`
        // / `.masonry("layout")` calls don't crash.
        $this->assertMatchesRegularExpression(
            '/if\s*\(typeof\s+options\s*===\s*[\'"]string[\'"]\s*\)\s*\{\s*return\s+this;/m',
            $src,
            'Masonry adapter MUST silently no-op on string-imperative '
            . 'commands (CSS-driven layout doesn\'t need reflow).'
        );
    }

    #[Test]
    public function datetimepicker_adapter_maps_options_to_native_input(): void
    {
        $src = $this->read('packages/frontend-assets-libs/resources/local-libs/native-datetimepicker-adapter/native-datetimepicker-adapter.js');
        // pickDate:false → time input
        $this->assertMatchesRegularExpression(
            '/opts\.pickDate\s*===\s*false[\s\S]{0,100}inputType\s*=\s*[\'"]time[\'"]/m',
            $src,
            'Datetimepicker adapter MUST map pickDate:false → '
            . '<input type="time">.'
        );
        // pickTime:false → date input
        $this->assertMatchesRegularExpression(
            '/opts\.pickTime\s*===\s*false[\s\S]{0,100}inputType\s*=\s*[\'"]date[\'"]/m',
            $src,
            'Datetimepicker adapter MUST map pickTime:false → '
            . '<input type="date">.'
        );
        // minuteStep → step in seconds
        $this->assertMatchesRegularExpression(
            '/opts\.minuteStep[\s\S]{0,200}this\.step\s*=\s*String\(parseInt\(opts\.minuteStep,\s*10\)\s*\*\s*60\)/m',
            $src,
            'Datetimepicker adapter MUST map minuteStep (in minutes) '
            . 'to native step attribute in seconds (×60).'
        );
    }

    #[Test]
    public function chosen_adapter_styles_native_select_with_44_floor(): void
    {
        $src = $this->read('packages/frontend-assets-libs/resources/local-libs/native-chosen-adapter/native-chosen-adapter.js');
        // 44px touch-target floor (WCAG 2.5.5).
        $this->assertStringContainsString('min-height: 44px', $src,
            'Chosen adapter MUST apply min-height: 44px to native '
            . '<select> for WCAG 2.5.5 touch-target compliance.');
        // 4px design-system radius (matches cycle-178 --radius-sm).
        $this->assertStringContainsString('border-radius: 4px', $src,
            'Chosen adapter MUST apply 4px border-radius to match '
            . 'cycle-178 design-system --radius-sm.');
        // Class applied to the <select>.
        $this->assertStringContainsString('mw-chosen-native', $src,
            'Chosen adapter MUST add mw-chosen-native class to the '
            . 'native <select> element.');
    }

    #[Test]
    public function all_adapters_register_via_jquery_extension_queue(): void
    {
        $files = [
            'masonry-vanilla-adapter.js' => 'packages/frontend-assets-libs/resources/local-libs/masonry-vanilla-adapter/masonry-vanilla-adapter.js',
            'native-datetimepicker-adapter.js' => 'packages/frontend-assets-libs/resources/local-libs/native-datetimepicker-adapter/native-datetimepicker-adapter.js',
            'native-chosen-adapter.js' => 'packages/frontend-assets-libs/resources/local-libs/native-chosen-adapter/native-chosen-adapter.js',
        ];
        foreach ($files as $name => $rel) {
            $src = $this->read($rel);
            $this->assertStringContainsString('__mwRegisterJqueryExtensions', $src,
                $name . ' MUST register via the cycle-181 '
                . '__mwRegisterJqueryExtensions[] queue if jQuery '
                . 'is missing at load time.');
            $this->assertMatchesRegularExpression(
                '/if\s*\(!registerAdapter\(\)\)\s*\{[\s\S]{0,300}__mwRegisterJqueryExtensions/m',
                $src,
                $name . ' MUST defer registration when jQuery is not '
                . 'available at load time.'
            );
        }
    }

    #[Test]
    public function captcha_replaces_jquery_dom_calls_with_vanilla(): void
    {
        $src = $this->read('Modules/Captcha/resources/views/templates/recaptcha.blade.php');
        // `$('#x').val(response)` replaced by vanilla.
        $this->assertStringNotContainsString("\$('#{{ \$input_id }}').val(response)", $src,
            'recaptcha.blade.php MUST NOT use jQuery $().val() to set '
            . 'the captcha token (replaced by document.getElementById '
            . '+ .value).');
        $this->assertStringContainsString("document.getElementById('{{ \$input_id }}')", $src,
            'recaptcha.blade.php MUST use document.getElementById to '
            . 'find the captcha input element.');

        // `$(document).ready` replaced by DOMContentLoaded.
        $this->assertStringContainsString("document.addEventListener('DOMContentLoaded'", $src,
            'recaptcha.blade.php MUST use DOMContentLoaded instead '
            . 'of jQuery $(document).ready.');

        // `$.getScript(...)` replaced by dynamic <script> element.
        $this->assertStringNotContainsString('$.getScript(', $src,
            'recaptcha.blade.php MUST NOT use jQuery $.getScript — '
            . 'replaced by dynamic <script> element with onload '
            . 'callback.');
        $this->assertStringContainsString("document.createElement('script')", $src,
            'recaptcha.blade.php MUST dynamically load the reCAPTCHA '
            . 'API via document.createElement("script").');
    }

    #[Test]
    public function captcha_v3_attach_uses_vanilla_dom_lookup(): void
    {
        $src = $this->read('Modules/Captcha/resources/views/templates/recaptcha.blade.php');
        // captcha_el = document.getElementById instead of $('#x')[0]
        $this->assertMatchesRegularExpression(
            '/captcha_el\s*=\s*document\.getElementById\(/m',
            $src,
            'recaptcha.blade.php V3 attach MUST use '
            . 'document.getElementById to fetch the captcha input '
            . '(was $(\'#x\') with [0] indexing).'
        );
        // firstParentWithTag now gets the DOM node directly (not
        // via $().0 indexing).
        $this->assertMatchesRegularExpression(
            '/mw\.tools\.firstParentWithTag\(captcha_el,\s*[\'"]form[\'"]\)/m',
            $src,
            'recaptcha.blade.php MUST pass the raw DOM element to '
            . 'firstParentWithTag (not the [0]-indexed jQuery '
            . 'collection).'
        );
    }

    #[Test]
    public function built_adapters_present_at_public_paths(): void
    {
        $bases = [
            'masonry-vanilla-adapter/masonry-vanilla-adapter.js',
            'native-datetimepicker-adapter/native-datetimepicker-adapter.js',
            'native-chosen-adapter/native-chosen-adapter.js',
        ];
        foreach ($bases as $rel) {
            $path = base_path('public/vendor/microweber-packages/frontend-assets-libs/' . $rel);
            $this->assertFileExists($path,
                'Built bundle MUST contain ' . $rel . ' at the '
                . 'public-asset path so mw.lib.require() can load it.');
        }

        // Old jQuery plugin libs are still present (un-deleted —
        // they're just no longer loaded by api_settings.js). Phase
        // B4 / future cleanup may delete them.
    }

    #[Test]
    public function all_adapters_have_load_guard_against_double_register(): void
    {
        $files = [
            'masonry-vanilla-adapter.js' => 'packages/frontend-assets-libs/resources/local-libs/masonry-vanilla-adapter/masonry-vanilla-adapter.js',
            'native-datetimepicker-adapter.js' => 'packages/frontend-assets-libs/resources/local-libs/native-datetimepicker-adapter/native-datetimepicker-adapter.js',
            'native-chosen-adapter.js' => 'packages/frontend-assets-libs/resources/local-libs/native-chosen-adapter/native-chosen-adapter.js',
        ];
        $guards = [
            'masonry-vanilla-adapter.js' => '__mwMasonryAdapterLoaded',
            'native-datetimepicker-adapter.js' => '__mwNativeDatetimepickerAdapterLoaded',
            'native-chosen-adapter.js' => '__mwNativeChosenAdapterLoaded',
        ];
        foreach ($files as $name => $rel) {
            $src = $this->read($rel);
            $guard = $guards[$name];
            $this->assertStringContainsString($guard, $src,
                $name . ' MUST set window.' . $guard . ' = true to '
                . 'guard against double registration.');
        }
    }
}
