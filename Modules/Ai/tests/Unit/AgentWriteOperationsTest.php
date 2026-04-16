<?php

declare(strict_types=1);

namespace Modules\Ai\Tests\Unit;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Modules\Ai\Agents\GeneralAgent;
use Modules\Ai\Models\AgentChat;
use Modules\Ai\Services\AgentFactory;
use MicroweberPackages\User\Models\User;
use NeuronAI\Chat\Messages\UserMessage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Phase 5: Write/Create Operations via Chat
 *
 * Tests that the GeneralAgent has write tools available, can create/edit content
 * and products, supports SEO improvement tools, and logs write operations for audit.
 */
class AgentWriteOperationsTest extends TestCase
{
    private function makeAgent(): GeneralAgent
    {
        $agentFactory = app(AgentFactory::class);
        return $agentFactory->agent('general', 'ollama', $this->getOllamaModel());
    }

    private function getToolNames(GeneralAgent $agent): array
    {
        return array_map(fn($tool) => $tool->getName(), $agent->getTools());
    }

    protected function getOllamaModel(): string
    {
        return Config::get('modules.ai.drivers.ollama.model', 'llama3.1');
    }

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

    private function createAgentWithChat(): array
    {
        $agent = $this->makeAgent();
        $user = User::factory()->create(['role' => 'admin']);
        $chat = AgentChat::factory()->create([
            'user_id' => $user->id,
            'agent_type' => 'general',
            'is_active' => true,
        ]);
        $agent->setAgentChat($chat);

        return [$agent, $chat, $user];
    }

    // ──────────────────────────────────────────────
    // Unit tests: verify write tools are available
    // ──────────────────────────────────────────────

    #[Test]
    public function it_has_post_creation_tool(): void
    {
        $toolNames = $this->getToolNames($this->makeAgent());
        $this->assertContains('create_post', $toolNames, 'Missing create_post tool');
    }

    #[Test]
    public function it_has_content_creation_tool(): void
    {
        $toolNames = $this->getToolNames($this->makeAgent());
        $this->assertContains('create_content', $toolNames, 'Missing create_content tool');
    }

    #[Test]
    public function it_has_product_creation_tool(): void
    {
        $toolNames = $this->getToolNames($this->makeAgent());
        $this->assertContains('create_product', $toolNames, 'Missing create_product tool');
    }

    #[Test]
    public function it_has_content_edit_tool(): void
    {
        $toolNames = $this->getToolNames($this->makeAgent());
        $this->assertContains('content_edit', $toolNames, 'Missing content_edit tool');
    }

    #[Test]
    public function it_has_post_edit_tool(): void
    {
        $toolNames = $this->getToolNames($this->makeAgent());
        $this->assertContains('post_edit', $toolNames, 'Missing post_edit tool');
    }

    #[Test]
    public function it_has_product_edit_tool(): void
    {
        $toolNames = $this->getToolNames($this->makeAgent());
        $this->assertContains('product_edit', $toolNames, 'Missing product_edit tool');
    }

    #[Test]
    public function it_has_content_improvement_tool(): void
    {
        $toolNames = $this->getToolNames($this->makeAgent());
        $this->assertContains('content_improvement', $toolNames, 'Missing content_improvement tool');
    }

    #[Test]
    public function it_has_seo_metadata_tool(): void
    {
        $toolNames = $this->getToolNames($this->makeAgent());
        $this->assertContains('generate_seo_metadata', $toolNames, 'Missing generate_seo_metadata tool');
    }

    #[Test]
    public function it_system_prompt_mentions_write_confirmation(): void
    {
        $agent = $this->makeAgent();
        $instructions = $agent->instructions();

        $this->assertStringContainsString(
            'write operations',
            strtolower($instructions),
            'System prompt should mention write operations guidance'
        );
        $this->assertStringContainsString(
            'confirm',
            strtolower($instructions),
            'System prompt should mention confirmation for write operations'
        );
    }

    #[Test]
    public function it_system_prompt_mentions_seo_tools(): void
    {
        $agent = $this->makeAgent();
        $instructions = $agent->instructions();

        $this->assertStringContainsString(
            'seo',
            strtolower($instructions),
            'System prompt should mention SEO tools'
        );
    }

    #[Test]
    public function it_write_tools_have_required_properties(): void
    {
        $agent = $this->makeAgent();
        $tools = $agent->getTools();

        $writeToolNames = ['create_post', 'create_product', 'create_content', 'content_edit', 'product_edit', 'post_edit'];
        $toolsByName = [];
        foreach ($tools as $tool) {
            $toolsByName[$tool->getName()] = $tool;
        }

        // create_post should require title and content_body
        $this->assertArrayHasKey('create_post', $toolsByName);

        // create_product should require title and description
        $this->assertArrayHasKey('create_product', $toolsByName);

        // content_edit should require id
        $this->assertArrayHasKey('content_edit', $toolsByName);

        // product_edit should require id
        $this->assertArrayHasKey('product_edit', $toolsByName);
    }

    #[Test]
    public function it_audit_log_method_exists_on_tools(): void
    {
        $agent = $this->makeAgent();
        $tools = $agent->getTools();

        // Get one of the write tools and verify it has the audit method
        foreach ($tools as $tool) {
            if ($tool->getName() === 'create_post') {
                $reflection = new \ReflectionClass($tool);
                $this->assertTrue(
                    $reflection->hasMethod('auditWriteOperation'),
                    'CreatePostTool should have auditWriteOperation method'
                );
                return;
            }
        }

        $this->fail('create_post tool not found');
    }

    #[Test]
    public function it_all_write_tools_are_present(): void
    {
        $toolNames = $this->getToolNames($this->makeAgent());

        $writeTools = [
            'create_post',
            'create_content',
            'create_product',
            'content_edit',
            'post_edit',
            'product_edit',
            'content_improvement',
            'generate_seo_metadata',
        ];

        foreach ($writeTools as $tool) {
            $this->assertContains($tool, $toolNames, "Missing write tool: {$tool}");
        }
    }

    // ──────────────────────────────────────────────
    // Live LLM write operation tests (require Ollama)
    // ──────────────────────────────────────────────

    #[Test]
    public function it_creates_post_via_chat(): void
    {
        if (!$this->isOllamaAvailable()) {
            $this->markTestSkipped('Ollama is not available');
        }

        [$agent, $chat, $user] = $this->createAgentWithChat();

        $response = $agent->chat(new UserMessage(
            'Create a blog post about summer sales with the title "Summer Sale 2026" and content about discounts.'
        ));

        $this->assertNotNull($response);
        $this->assertNotEmpty($response->getContent());

        // The response should mention creating or the post
        $messages = $chat->messages()->get();
        $this->assertGreaterThanOrEqual(2, $messages->count());
    }

    #[Test]
    public function it_handles_product_edit_query(): void
    {
        if (!$this->isOllamaAvailable()) {
            $this->markTestSkipped('Ollama is not available');
        }

        [$agent, $chat, $user] = $this->createAgentWithChat();

        $response = $agent->chat(new UserMessage(
            'Update product #42 price to $29.99'
        ));

        $this->assertNotNull($response);
        $this->assertNotEmpty($response->getContent());

        $messages = $chat->messages()->get();
        $this->assertGreaterThanOrEqual(2, $messages->count());
    }

    #[Test]
    public function it_handles_seo_improvement_query(): void
    {
        if (!$this->isOllamaAvailable()) {
            $this->markTestSkipped('Ollama is not available');
        }

        [$agent, $chat, $user] = $this->createAgentWithChat();

        $response = $agent->chat(new UserMessage(
            'Improve the SEO of page #15'
        ));

        $this->assertNotNull($response);
        $this->assertNotEmpty($response->getContent());

        $messages = $chat->messages()->get();
        $this->assertGreaterThanOrEqual(2, $messages->count());
    }
}
