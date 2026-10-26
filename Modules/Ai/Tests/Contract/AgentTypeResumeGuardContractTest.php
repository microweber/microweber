<?php

declare(strict_types=1);

namespace Modules\Ai\Tests\Contract;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Source-contract guard for the agent-type resume mismatch fix.
 *
 * Both chat endpoints build the agent from the RESUMED chat's stored agent_type
 * (AgentFactory::agentWithChat). So if a surface resumes a chat created by a
 * DIFFERENT agent — e.g. the Live-Edit box (agent_type=liveedit) picking up an old
 * 'customer' chat — it would run that agent's wrong tool set: the model then has
 * only read-only tools, cannot edit, and silently fails. The endpoints must start a
 * fresh chat of the REQUESTED type when the resumed chat's type differs.
 */
final class AgentTypeResumeGuardContractTest extends TestCase
{
    private function controllerSource(): string
    {
        $p = dirname(__DIR__, 4) . '/Modules/Ai/Http/Controllers/AiController.php';
        $this->assertFileExists($p);

        return (string) file_get_contents($p);
    }

    #[Test]
    public function both_chat_endpoints_guard_against_agent_type_mismatch_on_resume(): void
    {
        $src = $this->controllerSource();

        // The guard: when a resumed chat's agent_type differs from the requested
        // agent_type, recreate a chat of the requested type. Pin that both the
        // streaming (agentChatStream) and the non-streaming (agentChat) paths carry
        // a `$chat->agent_type !== $agentType` check that recreates the chat.
        $matches = preg_match_all(
            '~\$chat->agent_type\s*!==\s*\$agentType~',
            $src,
            $m
        );
        $this->assertGreaterThanOrEqual(
            2,
            $matches,
            'Both chat endpoints must guard: when the resumed chat belongs to another '
            . 'agent_type, start a fresh chat of the requested type (so the Live-Edit '
            . 'box never runs a different agent with the wrong tools).'
        );

        // And each guard branch must recreate via createOrGetChat with the requested type.
        $this->assertStringContainsString(
            'createOrGetChat',
            $src,
            'The mismatch guard must recreate the chat with the requested agent type.'
        );
    }
}
