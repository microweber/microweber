<?php

declare(strict_types=1);

namespace Modules\Ai\Agents;

use Modules\Ai\Services\AgentFactory;
use Modules\Ai\Services\RagSearchService;
use Modules\Ai\Tools\RagSearchTool;
use NeuronAI\SystemPrompt;
use Illuminate\Support\Facades\Config;

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
}
