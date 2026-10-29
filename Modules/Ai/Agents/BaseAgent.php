<?php

declare(strict_types=1);

namespace Modules\Ai\Agents;

use MicroweberPackages\AiTools\Contracts\ToolRegistryInterface;
use MicroweberPackages\AiTools\Facades\AiTools;

use GuzzleHttp\Exception\RequestException;
use Modules\Ai\Models\AgentChat;
use Modules\Ai\Services\AgentChatMessageStore;
use NeuronAI\Agent\Agent;
use NeuronAI\Chat\History\MessageStoreInterface;
use NeuronAI\Chat\History\InMemoryMessageStore;
use NeuronAI\Chat\Messages\Message;
use NeuronAI\Chat\Messages\ToolCallMessage;
use NeuronAI\Chat\Messages\UserMessage;
use NeuronAI\Exceptions\AgentException;
use NeuronAI\Observability\Events\AgentError;
use NeuronAI\Observability\Events\InferenceStart;
use NeuronAI\Observability\Events\InferenceStop;
use NeuronAI\Providers\AIProviderInterface;
use NeuronAI\Providers\Anthropic\Anthropic;
use NeuronAI\Providers\Deepseek;
use NeuronAI\Providers\Gemini\Gemini;
use NeuronAI\Providers\Mistral;
use NeuronAI\Providers\Ollama\Ollama;
use NeuronAI\Providers\OpenAI\OpenAI;
use NeuronAI\StructuredOutput\JsonSchema;
use NeuronAI\Agent\SystemPrompt;
use NeuronAI\Tools\ToolInterface;
use NeuronAI\Tools\Toolkits\ToolkitInterface;
use NeuronAI\Tools\ProviderToolInterface;
use NeuronAI\Agent\AgentInterface;
use NeuronAI\Workflow\WorkflowState;

class BaseAgent extends Agent
{
    protected array $tools = [];
    protected string $domain;
    protected int $maxTries = 5;
    // NeuronAI v4: the Agent (HandleInstructions trait) declares
    // `protected SystemMessage $instructions`; a `string $instructions` here would
    // be a fatal property-type collision. Each concrete agent overrides
    // instructions() anyway, so no default property is needed.
    protected $providerName = null;
    protected $model = null;
    protected ?AgentChat $agentChat = null;

    public function __construct(?string $providerName = null, ?string $model = null, protected array $dependencies = [])
    {
        // NeuronAI 3.x: Agent extends Workflow, whose constructor initializes the
        // workflow executor + persistence + middleware. In 2.x the base ctor was a
        // no-op so BaseAgent never chained to it; under 3.x that leaves
        // Workflow::$executor uninitialized and chat() fatals
        // ("$executor must not be accessed before initialization"). Chain up first.
        parent::__construct();

        if ($providerName) {
            $this->providerName = $providerName;
        } else {
            $this->providerName = config('modules.ai.default_driver');
        }

        if ($model) {
            $this->model = $model;
        } else {
            $this->model = config('modules.ai.drivers.' . $this->providerName . '.model');
        }

        $this->setupTools();
    }

    protected function setupTools(): void
    {
    }

    /**
     * Resolve and attach tools from the global AI tools registry by name.
     *
     * @param list<string> $toolNames
     */
    protected function loadToolsFromRegistry(array $toolNames): void
    {
        foreach ($toolNames as $name) {
            $tool = AiTools::make($name, $this->dependencies);
            if ($tool !== null) {
                $this->addTool($tool);
            }
        }
    }

    /**
     * Resolve and attach all registered tools for the given domains.
     *
     * @param list<string> $domains
     */
    protected function loadToolsByDomains(array $domains): void
    {
        /** @var ToolRegistryInterface $registry */
        $registry = app(ToolRegistryInterface::class);
        foreach ($domains as $domain) {
            foreach ($registry->getByDomain($domain) as $tool) {
                $fresh = $registry->make($tool->getName(), $this->dependencies) ?? $tool;
                $this->addTool($fresh);
            }
        }
    }


    public function setState(WorkflowState $state): static
    {
        foreach ($this->tools as $tool) {
            if (method_exists($tool, 'setState')) {
                $tool->setState($state);
            }
        }

        // NeuronAI v4: ResolveState::setState() returns static.
        return parent::setState($state);
    }

    protected function tools(): array
    {
        return $this->tools;
    }

    public function addTool(ToolInterface|ToolkitInterface|ProviderToolInterface|array $tools): static
    {
        if (is_array($tools)) {
            foreach ($tools as $tool) {
                $this->tools[] = $tool;
            }
        } else {
            $this->tools[] = $tools;
        }

        return $this;
    }

    public function getTools(): array
    {
        return $this->tools;
    }

    /**
     * Set the AgentChat instance for memory management
     */
    public function setAgentChat(AgentChat $agentChat): self
    {
        $this->agentChat = $agentChat;
        // NeuronAI v4: the agent is addressed by a thread ID (the conversation).
        // chat()/stream() require one bound before they run; the message store
        // resolves the AgentChat from it.
        $this->setThreadId((string) $agentChat->id);
        return $this;
    }

    /**
     * Get the current AgentChat instance
     */
    public function getAgentChat(): ?AgentChat
    {
        return $this->agentChat;
    }

    /**
     * NeuronAI v4: provide the database-backed message store (was chatHistory()).
     * The store is stateless and resolves the AgentChat from the bound thread ID;
     * with no AgentChat we fall back to the in-memory store.
     */
    protected function messageStore(): MessageStoreInterface
    {
        return $this->agentChat ? new AgentChatMessageStore() : new InMemoryMessageStore();
    }

    /**
     * NeuronAI v4: the context window is a hook on the Agent.
     */
    protected function contextWindow(): int
    {
        return $this->getContextWindow();
    }

    /**
     * Get the context window size for this agent
     */
    protected function getContextWindow(): int
    {
        $defaultContextWindow = 50000;

        // Get context window based on model
        $contextWindows = [
            // OpenAI models
            'gpt-4' => 8192,
            'gpt-4-32k' => 32768,
            'gpt-4-turbo' => 128000,
            'gpt-4o' => 128000,
            'gpt-3.5-turbo' => 16385,
            'gpt-3.5-turbo-16k' => 16385,

            // Anthropic models
            'claude-3-opus-20240229' => 200000,
            'claude-3-sonnet-20240229' => 200000,
            'claude-3-haiku-20240307' => 200000,
            'claude-3-5-sonnet-20241022' => 200000,

            // Gemini models
            'gemini-pro' => 30720,
            'gemini-1.5-pro' => 1000000,
            'gemini-1.5-flash' => 1000000,

            // Other models
            'deepseek-chat' => 32768,
            'mistral-large-latest' => 32768,
        ];

        return $contextWindows[$this->model] ?? $defaultContextWindow;
    }

    protected function provider(): AIProviderInterface
    {
        // return an AI provider (Anthropic, OpenAI, Ollama, Gemini, etc.)


        if ($this->providerName == 'anthropic') {
            return new Anthropic(
                key: config('modules.ai.drivers.anthropic.api_key'),
                model: $this->model,
            );
        }

        if ($this->providerName == 'openai') {
            return new OpenAI(
                key: config('modules.ai.drivers.openai.api_key'),
                model: $this->model,
            );
        }

        if ($this->providerName == 'ollama') {
            // NeuronAI's Ollama provider uses base_uri + 'chat' endpoint,
            // so we need the base API URL (e.g. http://localhost:11434/api)
            $url = config('modules.ai.drivers.ollama.url', 'http://localhost:11434/api');
            $url = rtrim($url, '/');
            // Strip /generate or /chat suffix if present — NeuronAI appends its own
            $url = preg_replace('#/(generate|chat)$#', '', $url);
            return new Ollama(
                url: $url,
                model: $this->model,
            );
        }

        if ($this->providerName == 'deepseek') {
            return new Deepseek(
                key: config('modules.ai.drivers.deepseek.api_key'),
                model: $this->model,
            );
        }

        if ($this->providerName == 'mistral') {
            return new Mistral(
                key: config('modules.ai.drivers.mistral.api_key'),
                model: $this->model,
            );
        }


        if ($this->providerName == 'gemini') {
            return new Gemini(
                key: config('modules.ai.drivers.gemini.api_key'),
                model: $this->model,
            );
        }

        throw new \RuntimeException("Unknown AI provider: {$this->providerName}");
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function getProviderName(): ?string
    {
        return $this->providerName;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function getDependencies(): array
    {
        return $this->dependencies;
    }
}
