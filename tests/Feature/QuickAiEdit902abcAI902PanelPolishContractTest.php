<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-22-902abc / AI-902 — Quick AI Edit panel: 3 UX improvements
 *
 * 1. Label fix    — panel title "Quick Edit" → "Quick AI Edit"
 * 2. Empty state  — when no canvas section selected, show icon + helper text
 * 3. Field groups — collapsible type headers: Headings / Body text / Buttons+CTAs / Other
 *                   11px uppercase label, --ese-border separator, default expanded
 *
 * Files changed:
 *  - packages/frontend-assets/resources/assets/live-edit/live-edit-widgets-service.js
 *  - packages/frontend-assets/resources/assets/components/quick-ai-edit.js
 *  - packages/microweber-filament-theme/resources/assets/css/microweber/general-styles.css
 */
class QuickAiEdit902abcAI902PanelPolishContractTest extends TestCase
{
    private string $widgetSvc;
    private string $widgetSvcStripped;
    private string $qaeJs;
    private string $qaeStripped;
    private string $css;
    private string $cssStripped;

    protected function setUp(): void
    {
        parent::setUp();

        $rawWidget = (string) file_get_contents(
            base_path('packages/frontend-assets/resources/assets/live-edit/live-edit-widgets-service.js')
        );
        $this->widgetSvc = $rawWidget;
        $stripped = preg_replace('~/\*[\s\S]*?\*/~s', '', $rawWidget) ?? $rawWidget;
        $this->widgetSvcStripped = preg_replace('~//[^\n]*~', '', $stripped) ?? $stripped;

        $rawQae = (string) file_get_contents(
            base_path('packages/frontend-assets/resources/assets/components/quick-ai-edit.js')
        );
        $this->qaeJs = $rawQae;
        $stripped2 = preg_replace('~/\*[\s\S]*?\*/~s', '', $rawQae) ?? $rawQae;
        $this->qaeStripped = preg_replace('~//[^\n]*~', '', $stripped2) ?? $stripped2;

        $rawCss = (string) file_get_contents(
            base_path('packages/microweber-filament-theme/resources/assets/css/microweber/general-styles.css')
        );
        $this->css = $rawCss;
        $this->cssStripped = preg_replace('~/\*[\s\S]*?\*/~s', '', $rawCss) ?? $rawCss;
    }

    // ─── Task marker ──────────────────────────────────────────────────────────

    #[Test]
    public function task_marker_present_in_all_files(): void
    {
        $this->assertStringContainsString('task-2026-05-22-902abc', $this->widgetSvc,
            'live-edit-widgets-service.js must carry the AI-902 task marker.'
        );
        $this->assertStringContainsString('task-2026-05-22-902abc', $this->qaeJs,
            'quick-ai-edit.js must carry the AI-902 task marker.'
        );
        $this->assertStringContainsString('task-2026-05-22-902abc', $this->css,
            'general-styles.css must carry the AI-902 task marker.'
        );
    }

    // ─── Item 1: Label fix ────────────────────────────────────────────────────

    #[Test]
    public function panel_title_is_quick_ai_edit(): void
    {
        $this->assertStringContainsString("mw.lang('Quick AI Edit')", $this->widgetSvcStripped,
            'Panel title must be "Quick AI Edit" to match the button tooltip.'
        );
    }

    #[Test]
    public function old_quick_edit_label_is_absent(): void
    {
        // Pre-strip to avoid false-match on comments; the selector-self-match guard pattern.
        $this->assertDoesNotMatchRegularExpression(
            "~mw\.lang\('Quick Edit'\)~",
            $this->widgetSvcStripped,
            'Legacy "Quick Edit" title must be replaced by "Quick AI Edit".'
        );
    }

    // ─── Item 2: Empty state ──────────────────────────────────────────────────

    #[Test]
    public function empty_state_element_is_created(): void
    {
        $this->assertStringContainsString('mw-quick-ai-edit-empty-state', $this->qaeStripped,
            'quick-ai-edit.js must create a .mw-quick-ai-edit-empty-state element for no-selection state.'
        );
    }

    #[Test]
    public function empty_state_shows_when_no_fields(): void
    {
        $this->assertMatchesRegularExpression(
            '~editFieldsContainer\.firstChild[\s\S]{0,200}mw-quick-ai-edit-empty-state~s',
            $this->qaeStripped,
            'Empty state must be appended to editFieldsContainer when no fields are collected.'
        );
    }

    #[Test]
    public function empty_state_has_helpful_text(): void
    {
        $this->assertStringContainsString(
            'Click a section on the canvas to see editable fields',
            $this->qaeJs,
            'Empty state must show the helper text "Click a section on the canvas to see editable fields".'
        );
    }

    #[Test]
    public function empty_state_css_is_present(): void
    {
        $this->assertMatchesRegularExpression(
            '~\.mw-quick-ai-edit-empty-state\s*\{[^}]*flex-direction:\s*column~s',
            $this->cssStripped,
            '.mw-quick-ai-edit-empty-state must be a flex column container.'
        );
        $this->assertStringContainsString('.mw-quick-ai-edit-empty-state__text', $this->cssStripped,
            '.mw-quick-ai-edit-empty-state__text must have styling in general-styles.css.'
        );
    }

    // ─── Item 3: Field grouping ───────────────────────────────────────────────

    #[Test]
    public function type_groups_structure_in_field_groups(): void
    {
        $this->assertStringContainsString('typeGroups', $this->qaeStripped,
            'fieldGroups must use a typeGroups sub-object for Headings/Body/CTAs/Other grouping.'
        );
    }

    #[Test]
    public function all_four_type_groups_defined(): void
    {
        $this->assertStringContainsString("'headings'", $this->qaeStripped,
            'headings type group must be defined.'
        );
        $this->assertStringContainsString("'body'", $this->qaeStripped,
            'body type group must be defined.'
        );
        $this->assertStringContainsString("'ctas'", $this->qaeStripped,
            'ctas type group must be defined.'
        );
        $this->assertStringContainsString("'other'", $this->qaeStripped,
            'other type group must be defined.'
        );
    }

    #[Test]
    public function type_groups_route_by_tag(): void
    {
        // H1-H6 → headings
        $this->assertStringContainsString("H[1-6]", $this->qaeStripped,
            'H1-H6 tags must be routed to the headings type group.'
        );
        // BUTTON/A → ctas
        $this->assertStringContainsString("'ctas'", $this->qaeStripped,
            'BUTTON and A tags must be routed to the ctas type group.'
        );
    }

    #[Test]
    public function type_group_headers_are_collapsible(): void
    {
        $this->assertStringContainsString('quick-ai-type-group-header', $this->qaeStripped,
            'Type group header button must carry .quick-ai-type-group-header class.'
        );
        $this->assertStringContainsString("dataset.open", $this->qaeStripped,
            'Collapse state must be tracked via dataset.open toggle.'
        );
        $this->assertStringContainsString("'aria-expanded'", $this->qaeStripped,
            'Type group header must update aria-expanded for accessibility.'
        );
    }

    #[Test]
    public function type_groups_default_expanded(): void
    {
        $this->assertStringContainsString("dataset.open = 'true'", $this->qaeStripped,
            'Type groups must default to expanded (dataset.open = true).'
        );
    }

    #[Test]
    public function collapsed_group_hides_body(): void
    {
        $this->assertMatchesRegularExpression(
            '~\.quick-ai-type-group\[data-open="false"\][^{]*\{[^}]*display:\s*none~s',
            $this->cssStripped,
            '.quick-ai-type-group[data-open="false"] .quick-ai-type-group-body must be hidden.'
        );
    }

    #[Test]
    public function type_group_header_uses_11px_uppercase_label(): void
    {
        $this->assertMatchesRegularExpression(
            '~\.quick-ai-type-group-header\s*\{[^}]*font-size:\s*var\(--font-label,\s*11px\)~s',
            $this->cssStripped,
            'Type group header must use 11px (--font-label) font per AI-902 spec.'
        );
        $this->assertMatchesRegularExpression(
            '~\.quick-ai-type-group-header\s*\{[^}]*text-transform:\s*uppercase~s',
            $this->cssStripped,
            'Type group header must be uppercase.'
        );
    }

    #[Test]
    public function type_groups_separated_by_ese_border(): void
    {
        $this->assertMatchesRegularExpression(
            '~\.quick-ai-type-group\s*\{[^}]*border-top:\s*1px solid var\(--ese-border~s',
            $this->cssStripped,
            'Type groups must be separated by --ese-border at the top.'
        );
    }

    #[Test]
    public function type_group_labels_use_localised_strings(): void
    {
        $this->assertStringContainsString("mw.lang('Headings')", $this->qaeStripped,
            '"Headings" label must use mw.lang() for localisation.'
        );
        $this->assertStringContainsString("mw.lang('Body text')", $this->qaeStripped,
            '"Body text" label must use mw.lang() for localisation.'
        );
        $this->assertStringContainsString("mw.lang('Buttons / CTAs')", $this->qaeStripped,
            '"Buttons / CTAs" label must use mw.lang() for localisation.'
        );
        $this->assertStringContainsString("mw.lang('Other')", $this->qaeStripped,
            '"Other" label must use mw.lang() for localisation.'
        );
    }

    // ─── Dark mode ─────────────────────────────────────────────────────────────

    #[Test]
    public function dark_mode_type_group_border_uses_dark_token(): void
    {
        $this->assertMatchesRegularExpression(
            '~html\.dark\s+\.quick-ai-type-group\s*\{[^}]*border-top-color~s',
            $this->cssStripped,
            'Type group border must have dark-mode override using --ese-border token.'
        );
    }

    // ─── Bundle probe ──────────────────────────────────────────────────────────

    #[Test]
    public function built_bundle_contains_quick_ai_edit_rename(): void
    {
        // live-edit-widgets-service.js and quick-ai-edit.js are compiled into live-edit-app.js
        $bundlePath = base_path('public/vendor/microweber-packages/frontend-assets/build/live-edit-app.js');
        if (!file_exists($bundlePath)) {
            $this->markTestSkipped('Vite live-edit-app.js bundle not found.');
        }
        $bundle = (string) file_get_contents($bundlePath);
        $this->assertStringContainsString('Quick AI Edit', $bundle,
            'live-edit-app.js bundle must contain the renamed "Quick AI Edit" panel title.'
        );
        $this->assertStringContainsString('quick-ai-type-group', $bundle,
            'live-edit-app.js bundle must contain the type-group class from quick-ai-edit.js.'
        );
    }
}
