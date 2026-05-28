<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;

/**
 * Contract test for Round 53 / task-2026-05-28-2ca6c2.
 *
 * AI-1212: Text editor toolbar dark mode skin — dynamic class switch
 * AI-1213: Text editor toolbar touch targets 44x44 + mobile overflow
 */
class Round53_2ca6c2_AI1212_AI1213_EditorToolbarContractTest extends TestCase
{
    private string $editorScss;
    private string $editorJs;

    private static function projectRoot(): string
    {
        return dirname(__DIR__, 2);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $root = self::projectRoot();

        $this->editorScss = (string) file_get_contents(
            $root . '/packages/frontend-assets/resources/assets/css/scss/editor.scss'
        );
        $this->editorJs = (string) file_get_contents(
            $root . '/packages/frontend-assets/resources/assets/editor/editor.js'
        );
    }

    // ── AI-1212: Dark mode skin ───────────────────────────────────────

    public function test_ai1212_dark_skin_background_color(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-small-editor\.mw-small-editor-skin-dark\s*\{[^}]*background-color:\s*rgb\(30,\s*30,\s*30\)/s',
            $this->editorScss,
            'AI-1212: dark skin must have dark background rgb(30, 30, 30)'
        );
    }

    public function test_ai1212_dark_skin_button_color(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-small-editor\.mw-small-editor-skin-dark[\s\S]*?\.mw-editor-controller-button\s*\{[^}]*color:\s*rgba\(255,\s*255,\s*255/s',
            $this->editorScss,
            'AI-1212: dark skin buttons must have white text'
        );
    }

    public function test_ai1212_dark_skin_button_hover(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-small-editor\.mw-small-editor-skin-dark[\s\S]*?&:hover[\s\S]*?background-color:\s*rgba\(255,\s*255,\s*255,\s*0\.1\)/s',
            $this->editorScss,
            'AI-1212: dark skin button hover must have subtle white overlay'
        );
    }

    public function test_ai1212_dark_skin_dropdown_background(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-small-editor\.mw-small-editor-skin-dark[\s\S]*?\.mw-editor-controller-component-select-values-holder\s*\{[^}]*background-color:\s*rgb\(30,\s*30,\s*30\)/s',
            $this->editorScss,
            'AI-1212: dark skin dropdowns must have dark background'
        );
    }

    public function test_ai1212_editor_js_detects_dark_mode_on_init(): void
    {
        $this->assertStringContainsString(
            "classList.contains('dark')",
            $this->editorJs,
            'AI-1212: editor must detect dark class on init'
        );
    }

    public function test_ai1212_editor_js_returns_dark_skin_when_dark(): void
    {
        $this->assertMatchesRegularExpression(
            "/classList\.contains\('dark'\).*?return\s*'dark'/s",
            $this->editorJs,
            'AI-1212: editor must return dark skin when dark class is present'
        );
    }

    public function test_ai1212_editor_js_has_mutation_observer_for_theme(): void
    {
        $this->assertStringContainsString(
            'new MutationObserver',
            $this->editorJs,
            'AI-1212: editor must use MutationObserver for theme changes'
        );
    }

    public function test_ai1212_editor_js_calls_setSmallEditorSkin(): void
    {
        $this->assertStringContainsString(
            'setSmallEditorSkin(newSkin)',
            $this->editorJs,
            'AI-1212: editor must call setSmallEditorSkin when theme changes'
        );
    }

    public function test_ai1212_observer_watches_class_attribute(): void
    {
        $this->assertStringContainsString(
            "attributeFilter: ['class']",
            $this->editorJs,
            'AI-1212: observer must watch class attribute changes'
        );
    }

    // ── AI-1213: Touch targets ────────────────────────────────────────

    public function test_ai1213_button_height_44px_in_small_editor(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-small-editor\.mw-small-editor-skin-light[\s\S]*?\.mw-editor-controller-button\s*\{[^}]*height:\s*44px/s',
            $this->editorScss,
            'AI-1213: small editor buttons must have height: 44px'
        );
    }

    public function test_ai1213_button_min_width_44px_in_small_editor(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.mw-small-editor\.mw-small-editor-skin-light[\s\S]*?\.mw-editor-controller-button\s*\{[^}]*min-width:\s*44px/s',
            $this->editorScss,
            'AI-1213: small editor buttons must have min-width: 44px'
        );
    }

    public function test_ai1213_base_button_height_44px(): void
    {
        $this->assertMatchesRegularExpression(
            '/^\.mw-editor-controller-button\s*\{[^}]*height:\s*44px/ms',
            $this->editorScss,
            'AI-1213: base button height must be 44px'
        );
    }

    public function test_ai1213_base_button_min_width_44px(): void
    {
        $this->assertMatchesRegularExpression(
            '/^\.mw-editor-controller-button\s*\{[^}]*min-width:\s*44px/ms',
            $this->editorScss,
            'AI-1213: base button min-width must be 44px'
        );
    }

    public function test_ai1213_old_35px_height_absent(): void
    {
        $stripped = preg_replace('~/\*.*?\*/~s', '', $this->editorScss);
        $stripped = preg_replace('~//[^\n]*~', '', $stripped);

        $this->assertDoesNotMatchRegularExpression(
            '/\.mw-editor-controller-button\s*\{[^}]*height:\s*35px/s',
            $stripped,
            'AI-1213: legacy 35px height must be absent'
        );
    }

    public function test_ai1213_old_33px_min_width_absent(): void
    {
        $stripped = preg_replace('~/\*.*?\*/~s', '', $this->editorScss);
        $stripped = preg_replace('~//[^\n]*~', '', $stripped);

        $this->assertDoesNotMatchRegularExpression(
            '/\.mw-editor-controller-button\s*\{[^}]*min-width:\s*33px/s',
            $stripped,
            'AI-1213: legacy 33px min-width must be absent'
        );
    }

    public function test_ai1213_old_32px_values_absent(): void
    {
        $stripped = preg_replace('~/\*.*?\*/~s', '', $this->editorScss);
        $stripped = preg_replace('~//[^\n]*~', '', $stripped);

        $this->assertDoesNotMatchRegularExpression(
            '/^\.mw-editor-controller-button\s*\{[^}]*height:\s*32px/ms',
            $stripped,
            'AI-1213: legacy 32px height must be absent from base rule'
        );
    }

    public function test_ai1213_mobile_bar_has_overflow_scroll(): void
    {
        $this->assertMatchesRegularExpression(
            '/@media\s*\(max-width:\s*800px\)[\s\S]*?\.mw-bar\s*\{[^}]*overflow-x:\s*auto/s',
            $this->editorScss,
            'AI-1213: mobile bar must have overflow-x: auto for horizontal scroll'
        );
    }

    public function test_ai1213_mobile_bar_row_nowrap(): void
    {
        $this->assertMatchesRegularExpression(
            '/@media\s*\(max-width:\s*800px\)[\s\S]*?\.mw-bar-row\s*\{[^}]*flex-wrap:\s*nowrap/s',
            $this->editorScss,
            'AI-1213: mobile bar row must not wrap to prevent overflow hiding controls'
        );
    }
}
