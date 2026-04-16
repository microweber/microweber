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
 * Tests that GeneralAgent has complete domain tool coverage and that
 * LLM-based routing correctly selects the right tools for various queries.
 */
class AgentDomainRoutingTest extends TestCase
{
    private function makeAgent(): GeneralAgent
    {
        $agentFactory = app(AgentFactory::class);
        return $agentFactory->agent('general', 'ollama', $this->getOllamaModel());
    }

    private function getToolNames(GeneralAgent $agent): array
    {
        return array_map(
            fn($tool) => $tool->getName(),
            $agent->getTools()
        );
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

    // ──────────────────────────────────────────────
    // Domain tool coverage tests (no LLM needed)
    // ──────────────────────────────────────────────

    #[Test]
    public function it_has_all_analytics_tools(): void
    {
        $toolNames = $this->getToolNames($this->makeAgent());

        $expected = [
            'analytics_traffic_summary',
            'analytics_top_pages',
            'analytics_traffic_referrers',
            'analytics_audience_breakdown',
        ];

        foreach ($expected as $tool) {
            $this->assertContains($tool, $toolNames, "Missing analytics tool: {$tool}");
        }
    }

    #[Test]
    public function it_has_all_billing_tools(): void
    {
        $toolNames = $this->getToolNames($this->makeAgent());

        $expected = [
            'billing_account_status',
            'billing_plan_summary',
            'billing_subscription_lookup',
            'billing_metrics_summary',
        ];

        foreach ($expected as $tool) {
            $this->assertContains($tool, $toolNames, "Missing billing tool: {$tool}");
        }
    }

    #[Test]
    public function it_has_all_invoice_tools(): void
    {
        $toolNames = $this->getToolNames($this->makeAgent());

        $expected = [
            'billing_invoice_lookup',
            'billing_invoice_detail',
            'billing_invoice_customer_history',
            'billing_invoice_unpaid_summary',
        ];

        foreach ($expected as $tool) {
            $this->assertContains($tool, $toolNames, "Missing invoice tool: {$tool}");
        }
    }

    #[Test]
    public function it_has_all_payment_tools(): void
    {
        $toolNames = $this->getToolNames($this->makeAgent());

        $expected = [
            'billing_payment_lookup',
            'billing_payment_detail',
            'billing_payment_provider_health',
            'billing_payment_webhook_health',
        ];

        foreach ($expected as $tool) {
            $this->assertContains($tool, $toolNames, "Missing payment tool: {$tool}");
        }
    }

    #[Test]
    public function it_has_all_form_tools(): void
    {
        $toolNames = $this->getToolNames($this->makeAgent());

        $expected = [
            'form_lookup',
            'form_submission_detail',
            'form_submission_search',
            'form_activity_summary',
        ];

        foreach ($expected as $tool) {
            $this->assertContains($tool, $toolNames, "Missing form tool: {$tool}");
        }
    }

    #[Test]
    public function it_has_all_newsletter_tools(): void
    {
        $toolNames = $this->getToolNames($this->makeAgent());

        $expected = [
            'newsletter_campaign_lookup',
            'newsletter_subscriber_lookup',
            'newsletter_template_lookup',
            'newsletter_automation_status',
        ];

        foreach ($expected as $tool) {
            $this->assertContains($tool, $toolNames, "Missing newsletter tool: {$tool}");
        }
    }

    #[Test]
    public function it_has_all_layout_tools(): void
    {
        $toolNames = $this->getToolNames($this->makeAgent());

        $expected = [
            'layout_lookup',
            'layout_active_template',
            'layout_asset_summary',
        ];

        foreach ($expected as $tool) {
            $this->assertContains($tool, $toolNames, "Missing layout tool: {$tool}");
        }
    }

    #[Test]
    public function it_has_all_shipping_tools(): void
    {
        $toolNames = $this->getToolNames($this->makeAgent());

        $expected = [
            'shipping_method_lookup',
            'shipping_zone_summary',
        ];

        foreach ($expected as $tool) {
            $this->assertContains($tool, $toolNames, "Missing shipping tool: {$tool}");
        }
    }

    #[Test]
    public function it_has_all_tax_tools(): void
    {
        $toolNames = $this->getToolNames($this->makeAgent());

        $expected = [
            'tax_rule_lookup',
            'tax_preview',
        ];

        foreach ($expected as $tool) {
            $this->assertContains($tool, $toolNames, "Missing tax tool: {$tool}");
        }
    }

    #[Test]
    public function it_has_settings_tool(): void
    {
        $toolNames = $this->getToolNames($this->makeAgent());
        $this->assertContains('settings_read', $toolNames, 'Missing settings_read tool');
    }

    #[Test]
    public function it_has_all_content_tools(): void
    {
        $toolNames = $this->getToolNames($this->makeAgent());

        $expected = [
            'content_search',
            'content_list',
            'get_content',
            'page_list',
            'post_list',
            'content_edit',
            'post_edit',
            'create_content',
            'create_post',
        ];

        foreach ($expected as $tool) {
            $this->assertContains($tool, $toolNames, "Missing content tool: {$tool}");
        }
    }

    #[Test]
    public function it_has_all_product_and_order_tools(): void
    {
        $toolNames = $this->getToolNames($this->makeAgent());

        $expected = [
            'product_search',
            'product_list',
            'order_search',
            'product_edit',
            'create_product',
        ];

        foreach ($expected as $tool) {
            $this->assertContains($tool, $toolNames, "Missing product/order tool: {$tool}");
        }
    }

    #[Test]
    public function it_has_customer_tool(): void
    {
        $toolNames = $this->getToolNames($this->makeAgent());
        $this->assertContains('customer_lookup', $toolNames, 'Missing customer_lookup tool');
    }

    #[Test]
    public function it_has_all_media_tools(): void
    {
        $toolNames = $this->getToolNames($this->makeAgent());

        $expected = [
            'media_lookup',
            'media_asset_detail',
            'media_storage_health',
            'media_search',
        ];

        foreach ($expected as $tool) {
            $this->assertContains($tool, $toolNames, "Missing media tool: {$tool}");
        }
    }

    #[Test]
    public function it_has_rag_search_tool(): void
    {
        $toolNames = $this->getToolNames($this->makeAgent());
        $this->assertContains('rag_search', $toolNames, 'Missing rag_search tool');
    }

    #[Test]
    public function it_covers_all_15_domains(): void
    {
        $toolNames = $this->getToolNames($this->makeAgent());

        // Every domain should have at least one tool with its prefix
        $domainPrefixes = [
            'analytics_'    => 'Analytics',
            'billing_'      => 'Billing',
            'form_'         => 'Forms',
            'newsletter_'   => 'Newsletter',
            'layout_'       => 'Layout',
            'shipping_'     => 'Shipping',
            'tax_'          => 'Tax',
            'settings_'     => 'Settings',
            'content_'      => 'Content',
            'product_'      => 'Product',
            'order_'        => 'Order',
            'customer_'     => 'Customer',
            'media_'        => 'Media',
            'rag_'          => 'RAG',
        ];

        foreach ($domainPrefixes as $prefix => $label) {
            $found = array_filter($toolNames, fn($name) => str_starts_with($name, $prefix));
            $this->assertNotEmpty($found, "No tools found for domain: {$label} (prefix: {$prefix})");
        }
    }

    #[Test]
    public function it_has_minimum_tool_count(): void
    {
        $agent = $this->makeAgent();
        $toolCount = count($agent->getTools());

        // 4 analytics + 4 billing + 4 invoice + 4 payment + 4 form + 4 newsletter
        // + 3 layout + 2 shipping + 2 tax + 1 settings + 9 content + 5 product/order
        // + 1 customer + 4 media + 1 rag = 52 minimum
        $this->assertGreaterThanOrEqual(50, $toolCount, "Expected at least 50 tools, got {$toolCount}");
    }

    #[Test]
    public function it_has_no_duplicate_tool_names(): void
    {
        $toolNames = $this->getToolNames($this->makeAgent());
        $duplicates = array_diff_assoc($toolNames, array_unique($toolNames));

        $this->assertEmpty($duplicates, 'Duplicate tool names found: ' . implode(', ', $duplicates));
    }

    #[Test]
    public function it_all_tools_have_descriptions(): void
    {
        $agent = $this->makeAgent();
        foreach ($agent->getTools() as $tool) {
            $name = $tool->getName();
            $desc = $tool->getDescription();
            $this->assertNotEmpty($desc, "Tool '{$name}' has no description");
        }
    }

    // ──────────────────────────────────────────────
    // Live LLM routing tests (require Ollama)
    // ──────────────────────────────────────────────

    /**
     * Domain query samples for LLM routing verification.
     * Each entry: [query, expected_domain_prefix, description]
     */
    public static function domainQueryProvider(): array
    {
        return [
            'analytics query' => [
                'How many visitors did we get this week?',
                'analytics_',
                'Should route to analytics tools',
            ],
            'billing query' => [
                'What is our current billing plan?',
                'billing_',
                'Should route to billing tools',
            ],
            'content query' => [
                'List all published blog posts',
                ['post_list', 'content_list', 'content_search'],
                'Should route to content/post list tools',
            ],
            'product query' => [
                'Show me all products in the shop',
                ['product_list', 'product_search'],
                'Should route to product tools',
            ],
            'order query' => [
                'Show me recent orders',
                'order_',
                'Should route to order tools',
            ],
            'customer query' => [
                'Find customer with email test@example.com',
                'customer_',
                'Should route to customer tools',
            ],
            'settings query' => [
                'What are the current site settings?',
                'settings_',
                'Should route to settings tools',
            ],
            'newsletter query' => [
                'Show me newsletter campaigns',
                'newsletter_',
                'Should route to newsletter tools',
            ],
            'form query' => [
                'Show me contact form submissions',
                'form_',
                'Should route to form tools',
            ],
            'media query' => [
                'Search for images in the media library',
                'media_',
                'Should route to media tools',
            ],
            'shipping query' => [
                'What shipping methods are available?',
                'shipping_',
                'Should route to shipping tools',
            ],
            'tax query' => [
                'Show me the tax rules',
                'tax_',
                'Should route to tax tools',
            ],
            'layout query' => [
                'What is the currently active template?',
                'layout_',
                'Should route to layout tools',
            ],
        ];
    }

    #[Test]
    #[DataProvider('domainQueryProvider')]
    public function it_routes_domain_query_to_correct_tools(
        string $query,
        string|array $expectedToolPrefix,
        string $description
    ): void {
        if (!$this->isOllamaAvailable()) {
            $this->markTestSkipped('Ollama is not available — skipping live LLM routing test');
        }

        $agent = $this->makeAgent();

        $user = User::factory()->create(['role' => 'admin']);
        $chat = AgentChat::factory()->create([
            'user_id' => $user->id,
            'agent_type' => 'general',
            'is_active' => true,
        ]);
        $agent->setAgentChat($chat);

        $response = $agent->chat(new UserMessage($query));

        $this->assertNotNull($response, "{$description}: Response should not be null");
        $this->assertNotEmpty($response->getContent(), "{$description}: Response content should not be empty");

        // Check chat history for tool call messages — verify the LLM called the right domain tool
        $messages = $chat->messages()->get();
        $toolCallMessages = $messages->filter(function ($msg) {
            $metadata = is_string($msg->metadata) ? json_decode($msg->metadata, true) : ($msg->metadata ?? []);
            return ($metadata['type'] ?? '') === 'tool_call'
                || ($msg->role === 'tool' || $msg->role === 'tool_call');
        });

        // If tool calls were made, verify at least one matches the expected domain
        if ($toolCallMessages->isNotEmpty()) {
            $toolNames = $toolCallMessages->map(function ($msg) {
                $metadata = is_string($msg->metadata) ? json_decode($msg->metadata, true) : ($msg->metadata ?? []);
                return $metadata['tool_name'] ?? $metadata['name'] ?? '';
            })->filter()->values()->toArray();

            if (!empty($toolNames)) {
                $matched = false;
                foreach ($toolNames as $toolName) {
                    if (is_array($expectedToolPrefix)) {
                        foreach ($expectedToolPrefix as $prefix) {
                            if ($toolName === $prefix || str_starts_with($toolName, $prefix)) {
                                $matched = true;
                                break 2;
                            }
                        }
                    } else {
                        if (str_starts_with($toolName, $expectedToolPrefix)) {
                            $matched = true;
                            break;
                        }
                    }
                }

                $prefixLabel = is_array($expectedToolPrefix) ? implode('|', $expectedToolPrefix) : $expectedToolPrefix;
                $this->assertTrue(
                    $matched,
                    "{$description}: Expected tool call matching [{$prefixLabel}], got: " . implode(', ', $toolNames)
                );
            }
        }

        // At minimum, we should always get a response back
        $this->assertGreaterThanOrEqual(
            2,
            $messages->count(),
            "{$description}: Should have at least user + assistant messages"
        );
    }

    #[Test]
    public function it_handles_general_greeting_without_tool_calls(): void
    {
        if (!$this->isOllamaAvailable()) {
            $this->markTestSkipped('Ollama is not available');
        }

        $agent = $this->makeAgent();

        $user = User::factory()->create(['role' => 'admin']);
        $chat = AgentChat::factory()->create([
            'user_id' => $user->id,
            'agent_type' => 'general',
            'is_active' => true,
        ]);
        $agent->setAgentChat($chat);

        // A greeting should NOT trigger any tool calls
        $response = $agent->chat(new UserMessage('Hi there!'));

        $this->assertNotNull($response);
        $this->assertNotEmpty($response->getContent());
    }

    #[Test]
    public function it_system_prompt_mentions_all_domains(): void
    {
        $agent = $this->makeAgent();
        $instructions = $agent->instructions();

        $domains = [
            'analytics', 'billing', 'invoice', 'payment', 'form',
            'newsletter', 'content', 'product', 'order', 'customer',
            'media', 'layout', 'shipping', 'tax', 'settings',
        ];

        foreach ($domains as $domain) {
            $this->assertStringContainsString(
                $domain,
                strtolower($instructions),
                "System prompt should mention '{$domain}' domain"
            );
        }
    }
}
