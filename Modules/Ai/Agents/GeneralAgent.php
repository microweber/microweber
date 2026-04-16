<?php

declare(strict_types=1);

namespace Modules\Ai\Agents;

use Modules\Ai\Services\AgentFactory;
use Modules\Ai\Services\RagSearchService;
use Modules\Ai\Tools\RagSearchTool;
use Modules\Ai\Workflows\GeneralAgentWorkflow;
use Modules\Ai\Events\ProgressEvent;
use NeuronAI\SystemPrompt;
use NeuronAI\Chat\Messages\UserMessage;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class GeneralAgent extends BaseAgent
{
    protected string $domain = 'general';

    protected AgentFactory $agentFactory;
    protected RagSearchService $ragService;

    public function __construct(
        AgentFactory $agentFactory,
        RagSearchService $ragService,
        ?string $providerName = null,
        ?string $model = null,
        protected array $dependencies = []
    ) {
        $this->agentFactory = $agentFactory;
        $this->ragService = $ragService;
        parent::__construct($providerName, $model, $dependencies);
    }

    public function instructions(): string
    {
        return (string)new SystemPrompt(
            background: [
                'You are the General AI Assistant for Microweber CMS with full access to the entire system.',
                'You have tools to query and manage every aspect of the CMS: analytics, billing, content, customers, forms, invoices, layouts, media, newsletters, orders, payments, products, settings, shipping, and taxes.',
                'Use the appropriate tool to answer user questions. Do NOT guess — always call the relevant tool to get real data.',
                'You can also create and edit content, posts, products, and pages directly through your tools.',
            ],
            steps: [
                'Analyze the user request and identify which tool(s) to call.',
                'For analytics/stats questions, use the analytics tools (traffic summary, top pages, referrers, audience).',
                'For billing/subscription questions, use the billing tools (account status, plan summary, subscription lookup, metrics).',
                'For invoice questions, use the invoice tools (lookup, detail, customer history, unpaid summary).',
                'For payment questions, use the payment tools (lookup, detail, provider health, webhook health).',
                'For form/submission questions, use the form tools (form lookup, submission search, activity summary).',
                'For newsletter questions, use the newsletter tools (campaign, subscriber, template lookup, automation status).',
                'For content/page/post questions, use the content tools (search, list, get, create, edit).',
                'For product/order questions, use the product and order tools (search, list, create, edit).',
                'For customer questions, use the customer lookup tool.',
                'For media questions, use the media tools (lookup, asset detail, storage health).',
                'For layout/template questions, use the layout tools (lookup, active template, asset summary).',
                'For shipping questions, use the shipping tools (method lookup, zone summary).',
                'For tax questions, use the tax tools (rule lookup, preview).',
                'For settings questions, use the settings read tool.',
                'For cross-domain questions, call multiple tools and combine the results.',
                'Always provide clear, data-driven responses based on tool results.',
            ],
            output: [
                'Format responses with well-structured HTML for readability.',
                'Present data in tables, cards, or lists as appropriate.',
                'Include relevant numbers, dates, and specific details from tool results.',
                'When multiple tools are needed, synthesize the results into a coherent answer.',
                'For write operations (create/edit), confirm what was changed and provide a summary.',
            ],
        );
    }

    protected function setupTools(): void
    {
        // --- Analytics tools ---
        $this->addTool(new \Modules\Ai\Tools\AnalyticsTrafficSummaryTool($this->dependencies));
        $this->addTool(new \Modules\Ai\Tools\AnalyticsTopPagesTool($this->dependencies));
        $this->addTool(new \Modules\Ai\Tools\AnalyticsTrafficReferrersTool($this->dependencies));
        $this->addTool(new \Modules\Ai\Tools\AnalyticsAudienceBreakdownTool($this->dependencies));

        // --- Billing tools ---
        $this->addTool(new \Modules\Ai\Tools\BillingAccountStatusTool($this->dependencies));
        $this->addTool(new \Modules\Ai\Tools\BillingPlanSummaryTool($this->dependencies));
        $this->addTool(new \Modules\Ai\Tools\BillingSubscriptionLookupTool($this->dependencies));
        $this->addTool(new \Modules\Ai\Tools\BillingMetricsSummaryTool($this->dependencies));

        // --- Invoice tools ---
        $this->addTool(new \Modules\Ai\Tools\BillingInvoiceLookupTool($this->dependencies));
        $this->addTool(new \Modules\Ai\Tools\BillingInvoiceDetailTool($this->dependencies));
        $this->addTool(new \Modules\Ai\Tools\BillingInvoiceCustomerHistoryTool($this->dependencies));
        $this->addTool(new \Modules\Ai\Tools\BillingInvoiceUnpaidSummaryTool($this->dependencies));

        // --- Payment tools ---
        $this->addTool(new \Modules\Ai\Tools\BillingPaymentLookupTool($this->dependencies));
        $this->addTool(new \Modules\Ai\Tools\BillingPaymentDetailTool($this->dependencies));
        $this->addTool(new \Modules\Ai\Tools\BillingPaymentProviderHealthTool($this->dependencies));
        $this->addTool(new \Modules\Ai\Tools\BillingPaymentWebhookHealthTool($this->dependencies));

        // --- Form tools ---
        $this->addTool(new \Modules\Ai\Tools\FormLookupTool($this->dependencies));
        $this->addTool(new \Modules\Ai\Tools\FormSubmissionDetailTool($this->dependencies));
        $this->addTool(new \Modules\Ai\Tools\FormSubmissionSearchTool($this->dependencies));
        $this->addTool(new \Modules\Ai\Tools\FormActivitySummaryTool($this->dependencies));

        // --- Newsletter tools ---
        $this->addTool(new \Modules\Ai\Tools\NewsletterCampaignLookupTool($this->dependencies));
        $this->addTool(new \Modules\Ai\Tools\NewsletterSubscriberLookupTool($this->dependencies));
        $this->addTool(new \Modules\Ai\Tools\NewsletterTemplateLookupTool($this->dependencies));
        $this->addTool(new \Modules\Ai\Tools\NewsletterAutomationStatusTool($this->dependencies));

        // --- Layout tools ---
        $this->addTool(new \Modules\Ai\Tools\LayoutLookupTool($this->dependencies));
        $this->addTool(new \Modules\Ai\Tools\LayoutActiveTemplateTool($this->dependencies));
        $this->addTool(new \Modules\Ai\Tools\LayoutAssetSummaryTool($this->dependencies));

        // --- Shipping tools ---
        $this->addTool(new \Modules\Ai\Tools\ShippingMethodLookupTool($this->dependencies));
        $this->addTool(new \Modules\Ai\Tools\ShippingZoneSummaryTool($this->dependencies));

        // --- Tax tools ---
        $this->addTool(new \Modules\Ai\Tools\TaxRuleLookupTool($this->dependencies));
        $this->addTool(new \Modules\Ai\Tools\TaxPreviewTool($this->dependencies));

        // --- Settings tool ---
        $this->addTool(new \Modules\Ai\Tools\SettingsReadTool($this->dependencies));

        // --- Content tools (read + write) ---
        $this->addTool(new \Modules\Ai\Tools\ContentSearchTool($this->dependencies));
        $this->addTool(new \Modules\Ai\Tools\ContentListTool($this->dependencies));
        $this->addTool(new \Modules\Ai\Tools\GetContentTool($this->dependencies));
        $this->addTool(new \Modules\Ai\Tools\PageListTool($this->dependencies));
        $this->addTool(new \Modules\Ai\Tools\PostListTool($this->dependencies));
        $this->addTool(new \Modules\Ai\Tools\ContentEditTool($this->dependencies));
        $this->addTool(new \Modules\Ai\Tools\PostEditTool($this->dependencies));
        $this->addTool(new \Modules\Ai\Tools\CreateContentTool($this->dependencies));
        $this->addTool(new \Modules\Ai\Tools\CreatePostTool($this->dependencies));

        // --- Product & Order tools ---
        $this->addTool(new \Modules\Ai\Tools\ProductSearchTool($this->dependencies));
        $this->addTool(new \Modules\Ai\Tools\ProductListTool($this->dependencies));
        $this->addTool(new \Modules\Ai\Tools\OrderSearchTool($this->dependencies));
        $this->addTool(new \Modules\Ai\Tools\ProductEditTool($this->dependencies));
        $this->addTool(new \Modules\Ai\Tools\CreateProductTool($this->dependencies));

        // --- Customer tools ---
        $this->addTool(new \Modules\Ai\Tools\CustomerLookupTool($this->dependencies));

        // --- Media tools ---
        $this->addTool(new \Modules\Ai\Tools\MediaLookupTool($this->dependencies));
        $this->addTool(new \Modules\Ai\Tools\MediaAssetDetailTool($this->dependencies));
        $this->addTool(new \Modules\Ai\Tools\MediaStorageHealthTool($this->dependencies));
        $this->addTool(new \Modules\Ai\Tools\MediaSearchTool($this->dependencies));

        // --- RAG cross-domain search ---
        $this->addTool(new RagSearchTool($this->ragService, $this->dependencies));

        // --- Optional external tools ---
        if (Config::get('modules.ai.drivers.tavily.enabled') && Config::get('modules.ai.drivers.tavily.api_key')) {
            $this->addTool(\NeuronAI\Tools\Toolkits\Tavily\TavilySearchTool::make(Config::get('modules.ai.drivers.tavily.api_key')));
        }
    }

    /**
     * Handle user query using workflow-based routing like the old agent patterns
     */
    public function handle(string $message): string
    {
        try {
            // Use the workflow system for proper routing and execution
            $workflow = new \Modules\Ai\Workflows\GeneralAgentWorkflow(
                userQuery: $message,
                agentFactory: $this->agentFactory,
                routingAgent: $this
            );

            // Execute the workflow and collect progress events
            $responses = [];
            $finalResponse = '';

            // Use reflection to access the execute method if it's protected
            try {
                $reflection = new \ReflectionMethod($workflow, 'execute');
                if (!$reflection->isPublic()) {
                    $reflection->setAccessible(true);
                }
                
                foreach ($reflection->invoke($workflow) as $event) {
                    if ($event instanceof \Modules\Ai\Events\ProgressEvent) {
                        $responses[] = $event->message;
                        Log::info('GeneralAgent Progress', ['message' => $event->message]);
                    } elseif ($event instanceof \NeuronAI\Workflow\StopEvent) {
                        // Get the final response from workflow state
                        $state = $workflow->getState();
                        $finalResponse = $state->get('agent_response', $this->getGeneralHelp());
                        break;
                    }
                }
            } catch (\ReflectionException $e) {
                Log::error('Workflow reflection error', ['error' => $e->getMessage()]);
                return $this->simpleRouting($message);
            }

            // If we have a final response, return it
            if (!empty($finalResponse)) {
                return $finalResponse;
            }

            // Fallback to simple routing if workflow fails
            Log::warning('Workflow execution did not return response, falling back to simple routing');
            return $this->simpleRouting($message);

        } catch (\Exception $e) {
            Log::error('GeneralAgent workflow error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Fallback to simple routing
            return $this->simpleRouting($message);
        }
    }

    /**
     * Fallback simple routing method
     */
    protected function simpleRouting(string $message): string
    {
        $domain = $this->detectDomain($message);

        if ($domain !== 'general') {
            return $this->routeToSpecializedAgent($domain, $message);
        }

        return $this->getGeneralHelp();
    }

    /**
     * Enhanced domain detection with context and intent analysis
     */
    protected function detectDomain(string $message): string
    {
        $message = strtolower($message);

        // Define domain patterns with priority scoring
        $patterns = [
            'content' => [
                'high' => [
                    '/\b(create|write|edit|update|publish)\s+(blog|post|page|content|article)\b/',
                    '/\b(seo|search engine optimization|trending topics|google trends)\b/',
                    '/\b(content management|cms)\b/',
                    '/\b(blog posts?|articles?|pages?)\b/'
                ],
                'medium' => [
                    '/\b(content|blog|post|page|article|write|seo|trending|trends)\b/',
                    '/\b(social media|facebook|twitter|instagram)\b/',
                    '/\b(keywords|meta description|title tag)\b/'
                ],
                'keywords' => ['content', 'blog', 'post', 'page', 'write', 'seo', 'article', 'trending', 'trends', 'google trends', 'social media']
            ],
            'shop' => [
                'high' => [
                    '/\b(create|add|edit|update|delete)\s+(product|item)\b/',
                    '/\b(manage|view|search|find|show)\s+(product|inventory|order|stock)\b/',
                    '/\b(e-commerce|online store|shopping cart)\b/',
                    '/\bprice\s+(range|filter|under|over|between)\b/',
                    '/\bproducts?\s+(under|over|below|above)\b/'
                ],
                'medium' => [
                    '/\b(product|shop|order|price|inventory|sku|buy|sell|cart|checkout)\b/',
                    '/\b(category|categories|catalog)\b/',
                    '/\b(payment|shipping|delivery)\b/',
                    '/\bmy\s+(product|shop|store|inventory)\b/'
                ],
                'keywords' => ['product', 'products', 'shop', 'order', 'price', 'inventory', 'sku', 'buy', 'sell', 'cart', 'checkout', 'ecommerce', 'store']
            ],
            'customer' => [
                'high' => [
                    '/\b(find|search|lookup|get)\s+(customer|user|client)\b/',
                    '/\b(customer\s+(details|information|data|profile))\b/',
                    '/\b(email|phone|address)\s+(search|lookup|find)\b/',
                    '/\b(user\s+(account|management|profile))\b/'
                ],
                'medium' => [
                    '/\b(customer|user|account|address|email|phone|client)\b/',
                    '/\b(member|membership|subscriber)\b/',
                    '/\b(contact|support|help desk)\b/'
                ],
                'keywords' => ['customer', 'user', 'account', 'address', 'email', 'phone', 'client', 'member', 'contact']
            ]
        ];

        // Calculate scores for each domain
        $scores = [];
        
        foreach ($patterns as $domain => $domainPatterns) {
            $score = 0;
            
            // High priority patterns (score: 10)
            foreach ($domainPatterns['high'] as $pattern) {
                if (preg_match($pattern, $message)) {
                    $score += 10;
                }
            }
            
            // Medium priority patterns (score: 5)
            foreach ($domainPatterns['medium'] as $pattern) {
                if (preg_match($pattern, $message)) {
                    $score += 5;
                }
            }
            
            // Keyword counting (score: 1 per keyword)
            foreach ($domainPatterns['keywords'] as $keyword) {
                $score += substr_count($message, $keyword);
            }
            
            $scores[$domain] = $score;
        }

        // Find the domain with the highest score
        $maxScore = max($scores);
        $bestDomain = array_search($maxScore, $scores);
        
        // Only return specialized domain if score is significant enough (lowered threshold)
        if ($maxScore >= 1) {
            Log::info('Domain detection', [
                'message' => substr($message, 0, 100),
                'scores' => $scores,
                'detected' => $bestDomain
            ]);
            return $bestDomain;
        }

        // Default to general if no clear domain detected
        Log::info('Domain detection defaulted to general', [
            'message' => substr($message, 0, 100),
            'scores' => $scores
        ]);
        
        return 'general';
    }

    public function routeToSpecializedAgent(string $domain, string $message): string
    {
        try {
            switch ($domain) {
                case 'customer':
                    $agent = $this->agentFactory->agent('customer', $this->providerName, $this->model);
                    return $agent->handle($message);

                case 'shop':
                case 'product':
                    $agent = $this->agentFactory->agent('shop', $this->providerName, $this->model);
                    return $agent->handle($message);

                case 'content':
                    $agent = $this->agentFactory->agent('content', $this->providerName, $this->model);
                    return $agent->handle($message);

                default:
                    return $this->getGeneralHelp();
            }
        } catch (\Exception $e) {
            Log::error('Agent routing error', [
                'domain' => $domain,
                'message' => $message,
                'error' => $e->getMessage()
            ]);
            return $this->getGeneralHelp();
        }
    }

    public function getGeneralHelp(): string
    {
        return '
        <div class="general-help">
            <h3>Welcome to Microweber AI Assistant</h3>
            <p>I can help you with various tasks in your Microweber CMS. Here are some things you can ask me about:</p>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-users"></i> Customer Management</h5>
                        </div>
                        <div class="card-body">
                            <p>I can help you find and manage customers:</p>
                            <ul>
                                <li>Search customers by email, phone, or name</li>
                                <li>View customer details and addresses</li>
                                <li>Check customer order history</li>
                            </ul>
                            <p><strong>Try asking:</strong> "Find customer john@example.com" or "Search for customers named Smith"</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-shopping-cart"></i> Product & Shop Management</h5>
                        </div>
                        <div class="card-body">
                            <p>I can help you manage your online store:</p>
                            <ul>
                                <li>Search products by name, SKU, or category</li>
                                <li>Filter products by price range</li>
                                <li>Check product inventory and details</li>
                            </ul>
                            <p><strong>Try asking:</strong> "Search for products under €50" or "Find products in Electronics category"</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-edit"></i> Content Management</h5>
                        </div>
                        <div class="card-body">
                            <p>I can assist with content creation and management:</p>
                            <ul>
                                <li>Help create SEO-friendly content</li>
                                <li>Research trending topics with Google Trends</li>
                                <li>Suggest content improvements</li>
                                <li>Provide writing and formatting guidance</li>
                            </ul>
                            <p><strong>Try asking:</strong> "What are trending topics about AI?" or "Help me write a product description"</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-question-circle"></i> General Help</h5>
                        </div>
                        <div class="card-body">
                            <p>I can provide general assistance with:</p>
                            <ul>
                                <li>Microweber CMS features and functionality</li>
                                <li>Best practices and recommendations</li>
                                <li>Troubleshooting guidance</li>
                            </ul>
                            <p><strong>Try asking:</strong> "How do I..." or "What is the best way to..."</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="alert alert-info">
                <h6>💡 Tips for better results:</h6>
                <ul class="mb-0">
                    <li>Be specific in your requests (include email addresses, product names, etc.)</li>
                    <li>Use natural language - ask questions as you would to a colleague</li>
                    <li>If you don\'t find what you\'re looking for, try different search terms</li>
                    <li>Ask about trending topics to get data-driven content ideas</li>
                </ul>
            </div>
        </div>';
    }
}
