<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * task-2026-05-17-f39d53 / AI-800  MainDrawer copy + grammar pass (P3).
 * Jira: https://microweber.atlassian.net/browse/AI-800
 *
 * Lineage:
 *   - AI-700 (task-2026-05-16-7326d6)  original MainDrawer ship
 *   - AI-798 (task-2026-05-17-7a9913)  hierarchy refactor (this session)
 *   - AI-799 (task-2026-05-17-918e58)  Users href fix (this session)
 *
 * Designer audit flagged 4 copy defects:
 *   1. Theme toggle label = current state (should be the ACTION)
 *      VERIFIED clean: existing `{{ theme === 'dark' ? 'Light mode'
 *      : 'Dark mode' }}` already shows the action — when dark theme is
 *      active, label reads "Light mode" (action: switch TO light); when
 *      light theme is active, label reads "Dark mode" (action: switch
 *      TO dark). NO CODE CHANGE needed; pinned by regression test.
 *   2. "Template & Layout" both singular but panel shows multiple
 *      RENAMED: "Templates & layouts".
 *   3. Mixed sentence/title case across items
 *      RENAMED to sentence-case (Filament convention):
 *        - "Back to Admin"  -> "Back to admin"
 *        - "Theme Settings" -> "Theme settings"
 *      "Layers" / "Pages" / "Users" / "Log out" already sentence-case.
 *   4. "See website" ambiguous (new tab? exit edit mode? navigate?)
 *      RENAMED: "View public site". The existing AI-798 up-right-arrow
 *      affordance on .mw-main-drawer__item--external conveys the
 *      external-tab cue; no inline ↗ in the label text needed.
 *
 * Scope: MainDrawer.vue ONLY. RightSidebar.vue h3 + bootstrap.js
 * controlBox title (AI-708 "Theme Settings" / "Template & Layout")
 * NOT cascaded in this slice — designer dispatch is drawer-scoped.
 * Optional cross-surface cascade flagged in SHIP report as AI-800a.
 *
 * Pin-evolution applied: 4 prior AI-700 + 1 prior AI-798 contract-test
 * assertions updated in place to the new labels (per LESSONS rule —
 * single source of truth wins over parallel contradictory pins).
 */
class LiveEditF39d53AI800MainDrawerCopyPassContractTest extends TestCase
{
    private string $drawer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->drawer = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/ui/components/Toolbar/MainDrawer.vue'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A  4 renamed labels (Slices 2 + 3 + 4)
    // ─────────────────────────────────────────────────────────────────────

    public static function renamedLabelCases(): array
    {
        return [
            // [oldLabel, newLabel, description]
            'Slice 3a sentence-case Back to admin'           => ['Back to Admin', 'Back to admin'],
            'Slice 2 plural-plural Templates & layouts'      => ['Template &amp; Layout', 'Templates &amp; layouts'],
            'Slice 3b sentence-case Theme settings'          => ['Theme Settings', 'Theme settings'],
            'Slice 4 disambiguated View public site'         => ['See website', 'View public site'],
        ];
    }

    #[Test]
    #[DataProvider('renamedLabelCases')]
    public function renamed_label_is_present_and_old_label_is_gone(string $oldLabel, string $newLabel): void
    {
        // Pre-strip the top-of-file HTML docblock so its prose
        // ("Back to Admin -> Back to admin" rename description)
        // doesn't false-pass the absence check. Recurring
        // selector-self-match guard family per LESSONS.
        $stripped = preg_replace('~<!--[\s\S]*?-->~', '', $this->drawer);

        $this->assertStringContainsString(
            '<span class="mw-main-drawer__item-label">' . $newLabel . '</span>',
            $stripped,
            'New label "' . $newLabel . '" must render in the .mw-main-drawer__item-label span.'
        );
        $this->assertStringNotContainsString(
            '<span class="mw-main-drawer__item-label">' . $oldLabel . '</span>',
            $stripped,
            'Legacy label "' . $oldLabel . '" must be gone from the rendered template.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B  Slice 1 state-aware theme toggle (regression guard only)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function theme_toggle_label_is_action_aware_already(): void
    {
        // Designer's spec: shows "Dark mode" when light is active
        // (action = switch to dark), shows "Light mode" when dark is
        // active (action = switch to light). The existing ternary
        // `theme === 'dark' ? 'Light mode' : 'Dark mode'` matches this
        // exactly — pin as regression guard so future "polish" passes
        // don't accidentally flip the semantics.
        $this->assertStringContainsString(
            "{{ theme === 'dark' ? 'Light mode' : 'Dark mode' }}",
            $this->drawer,
            'Theme toggle label must read `theme === "dark" ? "Light mode" : "Dark mode"` (label = action, NOT current state).'
        );
    }

    #[Test]
    public function theme_toggle_aria_pressed_reflects_current_state(): void
    {
        // Aria-pressed is correctly the CURRENT STATE (not the action).
        // AT users hear "Dark mode toggle, pressed" when in dark mode.
        $this->assertMatchesRegularExpression(
            "/:aria-pressed=\"theme === 'dark' \\? 'true' : 'false'\"/",
            $this->drawer,
            'aria-pressed must reflect current theme state (true when dark is active).'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C  sentence-case across ALL labels (Slice 3 broader sweep)
    // ─────────────────────────────────────────────────────────────────────

    public static function sentenceCaseLabelCases(): array
    {
        // Every drawer item label, with its expected exact rendered text.
        // First-word capitalised, rest lowercase (except acronyms).
        return [
            'EDIT Layers'                  => ['Layers'],
            'EDIT Templates & layouts'     => ['Templates &amp; layouts'],
            'EDIT Theme settings'          => ['Theme settings'],
            'NAVIGATE Pages'               => ['Pages'],
            'NAVIGATE Back to admin'       => ['Back to admin'],
            'NAVIGATE Users'               => ['Users'],
            'NAVIGATE View public site'    => ['View public site'],
            'FOOTER Log out'               => ['Log out'],
        ];
    }

    #[Test]
    #[DataProvider('sentenceCaseLabelCases')]
    public function every_static_label_is_sentence_case(string $expectedLabel): void
    {
        $this->assertStringContainsString(
            '<span class="mw-main-drawer__item-label">' . $expectedLabel . '</span>',
            $this->drawer,
            'Drawer item label "' . $expectedLabel . '" must render exactly (sentence-case Filament convention).'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D  scope discipline: no Title-Case leakage in rendered labels
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function no_legacy_title_case_labels_render(): void
    {
        // Pre-strip HTML docblock (which legitimately mentions legacy
        // labels in the rename description) before the absence check.
        $stripped = preg_replace('~<!--[\s\S]*?-->~', '', $this->drawer);

        // The four legacy Title-Case fragments must not appear as
        // rendered label content in the template body.
        $legacyTitleCaseLabels = [
            'Back to Admin',
            'Template &amp; Layout',
            'Theme Settings',
            'See website',  // also "ambiguous wording" defect
        ];
        foreach ($legacyTitleCaseLabels as $legacy) {
            $this->assertStringNotContainsString(
                '<span class="mw-main-drawer__item-label">' . $legacy . '</span>',
                $stripped,
                'Legacy label "' . $legacy . '" must be gone from rendered template (AI-800 sentence-case + disambiguation pass).'
            );
        }
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group E  markers + lineage
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_and_ai800_markers_present(): void
    {
        $this->assertStringContainsString('task-2026-05-17-f39d53', $this->drawer);
        $this->assertStringContainsString('AI-800', $this->drawer);
    }

    #[Test]
    public function docblock_cites_lineage_and_scope_decision(): void
    {
        $this->assertStringContainsString(
            'AI-700',
            $this->drawer,
            'MainDrawer docblock must cite AI-700 (original drawer ship).'
        );
        $this->assertStringContainsString(
            'AI-708',
            $this->drawer,
            'AI-800 docblock should cite AI-708 (sibling RightSidebar / bootstrap.js naming layer for cross-surface cascade decision).'
        );
        $this->assertStringContainsString(
            'AI-800a',
            $this->drawer,
            'AI-800 docblock should flag AI-800a as the optional cross-surface cascade follow-up.'
        );
    }
}
