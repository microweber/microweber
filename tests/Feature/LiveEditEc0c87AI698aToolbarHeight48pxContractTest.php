<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-16-ec0c87 / AI-698a (Medium) — Live-edit toolbar
 * height lock: 60px → 48px.
 *
 * Designer dispatch (live-edit-inspiration-from-v2-2026-05-16.md §2
 * P2, per-ticket email 2026-05-16T13:39) requires four sub-items:
 *
 *   1. device-preview → MwSegmented 2-icon strip
 *   2. 3 tools (paint/drop/kebab) → `⋯ Tools` MwToolButton popover
 *   3. existing side-panel triggers → `☰` hamburger opening AI-700
 *      drawer
 *   4. toolbar height lock to 48px (matches ESE §3.2 mobile-header)
 *
 * Item 3 hard-depends on AI-700 (drawer consolidation) which hasn't
 * shipped — the hamburger has nothing to open. Per the established
 * sub-slicing pattern (ESE 1.3 → 1.3a + 1.3b, designer-accepted),
 * AI-698 is sub-sliced:
 *
 *   AI-698a (THIS SLICE) — item 4 only: lock --toolbar-height to
 *     48px. Well-bounded; cascades to every existing consumer
 *     (`#toolbar`, `#live-edit-frame-holder` top + height calc,
 *     AI-697 anchored-picker `top` calc, SaveButton row); no AI-700
 *     dependency.
 *
 *   AI-698b (DEFERRED) — items 1+2+3: 9-element layout grouping +
 *     hamburger. Ships alongside AI-700 so the hamburger has a
 *     drawer to open.
 *
 * Cross-surface impact of the 48px change (documented for browser
 * verification):
 *
 *   - `#toolbar { height: var(--toolbar-height) }` → toolbar shrinks
 *     by 12px
 *   - `#live-edit-frame-holder { top: var(--toolbar-height); height:
 *     calc(100% - var(--toolbar-height) - var(--iframe-height-minus))
 *     }` → canvas shifts up 12px + grows 12px (stays flush)
 *   - AI-697 anchored picker modal reads `var(--toolbar-height,
 *     60px)` for its `top` calc — moves up 12px (literal fallback
 *     60px is NOT used because the token IS defined)
 *   - Live Edit mobile rules in `live-edit-mobile.css` consume the
 *     same token; mobile toolbar also shrinks 12px
 *   - The original task-0ff040 fix (mobile fi-main-ctn padding-top
 *     removal, SUMMARY.md Decisions row) used 56px/60px math — the
 *     48px change preserves the "no padding-top" property (60→48 is
 *     still ≥ 0)
 */
class LiveEditEc0c87AI698aToolbarHeight48pxContractTest extends TestCase
{
    private string $indexCss;
    private string $builtFrontendDist;

    protected function setUp(): void
    {
        parent::setUp();
        $this->indexCss = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/ui/css/index.css'
        ));
        // index.css source is the bundle entry-point for live-edit-app
        // (Vue's live-edit chunk), NOT admin.css. The Vite output that
        // carries the `--toolbar-height` token is
        // `packages/frontend-assets/resources/dist/build/live-edit-app.css`.
        $builtPath = base_path('packages/frontend-assets/resources/dist/build/live-edit-app.css');
        $this->builtFrontendDist = is_file($builtPath) ? (string) file_get_contents($builtPath) : '';
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — Token value lock at 48px
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function toolbar_height_token_locked_to_48px(): void
    {
        $this->assertMatchesRegularExpression(
            '/--toolbar-height:\s*48px\s*;/',
            $this->indexCss,
            'Source --toolbar-height must be locked to 48px per AI-698a spec (was 60px).'
        );
    }

    #[Test]
    public function legacy_60px_value_no_longer_assigned_to_toolbar_height(): void
    {
        // Regression guard — the previous `--toolbar-height: 60px`
        // assignment must NOT survive. Other 60px values elsewhere
        // in the file (e.g. `--toolbar-static-height: 70px`,
        // `--layouts-dialog-toolbar-height: 60px`) are different
        // tokens and stay; this guard targets ONLY the renamed
        // toolbar-height token.
        $this->assertDoesNotMatchRegularExpression(
            '/--toolbar-height:\s*60px\s*;/',
            $this->indexCss,
            '--toolbar-height: 60px must not appear (was the pre-AI-698a value).'
        );
    }

    #[Test]
    public function token_carries_task_id_marker_and_rationale(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-16-ec0c87',
            $this->indexCss,
            'index.css must carry the AI-698a task-id marker near the toolbar-height redefinition.'
        );
        $this->assertStringContainsString(
            'AI-698a',
            $this->indexCss
        );
        // Sub-slicing rationale captured inline so future agents
        // understand AI-698b is the layout-grouping continuation.
        $this->assertStringContainsString(
            'AI-698b',
            $this->indexCss,
            'index.css must reference AI-698b in the docblock so the sub-slicing rationale is discoverable from the source.'
        );
        $this->assertStringContainsString(
            'AI-700',
            $this->indexCss,
            'index.css docblock must mention AI-700 (drawer consolidation) as the AI-698b dependency.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — Cross-surface token consumers remain in place
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function toolbar_rule_still_consumes_token(): void
    {
        // `#toolbar { height: var(--toolbar-height) }` must remain
        // — that's the consumer that shrinks the toolbar via the
        // token change.
        $this->assertMatchesRegularExpression(
            '/#toolbar\s*\{[^}]*height:\s*var\(--toolbar-height\)/s',
            $this->indexCss,
            '#toolbar rule must still read height from var(--toolbar-height).'
        );
    }

    #[Test]
    public function canvas_frame_holder_still_consumes_token(): void
    {
        // The canvas must shift WITH the toolbar, not stay at the
        // old 60px gap (which would create a 12px dead zone).
        $this->assertMatchesRegularExpression(
            '/#live-edit-frame-holder\s*\{[^}]*top:\s*var\(--toolbar-height\)/s',
            $this->indexCss,
            '#live-edit-frame-holder top must consume var(--toolbar-height) so it shifts with the toolbar.'
        );
        $this->assertMatchesRegularExpression(
            '/#live-edit-frame-holder\s*\{[^}]*height:\s*calc\(100%\s*-\s*var\(--toolbar-height\)/s',
            $this->indexCss,
            '#live-edit-frame-holder height must subtract var(--toolbar-height) to stay flush.'
        );
    }

    #[Test]
    public function unrelated_60px_tokens_preserved(): void
    {
        // --layouts-dialog-toolbar-height: 60px is a SEPARATE token
        // for a different surface (in-canvas layout-picker dialog).
        // AI-698a must not touch it.
        $this->assertStringContainsString(
            '--layouts-dialog-toolbar-height: 60px',
            $this->indexCss,
            '--layouts-dialog-toolbar-height: 60px must be preserved — AI-698a only changes --toolbar-height.'
        );
        // --toolbar-static-height: 70px is also a different token
        // (used by some legacy surfaces).
        $this->assertStringContainsString(
            '--toolbar-static-height: 70px',
            $this->indexCss,
            '--toolbar-static-height: 70px must be preserved.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — Built bundle reflects the change
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function built_live_edit_app_css_carries_48px_value(): void
    {
        if ($this->builtFrontendDist === '') {
            $this->markTestSkipped('packages/frontend-assets/resources/dist/build/live-edit-app.css not present — run `cd packages/frontend-assets && npm run build` to verify.');
        }
        $this->assertMatchesRegularExpression(
            '/--toolbar-height:\s*48px/',
            $this->builtFrontendDist,
            'Vite-built live-edit-app.css must carry the new --toolbar-height: 48px value (re-run `npm run build` if stale).'
        );
    }

    #[Test]
    public function built_live_edit_app_css_does_not_retain_legacy_60px_toolbar_height(): void
    {
        if ($this->builtFrontendDist === '') {
            $this->markTestSkipped('packages/frontend-assets/resources/dist/build/live-edit-app.css not present.');
        }
        // The built bundle contains `--toolbar-height: 48px` as the
        // single AI-698a token + `--layouts-dialog-toolbar-height: 60px`
        // / `--toolbar-static-height: 70px` (unrelated tokens). Use a
        // negative-lookbehind anchored to the exact toolbar-height
        // token name so other "*-toolbar-height" tokens aren't matched.
        $this->assertDoesNotMatchRegularExpression(
            '/(?<![-a-z])--toolbar-height:\s*60px/',
            $this->builtFrontendDist,
            'Built live-edit-app.css must NOT carry --toolbar-height: 60px (the old value) — only --layouts-dialog-toolbar-height + --toolbar-static-height retain their original values.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group D — Sub-slicing scope discipline
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function ai698a_does_not_introduce_hamburger_dom_changes(): void
    {
        // AI-698a is the HEIGHT-LOCK slice only. The hamburger / `⋯
        // Tools` popover / MwSegmented device-preview wrapping all
        // ship in AI-698b. Pin that AI-698a did NOT add toolbar-DOM
        // change markers (the popover, hamburger button hooks).
        // Search just the index.css file (the only file touched by
        // this slice).
        $this->assertStringNotContainsString(
            'mw-toolbar-hamburger',
            $this->indexCss,
            'AI-698a is height-lock only — must not introduce hamburger DOM hooks.'
        );
        $this->assertStringNotContainsString(
            'mw-toolbar-tools-popover',
            $this->indexCss,
            'AI-698a is height-lock only — must not introduce `⋯ Tools` popover DOM hooks.'
        );
    }
}
