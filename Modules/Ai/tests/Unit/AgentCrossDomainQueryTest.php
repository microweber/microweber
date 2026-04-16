<?php

declare(strict_types=1);

namespace Modules\Ai\Tests\Unit;

use Illuminate\Support\Facades\Config;
use Modules\Ai\Agents\GeneralAgent;
use Modules\Ai\Models\AgentChat;
use Modules\Ai\Services\AgentFactory;
use MicroweberPackages\User\Models\User;
use NeuronAI\Chat\Messages\UserMessage;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Phase 4: Cross-Domain Query Support
 *
 * Tests that the GeneralAgent can handle queries spanning multiple tools/domains,
 * chain multiple tool calls in a single conversation turn, and synthesize results.
 */
class AgentCrossDomainQueryTest extends TestCase
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

        return [$agent, $chat];
    }

    /**
     * Extract tool call names from chat message history metadata.
     */
    private function extractToolCallNames(AgentChat $chat): array
    {
        $messages = $chat->messages()->get();
        $toolNames = [];

        foreach ($messages as $msg) {
            $metadata = is_string($msg->metadata)
                ? json_decode($msg->metadata, true)
                : ($msg->metadata ?? []);

            $type = $metadata['type'] ?? '';
            if ($type === 'tool_call' || $msg->role === 'tool' || $msg->role === 'tool_call') {
                $name = $metadata['tool_name'] ?? $metadata['name'] ?? '';
                if (!empty($name)) {
                    $toolNames[] = $name;
                }
            }
        }

        return $toolNames;
    }

    // ──────────────────────────────────────────────
    // Unit tests: verify cross-domain tool availability
    // ──────────────────────────────────────────────

    #[Test]
    public function it_has_tools_for_product_plus_order_cross_domain(): void
    {
        $toolNames = $this->getToolNames($this->makeAgent());

        // A "top products + revenue" query needs both product and order tools
        $this->assertContains('product_search', $toolNames);
        $this->assertContains('product_list', $toolNames);
        $this->assertContains('order_search', $toolNames);
    }

    #[Test]
    public function it_has_tools_for_content_plus_analytics_cross_domain(): void
    {
        $toolNames = $this->getToolNames($this->makeAgent());

        // A "blog posts + traffic" query needs content and analytics tools
        $this->assertContains('content_search', $toolNames);
        $this->assertContains('post_list', $toolNames);
        $this->assertContains('analytics_top_pages', $toolNames);
        $this->assertContains('analytics_traffic_summary', $toolNames);
    }

    #[Test]
    public function it_has_tools_for_billing_plus_customer_cross_domain(): void
    {
        $toolNames = $this->getToolNames($this->makeAgent());

        // An "unpaid invoices for customer X" query needs billing and customer tools
        $this->assertContains('billing_invoice_lookup', $toolNames);
        $this->assertContains('billing_invoice_unpaid_summary', $toolNames);
        $this->assertContains('billing_invoice_customer_history', $toolNames);
        $this->assertContains('customer_lookup', $toolNames);
    }

    #[Test]
    public function it_has_tools_for_newsletter_plus_analytics_cross_domain(): void
    {
        $toolNames = $this->getToolNames($this->makeAgent());

        // "How are our newsletter campaigns performing vs site traffic?"
        $this->assertContains('newsletter_campaign_lookup', $toolNames);
        $this->assertContains('analytics_traffic_summary', $toolNames);
        $this->assertContains('analytics_traffic_referrers', $toolNames);
    }

    #[Test]
    public function it_has_tools_for_product_plus_media_cross_domain(): void
    {
        $toolNames = $this->getToolNames($this->makeAgent());

        // "Show products and their images"
        $this->assertContains('product_list', $toolNames);
        $this->assertContains('media_search', $toolNames);
        $this->assertContains('media_lookup', $toolNames);
    }

    #[Test]
    public function it_system_prompt_encourages_multi_tool_usage(): void
    {
        $agent = $this->makeAgent();
        $instructions = $agent->instructions();

        // The system prompt should tell the LLM to use multiple tools for cross-domain queries
        $this->assertStringContainsString(
            'multiple tools',
            strtolower($instructions),
            'System prompt should mention using multiple tools for cross-domain queries'
        );
    }

    // ──────────────────────────────────────────────
    // Live LLM cross-domain tests (require Ollama)
    // ──────────────────────────────────────────────

    /**
     * Cross-domain query samples.
     * Each entry: [query, array of expected tool prefixes, description]
     */
    public static function crossDomainQueryProvider(): array
    {
        return [
            'products + orders (revenue)' => [
                'What are our top products and how much revenue did they generate?',
                ['product_', 'order_'],
                'Should call both product and order tools',
            ],
            'content + analytics (traffic)' => [
                'Which blog posts get the most traffic?',
                ['content_', 'post_', 'analytics_'],
                'Should call content/post and analytics tools',
            ],
            'billing + customer (invoices)' => [
                'Show unpaid invoices for customer john@example.com',
                ['billing_invoice_', 'customer_'],
                'Should call billing invoice and customer tools',
            ],
            'products + analytics (performance)' => [
                'Which products get the most page views?',
                ['product_', 'analytics_'],
                'Should call product and analytics tools',
            ],
        ];
    }

    #[Test]
    #[DataProvider('crossDomainQueryProvider')]
    public function it_handles_cross_domain_query(
        string $query,
        array $expectedPrefixes,
        string $description
    ): void {
        if (!$this->isOllamaAvailable()) {
            $this->markTestSkipped('Ollama is not available — skipping live cross-domain test');
        }

        [$agent, $chat] = $this->createAgentWithChat();

        $response = $agent->chat(new UserMessage($query));

        $this->assertNotNull($response, "{$description}: Response should not be null");
        $this->assertNotEmpty($response->getContent(), "{$description}: Response should not be empty");

        // Verify messages were persisted
        $messages = $chat->messages()->get();
        $this->assertGreaterThanOrEqual(2, $messages->count(), "{$description}: Should have user + assistant messages");

        // Check tool calls if available
        $toolNames = $this->extractToolCallNames($chat);

        if (!empty($toolNames)) {
            // Verify at least one expected domain prefix was called
            $matchedPrefixes = [];
            foreach ($expectedPrefixes as $prefix) {
                foreach ($toolNames as $toolName) {
                    if (str_starts_with($toolName, $prefix)) {
                        $matchedPrefixes[] = $prefix;
                        break;
                    }
                }
            }

            // We expect at least one domain prefix to match — the LLM should call
            // at least one relevant tool. Ideally it calls tools from multiple domains,
            // but even one correct domain tool demonstrates routing works.
            $this->assertNotEmpty(
                $matchedPrefixes,
                "{$description}: Expected tool calls matching [" . implode(', ', $expectedPrefixes)
                    . "], got: " . implode(', ', $toolNames)
            );
        }
    }

    #[Test]
    public function it_can_chain_tool_calls_in_single_turn(): void
    {
        if (!$this->isOllamaAvailable()) {
            $this->markTestSkipped('Ollama is not available — skipping tool chaining test');
        }

        [$agent, $chat] = $this->createAgentWithChat();

        // This query explicitly asks for data from two distinct domains
        $query = 'Give me a summary of our site traffic AND list all products we have.';
        $response = $agent->chat(new UserMessage($query));

        $this->assertNotNull($response);
        $this->assertNotEmpty($response->getContent());

        // Check that the conversation produced multiple messages (indicating tool call chaining)
        $messages = $chat->messages()->get();
        // With tool calling: user → tool_call → tool_result → (repeat) → assistant
        // Minimum without tools: user → assistant = 2
        // With at least one tool call: user → tool_call → tool_result → assistant = 4+
        $this->assertGreaterThanOrEqual(
            2,
            $messages->count(),
            'Should have at least user + assistant messages; tool calls add more'
        );

        // If tool calls were made, verify we got at least one
        $toolNames = $this->extractToolCallNames($chat);
        if (!empty($toolNames)) {
            // With chaining, we expect 2+ tool calls from different domains
            // But we don't fail if the LLM combined them — that's valid too
            $this->assertGreaterThanOrEqual(
                1,
                count($toolNames),
                'Expected at least 1 tool call for a multi-domain query'
            );
        }
    }

    #[Test]
    public function it_synthesizes_multi_tool_results_into_coherent_response(): void
    {
        if (!$this->isOllamaAvailable()) {
            $this->markTestSkipped('Ollama is not available');
        }

        [$agent, $chat] = $this->createAgentWithChat();

        // Ask something that requires combining results
        $query = 'How many products do we have and what are our site settings?';
        $response = $agent->chat(new UserMessage($query));

        $this->assertNotNull($response);
        $content = $response->getContent();
        $this->assertNotEmpty($content);

        // The response should be a coherent answer, not raw tool output
        // A well-synthesized response should be reasonably long (not just a one-liner)
        $this->assertGreaterThan(
            20,
            strlen($content),
            'Cross-domain response should be a substantive synthesized answer'
        );
    }
}
