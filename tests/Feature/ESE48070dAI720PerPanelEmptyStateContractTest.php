<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-22-48070d / AI-720 — ESE per-panel empty states.
 *
 * Three ESE panels previously showed silent empty space when the selected
 * element didn't support their controls. Each panel now renders a
 * context-appropriate empty state message.
 *
 * Panel 1 — Animations (ElementStyleEditorAnimations.vue)
 *   Previously: root `v-if="supportsAnimations"` hid the entire component
 *   (including header) when the element didn't support animations.
 *   Fix: removed root guard; expanded panel shows either the dropdown
 *   controls OR an empty state ("Animations apply to sections and modules").
 *
 * Panel 2 — Section Settings (ElementStyleEditorLayoutSettings.vue)
 *   Previously: root `v-if="activeLayoutNode"` hid the entire component.
 *   Fix: always renders; shows empty state when activeLayoutNode is null.
 *   The "Section settings" label is non-clickable when no node is found
 *   (.mw-ese-link-disabled style).
 *
 * Panel 3 — AI Style Editor (ElementStyleEditorAiChat.vue)
 *   Previously: inner `v-show="showAiChat"` showed the #ai-gui-editor div
 *   which could be empty when no text element was selected.
 *   Fix: added `elementSupportsAiStyles` computed that checks the selected
 *   element's tag against a list of text-content element types; empty state
 *   shows when the element is not text-based ("Select text to apply AI styles.").
 *
 * CSS added in element-style-editor.css: .mw-ese-panel-empty-state wrapper,
 * .mw-admin-empty-state__heading (15px/600-weight), .mw-admin-empty-state__body
 * (13px/muted), .mw-ese-link-disabled. Dark-mode overrides scoped to
 * .dark / .theme-dark / [data-theme="dark"] — light mode unchanged.
 *
 * Style: file-system reads only, no DB / Filament boot.
 */
class ESE48070dAI720PerPanelEmptyStateContractTest extends TestCase
{
    private const ANIM_VUE    = 'packages/frontend-assets/resources/assets/ui/apps/ElementStyleEditor/ElementStyleEditorAnimations.vue';
    private const LAYOUT_VUE  = 'packages/frontend-assets/resources/assets/ui/apps/ElementStyleEditor/ElementStyleEditorLayoutSettings.vue';
    private const AICHAT_VUE  = 'packages/frontend-assets/resources/assets/ui/apps/ElementStyleEditor/ElementStyleEditorAiChat.vue';
    private const ESE_CSS     = 'packages/microweber-filament-theme/resources/assets/css/microweber/element-style-editor.css';
    private const ESE_BUNDLE  = 'public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css';
    private const ESE_APP_JS  = 'packages/frontend-assets/resources/dist/build/element-style-editor-app.js';

    private string $anim;
    private string $animStripped;
    private string $layout;
    private string $layoutStripped;
    private string $aiChat;
    private string $aiChatStripped;
    private string $eseCss;
    private string $eseCssStripped;

    protected function setUp(): void
    {
        parent::setUp();

        $stripHtmlComments = fn (string $s): string =>
            preg_replace('~<!--[\s\S]*?-->~s', '', $s) ?? $s;
        $stripJsComments = fn (string $s): string =>
            preg_replace('~/\*[\s\S]*?\*/~s', preg_replace('~//[^\n]*~', '', $s) ?? $s) ?? $s;

        $rawAnim = (string) file_get_contents(base_path(self::ANIM_VUE));
        $this->anim = $rawAnim;
        $this->animStripped = $stripHtmlComments($rawAnim);

        $rawLayout = (string) file_get_contents(base_path(self::LAYOUT_VUE));
        $this->layout = $rawLayout;
        $this->layoutStripped = $stripHtmlComments($rawLayout);

        $rawAiChat = (string) file_get_contents(base_path(self::AICHAT_VUE));
        $this->aiChat = $rawAiChat;
        $this->aiChatStripped = $stripHtmlComments($rawAiChat);

        $rawEseCss = (string) file_get_contents(base_path(self::ESE_CSS));
        $this->eseCss = $rawEseCss;
        $this->eseCssStripped = preg_replace('~/\*[\s\S]*?\*/~s', '', $rawEseCss) ?? $rawEseCss;
    }

    // ─── Task markers ─────────────────────────────────────────────────────────

    #[Test]
    public function task_marker_present_in_animations_vue(): void
    {
        $this->assertStringContainsString('task-2026-05-22-48070d', $this->anim,
            'ElementStyleEditorAnimations.vue must carry the AI-720 task marker.');
    }

    #[Test]
    public function task_marker_present_in_layout_vue(): void
    {
        $this->assertStringContainsString('task-2026-05-22-48070d', $this->layout,
            'ElementStyleEditorLayoutSettings.vue must carry the AI-720 task marker.');
    }

    #[Test]
    public function task_marker_present_in_aichat_vue(): void
    {
        $this->assertStringContainsString('task-2026-05-22-48070d', $this->aiChat,
            'ElementStyleEditorAiChat.vue must carry the AI-720 task marker.');
    }

    // ─── Panel 1: Animations ──────────────────────────────────────────────────

    #[Test]
    public function animations_root_guard_removed(): void
    {
        // Root <div> must no longer carry v-if="supportsAnimations".
        // The header must always render so users see the accordion label.
        $this->assertDoesNotMatchRegularExpression(
            '~<div\s+v-if=["\']supportsAnimations["\']~',
            $this->animStripped,
            'Root div must not have v-if="supportsAnimations" (prevents accordion label from rendering)'
        );
    }

    #[Test]
    public function animations_empty_state_present(): void
    {
        $this->assertStringContainsString('mw-ese-panel-empty-state', $this->animStripped,
            'Animations panel must include .mw-ese-panel-empty-state empty state');
    }

    #[Test]
    public function animations_empty_state_guarded_by_else(): void
    {
        // Empty state is on a <div v-else class="mw-ese-panel-empty-state"...> element.
        // v-else and the class appear on the same tag — check both are present and
        // that v-else precedes the class on that line.
        $this->assertMatchesRegularExpression(
            '~<div\s+v-else[^>]*mw-ese-panel-empty-state~',
            $this->animStripped,
            'Animations empty state must be a <div v-else class="mw-ese-panel-empty-state">'
        );
    }

    #[Test]
    public function animations_empty_state_has_correct_message(): void
    {
        $this->assertStringContainsString(
            'Animations apply to sections and modules',
            $this->animStripped,
            'Animations empty state must explain that animations apply to sections/modules'
        );
    }

    #[Test]
    public function animations_controls_still_guarded_by_supports_animations(): void
    {
        // The dropdown controls must still be conditional on supportsAnimations
        $this->assertMatchesRegularExpression(
            '~v-if=["\']supportsAnimations["\']~',
            $this->animStripped,
            'Animation controls (dropdown) must be inside v-if="supportsAnimations" block'
        );
    }

    // ─── Panel 2: Section Settings ────────────────────────────────────────────

    #[Test]
    public function layout_settings_root_guard_removed(): void
    {
        // Root <div> must no longer carry v-if="activeLayoutNode".
        $this->assertDoesNotMatchRegularExpression(
            '~<div\s+v-if=["\']activeLayoutNode["\']~',
            $this->layoutStripped,
            'Root div must not have v-if="activeLayoutNode" (prevents header from always rendering)'
        );
    }

    #[Test]
    public function layout_settings_empty_state_present(): void
    {
        $this->assertStringContainsString('mw-ese-panel-empty-state', $this->layoutStripped,
            'Layout settings panel must include .mw-ese-panel-empty-state empty state');
    }

    #[Test]
    public function layout_settings_empty_state_guarded_by_not_active_layout_node(): void
    {
        $this->assertMatchesRegularExpression(
            '~v-if=["\']!activeLayoutNode["\']~',
            $this->layoutStripped,
            'Empty state must be shown when !activeLayoutNode'
        );
    }

    #[Test]
    public function layout_settings_empty_state_message(): void
    {
        $this->assertStringContainsString(
            'Select a section on the canvas',
            $this->layoutStripped,
            'Layout settings empty state must instruct user to select a section'
        );
    }

    #[Test]
    public function layout_settings_link_disabled_class(): void
    {
        $this->assertStringContainsString(
            'mw-ese-link-disabled',
            $this->layoutStripped,
            'Section settings label must carry .mw-ese-link-disabled when no layout node'
        );
    }

    // ─── Panel 3: AI Style Editor ────────────────────────────────────────────

    #[Test]
    public function aichat_computed_element_supports_ai_styles(): void
    {
        $this->assertStringContainsString('elementSupportsAiStyles', $this->aiChatStripped,
            'AiChat must define elementSupportsAiStyles computed property');
    }

    #[Test]
    public function aichat_empty_state_present(): void
    {
        $this->assertStringContainsString('mw-ese-panel-empty-state', $this->aiChatStripped,
            'AI Style Editor panel must include .mw-ese-panel-empty-state empty state');
    }

    #[Test]
    public function aichat_empty_state_message(): void
    {
        $this->assertStringContainsString(
            'Select text to apply AI styles',
            $this->aiChatStripped,
            'AI Style Editor empty state must say "Select text to apply AI styles"'
        );
    }

    #[Test]
    public function aichat_text_tags_list_includes_headings_and_paragraphs(): void
    {
        // The computed must include H1-H6 and P as text-supporting tags.
        $this->assertStringContainsString("'H1'", $this->aiChat,
            'elementSupportsAiStyles must include H1 in text-tags list');
        $this->assertStringContainsString("'P'", $this->aiChat,
            'elementSupportsAiStyles must include P in text-tags list');
    }

    // ─── CSS: empty-state styles ──────────────────────────────────────────────

    #[Test]
    public function ese_css_has_panel_empty_state_rule(): void
    {
        $this->assertStringContainsString('.mw-ese-panel-empty-state', $this->eseCssStripped,
            'element-style-editor.css must define .mw-ese-panel-empty-state');
    }

    #[Test]
    public function ese_css_has_heading_dark_override(): void
    {
        $this->assertMatchesRegularExpression(
            '~(\.dark|\.theme-dark)[^{]*\.mw-admin-empty-state__heading[^{]*\{[^}]*color:\s*var\(--ese-text~s',
            $this->eseCssStripped,
            '.dark must set .mw-admin-empty-state__heading color to --ese-text'
        );
    }

    #[Test]
    public function ese_css_has_body_dark_override(): void
    {
        $this->assertMatchesRegularExpression(
            '~(\.dark|\.theme-dark)[^{]*\.mw-admin-empty-state__body[^{]*\{[^}]*color:\s*var\(--ese-text-muted~s',
            $this->eseCssStripped,
            '.dark must set .mw-admin-empty-state__body color to --ese-text-muted'
        );
    }

    #[Test]
    public function ese_css_has_link_disabled_rule(): void
    {
        $this->assertStringContainsString('.mw-ese-link-disabled', $this->eseCssStripped,
            'element-style-editor.css must define .mw-ese-link-disabled');
    }

    // ─── AI-724/725 regression guard ─────────────────────────────────────────

    #[Test]
    public function ai724_and_ai725_task_markers_still_present(): void
    {
        $this->assertStringContainsString('task-2026-05-22-747f20', $this->eseCss,
            'AI-724 chrome dark mode task marker must still be present');
        $this->assertStringContainsString('task-2026-05-22-06f3f4', $this->eseCss,
            'AI-725 panel content dark mode task marker must still be present');
    }

    // ─── Bundle probe ─────────────────────────────────────────────────────────

    #[Test]
    public function ese_bundle_contains_panel_empty_state(): void
    {
        $bundlePath = base_path(self::ESE_BUNDLE);
        if (!file_exists($bundlePath)) {
            $this->markTestSkipped('Webpack bundle not present.');
        }
        $bundle = (string) file_get_contents($bundlePath);
        $this->assertStringContainsString('mw-ese-panel-empty-state', $bundle,
            'Built ESE bundle must contain .mw-ese-panel-empty-state');
    }

    #[Test]
    public function ese_app_js_bundle_contains_empty_state_markers(): void
    {
        // ESE Vue components compile into element-style-editor-app.js (not frontend.js).
        $bundlePath = base_path(self::ESE_APP_JS);
        if (!file_exists($bundlePath)) {
            $this->markTestSkipped('ESE app JS bundle not present.');
        }
        $bundle = (string) file_get_contents($bundlePath);
        $this->assertStringContainsString('mw-ese-panel-empty-state', $bundle,
            'ESE app JS bundle must contain mw-ese-panel-empty-state class');
        $this->assertStringContainsString('elementSupportsAiStyles', $bundle,
            'ESE app JS bundle must include elementSupportsAiStyles computed property');
    }
}
