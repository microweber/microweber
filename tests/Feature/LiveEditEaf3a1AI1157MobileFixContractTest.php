<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-29-eaf3a1 / AI-1157 — Live Edit mobile audit batch 1.
 *
 * Two fixes shipped together at 390x844 viewport audit:
 *
 *   FIX 1 (Templates and Layouts empty body):
 *   The "Templates and Layouts" right-rail button opened the controlBox
 *   but rendered an empty body. RightSidebar gates the Vue Teleport
 *   source <TemplateSettingsTeleport> behind showSidebar, which flips
 *   only when the emitter receives 'live-edit-ui-show'. The right-rail
 *   button path went through SettingsCustomize.toggle() which did NOT
 *   carry the emit (only show() did), so the teleport source never
 *   mounted and the controlBox body stayed empty.
 *
 *   Fix: SettingsCustomize.toggle() now emits 'live-edit-ui-show' at
 *   the top of the method (mirrors show()). Tier-3 verified: controlBox
 *   body now renders 335x641 with "Choose where to edit / Template /
 *   Layout / Website design settings / ..." content.
 *
 *   FIX 2 (Quick AI Edit 24 sub-44 violations):
 *   #mw-live-edit-quickEditComponent-box at 390x844 carried 12
 *   .quick-ai-type-group-header buttons at 32px tall and 13
 *   .form-control-live-edit-input inputs at 38px tall - both below
 *   the WCAG 2.5.5 / iOS HIG 44x44 touch-target floor.
 *
 *   Fix: live-edit-mobile.css inside the canonical
 *   `@media (max-width: 768px), (pointer: coarse)` block pins both
 *   selectors to min-height:44px !important. Specificity-bumped via
 *   `body.fi-panel-admin` prefix because
 *   microweber-filament-theme.css:100 ships a same-specificity
 *   desktop rule with the same !important pinning min-height:36px;
 *   that file concatenates AFTER live-edit-mobile.css in the bundle
 *   and would otherwise win source-order (Stage-2 sub-variant
 *   !important-cascade-loss). The body.fi-panel-admin prefix adds
 *   +0/1/1/0 to specificity, beating the bare #id .class rule.
 *
 *   Tier-3 verified: 0 violations across all 25 elements.
 *
 *   Scope note: #mw-live-edit-quickEditComponent-box teleports
 *   directly under body.fi-panel-admin with no
 *   .mw-admin-live-edit-page / .mw-live-edit-page ancestor, so the
 *   doubled-prefix pattern used elsewhere in live-edit-mobile.css
 *   does NOT match here. Scoped via the unique box ID so other Live
 *   Edit surfaces that reuse .form-control-live-edit-input
 *   (filepicker, module settings) keep their desktop sizing.
 */
class LiveEditEaf3a1AI1157MobileFixContractTest extends TestCase
{
    private string $vue;
    private string $vueStripped;
    private string $css;
    private string $cssStripped;

    protected function setUp(): void
    {
        parent::setUp();

        $this->vue = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/ui/components/Toolbar/SettingsCustomize.vue'
        ));
        $this->css = (string) file_get_contents(base_path(
            'packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-mobile.css'
        ));

        // Strip line + block comments for selector-self-match guard.
        $this->vueStripped = (string) preg_replace('~/\*.*?\*/~s', '', $this->vue);
        $this->vueStripped = (string) preg_replace('~//[^\n]*~', '', $this->vueStripped);

        $this->cssStripped = (string) preg_replace('~/\*.*?\*/~s', '', $this->css);
    }

    #[Test]
    public function settings_customize_toggle_emits_live_edit_ui_show(): void
    {
        // FIX 1 source-pin: toggle() method MUST carry an
        // emitter.emit('live-edit-ui-show', name) call. Match against
        // comment-stripped source so the docblock above the call site
        // does not satisfy the assertion on its own.
        $this->assertMatchesRegularExpression(
            "/toggle:\s*function\s*\(\s*name\s*\)\s*\{[^}]*this\.emitter\.emit\(\s*['\"]live-edit-ui-show['\"]\s*,\s*name\s*\)/s",
            $this->vueStripped,
            'SettingsCustomize.toggle() must emit live-edit-ui-show so RightSidebar.showSidebar flips and TemplateSettingsTeleport mounts.'
        );
    }

    #[Test]
    public function settings_customize_toggle_carries_task_marker(): void
    {
        // Audit-trail marker for the toggle() emit fix.
        $this->assertStringContainsString('task-2026-05-29-eaf3a1', $this->vue);
        $this->assertStringContainsString('AI-1157', $this->vue);
    }

    #[Test]
    public function quick_ai_edit_header_pinned_44px_inside_mobile_media(): void
    {
        // FIX 2a: .quick-ai-type-group-header inside the mobile media
        // block. body.fi-panel-admin prefix is required to beat the
        // later 36px desktop rule.
        $this->assertMatchesRegularExpression(
            '/@media\s*\(\s*max-width:\s*768px\s*\)\s*,\s*\(\s*pointer:\s*coarse\s*\)\s*\{[\s\S]*?body\.fi-panel-admin\s+#mw-live-edit-quickEditComponent-box\s+\.quick-ai-type-group-header\s*\{[^}]*min-height:\s*44px\s*!important/s',
            $this->css,
            'Quick AI Edit header rule must pin min-height:44px !important with body.fi-panel-admin specificity bump inside the mobile media block.'
        );
    }

    #[Test]
    public function quick_ai_edit_input_pinned_44px_inside_mobile_media(): void
    {
        // FIX 2b: .form-control-live-edit-input inside the mobile media
        // block. body.fi-panel-admin prefix is required to beat the
        // later same-specificity 36px desktop rule in
        // microweber-filament-theme.css:100.
        $this->assertMatchesRegularExpression(
            '/@media\s*\(\s*max-width:\s*768px\s*\)\s*,\s*\(\s*pointer:\s*coarse\s*\)\s*\{[\s\S]*?body\.fi-panel-admin\s+#mw-live-edit-quickEditComponent-box\s+\.form-control-live-edit-input\s*\{[^}]*min-height:\s*44px\s*!important/s',
            $this->css,
            'Quick AI Edit input rule must pin min-height:44px !important with body.fi-panel-admin specificity bump inside the mobile media block.'
        );
    }

    #[Test]
    public function quick_ai_edit_rules_carry_task_marker(): void
    {
        // Audit-trail marker for the CSS fix.
        $this->assertStringContainsString('task-2026-05-29-eaf3a1', $this->css);
        $this->assertStringContainsString('AI-1157', $this->css);
    }

    #[Test]
    public function quick_ai_edit_rules_scoped_to_unique_box_id_not_doubled_prefix(): void
    {
        // Scope guard: the controlBox teleports outside
        // .mw-admin-live-edit-page / .mw-live-edit-page. Our rules
        // MUST anchor on #mw-live-edit-quickEditComponent-box, NOT on
        // the doubled-prefix pattern. Strip comments before asserting
        // so the docblock prose mentioning the wrong patterns doesn't
        // false-positive.
        $this->assertDoesNotMatchRegularExpression(
            '/\.mw-admin-live-edit-page\s+\.quick-ai-type-group-header/',
            $this->cssStripped,
            'Quick AI Edit headers must not use the .mw-admin-live-edit-page ancestor (controlBox teleports outside that scope).'
        );
        $this->assertDoesNotMatchRegularExpression(
            '/\.mw-live-edit-page\s+\.quick-ai-type-group-header/',
            $this->cssStripped,
            'Quick AI Edit headers must not use the .mw-live-edit-page ancestor (controlBox teleports outside that scope).'
        );
        $this->assertDoesNotMatchRegularExpression(
            '/\.mw-admin-live-edit-page\s+\.form-control-live-edit-input,\s*\.mw-live-edit-page\s+\.form-control-live-edit-input/',
            $this->cssStripped,
            'Quick AI Edit inputs must not use the doubled-prefix pattern (controlBox teleports outside that scope).'
        );
    }
}
