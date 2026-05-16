<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * task-2026-05-17-d994e7 / AI-697 v3 CHANGE — anchor positioning
 * + backdrop transparency cascade fight.
 *
 * Designer verified the previous AI-697 re-ship (`34d309792b`
 * task-bc28fd) reached the loaded stylesheet (defect-class fix
 * worked) but the rendered modal still rendered off-screen left
 * + still had a dimmed backdrop. Runtime computed style at 1440:
 *
 *   position:       fixed                                    ✅
 *   top:            54.112px                                 ✅
 *   left:           64px (inset-inline-start applied)        ✅
 *   transform:      matrix(1,0,0,1,-336,0) (translateX -50%) ✗
 *   final visual:   -272px (off-screen LEFT)                 ✗
 *   overlay bg:     rgba(0,0,0,0.55) (still dimmed)          ✗
 *
 * Root cause: the picker modal carries BOTH classes
 * `.mw-content-form-modal .mw-content-picker-modal`. The existing
 * `.mw-content-form-modal` rule applies `transform: translateX(
 * -50%)`. Our AI-697 rule set inset-inline-start + margin: 0
 * but never cleared `transform` — the two stacked off-screen.
 * Backdrop transparency similarly lost the cascade to Filament's
 * `.fi-modal-close-overlay` selector specificity.
 *
 * Fix per designer's recommended additions:
 *   1. Add `transform: none` to the picker modal rule.
 *   2. Bump `.fi-modal-close-overlay` background-color to
 *      !important + add descendant variant alongside `>`
 *      combinator (belt-and-braces).
 */
class LiveEditD994e7AI697V3CascadeFixContractTest extends TestCase
{
    private string $css;

    protected function setUp(): void
    {
        parent::setUp();
        $this->css = (string) file_get_contents(base_path(
            'packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-classes.css'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group A — transform: none neutralises inherited translateX(-50%)
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function picker_modal_rule_carries_transform_none(): void
    {
        // Slice the picker modal positioning rule + assert it
        // explicitly sets `transform: none`.
        $start = strpos($this->css, '.fi-modal:has(> .fi-modal-window-ctn .mw-content-picker-modal) .fi-modal-window.mw-content-picker-modal');
        $this->assertNotFalse($start);
        $end = strpos($this->css, '}', $start);
        $this->assertNotFalse($end);
        $slice = substr($this->css, $start, $end - $start);
        $this->assertMatchesRegularExpression(
            '/transform:\s*none/i',
            $slice,
            'Picker modal rule must explicitly set `transform: none` to neutralise inherited .mw-content-form-modal translateX(-50%).'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group B — backdrop overlay transparent wins cascade
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function close_overlay_background_uses_important(): void
    {
        $this->assertMatchesRegularExpression(
            '/\.fi-modal-close-overlay[\s\S]*?background-color:\s*transparent\s*!important/i',
            $this->css,
            '.fi-modal-close-overlay rule must use !important to win cascade against Filament default backdrop.'
        );
    }

    #[Test]
    public function close_overlay_selector_includes_descendant_variant(): void
    {
        // Belt-and-braces: both direct-child `>` AND descendant
        // variants — Filament builds may nest the overlay deeper.
        $this->assertMatchesRegularExpression(
            '/\.fi-modal:has\(> \.fi-modal-window-ctn \.mw-content-picker-modal\) > \.fi-modal-close-overlay,\s*\.fi-modal:has\(> \.fi-modal-window-ctn \.mw-content-picker-modal\) \.fi-modal-close-overlay/',
            $this->css,
            'Close-overlay rule must include both `> .fi-modal-close-overlay` and ` .fi-modal-close-overlay` (descendant) selectors per AI-697 v3 belt-and-braces.'
        );
    }

    // ─────────────────────────────────────────────────────────────────────
    // Group C — markers
    // ─────────────────────────────────────────────────────────────────────

    #[Test]
    public function v3_change_markers_pinned(): void
    {
        $this->assertStringContainsString('task-2026-05-17-d994e7', $this->css);
        $this->assertStringContainsString('AI-697 v3 CHANGE', $this->css);
        // Audit chain — original ship hash / original task-id should
        // still be discoverable via grep.
        $this->assertStringContainsString(
            'task-2026-05-16-e3da1a',
            $this->css,
            'Original AI-697 task-id (task-e3da1a) must remain in the comment for audit-chain continuity.'
        );
    }
}
