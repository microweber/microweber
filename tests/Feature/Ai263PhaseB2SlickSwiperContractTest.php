<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-182 / AI-263 Phase B2 (2026-05-11) — Slick → Swiper
 * compatibility adapter.
 *
 * Phase A audit (cycle-180) inventoried 22 blade-template module
 * skins that call jQuery `$.fn.slick(options)` (Teamcard,
 * Testimonials, Post, Product, Pictures). Phase B1 (cycle-181)
 * made jQuery conditional. Phase B2 (cycle-182) replaces the
 * Slick library JS with a Swiper-backed compatibility adapter:
 *
 *   1. `mw.lib.require('slick')` no longer loads `slick/slick.js`
 *      (the 60KB+ Slick library) and no longer loads
 *      `slick/mw-slick.js` (the data-slick attribute wrapper —
 *      its functionality is baked into the adapter). Instead it
 *      loads:
 *        - `slick/slick.css` (kept — original CSS for dots/arrows)
 *        - `slick/slick-theme.css` (kept)
 *        - `swiper/swiper-bundle.min.css` (peer dep)
 *        - `swiper/swiper-bundle.min.js` (peer dep)
 *        - `slick-to-swiper-adapter/slick-to-swiper-adapter.js`
 *          (new)
 *
 *   2. The adapter defines `$.fn.slick(optsOrCommand, arg)` that:
 *        - Translates Slick options → Swiper options (slidesToShow
 *          → slidesPerView, dots → pagination, arrows → navigation,
 *          autoplay → autoplay.delay, infinite → loop, etc.)
 *        - Reads `data-slick` HTML attribute (the original
 *          mw-slick.js feature, baked in here so the adapter is
 *          the sole owner of `$.fn.slick`)
 *        - Wraps the container with Swiper structure
 *          (`.swiper`, `.swiper-wrapper`, `.swiper-slide`)
 *        - Stores the Swiper instance on `element.__mwSwiperInstance`
 *          for imperative-API calls
 *
 *   3. Imperative API translated:
 *        - `.slick('slickGoTo', n)`   → swiper.slideTo(n)
 *        - `.slick('slickNext')`       → swiper.slideNext()
 *        - `.slick('slickPrev')`       → swiper.slidePrev()
 *        - `.slick('slickPause'/'slickPlay')` → autoplay control
 *        - `.slick('unslick')`         → destroy
 *
 *   4. If Swiper isn't loaded yet at `.slick()` call time, the
 *      init is queued and re-played once Swiper finishes loading.
 *
 *   5. If jQuery isn't loaded yet, the adapter pushes itself into
 *      the cycle-181 `window.__mwRegisterJqueryExtensions[]` queue.
 *
 * Browser-verified at /?nocache=ai263b2w (Bootstrap template,
 * jQuery loaded via cycle-181 opt-in flag):
 *   .slick({...})            → Swiper instance constructed
 *   .slick('slickGoTo', 2)   → swiper.activeIndex === 2
 *   Container classes        → "swiper swiper-initialized
 *                                swiper-horizontal
 *                                swiper-backface-hidden"
 */
class Ai263PhaseB2SlickSwiperContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function source_carries_cycle_182_anchor(): void
    {
        $adapter = $this->read('packages/frontend-assets-libs/resources/local-libs/slick-to-swiper-adapter/slick-to-swiper-adapter.js');
        $settings = $this->read('src/MicroweberPackages/App/resources/includes/api/api_settings.js');
        $libs = $this->read('packages/frontend-assets/resources/assets/api-core/core/core/libs.js');

        $this->assertMatchesRegularExpression('/[Cc]ycle-182/', $adapter,
            'slick-to-swiper-adapter.js MUST carry the cycle-182 anchor.');
        $this->assertStringContainsString('AI-263 Phase B2', $adapter,
            'slick-to-swiper-adapter.js MUST carry the AI-263 Phase B2 anchor.');
        $this->assertStringContainsString('AI-263 Phase B2', $settings,
            'api_settings.js MUST carry the AI-263 Phase B2 anchor.');
        $this->assertStringContainsString('AI-263 Phase B2', $libs,
            'api-core/core/core/libs.js MUST carry the AI-263 Phase B2 anchor.');
    }

    #[Test]
    public function api_settings_slick_lib_swaps_to_adapter(): void
    {
        $settings = $this->read('src/MicroweberPackages/App/resources/includes/api/api_settings.js');

        // Slick library JS NO LONGER loaded — Swiper + adapter
        // take its place.
        $this->assertStringNotContainsString("'slick/slick.js'", $settings,
            'api_settings.js MUST NOT load slick/slick.js — the '
            . 'Swiper-backed adapter replaces the Slick library.');
        $this->assertStringNotContainsString("'slick/mw-slick.js'", $settings,
            'api_settings.js MUST NOT load slick/mw-slick.js — the '
            . 'data-slick attribute parser is baked into the adapter '
            . 'so the adapter is the sole owner of $.fn.slick (avoids '
            . 'double-wrap arg-swallow bugs).');

        // Swiper + adapter ARE loaded.
        $this->assertStringContainsString('swiper/swiper-bundle.min.js', $settings,
            'api_settings.js MUST load swiper/swiper-bundle.min.js '
            . 'so the adapter has Swiper available at runtime.');
        $this->assertStringContainsString('slick-to-swiper-adapter/slick-to-swiper-adapter.js', $settings,
            'api_settings.js MUST load the slick-to-swiper-adapter '
            . 'so $.fn.slick gets wired to Swiper.');

        // Slick CSS IS kept — module skins still use .slick-dots, etc.
        $this->assertStringContainsString("'slick/slick.css'", $settings,
            'api_settings.js MUST keep slick/slick.css load — module '
            . 'skins reference .slick-dots / .slick-prev / .slick-next '
            . 'class names and existing CSS must continue to apply.');
    }

    #[Test]
    public function adapter_defines_jquery_fn_slick_with_dual_arg_signature(): void
    {
        $src = $this->read('packages/frontend-assets-libs/resources/local-libs/slick-to-swiper-adapter/slick-to-swiper-adapter.js');

        // The plugin signature MUST accept (options OR command, arg)
        // so the imperative API (e.g. .slick('slickGoTo', 2)) works.
        $this->assertMatchesRegularExpression(
            '/\$\.fn\.slick\s*=\s*function\s*\(\s*optsOrCommand,\s*arg\s*\)/m',
            $src,
            'Adapter MUST define $.fn.slick = function(optsOrCommand, arg) '
            . 'so .slick("slickGoTo", n) reaches the imperative API '
            . 'with both arguments (mw-slick.js wrapper used to swallow '
            . 'the second arg).'
        );
        // String branch routes to imperativeApiCall.
        $this->assertMatchesRegularExpression(
            '/if\s*\(\s*typeof\s+optsOrCommand\s*===\s*[\'"]string[\'"]\s*\)\s*\{\s*return\s+imperativeApiCall/m',
            $src,
            'Adapter MUST route string commands to imperativeApiCall '
            . 'so .slick("slickGoTo"/"slickNext"/"unslick"/etc.) work.'
        );
        // Install guard so the adapter doesn't double-register.
        $this->assertStringContainsString('__mwSwiperAdapterInstalled', $src,
            'Adapter MUST set $.fn.__mwSwiperAdapterInstalled = true '
            . 'so multiple `mw.lib.require("slick")` calls do NOT '
            . 'cause double registration.');
    }

    #[Test]
    public function adapter_maps_common_slick_options_to_swiper(): void
    {
        $src = $this->read('packages/frontend-assets-libs/resources/local-libs/slick-to-swiper-adapter/slick-to-swiper-adapter.js');

        $mappings = [
            'slidesToShow' => 'slidesPerView',
            'slidesToScroll' => 'slidesPerGroup',
            'infinite' => 'loop',
            'adaptiveHeight' => 'autoHeight',
            'centerMode' => 'centeredSlides',
            'autoplaySpeed' => 'delay',
        ];
        foreach ($mappings as $slick => $swiper) {
            $this->assertStringContainsString($slick, $src,
                "Adapter MUST read the Slick `{$slick}` option.");
            $this->assertStringContainsString($swiper, $src,
                "Adapter MUST emit the Swiper `{$swiper}` option.");
        }

        // Responsive breakpoint translation.
        $this->assertMatchesRegularExpression(
            '/Array\.isArray\(slick\.responsive\)[\s\S]{0,500}swiperOpts\.breakpoints/m',
            $src,
            'Adapter MUST translate the Slick `responsive[]` array '
            . 'to Swiper `breakpoints{}` object (Slick uses MAX-width '
            . 'thresholds; Swiper uses MIN-width — inversion is the '
            . 'tricky part).'
        );
    }

    #[Test]
    public function adapter_implements_imperative_api(): void
    {
        $src = $this->read('packages/frontend-assets-libs/resources/local-libs/slick-to-swiper-adapter/slick-to-swiper-adapter.js');

        $commands = [
            'slickGoTo' => 'slideTo',
            'slickNext' => 'slideNext',
            'slickPrev' => 'slidePrev',
            'slickPause' => 'autoplay.stop',
            'slickPlay' => 'autoplay.start',
            'unslick' => 'destroy',
        ];
        foreach ($commands as $slick => $swiper) {
            $this->assertStringContainsString($slick, $src,
                "Adapter MUST handle the Slick imperative '{$slick}' command.");
        }

        // slideTo + parseInt for the index argument.
        $this->assertMatchesRegularExpression(
            '/slickGoTo[\s\S]{0,200}swiper\.slideTo\(parseInt\(arg/m',
            $src,
            'Adapter MUST call swiper.slideTo(parseInt(arg, 10)) on '
            . '.slick("slickGoTo", n) so string-indexes from '
            . 'event-handler `data-index` attributes work.'
        );
    }

    #[Test]
    public function adapter_bakes_data_slick_attribute_parser(): void
    {
        $src = $this->read('packages/frontend-assets-libs/resources/local-libs/slick-to-swiper-adapter/slick-to-swiper-adapter.js');

        // The data-slick HTML attribute parser is baked into the
        // adapter (was previously mw-slick.js).
        $this->assertStringContainsString("data-slick", $src,
            'Adapter MUST parse the data-slick HTML attribute '
            . '(baked in from mw-slick.js — module skins like '
            . 'Pictures use <div class="slickslider" data-slick=\'...\'>).');
        $this->assertMatchesRegularExpression(
            '/JSON\.parse\(frag\.innerHTML\)/m',
            $src,
            'Adapter MUST use the defensive innerHTML→JSON.parse '
            . 'pattern from the original mw-slick.js to handle '
            . 'HTML-entity-encoded JSON in the data-slick attribute.'
        );
    }

    #[Test]
    public function adapter_defers_when_swiper_or_jquery_missing(): void
    {
        $src = $this->read('packages/frontend-assets-libs/resources/local-libs/slick-to-swiper-adapter/slick-to-swiper-adapter.js');

        // queueInit deferral when Swiper not yet loaded.
        $this->assertMatchesRegularExpression(
            '/if\s*\(!window\.Swiper\)\s*\{\s*[\s\S]{0,200}queueInit\(this,\s*swiperOptions\);/m',
            $src,
            'Adapter MUST queue .slick() calls when Swiper has not '
            . 'finished loading yet, then re-play them once Swiper '
            . 'is available.'
        );
        // Register-deferred path when jQuery not yet loaded.
        $this->assertMatchesRegularExpression(
            '/if\s*\(!registerAdapter\(\)\)\s*\{[\s\S]{0,300}__mwRegisterJqueryExtensions/m',
            $src,
            'Adapter MUST register itself in the cycle-181 '
            . 'window.__mwRegisterJqueryExtensions[] queue if jQuery '
            . 'is missing at script load time, so it gets installed '
            . 'after the late-bootstrap script loads jQuery.'
        );
    }

    #[Test]
    public function adapter_aliases_slick_class_names_on_swiper_elements(): void
    {
        $src = $this->read('packages/frontend-assets-libs/resources/local-libs/slick-to-swiper-adapter/slick-to-swiper-adapter.js');

        // Pagination element gets `swiper-pagination slick-dots`
        // both classes so existing CSS keeps working.
        $this->assertStringContainsString("'swiper-pagination slick-dots'", $src,
            'Adapter MUST add both swiper-pagination AND slick-dots '
            . 'classes to the pagination element so module-skin CSS '
            . 'that targets .slick-dots continues to apply.');
        $this->assertStringContainsString("'swiper-button-next slick-next'", $src,
            'Adapter MUST add both swiper-button-next AND slick-next '
            . 'classes to the next-arrow button.');
        $this->assertStringContainsString("'swiper-button-prev slick-prev'", $src,
            'Adapter MUST add both swiper-button-prev AND slick-prev '
            . 'classes to the prev-arrow button.');
    }

    #[Test]
    public function adapter_load_guard_prevents_double_registration(): void
    {
        $src = $this->read('packages/frontend-assets-libs/resources/local-libs/slick-to-swiper-adapter/slick-to-swiper-adapter.js');

        $this->assertStringContainsString('__mwSlickToSwiperAdapterLoaded', $src,
            'Adapter MUST set window.__mwSlickToSwiperAdapterLoaded '
            . '= true and short-circuit on subsequent loads.');
        $this->assertMatchesRegularExpression(
            '/if\s*\(window\.__mwSlickToSwiperAdapterLoaded\)\s*return/m',
            $src,
            'Adapter MUST short-circuit when '
            . '__mwSlickToSwiperAdapterLoaded is already set so it '
            . 'never registers twice.'
        );
    }

    #[Test]
    public function built_bundle_carries_adapter_at_public_path(): void
    {
        $rel = 'public/vendor/microweber-packages/frontend-assets-libs/slick-to-swiper-adapter/slick-to-swiper-adapter.js';
        $path = base_path($rel);
        $this->assertFileExists($path,
            'Built bundle MUST contain slick-to-swiper-adapter.js at '
            . 'the public-asset path so mw.lib.require("slick") can '
            . 'load it via mw.settings.libs_url.');
        $built = file_get_contents($path);
        $this->assertStringContainsString('__mwSlickToSwiperAdapterLoaded', $built,
            'Built adapter MUST carry the load-guard identifier.');
        $this->assertStringContainsString('mapSlickToSwiper', $built,
            'Built adapter MUST carry the mapSlickToSwiper function '
            . '(the option-translation table).');
        $this->assertStringContainsString('imperativeApiCall', $built,
            'Built adapter MUST carry the imperativeApiCall handler '
            . '(the .slick("slickGoTo", n) entry point).');
    }

    #[Test]
    public function swiper_bundle_present_at_public_path(): void
    {
        $rel = 'public/vendor/microweber-packages/frontend-assets-libs/swiper/swiper-bundle.min.js';
        $path = base_path($rel);
        $this->assertFileExists($path,
            'Swiper bundle MUST be present at the public-asset path '
            . 'so the adapter can load it as a peer dependency.');
        $css = base_path('public/vendor/microweber-packages/frontend-assets-libs/swiper/swiper-bundle.min.css');
        $this->assertFileExists($css,
            'Swiper bundle CSS MUST also be present.');
    }
}
