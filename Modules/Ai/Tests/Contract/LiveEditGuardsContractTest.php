<?php

declare(strict_types=1);

namespace Modules\Ai\Tests\Contract;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Source-contract guards for the Live-Edit AI frontend tools + agent prompt.
 *
 * These pin the behavioural fixes for two real incidents:
 *   - the AI renamed a NAVIGATION MENU item with set_text (a DOM edit that does
 *     not persist — menus are DB-backed); it must use edit_menu_item.
 *   - the AI wiped the page by delete_element on the content region / a big
 *     section while "improving" the design.
 *
 * The tool logic lives in frontend JS (mw-ai.js) so we assert on the source
 * text, and on the agent's system-prompt instructions (LiveEditAgent.php).
 * DataProviders run pre-boot, so paths are resolved per-test from the repo root.
 */
final class LiveEditGuardsContractTest extends TestCase
{
    private function repoRoot(): string
    {
        // Modules/Ai/Tests/Contract -> repo root is four levels up.
        return dirname(__DIR__, 4);
    }

    private function mwAiSource(): string
    {
        $path = $this->repoRoot() . '/Modules/Ai/resources/assets/js/mw-ai.js';
        $this->assertFileExists($path, 'mw-ai.js frontend tools source is missing.');

        return (string) file_get_contents($path);
    }

    private function agentSource(): string
    {
        $path = $this->repoRoot() . '/Modules/Ai/Agents/LiveEditAgent.php';
        $this->assertFileExists($path, 'LiveEditAgent.php is missing.');

        return (string) file_get_contents($path);
    }

    #[Test]
    public function frontend_has_a_menu_target_detector(): void
    {
        $src = $this->mwAiSource();
        $this->assertStringContainsString('menuTargetInfo', $src,
            'menuTargetInfo() helper (detects DB-backed menu items) must exist.');
        // It should key off the menu module / nav-item markers.
        $this->assertMatchesRegularExpression('/data-type="menu"|nav-item|data-item-id/', $src);
    }

    #[Test]
    public function set_text_and_set_link_refuse_menu_items_and_redirect_to_edit_menu_item(): void
    {
        $src = $this->mwAiSource();

        // Both text and link tools must consult the menu detector and point the
        // model at edit_menu_item instead of writing the DOM.
        $setText = $this->sliceFunction($src, 'set_text: function');
        $this->assertStringContainsString('menuTargetInfo', $setText, 'set_text must guard menu items.');
        $this->assertStringContainsString('edit_menu_item', $setText, 'set_text must redirect to edit_menu_item.');

        $setLink = $this->sliceFunction($src, 'set_link: function');
        $this->assertStringContainsString('menuTargetInfo', $setLink, 'set_link must guard menu items.');
        $this->assertStringContainsString('edit_menu_item', $setLink, 'set_link must redirect to edit_menu_item.');
    }

    #[Test]
    public function delete_element_refuses_structural_containers_and_menu_items(): void
    {
        $del = $this->sliceFunction($this->mwAiSource(), 'delete_element: function');

        // Never delete body/html/head.
        $this->assertMatchesRegularExpression("/'BODY'|BODY/", $del);
        // Never delete the content region (would wipe the page).
        $this->assertStringContainsString('.edit[rel][field]', $del,
            'delete_element must refuse the content region.');
        $this->assertStringContainsString('contentRegion', $del,
            'delete_element must refuse deleting an element that contains the whole content region.');
        // Menu item deletes go through edit_menu_item(remove=true).
        $this->assertStringContainsString('menuTargetInfo', $del);
        $this->assertStringContainsString('remove=true', $del);
    }

    #[Test]
    public function agent_prompt_forbids_dom_menu_edits_and_delete_to_redo(): void
    {
        $prompt = $this->agentSource();

        $this->assertMatchesRegularExpression(
            '/NEVER edit a navigation MENU item with set_text/i',
            $prompt,
            'Agent must be told never to edit menu items via set_text/set_link/delete_element.'
        );
        $this->assertStringContainsString('edit_menu_item(id', $prompt,
            'Agent must be told to use edit_menu_item for menu changes.');
        $this->assertMatchesRegularExpression(
            '/DO NOT DELETE CONTENT TO "REDO"/i',
            $prompt,
            'Agent must be told not to delete content to redo a design.'
        );
    }

    /**
     * Return the source of a frontend tool function, from its declaration to a
     * reasonable bound, so assertions can't match an unrelated tool.
     */
    private function sliceFunction(string $src, string $needle): string
    {
        $start = strpos($src, $needle);
        $this->assertNotFalse($start, "Could not find \"{$needle}\" in mw-ai.js.");

        // ~2.2k chars is comfortably larger than any of these guarded tools.
        return substr($src, $start, 2200);
    }
}
