<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-173 / AI-231 + AI-232 (2026-05-10) — Live Edit sidebar
 * + toolbar overflow batch.
 *
 * agent-test's audit found 5 sub-44 controls in the live-edit
 * chrome and a 56px toolbar overflow at 390×844:
 *
 *   AI-231:
 *     1. `.mw-control-boxclose` panel close icon — 25×25 (4
 *        instances). Existing rule
 *        `.mw-control-box-default .mw-control-boxclose` hard-
 *        sets `width: 20px; height: 20px;` — must override
 *        with `width/height !important` (NOT `min-width` —
 *        the existing rule's fixed `width` would still win).
 *     2. `.reset-template-settings-and-stylesheet-button` —
 *        20×20. Tester flagged this as actively dangerous on
 *        mobile because users will miss-tap and accidentally
 *        reset.
 *     3. `.mw-ai-chat-box-action-voice` — 30×30 (2 instances)
 *     4. `.mw-ai-chat-box-action-send` — 320×40 (height failing)
 *     5. `a.dropdown-trigger[aria-label="Tools menu"]` — 46×35
 *        (height failing)
 *
 *   AI-232:
 *     `<div id="toolbar" class="shadow-sm md:px-6 px-3 gap-3">`
 *     scrollWidth=446 on 390 viewport — overflows by 56px.
 *     Fix: `flex-wrap: wrap` (primary) + `overflow-x: auto`
 *     with scroll-snap (defensive fallback).
 *
 * Note on scoping: the control-box and AI chat panels are
 * portaled to body root, NOT inside the `.mw-live-edit-page`
 * wrapper — direct-class selectors are used because the
 * `.mw-*` class names are themselves live-edit-specific.
 */
class Ai231Ai232LiveEditSidebarToolbarContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function source_carries_cycle_173_anchor(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-mobile.css');
        $this->assertMatchesRegularExpression('/[Cc]ycle-173/', $src,
            'live-edit-mobile.css MUST carry the cycle-173 anchor.');
        $this->assertStringContainsString('AI-231', $src,
            'live-edit-mobile.css MUST carry the AI-231 anchor.');
        $this->assertStringContainsString('AI-232', $src,
            'live-edit-mobile.css MUST carry the AI-232 anchor.');
    }

    #[Test]
    public function ai_231_control_box_close_floored_to_44(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-mobile.css');
        // The existing `.mw-control-box-default .mw-control-boxclose`
        // rule uses width/height, so the override must too.
        $this->assertMatchesRegularExpression(
            '/\.mw-control-box-default\s+\.mw-control-boxclose[\s\S]{0,500}width:\s*44px\s*!important/m',
            $src,
            'live-edit-mobile.css MUST override .mw-control-box-default '
            . '.mw-control-boxclose to width:44px !important — the '
            . 'existing rule hard-sets width:20px so a min-width '
            . 'override would lose. (4 instances)'
        );
        $this->assertMatchesRegularExpression(
            '/\.mw-control-box-default\s+\.mw-control-boxclose[\s\S]{0,500}height:\s*44px\s*!important/m',
            $src,
            'live-edit-mobile.css MUST override height:44px !important '
            . 'on .mw-control-boxclose.'
        );
    }

    #[Test]
    public function ai_231_reset_button_voice_send_tools_floored(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-mobile.css');

        // Reset stylesheet button — actively dangerous, must hit floor.
        $this->assertMatchesRegularExpression(
            '/\.reset-template-settings-and-stylesheet-button[\s\S]{0,400}min-height:\s*44px\s*!important/m',
            $src,
            'live-edit-mobile.css MUST floor '
            . '.reset-template-settings-and-stylesheet-button to '
            . 'min-height:44px !important — tester flagged this as '
            . 'actively dangerous on mobile (users will miss-tap and '
            . 'accidentally reset).'
        );

        // AI chat voice input.
        $this->assertMatchesRegularExpression(
            '/\.mw-ai-chat-box-action-voice[\s\S]{0,400}min-height:\s*44px\s*!important/m',
            $src,
            'live-edit-mobile.css MUST floor .mw-ai-chat-box-action-'
            . 'voice to min-height:44px !important (was 30×30).'
        );

        // AI chat send button — height-only fix (width OK).
        $this->assertMatchesRegularExpression(
            '/\.mw-ai-chat-box-action-send[\s\S]{0,400}min-height:\s*44px\s*!important/m',
            $src,
            'live-edit-mobile.css MUST floor .mw-ai-chat-box-action-'
            . 'send to min-height:44px !important (was 320×40 — '
            . 'width OK, height failing).'
        );

        // Tools menu dropdown trigger.
        $this->assertMatchesRegularExpression(
            '/a\.dropdown-trigger\[aria-label="Tools menu"\][\s\S]{0,400}min-height:\s*44px\s*!important/m',
            $src,
            'live-edit-mobile.css MUST floor a.dropdown-trigger'
            . '[aria-label="Tools menu"] to min-height:44px !important '
            . '(was 46×35 — height failing).'
        );
    }

    #[Test]
    public function ai_232_toolbar_overflow_fixed(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-mobile.css');

        $this->assertMatchesRegularExpression(
            '/\.mw-(?:admin-)?live-edit-page\s+#toolbar[\s\S]{0,400}flex-wrap:\s*wrap\s*!important/m',
            $src,
            'live-edit-mobile.css MUST set flex-wrap:wrap !important '
            . 'on the live-edit #toolbar so it stops overflowing the '
            . '390px viewport (was scrollWidth=446px, overflowed by '
            . '56px).'
        );
        // Defensive fallback — overflow-x scroll with snap.
        $this->assertStringContainsString('scroll-snap-type', $src,
            'live-edit-mobile.css MUST include scroll-snap-type as a '
            . 'defensive fallback if flex-wrap fails (e.g. if a single '
            . 'button exceeds the viewport on a future Filament bump).');
    }

    #[Test]
    public function cycle_173_inside_touch_media_query(): void
    {
        $src = $this->read('packages/microweber-filament-theme/resources/assets/css/microweber/live-edit-mobile.css');

        $anchorPos = strpos($src, 'cycle-173');
        $this->assertNotFalse($anchorPos, 'cycle-173 anchor must be present.');
        // Walk back from the anchor to find the most recent enclosing @media
        $before = substr($src, 0, $anchorPos);
        $mediaPos = strrpos($before, '@media');
        $this->assertNotFalse($mediaPos, 'cycle-173 rules MUST sit inside an @media block.');
        $mediaLine = substr($src, $mediaPos, 100);
        $this->assertMatchesRegularExpression(
            '/max-width:\s*768px/',
            $mediaLine,
            'cycle-173 rules MUST sit inside the live-edit-mobile '
            . '@media (max-width: 768px) block.'
        );
        $this->assertStringContainsString('pointer: coarse', $mediaLine,
            'cycle-173 @media MUST include (pointer: coarse) so real '
            . 'touch devices hit the floor regardless of width.');
    }

    #[Test]
    public function built_bundle_carries_live_edit_floors(): void
    {
        $rel = 'public/vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css';
        $path = base_path($rel);
        if (!file_exists($path)) {
            $this->markTestSkipped("Built filament-theme bundle missing.");
        }
        $built = file_get_contents($path);

        $this->assertStringContainsString(
            '.mw-control-box-default .mw-control-boxclose',
            $built,
            'Built bundle MUST contain the AI-231 control-box close '
            . 'floor rule.'
        );
        $this->assertStringContainsString(
            '.mw-ai-chat-box-action-send',
            $built,
            'Built bundle MUST contain the AI-231 AI chat send button '
            . 'rule.'
        );
        $this->assertStringContainsString(
            '#toolbar',
            $built,
            'Built bundle MUST contain the AI-232 toolbar overflow '
            . 'rule (#toolbar selector).'
        );
    }
}
