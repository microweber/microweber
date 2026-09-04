<?php

declare(strict_types=1);

namespace Modules\Ai\Agents;

use Modules\Ai\Services\AgentFactory;
use Modules\Ai\Services\RagSearchService;
use NeuronAI\Agent\SystemPrompt;
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
                'For SEO improvement requests, use content_improvement or generate_seo_metadata tools.',
                'For cross-domain questions, call multiple tools and combine the results.',
                'For write operations (create, edit, delete): briefly confirm what you are about to do before calling the tool, then call it and report the result.',
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
        // Domain tools are registered by their owning modules into the AiTools registry.
        $this->loadToolsFromRegistry([
            'analytics_traffic_summary',
            'analytics_top_pages',
            'analytics_traffic_referrers',
            'analytics_audience_breakdown',
            'billing_account_status',
            'billing_plan_summary',
            'billing_subscription_lookup',
            'billing_metrics_summary',
            'billing_invoice_lookup',
            'billing_invoice_detail',
            'billing_invoice_customer_history',
            'billing_invoice_unpaid_summary',
            'billing_payment_lookup',
            'billing_payment_detail',
            'billing_payment_provider_health',
            'billing_payment_webhook_health',
            'form_lookup',
            'form_submission_detail',
            'form_submission_search',
            'form_activity_summary',
            'newsletter_campaign_lookup',
            'newsletter_subscriber_lookup',
            'newsletter_template_lookup',
            'newsletter_automation_status',
            'layout_lookup',
            'layout_active_template',
            'layout_asset_summary',
            'shipping_method_lookup',
            'shipping_zone_summary',
            'tax_rule_lookup',
            'tax_preview',
            'settings_read',
            'content_search',
            'content_list',
            'get_content',
            'page_list',
            'post_list',
            'content_edit',
            'post_edit',
            'create_content',
            'create_post',
            'content_improvement',
            'generate_seo_metadata',
            'product_search',
            'product_list',
            'order_search',
            'product_edit',
            'create_product',
            'customer_lookup',
            'media_lookup',
            'media_asset_detail',
            'media_storage_health',
            'media_search',
            'rag_search',
            // Module data tools (menus, taxonomy, commerce extras, custom fields).
            'menu_list',
            'category_list',
            'tag_list',
            'comments_list',
            'testimonials_list',
            'faq_list',
            'slider_list',
            'country_list',
            'currency_rates',
            'coupons_list',
            'offer_list',
            'rating_list',
            'attributes_list',
            'mail_template_list',
            'company_list',
            'custom_fields_list',
            'custom_field_values',
            'content_data_get',
        ]);

        if (Config::get('modules.ai.drivers.tavily.enabled') && Config::get('modules.ai.drivers.tavily.api_key')) {
            $this->addTool(\NeuronAI\Tools\Toolkits\Tavily\TavilySearchTool::make(Config::get('modules.ai.drivers.tavily.api_key')));
        }
    }
}
