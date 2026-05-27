<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;

/**
 * Contract test for AI-1197 + AI-1200 / task-2026-05-27-9e89ba.
 *
 * AI-1197: Right-rail panel action buttons (.btn.btn-link, .x-close-modal-link,
 * style-pack search toggle/clear) must have 44x44px minimum touch targets
 * per WCAG 2.5.5. CSS rules enforce min-height + min-width on these selectors.
 *
 * AI-1200: Right-rail progressive disclosure. Default shows 3 primary sidebar
 * icons (Template, Style, More). Secondary icons (Layout, AI Edit) carry
 * data-mw-sidebar-priority="secondary" and are hidden by default, revealed
 * on hover/focus-within. Mobile always shows all.
 */
class LiveEdit9e89baAI1197AI1200RightRailContractTest extends TestCase
{
    private string $cssSource;
    private string $vueSource;

    private static function projectRoot(): string
    {
        return dirname(__DIR__, 2);
    }

    protected function setUp(): void
    {
        $root = self::projectRoot();
        $this->cssSource = (string) file_get_contents(
            $root . '/packages/microweber-filament-theme/resources/assets/css/microweber/general-styles.css'
        );
        $this->vueSource = (string) file_get_contents(
            $root . '/packages/frontend-assets/resources/assets/ui/components/Toolbar/SettingsCustomize.vue'
        );
    }

    // ── AI-1197: Touch target CSS rules ──────────────────────────────

    public function test_btn_link_has_44px_min_height(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-page\s+\.btn\.btn-link[^{]*\{[^}]*min-height:\s*44px/s',
            $this->cssSource
        );
    }

    public function test_btn_link_has_44px_min_width(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-page\s+\.btn\.btn-link[^{]*\{[^}]*min-width:\s*44px/s',
            $this->cssSource
        );
    }

    public function test_close_modal_link_has_44px_min_height(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-page\s+\.x-close-modal-link[^{]*\{[^}]*min-height:\s*44px/s',
            $this->cssSource
        );
    }

    public function test_close_modal_link_has_44px_min_width(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-page\s+\.x-close-modal-link[^{]*\{[^}]*min-width:\s*44px/s',
            $this->cssSource
        );
    }

    public function test_style_pack_search_buttons_have_44px(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-page\s+\.style-pack-search-toggle[^{]*\{[^}]*min-height:\s*44px/s',
            $this->cssSource
        );
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-page\s+\.style-pack-search-clear[^{]*\{[^}]*min-height:\s*44px/s',
            $this->cssSource
        );
    }

    // ── AI-1200: Progressive disclosure ──────────────────────────────

    public function test_layout_button_has_secondary_priority(): void
    {
        $this->assertMatchesRegularExpression(
            '/data-mw-sidebar-priority="secondary"[^>]*>.*?Insert layout/s',
            $this->vueSource
        );
    }

    public function test_ai_edit_button_has_secondary_priority(): void
    {
        $this->assertMatchesRegularExpression(
            '/data-mw-sidebar-priority="secondary"[^>]*>.*?Quick AI edit/s',
            $this->vueSource
        );
    }

    public function test_template_button_is_primary(): void
    {
        // Template button must NOT have secondary priority
        $templateBtn = $this->sliceButton('Templates &amp; layouts');
        $this->assertStringNotContainsString('data-mw-sidebar-priority="secondary"', $templateBtn);
    }

    public function test_style_button_is_primary(): void
    {
        $styleBtn = $this->sliceButton('Element styles');
        $this->assertStringNotContainsString('data-mw-sidebar-priority="secondary"', $styleBtn);
    }

    public function test_more_button_is_primary(): void
    {
        $moreBtn = $this->sliceButton('Advanced');
        $this->assertStringNotContainsString('data-mw-sidebar-priority="secondary"', $moreBtn);
    }

    public function test_css_hides_secondary_by_default(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-right-sidebar-wrapper[^{]*\[data-mw-sidebar-priority="secondary"\]\s*\{[^}]*display:\s*none/s',
            $this->cssSource
        );
    }

    public function test_css_shows_secondary_on_hover(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-right-sidebar-wrapper[^{]*:hover\s*\n?\s*\[data-mw-sidebar-priority="secondary"\]/s',
            $this->cssSource
        );
    }

    public function test_css_shows_secondary_on_focus_within(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-live-edit-right-sidebar-wrapper[^{]*:focus-within\s*\n?\s*\[data-mw-sidebar-priority="secondary"\]/s',
            $this->cssSource
        );
    }

    public function test_mobile_always_shows_secondary(): void
    {
        $this->assertMatchesRegularExpression(
            '/@media\s*\(max-width:\s*768px\)\s*\{[^}]*\[data-mw-sidebar-priority="secondary"\]\s*\{[^}]*display:\s*flex\s*!important/s',
            $this->cssSource
        );
    }

    public function test_reduced_motion_guard_on_disclosure(): void
    {
        $this->assertMatchesRegularExpression(
            '/@media\s*\(prefers-reduced-motion:\s*no-preference\)\s*\{[^}]*\[data-mw-sidebar-priority="secondary"\]/s',
            $this->cssSource
        );
    }

    public function test_exactly_two_secondary_buttons_in_vue(): void
    {
        preg_match_all('/data-mw-sidebar-priority="secondary"/', $this->vueSource, $matches);
        $this->assertSame(
            2,
            count($matches[0]),
            'Expected exactly 2 secondary sidebar buttons (Layout + AI Edit)'
        );
    }

    private function sliceButton(string $label): string
    {
        $pos = strpos($this->vueSource, $label);
        if ($pos === false) {
            return '';
        }
        $start = max(0, $pos - 500);
        return substr($this->vueSource, $start, 600);
    }
}
