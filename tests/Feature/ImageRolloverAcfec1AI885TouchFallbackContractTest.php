<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;

/**
 * Contract test for AI-885 / task-2026-05-28-acfec1.
 *
 * ImageRollover module was hover-dependent — CSS :hover on .mw-rollover
 * revealed the overlay, which does not fire on touch devices.
 * Fix: @media (hover: none) CSS fallback + JS tap-to-toggle via
 * .mw-rollover-active class + keyboard a11y (Enter/Space keydown).
 */
class ImageRolloverAcfec1AI885TouchFallbackContractTest extends TestCase
{
    private string $template;

    private static function projectRoot(): string
    {
        return dirname(__DIR__, 2);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->template = (string) file_get_contents(
            self::projectRoot() . '/Modules/ImageRollover/resources/views/templates/default.blade.php'
        );
    }

    public function test_hover_none_media_query_present(): void
    {
        $this->assertStringContainsString(
            '@media (hover: none)',
            $this->template,
            'AI-885: must have @media (hover: none) fallback for touch devices'
        );
    }

    public function test_hover_none_sets_cursor_pointer(): void
    {
        $this->assertMatchesRegularExpression(
            '/@media\s*\(hover:\s*none\)\s*\{[^}]*\.mw-rollover\s*\{[^}]*cursor:\s*pointer/s',
            $this->template,
            'AI-885: touch devices must show pointer cursor on rollover container'
        );
    }

    public function test_hover_none_shows_subtle_overlay_preview(): void
    {
        $this->assertMatchesRegularExpression(
            '/@media\s*\(hover:\s*none\)\s*\{[\s\S]*?\.mw-rollover-overlay\s*\{[^}]*opacity:\s*0\.12/s',
            $this->template,
            'AI-885: touch devices must show a subtle preview of the overlay image'
        );
    }

    public function test_active_class_shows_full_overlay(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-rollover\.mw-rollover-active\s+\.mw-rollover-overlay\s*\{[^}]*opacity:\s*1/s',
            $this->template,
            'AI-885: .mw-rollover-active must reveal overlay at full opacity'
        );
    }

    public function test_prefers_reduced_motion_guard(): void
    {
        $this->assertStringContainsString(
            'prefers-reduced-motion: reduce',
            $this->template,
            'AI-885: must include prefers-reduced-motion guard'
        );
    }

    public function test_js_matches_hover_none_media_query(): void
    {
        $this->assertStringContainsString(
            "window.matchMedia('(hover: none)').matches",
            $this->template,
            'AI-885: JS must detect touch devices via matchMedia hover:none'
        );
    }

    public function test_js_toggles_active_class(): void
    {
        $this->assertStringContainsString(
            "container.classList.toggle('mw-rollover-active')",
            $this->template,
            'AI-885: JS must toggle mw-rollover-active class on tap'
        );
    }

    public function test_js_adds_role_button_on_touch(): void
    {
        $this->assertStringContainsString(
            "setAttribute('role', 'button')",
            $this->template,
            'AI-885: JS must add role=button on touch devices for a11y'
        );
    }

    public function test_js_adds_aria_label_on_touch(): void
    {
        $this->assertStringContainsString(
            "setAttribute('aria-label', 'Tap to toggle image')",
            $this->template,
            'AI-885: JS must add aria-label on touch devices'
        );
    }

    public function test_js_adds_tabindex_on_touch(): void
    {
        $this->assertStringContainsString(
            "setAttribute('tabindex', '0')",
            $this->template,
            'AI-885: JS must add tabindex=0 on touch devices for keyboard focus'
        );
    }

    public function test_js_keydown_enter_handler(): void
    {
        $this->assertStringContainsString(
            "e.key === 'Enter'",
            $this->template,
            'AI-885: JS keydown handler must respond to Enter key'
        );
    }

    public function test_js_keydown_space_handler(): void
    {
        $this->assertStringContainsString(
            "e.key === ' '",
            $this->template,
            'AI-885: JS keydown handler must respond to Space key'
        );
    }

    public function test_js_prevent_default_on_keydown(): void
    {
        $this->assertStringContainsString(
            'e.preventDefault()',
            $this->template,
            'AI-885: keydown handler must call preventDefault to suppress scroll on Space'
        );
    }

    public function test_alt_text_fallback_chain(): void
    {
        $this->assertStringContainsString(
            "get_option('website_title', 'website')",
            $this->template,
            'AI-885: alt text must use three-tier fallback chain per AI-804 pattern'
        );
    }

    public function test_tap_highlight_suppressed(): void
    {
        $this->assertStringContainsString(
            '-webkit-tap-highlight-color: transparent',
            $this->template,
            'AI-885: tap highlight must be suppressed on touch devices'
        );
    }
}
