<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-83 / AI-70 / TICKET-LL — control-box display:none on inactive
 * regression coverage.
 *
 * Pins the contract that:
 *   - ControlBox.hide() schedules a setTimeout that sets
 *     `display: none` on the box AFTER the .5s slide-out
 *     transition completes. This drops the box from layout AND
 *     releases the GPU composite layer.
 *   - The timer re-checks `#active` before applying display:none
 *     so a rapid hide → show sequence doesn't accidentally hide
 *     the now-active panel.
 *   - ControlBox.show() clears `display: ''` BEFORE adding the
 *     .active class, AND uses requestAnimationFrame double-tick
 *     so the slide-in transition has a non-display:none starting
 *     frame to animate from.
 *
 * Style after the cycle-52..82 contract tests (file-system reads only,
 * no DB touch). Per project memory `feedback_testing`: contract tests
 * never mount Filament resources or hit MySQL.
 */
class ControlBoxDisplayNoneContractTest extends TestCase
{
    private string $controlBoxSrc;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controlBoxSrc = file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/components/control_box.js'
        ));
    }

    #[Test]
    public function hide_schedules_display_none_after_transition(): void
    {
        // setTimeout call must exist inside hide(). We pin both the
        // delay constant + the actual style assignment.
        $this->assertStringContainsString(
            'const HIDE_TRANSITION_MS = 550;',
            $this->controlBoxSrc,
            'control_box.js: hide() must declare HIDE_TRANSITION_MS = 550 (.5s transition + 50ms safety margin)'
        );
        $this->assertStringContainsString(
            "this.box.style.display = 'none';",
            $this->controlBoxSrc,
            'control_box.js: hide() must set this.box.style.display = "none" after transition'
        );
        $this->assertStringContainsString(
            'setTimeout(() => {',
            $this->controlBoxSrc,
            'control_box.js: hide() must wrap the display:none assignment in setTimeout'
        );
    }

    #[Test]
    public function hide_timer_rechecks_active_to_handle_rapid_show(): void
    {
        // Without the re-check, a rapid hide() → show() sequence
        // would hide the now-active panel when the timer fires.
        // Pin the `if (!this.#active && this.box)` guard.
        $this->assertStringContainsString(
            'if (!this.#active && this.box)',
            $this->controlBoxSrc,
            'control_box.js: hide() setTimeout callback must re-check `if (!this.#active && this.box)` so a rapid hide→show does not hide the now-active panel'
        );
    }

    #[Test]
    public function show_clears_display_before_animation(): void
    {
        // show() must clear style.display BEFORE adding .active so
        // the slide-in transition has a non-display:none starting
        // frame to animate from.
        $this->assertStringContainsString(
            "this.box.style.display = '';",
            $this->controlBoxSrc,
            'control_box.js: show() must clear style.display before adding .active'
        );
        // The clear must come BEFORE the addClass('active') call —
        // pinned via byte-offset comparison.
        $clearPos = strpos(
            $this->controlBoxSrc,
            "this.box.style.display = '';"
        );
        $addClassPos = strpos(
            $this->controlBoxSrc,
            "mw.$(this.box).addClass('active');"
        );
        $this->assertNotFalse($clearPos);
        $this->assertNotFalse($addClassPos);
        $this->assertLessThan(
            $addClassPos,
            $clearPos,
            'control_box.js: show() must clear display BEFORE adding .active (byte-offset compare)'
        );
    }

    #[Test]
    public function show_uses_double_raf_for_animation_start_frame(): void
    {
        // requestAnimationFrame inside requestAnimationFrame is the
        // canonical pattern for "ensure layout has flushed before the
        // class change so the browser treats it as a transition
        // start". Without the double-rAF, a synchronous
        // display:'' → addClass('active') in the same tick collapses
        // into one paint and the slide-in animation is skipped.
        $this->assertMatchesRegularExpression(
            '/requestAnimationFrame\\(\\(\\)\\s*=>\\s*\\{\\s*\\n\\s*requestAnimationFrame/s',
            $this->controlBoxSrc,
            'control_box.js: show() must use a double-rAF wrapper around addClass("active") so the slide-in transition has a starting frame'
        );
    }

    #[Test]
    public function hide_all_by_side_is_invoked_on_show(): void
    {
        // The original cycle-pre-83 logic already had this — pin
        // it stays so future refactors don't regress the
        // single-panel-active invariant from the JS side. The CSS
        // display:none on inactive is the cycle-83 reinforcement;
        // hideAllBySide on show is the JS counterpart.
        $this->assertMatchesRegularExpression(
            "/show\\(\\)\\s*\\{\\s*\\n\\s*ControlBox\\.hideAllBySide\\(this\\.settings\\.position,\\s*this\\)/s",
            $this->controlBoxSrc,
            'control_box.js: show() must invoke ControlBox.hideAllBySide(this.settings.position, this) FIRST so other panels on the same side are hidden before this one shows'
        );
    }
}
