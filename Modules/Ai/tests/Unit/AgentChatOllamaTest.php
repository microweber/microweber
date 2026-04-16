<?php

declare(strict_types=1);

namespace Modules\Ai\Tests\Unit;

use Illuminate\Support\Facades\Config;
use Modules\Ai\Agents\GeneralAgent;
use Modules\Ai\Models\AgentChat;
use Modules\Ai\Services\AgentFactory;
use Modules\Ai\Services\RagSearchService;
use MicroweberPackages\User\Models\User;
use NeuronAI\Chat\Messages\UserMessage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AgentChatOllamaTest extends TestCase
{
    protected function isOllamaAvailable(): bool
    {
        $url = Config::get('modules.ai.drivers.ollama.url', 'http://localhost:11434/api');
        $url = rtrim($url, '/');
        $url = preg_replace('#/(generate|chat)$#', '', $url);

        try {
            $ch = curl_init($url . '/../api/tags');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            return $httpCode === 200 && !empty($response);
        } catch (\Exception $e) {
            return false;
        }
    }

    protected function getOllamaModel(): string
    {
        return Config::get('modules.ai.drivers.ollama.model', 'llama3.1');
    }

    #[Test]
    public function it_creates_general_agent_with_ollama_provider(): void
    {
        $agentFactory = app(AgentFactory::class);
        $agent = $agentFactory->agent('general', 'ollama', $this->getOllamaModel());

        $this->assertInstanceOf(GeneralAgent::class, $agent);
        $this->assertEquals('ollama', $agent->getProviderName());
        $this->assertEquals($this->getOllamaModel(), $agent->getModel());
    }

    #[Test]
    public function it_general_agent_has_tools_registered(): void
    {
        $agentFactory = app(AgentFactory::class);
        $agent = $agentFactory->agent('general', 'ollama', $this->getOllamaModel());

        $tools = $agent->getTools();

        // Should have 50+ tools wired from all domains
        $this->assertGreaterThan(40, count($tools), 'GeneralAgent should have 40+ tools registered');

        // Verify key domain tools are present
        $toolNames = array_map(function ($tool) {
            return method_exists($tool, 'getName') ? $tool->getName() : (string) $tool;
        }, $tools);

        // Analytics
        $this->assertContains('analytics_traffic_summary', $toolNames, 'Missing analytics traffic summary tool');
        // Billing
        $this->assertContains('billing_account_status', $toolNames, 'Missing billing account status tool');
        // Content
        $this->assertContains('content_search', $toolNames, 'Missing content search tool');
        // Customer
        $this->assertContains('customer_lookup', $toolNames, 'Missing customer lookup tool');
        // Products
        $this->assertContains('product_search', $toolNames, 'Missing product search tool');
        // Settings
        $this->assertContains('settings_read', $toolNames, 'Missing settings read tool');
    }

    #[Test]
    public function it_agent_with_chat_sets_history(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $chat = AgentChat::factory()->create([
            'user_id' => $user->id,
            'agent_type' => 'general',
            'is_active' => true,
        ]);

        $agentFactory = app(AgentFactory::class);
        $agent = $agentFactory->agentWithChat($chat, 'ollama', $this->getOllamaModel());

        $this->assertInstanceOf(GeneralAgent::class, $agent);
        $this->assertNotNull($agent->getAgentChat());
        $this->assertEquals($chat->id, $agent->getAgentChat()->id);
    }

    #[Test]
    public function it_ollama_chat_returns_response(): void
    {
        if (!$this->isOllamaAvailable()) {
            $this->markTestSkipped('Ollama is not available on this system');
        }

        $agentFactory = app(AgentFactory::class);
        $agent = $agentFactory->agent('general', 'ollama', $this->getOllamaModel());

        $user = User::factory()->create(['role' => 'admin']);
        $chat = AgentChat::factory()->create([
            'user_id' => $user->id,
            'agent_type' => 'general',
            'is_active' => true,
        ]);
        $agent->setAgentChat($chat);

        // Send a simple greeting that doesn't require tool calls
        $message = new UserMessage('Hello, what can you help me with?');
        $response = $agent->chat($message);

        $this->assertNotNull($response);
        $this->assertNotEmpty($response->getContent());
    }

    #[Test]
    public function it_ollama_chat_with_tool_calling(): void
    {
        if (!$this->isOllamaAvailable()) {
            $this->markTestSkipped('Ollama is not available on this system');
        }

        $agentFactory = app(AgentFactory::class);
        $agent = $agentFactory->agent('general', 'ollama', $this->getOllamaModel());

        $user = User::factory()->create(['role' => 'admin']);
        $chat = AgentChat::factory()->create([
            'user_id' => $user->id,
            'agent_type' => 'general',
            'is_active' => true,
        ]);
        $agent->setAgentChat($chat);

        // Send a query that should trigger tool calling
        $message = new UserMessage('Show me the site settings');
        $response = $agent->chat($message);

        $this->assertNotNull($response);
        $this->assertNotEmpty($response->getContent());

        // Verify messages were persisted in chat history
        $messages = $chat->messages()->get();
        $this->assertGreaterThanOrEqual(2, $messages->count(), 'Should have at least user + assistant messages');

        // Check that user message was stored
        $userMsg = $messages->where('role', 'user')->first();
        $this->assertNotNull($userMsg);

        // Check that assistant response was stored
        $assistantMsg = $messages->where('role', 'assistant')->last();
        $this->assertNotNull($assistantMsg);
        $this->assertNotEmpty($assistantMsg->content);
    }
}
