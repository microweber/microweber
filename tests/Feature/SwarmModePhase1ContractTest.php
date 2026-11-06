<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Modules\Ai\Services\SseToolEmitter;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Contract test for Swarm Mode Phase 1 (Live Edit AI box).
 *
 * Swarm mode is additive + back-compat: a planner decomposes a brief into page
 * lanes, a coordinator SSE endpoint runs a LiveEdit worker per lane, and every
 * tool frame is tagged with its lane agent_id. The single-agent path
 * (AiController::agentChatStream + a no-arg SseToolEmitter) must stay unchanged.
 *
 * These assertions pin: the lane/agent_id wiring on SseToolEmitter (back-compat),
 * the swarm route + controller, the planner's structured-output shape, and the
 * frontend lane-router / build-board / toggle wiring. Source assertions here are
 * PRESENCE checks, so the selector-self-match guard does not apply.
 */
class SwarmModePhase1ContractTest extends TestCase
{
    private function read(string $rel): string
    {
        return (string) file_get_contents(base_path($rel));
    }

    // ── SseToolEmitter: optional lane/agent id, back-compat ───────────────────

    #[Test]
    public function sse_emitter_constructor_takes_two_optional_params(): void
    {
        $ctor = (new \ReflectionClass(SseToolEmitter::class))->getConstructor();
        $this->assertNotNull($ctor, 'SseToolEmitter must declare a constructor for swarm lane tagging.');
        $params = $ctor->getParameters();
        $this->assertCount(2, $params, 'SseToolEmitter::__construct must take (onToolCall, agentId).');
        foreach ($params as $p) {
            $this->assertTrue($p->isOptional(), "SseToolEmitter ctor param \${$p->getName()} must be optional (back-compat).");
        }
    }

    #[Test]
    public function sse_emitter_no_arg_construction_still_works(): void
    {
        // The single-agent path (AiController) constructs it with no args.
        $emitter = new SseToolEmitter();
        $this->assertInstanceOf(SseToolEmitter::class, $emitter);
    }

    #[Test]
    public function sse_emitter_emit_writes_the_sse_frame_format(): void
    {
        // emit() flushes every output buffer (SSE transport design), so it can't be
        // captured with ob_start here; assert the frame format at the source. The
        // live wire was separately verified against the real endpoint.
        $src = $this->read('Modules/Ai/Services/SseToolEmitter.php');
        $this->assertStringContainsString("echo 'event: ' . \$event", $src);
        $this->assertStringContainsString("echo 'data: ' . json_encode(\$data", $src);
    }

    #[Test]
    public function sse_emitter_adds_agent_id_only_when_lane_tagged(): void
    {
        // Source-level: agent_id is written into the auto tool frame ONLY when the
        // agentId is set, so the no-arg single-agent frame stays {tool,args}.
        $src = $this->read('Modules/Ai/Services/SseToolEmitter.php');
        $this->assertMatchesRegularExpression(
            '/if \(\$this->agentId !== null\)\s*\{\s*\$call\[\x27agent_id\x27\]/',
            $src,
            'SseToolEmitter must add agent_id to the tool frame only when a lane id is set (back-compat).'
        );
        $this->assertStringContainsString('if ($this->onToolCall !== null)', $src,
            'SseToolEmitter must invoke the optional per-tool-call callback used for lane progress.');
    }

    // ── Route + controller + planner ──────────────────────────────────────────

    #[Test]
    public function swarm_stream_route_is_registered(): void
    {
        $route = Route::getRoutes()->getByName('api.ai.agent-swarm-stream');
        $this->assertNotNull($route, 'POST api/ai/agent-swarm-stream must be registered.');
        $this->assertContains('POST', $route->methods());
        $this->assertSame('api/ai/agent-swarm-stream', $route->uri());
        $this->assertStringContainsString('SwarmController', $route->getActionName());
        $this->assertStringContainsString('agentSwarmStream', $route->getActionName());
    }

    #[Test]
    public function planner_exposes_plan_returning_structured_output(): void
    {
        $m = new \ReflectionMethod(\Modules\Ai\Agents\SwarmPlannerAgent::class, 'plan');
        $this->assertSame('plan', $m->getName());
        $ret = $m->getReturnType();
        $this->assertNotNull($ret);
        $this->assertStringContainsString('SwarmPlanOutput', (string) $ret);
    }

    #[Test]
    public function plan_output_dto_has_design_and_lanes(): void
    {
        $this->assertTrue(property_exists(\Modules\Ai\Agents\Output\SwarmPlanOutput::class, 'design'));
        $this->assertTrue(property_exists(\Modules\Ai\Agents\Output\SwarmPlanOutput::class, 'lanes'));
        $this->assertTrue(property_exists(\Modules\Ai\Agents\Output\SwarmLaneOutput::class, 'agent_id'));
        $this->assertTrue(property_exists(\Modules\Ai\Agents\Output\SwarmLaneOutput::class, 'order'));
    }

    #[Test]
    public function planner_self_binds_a_thread_for_structured_output(): void
    {
        // NeuronAI v4 structured() requires a bound thread id; the planner is a
        // one-shot, so plan() must self-bind when the caller did not.
        $src = $this->read('Modules/Ai/Agents/SwarmPlannerAgent.php');
        $this->assertStringContainsString('getThreadId()', $src);
        $this->assertStringContainsString('setThreadId(', $src);
    }

    #[Test]
    public function coordinator_emits_the_locked_frame_vocabulary(): void
    {
        $src = $this->read('Modules/Ai/Http/Controllers/SwarmController.php');
        foreach (["emit('plan'", "emit('tool'", "emit('lane'", "emit('done'"] as $frame) {
            $this->assertStringContainsString($frame, $src, "SwarmController must emit the {$frame} frame.");
        }
        // Design system is locked up front via a coordinator-tagged tool frame.
        $this->assertStringContainsString("'agent_id' => 'coordinator'", $src);
        // Each lane worker gets a lane-tagged emitter.
        $this->assertStringContainsString('new SseToolEmitter($onToolCall, $laneAgentId)', $src);
    }

    // ── Frontend: router + board + toggle wiring ──────────────────────────────

    #[Test]
    public function frontend_router_exposes_the_swarm_api(): void
    {
        $src = $this->read('packages/frontend-assets/resources/assets/services/mw-ai-swarm.js');
        $this->assertStringContainsString('aiSwarm', $src);
        $this->assertStringContainsString('agent-swarm-stream', $src);
        $this->assertStringContainsString('applyEdit', $src, 'Router must reuse MwAi applyEdit, not duplicate tool logic.');
        $this->assertStringContainsString('saveCanvas', $src, 'Router must perform the single end-of-run save.');
    }

    #[Test]
    public function frontend_board_exposes_its_api(): void
    {
        $src = $this->read('packages/frontend-assets/resources/assets/components/mw-ai-swarm-board.js');
        $this->assertStringContainsString('mwAiSwarmBoard', $src);
        foreach (['mount', 'setPlan', 'updateLane', 'tool', 'finish', 'destroy'] as $method) {
            $this->assertStringContainsString($method, $src, "Board must expose {$method}().");
        }
    }

    #[Test]
    public function conversation_wires_the_swarm_toggle_and_route(): void
    {
        $src = $this->read('packages/frontend-assets/resources/assets/components/mw-ai-conversation.js');
        $this->assertStringContainsString('mw-ai-conv-swarm-btn', $src, 'Conversation must render the Swarm toggle.');
        $this->assertStringContainsString('toggleSwarm', $src);
        $this->assertStringContainsString('_sendSwarm', $src);
        $this->assertStringContainsString('mw.aiSwarm', $src, 'send() must route to the swarm coordinator when enabled.');
    }

    #[Test]
    public function sidebar_imports_the_swarm_pieces(): void
    {
        $src = $this->read('packages/frontend-assets/resources/assets/services/mw-ai-chat-sidebar.js');
        $this->assertStringContainsString('mw-ai-swarm.js', $src);
        $this->assertStringContainsString('mw-ai-swarm-board.js', $src);
    }
}
