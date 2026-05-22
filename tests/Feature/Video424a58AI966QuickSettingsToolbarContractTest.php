<?php

use Tests\TestCase;

/**
 * Contract test — AI-966 / task-2026-05-22-424a58
 *
 * Verifies that the Video module template registers mw.quickSettings.video
 * in Live-Edit mode so the floating toolbar gains video-specific controls
 * (Play-Pause preview + Mute-Unmute).
 *
 * Two-layer selector-self-match guard applied per project protocol:
 * Layer 1 (belt): Blade and JS comments stripped before executable assertions.
 * Layer 2 (suspenders): prose avoids literal source tokens that assertions match.
 */
class Video424a58AI966QuickSettingsToolbarContractTest extends TestCase
{
    private string $src;
    private string $executable;

    protected function setUp(): void
    {
        parent::setUp();

        $raw = (string) file_get_contents(
            base_path('Modules/Video/resources/views/templates/default.blade.php')
        );
        $this->src = $raw;

        // Strip Blade block comments and JS block comments before executable checks.
        $stripped = preg_replace('~\{\{--[\s\S]*?--\}\}~s', '', $raw);
        $stripped = preg_replace('~/\*[\s\S]*?\*/~s', '', $stripped);
        $stripped = preg_replace('~//[^\n]*~', '', $stripped);
        $this->executable = $stripped;
    }

    // ── Group A: registration block presence ─────────────────────────────────

    public function test_quicksettings_registration_is_gated_by_is_live_edit(): void
    {
        // The registration script must only run on the live-edit canvas.
        $this->assertStringContainsString(
            "is_live_edit()",
            $this->executable,
            'mw.quickSettings registration must be inside an is_live_edit() guard'
        );
        // Verify it wraps the quickSettings assignment (not just other code).
        $pos = strrpos($this->executable, 'mw.quickSettings.video');
        $this->assertNotFalse($pos, 'mw.quickSettings.video assignment not found');
        // Find the preceding @if(is_live_edit())
        $before = substr($this->executable, 0, $pos);
        $this->assertStringContainsString('@if(is_live_edit())', $before,
            'mw.quickSettings.video assignment must appear after an is_live_edit() check');
    }

    public function test_global_dedup_guard_prevents_double_registration(): void
    {
        $this->assertStringContainsString(
            '_mwVideoQSRegistered',
            $this->executable,
            'A global flag must guard against registering quickSettings multiple times when several video modules are on the same canvas page'
        );
    }

    public function test_quicksettings_video_array_defined(): void
    {
        $this->assertStringContainsString(
            'mw.quickSettings.video',
            $this->executable,
            'Template must assign mw.quickSettings.video so the toolbar dynamic menu slot is populated'
        );
    }

    // ── Group B: play-pause button ───────────────────────────────────────────

    public function test_play_pause_action_calls_video_play_and_pause(): void
    {
        $this->assertStringContainsString('vid.play()', $this->executable,
            'Play-Pause action must call video.play() for a paused native video');
        $this->assertStringContainsString('vid.pause()', $this->executable,
            'Play-Pause action must call video.pause() for a playing native video');
    }

    public function test_play_pause_ontarget_hides_button_for_iframes(): void
    {
        // onTarget must check for a video element and hide the button when absent.
        $this->assertStringContainsString(
            "target.querySelector('video')",
            $this->executable,
            'onTarget must query for a native video element to determine button visibility'
        );
        $this->assertStringContainsString(
            "selfNode.style.display",
            $this->executable,
            'onTarget must set display style to hide/show button based on video presence'
        );
    }

    public function test_play_pause_button_swaps_icon_to_reflect_state(): void
    {
        // onTarget should update innerHTML to toggle between play and pause icons.
        $this->assertStringContainsString(
            'vid.paused',
            $this->executable,
            'onTarget must check video.paused to determine which icon to show'
        );
    }

    // ── Group C: mute-unmute button ──────────────────────────────────────────

    public function test_mute_toggle_action_flips_muted_property(): void
    {
        $this->assertStringContainsString(
            'vid.muted = !vid.muted',
            $this->executable,
            'Mute action must toggle vid.muted'
        );
    }

    public function test_mute_button_ontarget_hides_for_iframes(): void
    {
        // Count querySelector calls — there should be at least two (one per button).
        $count = substr_count($this->executable, "target.querySelector('video')");
        $this->assertGreaterThanOrEqual(2, $count,
            'Both Play-Pause and Mute buttons must each check for a native video element in their onTarget callbacks');
    }

    // ── Group D: markers and task-id ─────────────────────────────────────────

    public function test_task_id_marker_present(): void
    {
        $this->assertStringContainsString(
            'task-2026-05-22-424a58',
            $this->src,
            'Template must carry the AI-966 task-id marker'
        );
    }

    public function test_ai_966_ticket_reference_present(): void
    {
        $this->assertStringContainsString(
            'AI-966',
            $this->src,
            'Template must reference AI-966 for audit trail'
        );
    }

    // ── Group E: regression guards ───────────────────────────────────────────

    public function test_live_edit_play_pause_handler_still_excluded(): void
    {
        // AI-1010 guard: the existing DOM-ready play/pause jQuery handlers
        // must still be inside a is_live_edit() else-branch (not-live-edit guard).
        $this->assertStringContainsString(
            "if(!is_live_edit())",
            $this->executable,
            'AI-1010 guard: jQuery play/pause DOM handler must remain excluded from live-edit mode'
        );
    }

    public function test_registration_script_runs_in_canvas_context(): void
    {
        // The script must NOT use parent.mw or top.mw — it runs in the canvas iframe.
        $this->assertStringNotContainsString(
            'parent.mw.quickSettings',
            $this->executable,
            'Registration must use window.mw (canvas iframe context), not parent.mw'
        );
        $this->assertStringNotContainsString(
            'top.mw.quickSettings',
            $this->executable,
            'Registration must use window.mw (canvas iframe context), not top.mw'
        );
    }
}
