<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Contract for two admin-AI usability fixes (2026-11-10):
 *   1. When the AI creates a page, the create_content tool returns the page's
 *      absolute Public URL (+ an "open in Live Edit" URL), and the agents are
 *      told to hand the user clickable links — so you can verify what was built.
 *   2. A reloaded chat folds its tool-call / tool-result turns into one collapsed
 *      "N steps" group (like the swarm board) instead of a flood of raw
 *      "Tool call" / "Tool result" bubbles.
 *
 * Source PRESENCE checks, so the selector-self-match guard does not apply.
 */
class AiCreatedPageLinksAndToolFoldingContractTest extends TestCase
{
    private function read(string $rel): string
    {
        return (string) file_get_contents(base_path($rel));
    }

    // ── 1: created-page links ─────────────────────────────────────────────────

    #[Test]
    public function create_content_tool_returns_the_public_url(): void
    {
        $src = $this->read('Modules/Content/Tools/CreateContentTool.php');
        $this->assertStringContainsString('resolvePublicUrl', $src, 'The tool must resolve the page public URL.');
        $this->assertStringContainsString('Public URL:', $src, 'The tool result must include the Public URL so the model can link it.');
        $this->assertStringContainsString('editmode=y', $src, 'The tool result must include an open-in-Live-Edit URL.');
        $this->assertTrue(
            method_exists(\Modules\Content\Tools\CreateContentTool::class, 'resolvePublicUrl'),
            'CreateContentTool must declare resolvePublicUrl().'
        );
    }

    #[Test]
    public function agents_are_told_to_hand_back_clickable_links(): void
    {
        foreach ([
            'Modules/Ai/Agents/GeneralAgent.php',
            'Modules/Ai/Agents/ContentAgent.php',
        ] as $agent) {
            $src = $this->read($agent);
            $this->assertMatchesRegularExpression(
                '/clickable markdown link|Public URL.*link/si',
                $src,
                "{$agent} must instruct the model to return clickable links to created pages."
            );
        }
    }

    // ── 2: tool-call folding in reloaded chats ────────────────────────────────

    #[Test]
    public function reloaded_chat_folds_tool_turns_into_one_group(): void
    {
        $js = $this->read('packages/frontend-assets/resources/assets/components/mw-ai-conversation.js');
        // The loader recognises persisted tool turns by their message_type…
        $this->assertStringContainsString("message_type", $js);
        $this->assertStringContainsString("tool_call", $js);
        $this->assertStringContainsString("tool_result", $js);
        // …and folds a run of them into one collapsed group rather than bubbles.
        $this->assertStringContainsString('addHistoryToolGroup', $js);
        $this->assertStringContainsString('mw-ai-conv-edits-group', $js);
    }
}
