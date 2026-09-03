<?php

declare(strict_types=1);

namespace Modules\Ai\Services;

use NeuronAI\Observability\Events\ToolCalled;
use NeuronAI\Observability\ObserverInterface;

/**
 * Collects the tool calls an agent makes during a chat turn.
 *
 * The Live-Edit agent's tools are side-effect-free command emitters (they do not
 * persist anything). To actually apply edits, the front-end needs to know which
 * tools ran and with what arguments. Attach this observer to an agent
 * ($agent->observe($collector)) before chat(); afterwards ->all() returns the
 * ordered list of { tool, args } that the /api/ai/agent-chat response surfaces
 * to the Live-Edit canvas so it can apply them to the real DOM and mark them
 * dirty for the normal Live-Edit SAVE.
 */
class ToolCallCollector implements ObserverInterface
{
    /** @var list<array{tool:string,args:array}> */
    protected array $calls = [];

    public function onEvent(string $event, object $source, mixed $data = null, ?string $branchId = null): void
    {
        if ($data instanceof ToolCalled) {
            $tool = $data->tool;
            $this->calls[] = [
                'tool' => $tool->getName(),
                'args' => \method_exists($tool, 'getInputs') ? $tool->getInputs() : [],
            ];
        }
    }

    /**
     * @return list<array{tool:string,args:array}>
     */
    public function all(): array
    {
        return $this->calls;
    }
}
