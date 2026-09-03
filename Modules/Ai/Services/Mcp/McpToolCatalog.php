<?php

declare(strict_types=1);

namespace Modules\Ai\Services\Mcp;

use Modules\Category\Tools\CategoryListTool;
use Modules\Comments\Tools\CommentsListTool;
use Modules\Content\Tools\ContentSearchTool;
use Modules\Country\Tools\CountryListTool;
use Modules\Currency\Tools\CurrencyRatesTool;
use Modules\Content\Tools\CreateContentTool;
use Modules\Faq\Tools\FaqListTool;
use Modules\Menu\Tools\MenuListTool;
use Modules\Slider\Tools\SliderListTool;
use Modules\Testimonials\Tools\TestimonialsListTool;
use Modules\Product\Tools\CreateProductTool;
use Modules\Content\Tools\GetContentTool;
use Modules\SiteStats\Tools\AnalyticsAudienceBreakdownTool;
use Modules\SiteStats\Tools\AnalyticsTopPagesTool;
use Modules\SiteStats\Tools\AnalyticsTrafficReferrersTool;
use Modules\SiteStats\Tools\AnalyticsTrafficSummaryTool;
use Modules\Billing\Tools\BillingAccountStatusTool;
use Modules\Billing\Tools\BillingInvoiceCustomerHistoryTool;
use Modules\Billing\Tools\BillingInvoiceDetailTool;
use Modules\Billing\Tools\BillingInvoiceLookupTool;
use Modules\Billing\Tools\BillingInvoiceUnpaidSummaryTool;
use Modules\Billing\Tools\BillingPaymentDetailTool;
use Modules\Billing\Tools\BillingPaymentLookupTool;
use Modules\Billing\Tools\BillingPaymentProviderHealthTool;
use Modules\Billing\Tools\BillingPaymentWebhookHealthTool;
use Modules\Shipping\Tools\ShippingMethodLookupTool;
use Modules\Shipping\Tools\ShippingZoneSummaryTool;
use Modules\Tax\Tools\TaxPreviewTool;
use Modules\Tax\Tools\TaxRuleLookupTool;
use Modules\Billing\Tools\BillingMetricsSummaryTool;
use Modules\Billing\Tools\BillingPlanSummaryTool;
use Modules\Billing\Tools\BillingSubscriptionLookupTool;
use Modules\Form\Tools\FormActivitySummaryTool;
use Modules\Form\Tools\FormLookupTool;
use Modules\Form\Tools\FormSubmissionDetailTool;
use Modules\Form\Tools\FormSubmissionSearchTool;
use Modules\Layouts\Tools\LayoutActiveTemplateTool;
use Modules\Layouts\Tools\LayoutAssetSummaryTool;
use Modules\Layouts\Tools\LayoutLookupTool;
use Modules\Newsletter\Tools\NewsletterAutomationStatusTool;
use Modules\Newsletter\Tools\NewsletterCampaignLookupTool;
use Modules\Newsletter\Tools\NewsletterSubscriberLookupTool;
use Modules\Newsletter\Tools\NewsletterTemplateLookupTool;
use Modules\Media\Tools\MediaAssetDetailTool;
use Modules\Media\Tools\MediaLookupTool;
use Modules\Media\Tools\MediaStorageHealthTool;
use Modules\Order\Tools\OrderSearchTool;
use Modules\Product\Tools\ProductSearchTool;
use Modules\Settings\Tools\SettingsReadTool;
use MicroweberPackages\AiTools\Contracts\ToolInterface;
use ReflectionClass;

class McpToolCatalog
{
    /**
     * @return array<string, array{tool: class-string<ToolInterface>, module: string, title: string}>
     */
    public function allDefinitions(): array
    {
        return [
            'content.lookup' => [
                'tool' => ContentSearchTool::class,
                'module' => 'content',
                'title' => 'Search Microweber content by keyword and type.',
            ],
            'content.get' => [
                'tool' => GetContentTool::class,
                'module' => 'content',
                'title' => 'Retrieve detailed information for a Microweber content item by ID.',
            ],
            'menu.list' => [
                'tool' => MenuListTool::class,
                'module' => 'menu',
                'title' => 'List the site navigation menus and their items.',
            ],
            'category.list' => [
                'tool' => CategoryListTool::class,
                'module' => 'category',
                'title' => 'List the site content categories.',
            ],
            'comments.list' => [
                'tool' => CommentsListTool::class,
                'module' => 'comments',
                'title' => 'List recent site comments with moderation status.',
            ],
            'testimonials.list' => [
                'tool' => TestimonialsListTool::class,
                'module' => 'testimonials',
                'title' => 'List the site testimonials.',
            ],
            'faq.list' => [
                'tool' => FaqListTool::class,
                'module' => 'faq',
                'title' => 'List the site FAQ entries.',
            ],
            'slider.list' => [
                'tool' => SliderListTool::class,
                'module' => 'slider',
                'title' => 'List the site slider slides.',
            ],
            'country.list' => [
                'tool' => CountryListTool::class,
                'module' => 'country',
                'title' => 'Look up countries (name, ISO code, phone code).',
            ],
            'currency.rates' => [
                'tool' => CurrencyRatesTool::class,
                'module' => 'currency',
                'title' => 'List configured currency exchange rates.',
            ],
            'content.create' => [
                'tool' => CreateContentTool::class,
                'module' => 'content',
                'title' => 'Create a new page, post, or generic content item in Microweber.',
                // task-2026-06-06-mcpwritehint: CreateContentTool persists a row
                // (Content::create). It is NOT read-only — MCP clients gate
                // auto-invocation on readOnlyHint, so this must be false or the
                // tool is advertised as safe-by-default.
                'readOnlyHint' => false,
            ],
            'product.lookup' => [
                'tool' => ProductSearchTool::class,
                'module' => 'product',
                'title' => 'Search Microweber products by title, category, or price range.',
            ],
            'product.create' => [
                'tool' => CreateProductTool::class,
                'module' => 'product',
                'title' => 'Create a new product (title, price, SKU, description, stock) in Microweber.',
                // task-2026-06-06-mcpwritehint: CreateProductTool persists a row
                // (Product::create). Not read-only — see content.create above.
                'readOnlyHint' => false,
            ],
            'order.lookup' => [
                'tool' => OrderSearchTool::class,
                'module' => 'order',
                'title' => 'Search Microweber orders by reference, customer, status, or date.',
            ],
            'settings.read' => [
                'tool' => SettingsReadTool::class,
                'module' => 'settings',
                'title' => 'Read non-sensitive Microweber option values by group and key.',
            ],
            'media.lookup' => [
                'tool' => MediaLookupTool::class,
                'module' => 'media',
                'title' => 'Search uploaded media assets by filename, title, folder, or CDN sync status.',
            ],
            'media.asset_detail' => [
                'tool' => MediaAssetDetailTool::class,
                'module' => 'media',
                'title' => 'Inspect a single media asset with safe path, folder, relation, and metadata summaries.',
            ],
            'media.storage_health' => [
                'tool' => MediaStorageHealthTool::class,
                'module' => 'media',
                'title' => 'Summarize media-library storage, public disk usage, and folder distribution without write access.',
            ],
            'layouts.layout_lookup' => [
                'tool' => LayoutLookupTool::class,
                'module' => 'layouts',
                'title' => 'Search installed template layouts by template, file, category, or content type.',
            ],
            'layouts.active_template' => [
                'tool' => LayoutActiveTemplateTool::class,
                'module' => 'layouts',
                'title' => 'Summarize the active template state, linked content usage, and style-setting groups.',
            ],
            'layouts.asset_summary' => [
                'tool' => LayoutAssetSummaryTool::class,
                'module' => 'layouts',
                'title' => 'Summarize template asset categories and safe relative design/style references.',
            ],
            'analytics.traffic_summary' => [
                'tool' => AnalyticsTrafficSummaryTool::class,
                'module' => 'analytics',
                'title' => 'Summarize visitors, pageviews, bounce rate, average session duration, and top traffic sources.',
            ],
            'analytics.top_pages' => [
                'tool' => AnalyticsTopPagesTool::class,
                'module' => 'analytics',
                'title' => 'List the top pages or content items by sessions and views.',
            ],
            'analytics.traffic_referrers' => [
                'tool' => AnalyticsTrafficReferrersTool::class,
                'module' => 'analytics',
                'title' => 'Summarize traffic referrers by domain and path.',
            ],
            'analytics.audience_breakdown' => [
                'tool' => AnalyticsAudienceBreakdownTool::class,
                'module' => 'analytics',
                'title' => 'Break down audiences by country and device type.',
            ],
            'forms.form_lookup' => [
                'tool' => FormLookupTool::class,
                'module' => 'forms',
                'title' => 'Search form definitions and summarize submission activity.',
            ],
            'forms.submission_search' => [
                'tool' => FormSubmissionSearchTool::class,
                'module' => 'forms',
                'title' => 'Search form submissions with masked sender information and message previews.',
            ],
            'forms.submission_detail' => [
                'tool' => FormSubmissionDetailTool::class,
                'module' => 'forms',
                'title' => 'Inspect a single form submission with normalized fields and masked personal data.',
            ],
            'forms.activity_summary' => [
                'tool' => FormActivitySummaryTool::class,
                'module' => 'forms',
                'title' => 'Summarize recent form submission volume, unread backlog, and active forms.',
            ],
            'billing.subscription_lookup' => [
                'tool' => BillingSubscriptionLookupTool::class,
                'module' => 'billing',
                'title' => 'Search subscriptions by customer, plan, status, or provider reference.',
            ],
            'billing.plan_summary' => [
                'tool' => BillingPlanSummaryTool::class,
                'module' => 'billing',
                'title' => 'List subscription plans with pricing, intervals, discounts, and feature summaries.',
            ],
            'billing.account_status' => [
                'tool' => BillingAccountStatusTool::class,
                'module' => 'billing',
                'title' => 'Inspect a billing customer account with masked payment details and subscription state.',
            ],
            'billing.metrics_summary' => [
                'tool' => BillingMetricsSummaryTool::class,
                'module' => 'billing',
                'title' => 'Summarize recurring billing metrics such as MRR, active subscriptions, and churn.',
            ],
            'billing.invoice_lookup' => [
                'tool' => BillingInvoiceLookupTool::class,
                'module' => 'billing',
                'title' => 'Search invoices by number, reference, customer, date range, and status.',
            ],
            'billing.invoice_detail' => [
                'tool' => BillingInvoiceDetailTool::class,
                'module' => 'billing',
                'title' => 'Inspect a single invoice with masked customer data, totals, line items, and reference context.',
            ],
            'billing.invoice_unpaid_summary' => [
                'tool' => BillingInvoiceUnpaidSummaryTool::class,
                'module' => 'billing',
                'title' => 'Summarize unpaid and overdue invoices with aging and outstanding balances.',
            ],
            'billing.invoice_customer_history' => [
                'tool' => BillingInvoiceCustomerHistoryTool::class,
                'module' => 'billing',
                'title' => 'Review a customer invoice history with masked contact details and outstanding balance.',
            ],
            'billing.payment_lookup' => [
                'tool' => BillingPaymentLookupTool::class,
                'module' => 'billing',
                'title' => 'Search payments by transaction ID, provider, status, related record, or date range.',
            ],
            'billing.payment_detail' => [
                'tool' => BillingPaymentDetailTool::class,
                'module' => 'billing',
                'title' => 'Inspect a single payment transaction without exposing raw provider payloads or secrets.',
            ],
            'billing.payment_provider_health' => [
                'tool' => BillingPaymentProviderHealthTool::class,
                'module' => 'billing',
                'title' => 'Summarize payment provider transaction health, success rate, and recent volume.',
            ],
            'billing.payment_webhook_health' => [
                'tool' => BillingPaymentWebhookHealthTool::class,
                'module' => 'billing',
                'title' => 'Review payment webhook processing health by provider and status without exposing raw payloads.',
            ],
            'shipping.method_lookup' => [
                'tool' => ShippingMethodLookupTool::class,
                'module' => 'shipping',
                'title' => 'Search configured shipping methods by provider, status, or default selection without exposing raw settings.',
            ],
            'shipping.zone_summary' => [
                'tool' => ShippingZoneSummaryTool::class,
                'module' => 'shipping',
                'title' => 'Summarize country-based shipping zones and pricing rules without exposing raw provider settings.',
            ],
            'tax.rule_lookup' => [
                'tool' => TaxRuleLookupTool::class,
                'module' => 'tax',
                'title' => 'Search modern tax rates and legacy tax rules by name, country, status, or identifier.',
            ],
            'tax.preview' => [
                'tool' => TaxPreviewTool::class,
                'module' => 'tax',
                'title' => 'Preview tax calculations for a subtotal and location using the current Microweber tax rules.',
            ],
            'newsletter.campaign_lookup' => [
                'tool' => NewsletterCampaignLookupTool::class,
                'module' => 'newsletter',
                'title' => 'Search newsletter campaigns by name, status, type, and engagement summary.',
            ],
            'newsletter.subscriber_lookup' => [
                'tool' => NewsletterSubscriberLookupTool::class,
                'module' => 'newsletter',
                'title' => 'Search newsletter subscribers with masked email output and list membership details.',
            ],
            'newsletter.template_lookup' => [
                'tool' => NewsletterTemplateLookupTool::class,
                'module' => 'newsletter',
                'title' => 'Search newsletter templates and review their campaign usage.',
            ],
            'newsletter.automation_status' => [
                'tool' => NewsletterAutomationStatusTool::class,
                'module' => 'newsletter',
                'title' => 'Review newsletter automation queue health and recent workflow execution status.',
            ],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listTools(McpRequestContext $context): array
    {
        $tools = [];

        foreach ($this->allDefinitions() as $mcpToolName => $definition) {
            if (! $context->client->allowsTool($mcpToolName) || ! $context->client->allowsModule($definition['module'])) {
                continue;
            }

            if ($this->requiresAdminScope($mcpToolName, $definition['module']) && ! $context->token->allowsScope((string) config('modules.ai.mcp.auth.admin_scope', 'mcp:admin'))) {
                continue;
            }

            $tool = app()->make($definition['tool']);

            // Each entry is gated through `definition.readOnlyHint`,
            // which defaults to `true` (the catalog is overwhelmingly
            // read-only). The write tools — content.create and
            // product.create, which persist rows via Content::create /
            // Product::create — explicitly set `readOnlyHint => false`
            // so MCP clients don't treat them as safe-by-default and
            // auto-invoke a content/product mutation without approval.
            // Any new write tool MUST set this to false; the
            // McpToolCatalogContractTest read/write split pins it.
            $readOnlyHint = (bool) ($definition['readOnlyHint'] ?? true);

            $tools[] = [
                'name' => $mcpToolName,
                'description' => $definition['title'] ?: $tool->getDescription(),
                'inputSchema' => $this->buildInputSchema($tool),
                'annotations' => [
                    'module' => $definition['module'],
                    'domain' => $tool->getDomain(),
                    'readOnlyHint' => $readOnlyHint,
                    // Documented output contract: every tool today
                    // returns one `content[0].text` item carrying
                    // HTML-stripped, whitespace-collapsed plain text
                    // (see McpServer::normalizeToolOutput). Declaring
                    // this in the annotations bag lets MCP 2025-06-18
                    // clients reason about the response shape without
                    // a per-tool outputSchema; once any tool starts
                    // emitting structured JSON, swap this for a real
                    // outputSchema on that tool.
                    'outputFormat' => 'text',
                ],
            ];
        }

        return $tools;
    }

    public function hasTool(string $name): bool
    {
        return array_key_exists($name, $this->allDefinitions());
    }

    public function callTool(string $name, array $arguments): string
    {
        $definition = $this->allDefinitions()[$name] ?? null;

        if ($definition === null) {
            throw new \InvalidArgumentException("MCP tool [{$name}] is not registered.");
        }

        /** @var ToolInterface $tool */
        $tool = app()->make($definition['tool']);

        return (string) $tool(...$arguments);
    }

    private function buildInputSchema(ToolInterface $tool): array
    {
        $properties = [];
        $required = [];

        foreach ($tool->getProperties() as $property) {
            $propertyData = $this->extractToolPropertyData($property);
            $propertyName = (string) ($propertyData['name'] ?? '');

            if ($propertyName === '') {
                continue;
            }

            $schema = [
                'type' => $propertyData['type'] ?? 'string',
                'description' => $propertyData['description'] ?? '',
            ];

            if (! empty($propertyData['enum'])) {
                $schema['enum'] = $propertyData['enum'];
            }

            // Promote optional JSON-Schema decorators when the
            // underlying property class declares them. Lets a
            // future tool emit richer schemas (URL format hints,
            // numeric ranges, default values) without changing the
            // catalog wire format.
            foreach (['format', 'pattern', 'minimum', 'maximum', 'default'] as $decorator) {
                if (array_key_exists($decorator, $propertyData) && $propertyData[$decorator] !== null) {
                    $schema[$decorator] = $propertyData[$decorator];
                }
            }

            $properties[$propertyName] = $schema;

            if (($propertyData['required'] ?? false) === true) {
                $required[] = $propertyName;
            }
        }

        $schema = [
            'type' => 'object',
            'properties' => $properties,
            'additionalProperties' => false,
        ];

        if ($required !== []) {
            $schema['required'] = $required;
        }

        return $schema;
    }

    /**
     * @return array{name?: string, type?: string, description?: string, required?: bool, enum?: array<int, mixed>, format?: string, pattern?: string, minimum?: int|float, maximum?: int|float, default?: mixed}
     */
    private function extractToolPropertyData(object $property): array
    {
        $reflection = new ReflectionClass($property);
        $data = [];

        foreach (['name', 'type', 'description', 'required', 'enum', 'format', 'pattern', 'minimum', 'maximum', 'default'] as $field) {
            if (! $reflection->hasProperty($field)) {
                continue;
            }

            $reflectionProperty = $reflection->getProperty($field);
            $reflectionProperty->setAccessible(true);
            if (! $reflectionProperty->isInitialized($property)) {
                // Untyped properties default to null on instantiation,
                // but a typed property without an explicit default is
                // uninitialised — accessing it throws. Skip silently
                // so partial declarations don't crash the catalog.
                continue;
            }
            $value = $reflectionProperty->getValue($property);

            if ($field === 'type' && $value instanceof \UnitEnum) {
                $value = $value->value;
            }

            $data[$field] = $value;
        }

        return $data;
    }

    private function requiresAdminScope(string $toolName, string $moduleName): bool
    {
        $adminOnlyTools = (array) config('modules.ai.mcp.auth.admin_only_tools', []);
        $adminOnlyModules = (array) config('modules.ai.mcp.auth.admin_only_modules', []);

        return in_array($toolName, $adminOnlyTools, true)
            || in_array($moduleName, $adminOnlyModules, true);
    }
}
