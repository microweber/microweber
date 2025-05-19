<?php

namespace Modules\Ai\Services;

use Illuminate\Contracts\Container\Container;
use Modules\Ai\Agents\BaseAgent;

class AgentFactory
{
    protected $app;
    protected $agents = [];

    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    public function register(string $name, string $agentClass)
    {
        $this->agents[$name] = $agentClass;
    }

    public function agent(string $name, ?string $providerName = null, ?string $model = null): BaseAgent
    {
        if (!isset($this->agents[$name])) {
            throw new \InvalidArgumentException("Agent [{$name}] not registered.");
        }

        return $this->app->make($this->agents[$name], [
            'providerName' => $providerName,
            'model' => $model,
        ]);
    }

    public function getRegisteredAgents(): array
    {
        return array_keys($this->agents);
    }
}
