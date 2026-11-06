<?php

declare(strict_types=1);

namespace Modules\Ai\Services;

use NeuronAI\Agent\Observability\ToolCalled;
use NeuronAI\Observability\ObserverInterface;

/**
 * Streams each tool call the Live-Edit agent makes as a Server-Sent Event.
 *
 * ARCHITECTURE: the Live-Edit tools are *frontend* tools. The backend only holds
 * thin declarations so NeuronAI/Kimi can decide to call them; the real work runs
 * in the browser on the live canvas (see mw-ai.js frontendTools). This observer
 * is the transport: the instant the agent calls a tool during chat(), it flushes
 *
 *     event: tool
 *     data: {"tool":"apply_css","args":{"css":"h2{color:blue}"}}
 *
 * to the open SSE response, so the canvas applies the edit in real time (and marks
 * it dirty for the normal Live-Edit SAVE). Attach with $agent->observe($emitter)
 * before running chat(); it also records the calls so the final frame can repeat
 * them for clients that prefer to apply once at the end.
 */
class SseToolEmitter implements ObserverInterface
{
    /** @var list<array{tool:string,args:array}> */
    protected array $calls = [];

    /**
     * Optional per-tool-call callback — invoked once per tool call with the
     * recorded ['tool'=>…,'args'=>…(,'agent_id'=>…)] array. Swarm mode uses it to
     * stream per-lane `lane` status:"building" progress with a running count.
     */
    protected ?\Closure $onToolCall;

    /**
     * Optional lane / agent id. When set, every auto-emitted `tool` frame carries
     * `agent_id` so the client can route it to the right swarm lane. When null the
     * key is omitted — the single-agent path stays byte-for-byte unchanged.
     */
    protected ?string $agentId;

    public function __construct(?\Closure $onToolCall = null, ?string $agentId = null)
    {
        $this->onToolCall = $onToolCall;
        $this->agentId = $agentId;
    }

    public function onEvent(string $event, object $source, mixed $data = null, ?string $branchId = null): void
    {
        if (!$data instanceof ToolCalled) {
            return;
        }

        $tool = $data->tool;
        $call = [
            'tool' => $tool->getName(),
            'args' => \method_exists($tool, 'getInputs') ? $tool->getInputs() : [],
        ];
        if ($this->agentId !== null) {
            $call['agent_id'] = $this->agentId;
        }
        $this->calls[] = $call;

        if ($this->onToolCall !== null) {
            ($this->onToolCall)($call);
        }

        $this->emit('tool', $call);
    }

    /**
     * @return list<array{tool:string,args:array}>
     */
    public function all(): array
    {
        return $this->calls;
    }

    /**
     * Write one SSE frame and flush it to the client immediately.
     */
    public function emit(string $event, mixed $data): void
    {
        echo 'event: ' . $event . "\n";
        echo 'data: ' . json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n\n";

        if (\function_exists('ob_get_level')) {
            while (ob_get_level() > 0) {
                @ob_end_flush();
            }
        }
        @flush();
    }
}
