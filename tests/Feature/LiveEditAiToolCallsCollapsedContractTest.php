<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Contract: in the AI chat, all of a turn's tool-call chips collapse under ONE
 * block (a summary pill that expands to the individual chips), so a long build
 * no longer fills the conversation with dozens of separate rows.
 *
 * Presence checks against the conversation component source.
 */
class LiveEditAiToolCallsCollapsedContractTest extends TestCase
{
    private string $src = '';

    protected function setUp(): void
    {
        parent::setUp();
        $this->src = (string) file_get_contents(base_path(
            'packages/frontend-assets/resources/assets/components/mw-ai-conversation.js'
        ));
    }

    #[Test]
    public function tool_chips_are_grouped_under_one_collapsible_block(): void
    {
        // The per-turn edits container becomes a collapsible group with a list.
        $this->assertStringContainsString('_ensureEditsGroup', $this->src, 'A collapsible edits group must be built per turn.');
        $this->assertStringContainsString('mw-ai-conv-edits-group', $this->src, 'The edits container must become a group.');
        $this->assertStringContainsString('mw-ai-conv-edits-list', $this->src, 'The chips must live in a collapsible list.');
        // Collapsed by default.
        $this->assertStringContainsString('"mw-ai-conv-edits-group", "is-collapsed"', $this->src, 'The block must start collapsed.');
    }

    #[Test]
    public function group_has_a_summary_header_and_chips_go_into_the_list(): void
    {
        $this->assertStringContainsString('mw-ai-conv-edits-head', $this->src, 'The group must have a summary header.');
        $this->assertStringContainsString('mw-ai-conv-edits-summary', $this->src, 'The header must show a step count summary.');
        // Chips are appended to the list, not straight into the container.
        $this->assertStringContainsString('list.appendChild(wrap)', $this->src, 'Each tool chip must be appended into the group list.');
    }
}
