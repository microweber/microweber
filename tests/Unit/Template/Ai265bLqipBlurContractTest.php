<?php

namespace Tests\Unit\Template;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/*
 * AI-265b LQIP blur-up effect contract test (task-2026-05-13-56ea16).
 *
 * Builds on AI-265's first-slice colored-placeholder wrapper. The
 * blur-up CSS animates each `<img>` inside `.mw-product-card-image-
 * placeholder` from blur(8px)+opacity:0 to blur(0)+opacity:1 over
 * 400ms on every render. Pure CSS — no JS load-event listener, no
 * pipeline, no new dependency.
 *
 * Pinned shape:
 *   - rule wrapped in `@media (prefers-reduced-motion: no-preference)`
 *     so motion-sensitive users keep the static colored placeholder
 *   - selector targets BOTH `.placeholder > img` (current emission) and
 *     `.placeholder picture > img` (future WebP pipeline)
 *   - 400ms ease-out animation, single iteration, fill mode `both` so
 *     the end state persists
 *   - keyframe `from` carries blur(8px) AND opacity:0
 *   - keyframe `to` carries blur(0) AND opacity:1
 *   - `will-change: opacity, filter` hint present so the blur is
 *     hardware-accelerated (without it Chrome falls back to a
 *     low-quality software blur)
 *   - rule does NOT touch margin/padding/transform-translate or box
 *     dimensions (no CLS — AC #3)
 *   - served mirror byte-matches canonical
 *   - regression guard: the AI-265 first-slice
 *     `background-color: var(--color-surface-raised)` placeholder
 *     rule is still in place, so reduced-motion users keep the
 *     no-flash benefit
 */
class Ai265bLqipBlurContractTest extends TestCase
{
    private const PUBLIC_TOUCH_CSS = __DIR__ . '/../../../Templates/Bootstrap/resources/assets/css/public-touch.css';
    private const PUBLIC_TOUCH_CSS_SERVED = __DIR__ . '/../../../public/templates/bootstrap/css/public-touch.css';

    #[Test]
    public function blur_up_rule_is_gated_by_prefers_reduced_motion_no_preference(): void
    {
        $css = $this->readFile(self::PUBLIC_TOUCH_CSS);

        $this->assertMatchesRegularExpression(
            '/@media\s*\(prefers-reduced-motion:\s*no-preference\)\s*\{[^@]*\.mw-product-card-image-placeholder\s*>\s*img,\s*\.mw-product-card-image-placeholder\s+picture\s*>\s*img\s*\{[^}]*animation:\s*mw-product-card-image-blur-up\b/s',
            $css,
            'AI-265b must wrap the blur-up rule in @media (prefers-reduced-motion: no-preference) so motion-sensitive users keep the static placeholder.'
        );
    }

    #[Test]
    public function blur_up_rule_uses_400ms_ease_out_single_iteration_both_fill(): void
    {
        $css = $this->readFile(self::PUBLIC_TOUCH_CSS);

        $this->assertMatchesRegularExpression(
            '/animation:\s*mw-product-card-image-blur-up\s+400ms\s+ease-out\s+1\s+both\s*;/',
            $css,
            'AI-265b animation shorthand must be exactly `mw-product-card-image-blur-up 400ms ease-out 1 both;` — drift on duration / easing / fill-mode would change the perceived performance feel.'
        );
    }

    #[Test]
    public function will_change_hints_opacity_and_filter_for_hardware_acceleration(): void
    {
        $css = $this->readFile(self::PUBLIC_TOUCH_CSS);

        $this->assertMatchesRegularExpression(
            '/\.mw-product-card-image-placeholder\s*>\s*img,\s*\.mw-product-card-image-placeholder\s+picture\s*>\s*img\s*\{[^}]*will-change:\s*opacity,\s*filter/s',
            $css,
            'AI-265b must include `will-change: opacity, filter` so the blur is hardware-accelerated — without it Chrome falls back to a low-quality software blur during the animation.'
        );
    }

    #[Test]
    public function keyframes_define_blur_8px_to_blur_0_with_opacity_fade(): void
    {
        $css = $this->readFile(self::PUBLIC_TOUCH_CSS);

        $this->assertMatchesRegularExpression(
            '/@keyframes\s+mw-product-card-image-blur-up\s*\{[\s\S]*?from\s*\{[^}]*opacity:\s*0[^}]*filter:\s*blur\(8px\)[\s\S]*?to\s*\{[^}]*opacity:\s*1[^}]*filter:\s*blur\(0\)/s',
            $css,
            'AI-265b @keyframes mw-product-card-image-blur-up must define from { opacity:0; filter:blur(8px); } to { opacity:1; filter:blur(0); }.'
        );
    }

    #[Test]
    public function blur_up_rule_does_not_touch_layout_properties_no_cls(): void
    {
        $css = $this->readFile(self::PUBLIC_TOUCH_CSS);

        // Extract the @keyframes block and verify it does NOT contain
        // any property that would cause layout shift during the
        // animation (AC #3: CLS = 0).
        $matched = preg_match('/@keyframes\s+mw-product-card-image-blur-up\s*\{([^}]*\{[^}]*\}[^}]*\{[^}]*\}[^}]*)\}/s', $css, $matches);
        $this->assertSame(1, $matched, 'mw-product-card-image-blur-up keyframes block must be extractable.');

        $keyframesBody = $matches[1];

        foreach ([
            'margin',
            'padding',
            'width',
            'height',
            'top',
            'left',
            'right',
            'bottom',
            'transform: translate',
            'transform: scale',
        ] as $forbiddenProp) {
            $this->assertStringNotContainsString(
                $forbiddenProp,
                $keyframesBody,
                "AI-265b keyframes must NOT animate `{$forbiddenProp}` — that would cause CLS during the blur-up. Only opacity + filter are allowed."
            );
        }
    }

    #[Test]
    public function ai_265_first_slice_color_placeholder_is_not_regressed(): void
    {
        $css = $this->readFile(self::PUBLIC_TOUCH_CSS);

        // The AI-265 first-slice rule must remain intact so
        // reduced-motion users (who skip the blur animation) still
        // get the no-CLS / no-white-flash colored placeholder
        // underneath every image.
        $this->assertMatchesRegularExpression(
            '/\.mw-product-card-image-placeholder\s*\{[^}]*background-color:\s*var\(--color-surface-raised\)/s',
            $css,
            'AI-265b must NOT regress the AI-265 first-slice `.mw-product-card-image-placeholder { background-color: var(--color-surface-raised) }` rule — reduced-motion users depend on it for the no-flash benefit.'
        );
    }

    #[Test]
    public function served_public_touch_mirrors_canonical_byte_for_byte(): void
    {
        if (!file_exists(self::PUBLIC_TOUCH_CSS_SERVED)) {
            $this->markTestSkipped('Served public-touch.css missing.');
        }

        $canonical = file_get_contents(self::PUBLIC_TOUCH_CSS);
        $served    = file_get_contents(self::PUBLIC_TOUCH_CSS_SERVED);

        $this->assertSame(
            $canonical,
            $served,
            'Served public/templates/bootstrap/css/public-touch.css must byte-match canonical so production traffic gets the blur-up rule.'
        );
    }

    private function readFile(string $path): string
    {
        $real = realpath($path);
        $this->assertNotFalse($real, "File not found: {$path}");

        $contents = file_get_contents($real);
        $this->assertNotFalse($contents, "Could not read: {$path}");
        $this->assertNotEmpty($contents, "File is empty: {$path}");

        return $contents;
    }
}
