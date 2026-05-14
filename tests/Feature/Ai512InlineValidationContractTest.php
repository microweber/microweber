<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * AI-512 — Inline form validation: auto-scroll + consistent error styling.
 *
 * Audit task 1.3.2 asked for four changes. This slice ships the two
 * that fit a bounded JS+CSS scope:
 *   A. Auto-scroll-to-first-error JS in microweber-filament-theme.js
 *      (capturing click on `<button type="submit">` → 250ms wait →
 *      smooth scroll the `.fi-fo-field` containing the first
 *      `[data-validation-error]` element → focus its input).
 *   B. Consistent error styling CSS in mobile-touch.css
 *      (#dc2626 red border + alert-icon `::before` + soft red glow,
 *      with Tailwind red-400 dark-mode variant).
 *
 * Deferred to AI-512a/b/c follow-ups (documented in the CSS comment):
 *   - Per-resource `->live(onBlur: true)` opt-in for true blur
 *     validation (sweep dispatch needed)
 *   - Per-field validation message refactor with specific messages
 *   - Field-level helper text via `->helperText('...')`
 *
 * Style: file-system reads only, no DB / Filament boot.
 */
class Ai512InlineValidationContractTest extends TestCase
{
    private const MOBILE_TOUCH_CSS = 'packages/microweber-filament-theme/resources/assets/css/microweber/mobile-touch.css';
    private const THEME_JS          = 'packages/microweber-filament-theme/resources/assets/js/microweber-filament-theme.js';

    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    private function ai512CssBlock(): string
    {
        $css = $this->read(self::MOBILE_TOUCH_CSS);
        $start = strpos($css, 'AI-512 — Consistent error styling');
        $this->assertNotFalse($start, 'mobile-touch.css must contain the AI-512 CSS marker comment.');
        return substr($css, $start);
    }

    private function ai512JsBlock(): string
    {
        $js = $this->read(self::THEME_JS);
        $start = strpos($js, 'AI-512 — Auto-scroll');
        $this->assertNotFalse($start, 'microweber-filament-theme.js must contain the AI-512 JS marker comment.');
        return substr($js, $start);
    }

    // --- A. Auto-scroll JS sister-slice -----------------------------

    #[Test]
    public function ai512_js_marker_is_present(): void
    {
        $block = $this->ai512JsBlock();
        $this->assertStringContainsString('AI-512 — Auto-scroll', $block);
    }

    /**
     * Shape facts the AI-512 JS must contain.
     */
    public static function jsShapeFactsProvider(): array
    {
        return [
            'admin panel scope'        => ['fi-panel-admin'],
            'checkout panel scope'     => ['fi-panel-checkout'],
            'submit-button selector'   => ['button[type="submit"]'],
            'capture phase listener'   => ["'click', function (e) {"],
            'data-validation-error attr' => ['[data-validation-error]'],
            'fi-fo-field wrapper'      => ['.fi-fo-field'],
            'smooth scroll'            => ['scrollIntoView'],
            'IIFE wrapper'             => ['mwScrollToFirstValidationError'],
            'strict mode'              => ["'use strict'"],
        ];
    }

    #[Test]
    #[DataProvider('jsShapeFactsProvider')]
    public function ai512_js_block_contains_each_shape_fact(string $needle): void
    {
        $block = $this->ai512JsBlock();
        $this->assertStringContainsString(
            $needle,
            $block,
            "AI-512 JS block must contain `{$needle}`."
        );
    }

    #[Test]
    public function ai512_js_uses_capture_phase_listener(): void
    {
        $block = $this->ai512JsBlock();

        // The third argument to addEventListener must be `true`
        // (capture phase) so the listener fires before Livewire's
        // own click handler swaps the DOM. Pin that.
        $this->assertMatchesRegularExpression(
            '/document\.addEventListener\(\s*\'click\',.*?\},\s*true\s*\)/s',
            $block,
            'AI-512 click listener must use capture phase (third arg = true).'
        );
    }

    #[Test]
    public function ai512_js_does_not_use_morph_or_keystroke_hooks(): void
    {
        $block = $this->ai512JsBlock();

        // Regression guard — the design decision is "scroll on
        // submit, not on every keystroke". Pinning that we did not
        // accidentally wire a morph hook or keyup listener.
        $this->assertStringNotContainsString(
            'Livewire.hook',
            $block,
            'AI-512 must not use Livewire morph hooks (would scroll on every keystroke when live-validation is on).'
        );
        $this->assertStringNotContainsString(
            "'keyup'",
            $block,
            'AI-512 must not listen to keyup events.'
        );
    }

    // --- B. Consistent error styling CSS sister-slice ---------------

    #[Test]
    public function ai512_css_marker_is_present(): void
    {
        $block = $this->ai512CssBlock();
        $this->assertStringContainsString('AI-512 — Consistent error styling', $block);
    }

    /**
     * Shape facts the AI-512 CSS must contain.
     */
    public static function cssShapeFactsProvider(): array
    {
        return [
            // Light-mode error styling.
            'red color #dc2626'           => ['color: #dc2626'],
            'data-validation-error attr'  => ['[data-validation-error]'],
            'admin panel scope'           => ['body.fi-panel-admin [data-validation-error]'],
            'checkout panel scope'        => ['body.fi-panel-checkout [data-validation-error]'],
            'alert icon ::before content' => ["content: '!'"],
            'icon disc background'        => ['background-color: #dc2626'],
            'icon disc border-radius 50%' => ['border-radius: 50%'],

            // Input border with :has()
            ':has() field selector'       => [':has([data-validation-error])'],
            'red input border !important' => ['border-color: #dc2626 !important'],
            'soft red glow'               => ['box-shadow: 0 0 0 1px rgba(220, 38, 38, 0.15)'],

            // Dark-mode variants.
            'dark-mode red-400'           => ['color: #f87171'],
            'dark-mode icon'              => ['background-color: #f87171'],
            'dark-mode input border'      => ['border-color: #f87171 !important'],
        ];
    }

    #[Test]
    #[DataProvider('cssShapeFactsProvider')]
    public function ai512_css_block_contains_each_shape_fact(string $needle): void
    {
        $block = $this->ai512CssBlock();
        $this->assertStringContainsString(
            $needle,
            $block,
            "AI-512 CSS block must contain `{$needle}`."
        );
    }

    #[Test]
    public function ai512_css_does_not_use_modal_or_public_panel(): void
    {
        $block = $this->ai512CssBlock();

        // Regression guard — public storefront forms have separate
        // jQuery-era validation. AI-512 must not bleed into them.
        // Also no modal-scoped rules (the modal-internal form layer
        // is owned by AI-211 / AI-240 / AI-307 follow-ups).
        $this->assertStringNotContainsString(
            'body:not(.fi-panel-admin)',
            $block,
            'AI-512 must not target non-Filament-panel surfaces.'
        );
    }

    #[Test]
    public function ai512_css_red_matches_tailwind_red_600(): void
    {
        $block = $this->ai512CssBlock();

        // Tailwind red-600 = #dc2626. Anchor it so future commits
        // don't silently drift to red-500 (#ef4444) or red-700 (#b91c1c).
        $this->assertStringContainsString('#dc2626', $block);
        $this->assertStringNotContainsString('#ef4444', $block);
        $this->assertStringNotContainsString('#b91c1c', $block);
    }

    #[Test]
    public function ai512_css_dark_mode_red_matches_tailwind_red_400(): void
    {
        $block = $this->ai512CssBlock();

        // Tailwind red-400 = #f87171. Lighter shade for dark-mode
        // contrast against the panel background. Anchor it.
        $this->assertStringContainsString('#f87171', $block);
    }
}
