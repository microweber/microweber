<?php

namespace Tests\Unit\Template;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/*
 * AI-292 Form Elements contract test.
 *
 * Pins the form-element design tokens (input height, border, radius,
 * padding, focus shadow, label font, helper-text font + color) and
 * the CSS rules that apply them to Bootstrap's form classes
 * (.form-control, .form-select, .form-label, .form-text,
 * .is-invalid, .is-valid, plus the feedback companions).
 *
 * Fails fast in unit CI if a future refactor:
 *   - drops the 44px input height (a11y regression — sub-44px inputs
 *     fail the WCAG 2.5.5 touch-target floor)
 *   - decouples --input-height from --touch-target-min (would let
 *     the touch-target floor and input height drift independently)
 *   - drops the focus shadow (a11y regression — keyboard users lose
 *     visible focus on form fields)
 *   - drops the error/success state border-color → --color-error /
 *     --color-success mappings
 */
class DesignSystemFormElementsContractTest extends TestCase
{
    private const DESIGN_SYSTEM_CSS = __DIR__ . '/../../../packages/frontend-assets/resources/assets/css/microweber/css/design-system.css';
    private const PUBLIC_TOUCH_CSS = __DIR__ . '/../../../Templates/Bootstrap/resources/assets/css/public-touch.css';

    private const EXPECTED_TOKENS = [
        // Input box-model
        '--input-height' => 'var(--touch-target-min)',
        '--input-border-width' => '1px',
        '--input-radius' => '6px',
        '--input-padding-x' => 'var(--space-3)',
        '--input-focus-shadow' => '0 0 0 3px rgba(13, 110, 253, 0.15)',
        // Label
        '--label-font-size' => 'var(--font-size-small)',
        '--label-font-weight' => '500',
        '--label-color' => 'var(--color-text-secondary)',
        // Helper text
        '--helper-text-font-size' => '12px',
        '--helper-text-color' => 'var(--color-text-muted)',
    ];

    #[Test]
    public function design_system_css_defines_every_form_token(): void
    {
        $css = $this->readCss(self::DESIGN_SYSTEM_CSS);

        foreach (self::EXPECTED_TOKENS as $token => $value) {
            $this->assertMatchesRegularExpression(
                '/' . preg_quote($token, '/') . '\s*:\s*' . preg_quote($value, '/') . '\s*;/',
                $css,
                "design-system.css must define {$token}: {$value};"
            );
        }
    }

    #[Test]
    public function public_touch_css_mirrors_every_form_token(): void
    {
        $css = $this->readCss(self::PUBLIC_TOUCH_CSS);

        foreach (self::EXPECTED_TOKENS as $token => $value) {
            $this->assertMatchesRegularExpression(
                '/' . preg_quote($token, '/') . '\s*:\s*' . preg_quote($value, '/') . '\s*;/',
                $css,
                "public-touch.css must mirror {$token}: {$value};"
            );
        }
    }

    #[Test]
    public function input_height_chains_through_to_touch_target_min(): void
    {
        // The chain is --input-height → --touch-target-min → 44px.
        // Pinning the chain catches anyone tempted to inline `44px` on
        // --input-height (which would let a future touch-target bump
        // diverge from inputs).
        foreach ([self::DESIGN_SYSTEM_CSS, self::PUBLIC_TOUCH_CSS] as $path) {
            $css = $this->readCss($path);
            $this->assertMatchesRegularExpression(
                '/--input-height\s*:\s*var\(--touch-target-min\)/',
                $css,
                basename($path) . ' must keep --input-height as var(--touch-target-min), not a hardcoded 44px.'
            );
            $this->assertMatchesRegularExpression(
                '/--touch-target-min\s*:\s*44px/',
                $css,
                basename($path) . ' must keep --touch-target-min: 44px (WCAG 2.5.5 floor).'
            );
        }
    }

    #[Test]
    public function form_control_rule_applies_height_border_radius_tokens(): void
    {
        $css = $this->readCss(self::PUBLIC_TOUCH_CSS);

        // The rule grouping must cover at minimum .form-control,
        // .form-select, textarea.form-control.
        $this->assertMatchesRegularExpression(
            '/\.form-control,\s*\.form-select,\s*textarea\.form-control\s*\{[^}]*min-height:\s*var\(--input-height\)/s',
            $css,
            'Form control rule must set min-height: var(--input-height) on .form-control + .form-select + textarea.'
        );
        $this->assertMatchesRegularExpression(
            '/\.form-control,\s*\.form-select,\s*textarea\.form-control\s*\{[^}]*border-radius:\s*var\(--input-radius\)/s',
            $css,
            'Form control rule must set border-radius: var(--input-radius).'
        );
    }

    #[Test]
    public function form_control_focus_state_uses_focus_shadow_and_focus_border_color(): void
    {
        $css = $this->readCss(self::PUBLIC_TOUCH_CSS);

        $this->assertMatchesRegularExpression(
            '/\.form-control:focus,\s*\.form-select:focus\s*\{[^}]*border-color:\s*var\(--color-border-focus\)/s',
            $css,
            'Form focus state must apply var(--color-border-focus).'
        );
        $this->assertMatchesRegularExpression(
            '/\.form-control:focus,\s*\.form-select:focus\s*\{[^}]*box-shadow:\s*var\(--input-focus-shadow\)/s',
            $css,
            'Form focus state must apply var(--input-focus-shadow).'
        );
    }

    #[Test]
    public function form_label_uses_label_tokens_and_resets_text_transform(): void
    {
        $css = $this->readCss(self::PUBLIC_TOUCH_CSS);

        $this->assertMatchesRegularExpression(
            '/\.form-label\s*\{[^}]*font-size:\s*var\(--label-font-size\)/s',
            $css,
            '.form-label must apply var(--label-font-size).'
        );
        $this->assertMatchesRegularExpression(
            '/\.form-label\s*\{[^}]*color:\s*var\(--label-color\)/s',
            $css,
            '.form-label must apply var(--label-color).'
        );
        // Sentence case requirement: reset any inherited uppercase
        // transform so the audit's "sentence case" rule holds.
        $this->assertMatchesRegularExpression(
            '/\.form-label\s*\{[^}]*text-transform:\s*none/s',
            $css,
            '.form-label must reset text-transform: none (AI-292 sentence-case requirement).'
        );
    }

    #[Test]
    public function form_text_helper_uses_helper_text_tokens(): void
    {
        $css = $this->readCss(self::PUBLIC_TOUCH_CSS);

        $this->assertMatchesRegularExpression(
            '/\.form-text\s*\{[^}]*font-size:\s*var\(--helper-text-font-size\)/s',
            $css,
            '.form-text must apply var(--helper-text-font-size).'
        );
        $this->assertMatchesRegularExpression(
            '/\.form-text\s*\{[^}]*color:\s*var\(--helper-text-color\)/s',
            $css,
            '.form-text must apply var(--helper-text-color).'
        );
    }

    #[Test]
    public function error_and_success_states_bind_to_design_system_status_tokens(): void
    {
        $css = $this->readCss(self::PUBLIC_TOUCH_CSS);

        // Error state: border-color → --color-error
        $this->assertMatchesRegularExpression(
            '/\.form-control\.is-invalid,\s*\.form-select\.is-invalid\s*\{[^}]*border-color:\s*var\(--color-error\)/s',
            $css,
            'Error state must bind border-color to var(--color-error).'
        );
        // .invalid-feedback color → --color-error
        $this->assertMatchesRegularExpression(
            '/\.invalid-feedback,\s*\.invalid-tooltip\s*\{[^}]*color:\s*var\(--color-error\)/s',
            $css,
            'Error feedback text must use var(--color-error).'
        );

        // Success state: border-color → --color-success
        $this->assertMatchesRegularExpression(
            '/\.form-control\.is-valid,\s*\.form-select\.is-valid\s*\{[^}]*border-color:\s*var\(--color-success\)/s',
            $css,
            'Success state must bind border-color to var(--color-success).'
        );
        // .valid-feedback color → --color-success
        $this->assertMatchesRegularExpression(
            '/\.valid-feedback,\s*\.valid-tooltip\s*\{[^}]*color:\s*var\(--color-success\)/s',
            $css,
            'Success feedback text must use var(--color-success).'
        );
    }

    private function readCss(string $path): string
    {
        $real = realpath($path);
        $this->assertNotFalse($real, "CSS file not found: {$path}");

        $css = file_get_contents($real);
        $this->assertNotFalse($css, "Could not read CSS file: {$path}");
        $this->assertNotEmpty($css, "CSS file is empty: {$path}");

        return $css;
    }
}
