<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-b91c0c / AI-810 — Live-edit resolution switcher
 * clipped above viewport at y=-19.5px (Stage-3 viewport-scope leak
 * family, vertical axis).
 *
 * Jira: https://microweber.atlassian.net/browse/AI-810
 * Priority: High
 *
 * Pre-fix:
 * - `Modules/...Toolbar/Toolbar.vue` line ~107 had the inner right-
 *   col container with `class="d-flex align-items-center flex-wrap
 *   gap-2"`. Bootstrap `flex-wrap` utility lets the children stack
 *   to a 2nd row when their total width exceeds the right col's
 *   flex share.
 * - When wrapping occurred, `.toolbar-col`'s natural content height
 *   grew to ~67-90px (2 rows of 35px buttons + gap).
 * - `#toolbar` has `height: 48px` (--toolbar-height per AI-698a /
 *   task-ec0c87) + `align-items: center`. The over-tall .toolbar-col
 *   was flex-centred to y = 24 - (col_height / 2), putting the TOP
 *   at a negative y. Designer's DOM probe at 1440×900:
 *   #preview-nav (35px tall) rendered at y=-19.5px — top half
 *   clipped above viewport edge.
 *
 * Post-fix (two-layer defense):
 *   (1) Toolbar.vue inner row class flex-wrap → flex-nowrap so
 *       items stay on one row regardless of right-col width.
 *   (2) `.toolbar-col` rule in index.css gains explicit
 *       `min-height: var(--toolbar-height); height: var(--toolbar-
 *       height); align-items: center` so the col is anchored to the
 *       toolbar's height token even if a future child grows beyond
 *       budget — the col itself stays at 48px and items inside
 *       centre vertically within that frame.
 *
 * This is the Stage-3 viewport-scope leak family applied to the
 * vertical axis — a layout rule (flex-wrap) tolerated at the old
 * 60px toolbar broke at the new 48px toolbar. Same family as
 * AI-803 (CSS rule meant for one viewport applied to all).
 *
 * Designer's tier-3 probe:
 *   expect(
 *     document.querySelector('#preview-nav').getBoundingClientRect().top
 *   ).toBeGreaterThanOrEqual(0);
 *
 * NOTE on the selector-self-match guard family (17+ session-
 * recurrences): this docblock legitimately mentions `flex-wrap`
 * as the pre-fix marker. Group A's absence assertion pre-strips
 * Blade HTML comments before scanning the executable template
 * body so the AI-810 docblock comment inside Toolbar.vue cannot
 * false-fail the absence guard.
 */
class LiveEditB91c0cAI810ResolutionSwitcherClippingContractTest extends TestCase
{
    private string $toolbarVue;
    private string $indexCss;
    private string $toolbarExecutable;
    private string $indexCssExecutable;

    protected function setUp(): void
    {
        parent::setUp();
        $this->toolbarVue = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/ui/components/Toolbar/Toolbar.vue'
        ));
        $this->indexCss = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/ui/css/index.css'
        ));

        // Strip HTML comments + Blade {{-- --}} comments + CSS
        // /* */ comments before absence assertions (selector-self-
        // match guard family).
        $this->toolbarExecutable = preg_replace('~<!--[\s\S]*?-->~', '', $this->toolbarVue);
        $this->toolbarExecutable = preg_replace('~\{\{--[\s\S]*?--\}\}~', '', $this->toolbarExecutable);
        $this->indexCssExecutable = preg_replace('~/\*[\s\S]*?\*/~', '', $this->indexCss);
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A  Toolbar.vue inner-row fix (flex-wrap → flex-nowrap)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function inner_right_col_row_uses_flex_nowrap_not_flex_wrap(): void
    {
        // Positive: inner row carries `flex-nowrap` class.
        $this->assertMatchesRegularExpression(
            '/<div\s+class="d-flex\s+align-items-center\s+flex-nowrap\s+gap-2">\s*<ResolutionSwitch/',
            $this->toolbarExecutable,
            'AI-810: inner right-col row (the one containing <ResolutionSwitch>) MUST carry `flex-nowrap` so items stay on one row at every viewport ≥ 1024px.'
        );

        // Negative: the exact pre-fix shape must be gone.
        $this->assertDoesNotMatchRegularExpression(
            '/<div\s+class="d-flex\s+align-items-center\s+flex-wrap\s+gap-2">\s*<ResolutionSwitch/',
            $this->toolbarExecutable,
            'AI-810: the legacy `flex-wrap` (allowing 2-row stacking that triggered y<0 clipping) MUST be gone from the inner row.'
        );
    }

    #[Test]
    public function resolution_switch_is_first_child_of_inner_row(): void
    {
        // Pin the structural assumption: <ResolutionSwitch> is the
        // first child of the inner d-flex row. If a future refactor
        // moves it elsewhere, this test fails so we can re-verify
        // the AI-810 clip-fix still applies at the new position.
        $this->assertMatchesRegularExpression(
            '/<div\s+class="d-flex\s+align-items-center\s+flex-nowrap\s+gap-2">\s*<ResolutionSwitch>/',
            $this->toolbarExecutable,
            'AI-810: <ResolutionSwitch> MUST remain the first child of the inner right-col row (clip-fix assumption).'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B  .toolbar-col CSS defense rule
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function toolbar_col_anchors_height_to_toolbar_height_token(): void
    {
        // The .toolbar-col rule body MUST declare min-height +
        // height pinned to var(--toolbar-height) so the right col
        // can never exceed the toolbar's 48px frame even if a
        // future child grows beyond budget.
        if (! preg_match('/\.toolbar-col\s*\{([^}]+)\}/', $this->indexCssExecutable, $m)) {
            $this->fail('AI-810: could not locate .toolbar-col rule body in index.css.');
        }
        $body = $m[1];

        $this->assertMatchesRegularExpression(
            '/min-height:\s*var\(--toolbar-height\)/',
            $body,
            'AI-810: .toolbar-col MUST declare `min-height: var(--toolbar-height)` (anchors col to toolbar frame, prevents vertical overflow centring to negative y).'
        );
        $this->assertMatchesRegularExpression(
            '/(?<!min-)height:\s*var\(--toolbar-height\)/',
            $body,
            'AI-810: .toolbar-col MUST declare `height: var(--toolbar-height)` (explicit fixed frame, not just min-height).'
        );
        $this->assertMatchesRegularExpression(
            '/align-items:\s*center/',
            $body,
            'AI-810: .toolbar-col MUST declare `align-items: center` so items inside centre vertically within the 48px frame.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C  back-compat / no-regression on adjacent rules
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function toolbar_col_existing_layout_properties_preserved(): void
    {
        // Don't drop the existing display:flex / gap:20px / flex-wrap:
        // nowrap properties when adding the height-anchor.
        if (! preg_match('/\.toolbar-col\s*\{([^}]+)\}/', $this->indexCssExecutable, $m)) {
            $this->fail('Could not locate .toolbar-col rule body.');
        }
        $body = $m[1];

        foreach (['display:\s*flex', 'flex-wrap:\s*nowrap', 'gap:\s*20px'] as $expected) {
            $this->assertMatchesRegularExpression(
                "/{$expected}/",
                $body,
                'AI-810: existing .toolbar-col layout property MUST be preserved (regression guard).'
            );
        }
    }

    #[Test]
    public function toolbar_height_token_definition_preserved(): void
    {
        // The fix relies on --toolbar-height being defined at :root.
        // Pin its presence + the AI-698a 48px value so an unrelated
        // change to the token doesn't break AI-810 silently.
        $this->assertMatchesRegularExpression(
            '/--toolbar-height:\s*48px/',
            $this->indexCss,
            'AI-810 depends on --toolbar-height existing at :root with the AI-698a value (48px). If this changes, the .toolbar-col anchor must follow.'
        );
    }

    #[Test]
    public function toolbar_height_fixed_at_48px_unchanged(): void
    {
        // #toolbar { height: var(--toolbar-height); } MUST stay.
        $this->assertMatchesRegularExpression(
            '/#toolbar\s*\{[^}]*height:\s*var\(--toolbar-height\)/',
            $this->indexCss,
            'AI-810 depends on #toolbar consuming the token. If #toolbar height stops following the token, AI-810 may regress silently.'
        );
    }

    #[Test]
    public function resolution_switch_aria_contract_preserved(): void
    {
        // Belt-and-braces: <ResolutionSwitch> still imports + renders.
        // (Not asserting full ARIA shape here — that's a separate
        // ResolutionSwitch contract test; just confirming the
        // component is still referenced from Toolbar.vue.)
        $this->assertStringContainsString(
            '<ResolutionSwitch></ResolutionSwitch>',
            $this->toolbarExecutable,
            'AI-810 fix MUST NOT accidentally remove the <ResolutionSwitch> element from the toolbar.'
        );
        $this->assertStringContainsString(
            "import ResolutionSwitch from",
            $this->toolbarVue,
            'AI-810 fix MUST NOT accidentally remove the ResolutionSwitch import.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D  task-id markers
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function task_id_and_ai810_markers_present(): void
    {
        $this->assertStringContainsString('task-2026-05-17-b91c0c', $this->toolbarVue);
        $this->assertStringContainsString('AI-810', $this->toolbarVue);
        $this->assertStringContainsString('task-2026-05-17-b91c0c', $this->indexCss);
        $this->assertStringContainsString('AI-810', $this->indexCss);
    }
}
