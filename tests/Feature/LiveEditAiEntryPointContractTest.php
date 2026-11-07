<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Contract: the Live-Edit AI has ONE entry point — the right-rail button, which
 * opens the docked chat (primary). The classic field editor is demoted to a
 * "Quick edit" item in the chat header dropdown, and the floating admin bubble
 * is suppressed on the Live-Edit surface so there is no duplicate trigger.
 *
 * All checks are PRESENCE checks except one absence check, for which the source
 * is pre-stripped of comments so the assertion cannot match its own prose.
 */
class LiveEditAiEntryPointContractTest extends TestCase
{
    private function read(string $rel): string
    {
        return (string) file_get_contents(base_path($rel));
    }

    /** Strip JS block + line comments so an absence assertion can't self-match. */
    private function stripJsComments(string $src): string
    {
        $src = preg_replace('#/\*.*?\*/#s', '', $src) ?? $src;
        $src = preg_replace('#(^|\s)//[^\n]*#', '$1', $src) ?? $src;

        return $src;
    }

    #[Test]
    public function rail_button_opens_the_chat_and_wears_a_chat_icon(): void
    {
        $vue = $this->read('packages/frontend-assets/resources/assets/ui/components/Toolbar/SettingsCustomize.vue');
        // The AI rail button now toggles the docked chat sidebar…
        $this->assertStringContainsString("'mw:ai-chat:toggle'", $vue, 'The rail AI button must open the chat sidebar.');
        // …and carries the speech-bubble icon path (not the old AI-chip svg).
        $this->assertStringContainsString('M21 15a2 2 0 0 1-2 2H7', $vue, 'The rail AI button must use the chat-bubble icon.');
        $this->assertStringNotContainsString('clip-rule="evenodd"', $vue, 'The old AI-chip icon must be gone.');
    }

    #[Test]
    public function chat_sidebar_has_a_quick_edit_mode_dropdown(): void
    {
        $sidebar = $this->read('packages/frontend-assets/resources/assets/services/mw-ai-chat-sidebar.js');
        $this->assertStringContainsString('mw-ai-chat-sidebar-modes', $sidebar, 'The chat header must have the mode dropdown.');
        $this->assertStringContainsString('_openQuickEdit', $sidebar, 'The dropdown must be able to open the field editor.');
        $this->assertStringContainsString('openQuickEditComponent', $sidebar, 'Quick edit must delegate to the Live-Edit field panel.');
    }

    #[Test]
    public function quick_edit_panel_is_fields_only_without_a_chat_mode_bar(): void
    {
        $widgets = $this->stripJsComments(
            $this->read('packages/frontend-assets/resources/assets/live-edit/live-edit-widgets-service.js')
        );
        // The old Edit-fields/AI-Chat switcher bar is removed from the panel.
        $this->assertStringNotContainsString('mw-ai-quick-modes', $widgets, 'The quick-edit panel must not carry the old mode switcher.');
        $this->assertStringNotContainsString('data-mode=', $widgets, 'The quick-edit panel must not carry the old mode buttons.');
    }

    #[Test]
    public function floating_admin_bubble_is_suppressed_in_live_edit(): void
    {
        $provider = $this->read('packages/microweber-filament/src/Providers/MicroweberFilamentServiceProvider.php');
        // The floating trigger is gated on the live-edit path so it does not
        // double up with the right-rail AI button there.
        $this->assertStringContainsString("str_contains((string) request()->path(), 'live-edit')", $provider, 'The floating bubble must be gated on the live-edit surface.');
        $this->assertStringContainsString('mw-admin-ai-chat-trigger', $provider, 'The floating bubble is kept for admin pages.');
    }
}
