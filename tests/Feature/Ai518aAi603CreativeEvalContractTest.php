<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * AI-518a (CRITICAL) — Header CTA `.btn.btn-sm` broader selector.
 * AI-603 (HIGH) — Slider `.slider-button` 44h floor.
 *
 * Both items raised in tester CREATIVE EVAL 2026-05-15 with runtime
 * Playwright measurements at 390×844.
 *
 * AI-518a context: the original AI-518 rule (line 721 of public-touch.css)
 * targets `#header-layout-btn` + `.templates-top-header-menu .btn.btn-sm`.
 * Tester confirmed the rendered DOM at 390×844 carries neither wrapper
 * class nor the id, so the existing rule misses — header CTA measures
 * 137×38 px. Fix broadens to bare `.btn.btn-sm` at viewports ≤575.98px
 * only (narrow phone-sized scope to limit broad-blast risk).
 *
 * AI-603 context: slider templates DO render the anchor with class
 * `slider-button btn btn-primary` (recon SOUL #72), but each slide's
 * inline `<style>` block declares
 * `#js-slider-{id} .swiper-slide-{slide-id} .slider-button { padding: 8px 20px; }`
 * — id + 2 classes specificity beats bare `.btn`. The fix adds
 * `.slider-button { min-height: 44px; }` (additive — neither Bootstrap
 * `.btn` nor the inline override sets min-height).
 *
 * Style: file-system reads only, no DB / Filament boot.
 */
class Ai518aAi603CreativeEvalContractTest extends TestCase
{
    private const PUBLIC_TOUCH_CSS = 'Templates/Bootstrap/resources/assets/css/public-touch.css';
    private const SERVED_TOUCH_CSS = 'public/templates/bootstrap/css/public-touch.css';

    private string $css;

    protected function setUp(): void
    {
        parent::setUp();
        $this->css = file_get_contents(base_path(self::PUBLIC_TOUCH_CSS));
    }

    #[Test]
    public function ai518a_marker_present_with_critical_rationale(): void
    {
        $this->assertStringContainsString('AI-518a', $this->css);
        $this->assertStringContainsString('CRITICAL', $this->css);
    }

    #[Test]
    public function ai518a_rule_floors_btn_sm_in_narrow_viewport_media(): void
    {
        // Match the @media + rule pair structurally: the @media opener
        // must be ≤575.98px (narrow scope per tester recommendation,
        // limits broad-blast risk vs the project-standard 1023.98px).
        $this->assertMatchesRegularExpression(
            '/@media\s*\(\s*max-width:\s*575\.98px\s*\)\s*\{[^}]*\.btn\.btn-sm\s*\{[^}]*min-height:\s*44px;[^}]*\}/s',
            $this->css,
            'AI-518a: @media (max-width: 575.98px) must contain a `.btn.btn-sm { min-height: 44px; }` rule'
        );
    }

    #[Test]
    public function ai603_marker_present_with_high_rationale(): void
    {
        $this->assertStringContainsString('AI-603', $this->css);
        $this->assertStringContainsString('Slider', $this->css);
        $this->assertStringContainsString('inline `<style>`', $this->css);
    }

    #[Test]
    public function ai603_slider_button_floors_44_height(): void
    {
        // Direct `.slider-button { min-height: 44px; }` declaration —
        // class-only selector. The inline-style override the tester
        // identified does NOT set min-height, so our rule applies
        // without specificity conflict.
        $this->assertMatchesRegularExpression(
            '/(?<![.\w-])\.slider-button\s*\{[^}]*min-height:\s*44px;[^}]*\}/s',
            $this->css,
            'AI-603: a top-level `.slider-button { min-height: 44px; }` rule must be present'
        );
    }

    #[Test]
    public function ai603_anchor_in_slider_templates_uses_slider_button_class(): void
    {
        // Anchor: confirm both slider templates render an element with
        // class "slider-button" so the rule reaches the rendered DOM.
        $default = file_get_contents(base_path('Modules/Slider/resources/views/templates/default.blade.php'));
        $swiper  = file_get_contents(base_path('Modules/Slider/resources/views/templates/swiper-skin-1.blade.php'));
        $this->assertStringContainsString(
            'class="slider-button btn btn-primary',
            $default,
            'AI-603 anchor: Slider default template must render `<a class="slider-button btn btn-primary ...">`'
        );
        $this->assertStringContainsString(
            'class="slider-button btn btn-primary',
            $swiper,
            'AI-603 anchor: Slider swiper-skin-1 template must render `<a class="slider-button btn btn-primary ...">`'
        );
    }

    #[Test]
    public function ai518a_anchor_btn_module_renders_btn_with_size_class(): void
    {
        // Anchor: confirm the Btn module's bootstrap.blade.php (included
        // by default.blade.php) renders the header CTA with `.btn` +
        // `$size` injection, so when `button_size="btn-sm"` is passed
        // (as in `layouts/templates/menus/skin-1.blade.php` line 43)
        // the rendered element carries `.btn.btn-sm` and the AI-518a
        // broader rule reaches it.
        $btnBootstrap = file_get_contents(base_path('Modules/Btn/resources/views/templates/bootstrap.blade.php'));
        $this->assertStringContainsString(
            'class="btn {{ $style',
            $btnBootstrap,
            'AI-518a anchor: Btn module bootstrap template must render `.btn` + `$style` + `$size` class injection (carries .btn-sm when configured)'
        );
    }

    #[Test]
    public function served_mirror_is_byte_identical_with_source(): void
    {
        $source = file_get_contents(base_path(self::PUBLIC_TOUCH_CSS));
        $served = file_get_contents(base_path(self::SERVED_TOUCH_CSS));
        $this->assertSame(
            $source,
            $served,
            'public-touch.css served mirror must be byte-identical with the source'
        );
    }
}
