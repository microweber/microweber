<?php

namespace Modules\Ai\Services;

use Illuminate\Contracts\Container\Container;
use Modules\Ai\Agents\BaseAgent;
use Modules\Ai\Agents\ContentAgent;
use Modules\Ai\Agents\CustomerAgent;
use Modules\Ai\Agents\GeneralAgent;
use Modules\Ai\Agents\LiveEditAgent;
use Modules\Ai\Agents\MediaAgent;
use Modules\Ai\Agents\ShopAgent;
use Modules\Ai\Models\AgentChat;
use Modules\Ai\Services\RagSearchService;

class AgentFactory
{
    protected $app;
    protected $agents = [];
    protected array $domainRegistry = [
        'content' => ContentAgent::class,
        'customer' => CustomerAgent::class,
        'shop' => ShopAgent::class,
        'media' => MediaAgent::class,
        'general' => GeneralAgent::class,
        'liveedit' => LiveEditAgent::class,
    ];

    public function __construct(Container $app)
    {
        $this->app = $app;
        $this->registerDefaultAgents();
    }

    protected function registerDefaultAgents(): void
    {
        foreach ($this->domainRegistry as $name => $agentClass) {
            $this->register($name, $agentClass);
        }
    }

    public function register(string $name, string $agentClass): void
    {
        $this->agents[$name] = $agentClass;
    }

    public function agent(string $name, ?string $providerName = null, ?string $model = null): BaseAgent
    {
        if (!isset($this->agents[$name])) {
            throw new \InvalidArgumentException("Agent [{$name}] not registered.");
        }

        // Special handling for GeneralAgent which needs AgentFactory and RagSearchService
        if ($this->agents[$name] === GeneralAgent::class) {
            return $this->app->make($this->agents[$name], [
                'agentFactory' => $this,
                'ragService' => $this->app->make(RagSearchService::class),
                'providerName' => $providerName,
                'model' => $model,
            ]);
        }

        return $this->app->make($this->agents[$name], [
            'providerName' => $providerName,
            'model' => $model,
        ]);
    }

    /**
     * Create an agent with chat history from an AgentChat model
     */
    public function agentWithChat(AgentChat $agentChat, ?string $providerName = null, ?string $model = null): BaseAgent
    {
        $agent = $this->agent($agentChat->agent_type, $providerName, $model);
        $agent->setAgentChat($agentChat);
        return $agent;
    }

    /**
     * Create or get an existing chat for an agent
     */
    public function createOrGetChat(
        string $agentType,
        string $title,
        ?int $userId = null,
        ?string $description = null,
        array $metadata = []
    ): AgentChat {
        return AgentChat::firstOrCreate(
            [
                'agent_type' => $agentType,
                'user_id' => $userId,
                'title' => $title,
            ],
            [
                'description' => $description,
                'metadata' => $metadata,
                'is_active' => true,
            ]
        );
    }

    /**
     * Get an agent with a new or existing chat session
     */
    public function agentWithSession(
        string $agentType,
        string $title,
        ?int $userId = null,
        ?string $description = null,
        array $metadata = [],
        ?string $providerName = null,
        ?string $model = null
    ): BaseAgent {
        $chat = $this->createOrGetChat($agentType, $title, $userId, $description, $metadata);
        return $this->agentWithChat($chat, $providerName, $model);
    }

    public function getRegisteredAgents(): array
    {
        return array_keys($this->agents);
    }

    public function getAgentsByDomain(): array
    {
        return $this->domainRegistry;
    }

    public function getAgentForDomain(string $domain): ?string
    {
        return $this->domainRegistry[$domain] ?? null;
    }
}
