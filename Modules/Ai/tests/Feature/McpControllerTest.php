<?php

declare(strict_types=1);

namespace Modules\Ai\Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Modules\Ai\Models\McpClient;
use Modules\Ai\Models\McpClientToken;
use Modules\Ai\Providers\AiServiceProvider;
use Modules\Ai\Services\Mcp\GeneratedMcpClientToken;
use Modules\Ai\Services\Mcp\McpClientTokenManager;
use Modules\Ai\Services\Secrets\PassCommandRunner;
use Modules\Ai\Services\Secrets\PassSecretStore;
use Modules\Billing\Models\Subscription as BillingSubscription;
use Modules\Billing\Models\SubscriptionPlan;
use Modules\Billing\Models\SubscriptionPlanFeature;
use Modules\Billing\Models\SubscriptionPlanGroup;
use Modules\Billing\Models\SubscriptionCustomer;
use Modules\ContactForm\Models\Form as ContactForm;
use Modules\Content\Models\Content;
use Modules\Customer\Models\Customer;
use Modules\Form\Models\FormData;
use Modules\Form\Models\FormDataValue;
use Modules\Form\Models\FormList;
use Modules\Invoice\Models\Invoice as InvoiceRecord;
use Modules\Invoice\Models\InvoiceItem;
use Modules\Media\Models\Media;
use Modules\Media\Models\MediaFolder;
use Modules\Newsletter\Models\NewsletterAutomationQueue;
use Modules\Newsletter\Models\NewsletterCampaign;
use Modules\Newsletter\Models\NewsletterCampaignClickedLink;
use Modules\Newsletter\Models\NewsletterCampaignPixel;
use Modules\Newsletter\Models\NewsletterCampaignsSendLog;
use Modules\Newsletter\Models\NewsletterList;
use Modules\Newsletter\Models\NewsletterSubscriber;
use Modules\Newsletter\Models\NewsletterSubscriberList;
use Modules\Newsletter\Models\NewsletterTemplate;
use Modules\Newsletter\Models\Workflow;
use Modules\Newsletter\Models\WorkflowExecution;
use Modules\Payment\Models\Payment;
use Modules\Payment\Models\PaymentProvider;
use Modules\Shipping\Models\ShippingProvider;
use Modules\Order\Models\Order;
use Modules\Product\Models\Product;
use Modules\SiteStats\Models\Browsers;
use Modules\SiteStats\Models\Geoip;
use Modules\SiteStats\Models\Log as SiteStatsLog;
use Modules\SiteStats\Models\Referrers;
use Modules\SiteStats\Models\ReferrersDomains;
use Modules\SiteStats\Models\ReferrersPaths;
use Modules\SiteStats\Models\Sessions as SiteStatsSession;
use Modules\SiteStats\Models\StatsUrl;
use Modules\Billing\Models\WebhookLog;
use Modules\Tax\Models\TaxRate;
use Modules\Tax\Models\TaxType;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class McpControllerTest extends TestCase
{
    protected ?McpClient $fullAccessClient = null;
    protected ?McpClient $limitedToolClient = null;
    protected ?GeneratedMcpClientToken $fullAccessToken = null;
    protected ?GeneratedMcpClientToken $missingScopeToken = null;
    protected ?GeneratedMcpClientToken $missingAdminScopeToken = null;
    protected ?GeneratedMcpClientToken $limitedToolToken = null;
    protected ?McpClient $analyticsClient = null;
    protected ?GeneratedMcpClientToken $analyticsToken = null;
    protected ?McpClient $billingClient = null;
    protected ?GeneratedMcpClientToken $billingToken = null;
    protected ?GeneratedMcpClientToken $billingNoAdminToken = null;
    protected ?McpClient $invoiceClient = null;
    protected ?GeneratedMcpClientToken $invoiceToken = null;
    protected ?McpClient $mediaClient = null;
    protected ?GeneratedMcpClientToken $mediaToken = null;
    protected ?McpClient $paymentClient = null;
    protected ?GeneratedMcpClientToken $paymentToken = null;
    protected ?McpClient $shippingTaxClient = null;
    protected ?GeneratedMcpClientToken $shippingTaxToken = null;
    protected ?McpClient $formsClient = null;
    protected ?GeneratedMcpClientToken $formsToken = null;
    protected ?McpClient $newsletterClient = null;
    protected ?GeneratedMcpClientToken $newsletterToken = null;
    protected ?GeneratedMcpClientToken $revokedToken = null;

    protected function setUp(): void
    {
        parent::setUp();

        if (! Schema::hasTable('mcp_clients') || ! Schema::hasTable('mcp_client_tokens') || ! Schema::hasTable('mcp_client_token_events')) {
            DB::table('migrations')
                ->where('migration', '2026_04_13_184400_create_mcp_client_tables')
                ->delete();

            Artisan::call('migrate', [
                '--path' => base_path('Modules/Ai/database/migrations'),
                '--realpath' => true,
                '--force' => true,
            ]);
        }

        if (! Schema::hasTable('newsletter_campaigns') || ! Schema::hasTable('workflow_executions')) {
            Artisan::call('migrate', [
                '--path' => base_path('Modules/Newsletter/database/migrations'),
                '--realpath' => true,
                '--force' => true,
            ]);
        }

        if (! Schema::hasTable('subscriptions') || ! Schema::hasTable('subscription_plans') || ! Schema::hasTable('subscription_plans_groups')) {
            Artisan::call('migrate', [
                '--path' => base_path('Modules/Billing/database/migrations'),
                '--realpath' => true,
                '--force' => true,
            ]);
        }

        if (! Schema::hasTable('invoices') || ! Schema::hasTable('invoice_items')) {
            Artisan::call('migrate', [
                '--path' => base_path('Modules/Invoice/database/migrations'),
                '--realpath' => true,
                '--force' => true,
            ]);
        }

        if (! Schema::hasTable('payments') || ! Schema::hasTable('payment_providers')) {
            Artisan::call('migrate', [
                '--path' => base_path('Modules/Payment/database/migrations'),
                '--realpath' => true,
                '--force' => true,
            ]);
        }

        if (! Schema::hasTable('media') || ! Schema::hasTable('media_folders')) {
            Artisan::call('migrate', [
                '--path' => base_path('Modules/Media/database/migrations'),
                '--realpath' => true,
                '--force' => true,
            ]);
        }

        if (! Schema::hasTable('shipping_providers')) {
            Artisan::call('migrate', [
                '--path' => base_path('Modules/Shipping/database/migrations'),
                '--realpath' => true,
                '--force' => true,
            ]);
        }

        if (! Schema::hasTable('tax_types') || ! Schema::hasTable('tax_rates')) {
            Artisan::call('migrate', [
                '--path' => base_path('Modules/Tax/database/migrations'),
                '--realpath' => true,
                '--force' => true,
            ]);
        }

        if (! Schema::hasTable('forms_data') || ! Schema::hasTable('forms_data_values') || ! Schema::hasTable('forms_lists') || ! Schema::hasTable('forms')) {
            Artisan::call('migrate', [
                '--path' => base_path('Modules/Form/database/migrations'),
                '--realpath' => true,
                '--force' => true,
            ]);

            Artisan::call('migrate', [
                '--path' => base_path('Modules/ContactForm/database/migrations'),
                '--realpath' => true,
                '--force' => true,
            ]);
        }

        if (! Schema::hasTable('stats_sessions') || ! Schema::hasTable('stats_visits_log') || ! Schema::hasTable('stats_urls')) {
            DB::table('migrations')->whereIn('migration', [
                '2023_10_01_000002_create_stats_visits_log_table',
                '2023_10_01_000003_create_stats_browser_agents_table',
                '2023_10_01_000004_create_stats_referrers_table',
                '2023_10_01_000005_create_stats_referrers_domains_table',
                '2023_10_01_000006_create_stats_referrers_paths_table',
                '2023_10_01_000007_create_stats_urls_table',
                '2023_10_01_000008_create_stats_sessions_table',
                '2023_10_01_000009_create_stats_geoip_table',
            ])->delete();

            Artisan::call('migrate', [
                '--path' => base_path('Modules/SiteStats/database/migrations'),
                '--realpath' => true,
                '--force' => true,
            ]);
        }

        DB::table('mcp_client_token_events')->delete();
        DB::table('mcp_client_tokens')->delete();
        DB::table('mcp_clients')->delete();
        if (Schema::hasTable('webhook_logs')) {
            DB::table('webhook_logs')->delete();
        }
        if (Schema::hasTable('subscription_cancel_reasons')) {
            DB::table('subscription_cancel_reasons')->delete();
        }
        if (Schema::hasTable('subscription_plans_groups_features')) {
            DB::table('subscription_plans_groups_features')->delete();
        }
        if (Schema::hasTable('subscription_plans_features')) {
            DB::table('subscription_plans_features')->delete();
        }
        if (Schema::hasTable('subscriptions')) {
            DB::table('subscriptions')->delete();
        }
        if (Schema::hasTable('subscription_plans')) {
            DB::table('subscription_plans')->delete();
        }
        if (Schema::hasTable('subscription_plans_groups')) {
            DB::table('subscription_plans_groups')->delete();
        }
        if (Schema::hasTable('customers')) {
            DB::table('customers')->where('email', 'like', 'billing-mcp-%@example.com')->delete();
            DB::table('customers')->where('email', 'like', 'invoice-mcp-%@example.com')->delete();
        }
        if (Schema::hasTable('users')) {
            DB::table('users')->where('email', 'like', 'billing-mcp-%@example.com')->delete();
        }
        if (Schema::hasTable('payments')) {
            DB::table('payments')->delete();
        }
        if (Schema::hasTable('payment_providers')) {
            DB::table('payment_providers')->delete();
        }
        if (Schema::hasTable('media_thumbnails')) {
            DB::table('media_thumbnails')->delete();
        }
        if (Schema::hasTable('media')) {
            DB::table('media')->delete();
        }
        if (Schema::hasTable('media_folders')) {
            DB::table('media_folders')->delete();
        }
        if (Schema::hasTable('shipping_providers')) {
            DB::table('shipping_providers')->delete();
        }
        if (Schema::hasTable('tax_rates')) {
            DB::table('tax_rates')->delete();
        }
        if (Schema::hasTable('tax_types')) {
            DB::table('tax_types')->delete();
        }
        if (Schema::hasTable('invoice_items')) {
            DB::table('invoice_items')->delete();
        }
        if (Schema::hasTable('invoices')) {
            DB::table('invoices')->delete();
        }
        if (Schema::hasTable('forms_data_values')) {
            DB::table('forms_data_values')->delete();
        }
        if (Schema::hasTable('forms_data')) {
            DB::table('forms_data')->delete();
        }
        if (Schema::hasTable('forms_lists')) {
            DB::table('forms_lists')->delete();
        }
        if (Schema::hasTable('forms')) {
            DB::table('forms')->delete();
        }
        if (Schema::hasTable('stats_visits_log')) {
            DB::table('stats_visits_log')->delete();
        }
        if (Schema::hasTable('stats_sessions')) {
            DB::table('stats_sessions')->delete();
        }
        if (Schema::hasTable('stats_urls')) {
            DB::table('stats_urls')->delete();
        }
        if (Schema::hasTable('stats_referrers')) {
            DB::table('stats_referrers')->delete();
        }
        if (Schema::hasTable('stats_referrers_domains')) {
            DB::table('stats_referrers_domains')->delete();
        }
        if (Schema::hasTable('stats_referrers_paths')) {
            DB::table('stats_referrers_paths')->delete();
        }
        if (Schema::hasTable('stats_browser_agents')) {
            DB::table('stats_browser_agents')->delete();
        }
        if (Schema::hasTable('stats_geoip')) {
            DB::table('stats_geoip')->delete();
        }
        DB::table('newsletter_campaigns_send_log')->delete();
        DB::table('newsletter_campaigns_clicked_link')->delete();
        DB::table('newsletter_campaigns_pixel')->delete();
        DB::table('newsletter_automation_queue')->delete();
        if (Schema::hasTable('workflow_execution_steps')) {
            DB::table('workflow_execution_steps')->delete();
        }
        DB::table('workflow_executions')->delete();
        if (Schema::hasTable('workflow_nodes')) {
            DB::table('workflow_nodes')->delete();
        }
        DB::table('workflows')->delete();
        DB::table('newsletter_subscribers_lists')->delete();
        DB::table('newsletter_campaigns')->delete();
        DB::table('newsletter_templates')->delete();
        DB::table('newsletter_subscribers')->delete();
        DB::table('newsletter_lists')->delete();
        RateLimiter::clear('mcp-client-token:1');
        RateLimiter::clear('mcp-client-token:2');
        RateLimiter::clear('mcp-client-token:3');
        RateLimiter::clear('mcp-client-token:4');
        RateLimiter::clear('mcp-client-token:5');
        RateLimiter::clear('mcp-client-token:6');
        RateLimiter::clear('mcp-client-token:7');
        RateLimiter::clear('mcp-client-token:8');
        RateLimiter::clear('mcp-client-token:9');
        RateLimiter::clear('mcp-client-token:10');
        RateLimiter::clear('mcp-client-token:11');
        RateLimiter::clear('mcp-client-token:12');
        RateLimiter::clear('mcp-client-token:13');
        RateLimiter::clear('mcp-client-token:14');

        config([
            'modules.ai.enabled' => true,
            'modules.ai.mcp.enabled' => true,
            'modules.ai.mcp.server_name' => 'Microweber AI MCP',
            'modules.ai.mcp.server_version' => '0.1.0',
            'modules.ai.mcp.protocol_version' => '2025-03-26',
            'modules.ai.mcp.transport' => 'http-jsonrpc',
            'modules.ai.mcp.client_token_prefix' => 'mcp_',
            'modules.ai.mcp.auth.required_abilities' => ['mcp:access'],
            'modules.ai.mcp.auth.admin_scope' => 'mcp:admin',
            'modules.ai.mcp.auth.admin_only_tools' => [],
            'modules.ai.mcp.auth.admin_only_modules' => [],
        ]);

        Storage::fake('public');

        /** @var McpClientTokenManager $manager */
        $manager = app(McpClientTokenManager::class);

        $this->fullAccessClient = $manager->createClient([
            'name' => 'Full Access MCP Client',
            'allowed_scopes' => ['mcp:access', 'mcp:admin'],
            'allowed_tools' => ['*'],
            'allowed_modules' => ['*'],
            'rate_limit_per_minute' => 60,
            'is_active' => true,
        ]);

        $this->limitedToolClient = $manager->createClient([
            'name' => 'Limited Tool MCP Client',
            'allowed_scopes' => ['mcp:access', 'mcp:admin'],
            'allowed_tools' => ['content.lookup', 'order.lookup'],
            'allowed_modules' => ['content', 'order'],
            'rate_limit_per_minute' => 60,
            'is_active' => true,
        ]);
        $this->analyticsClient = $manager->createClient([
            'name' => 'Analytics MCP Client',
            'allowed_scopes' => ['mcp:access', 'mcp:admin'],
            'allowed_tools' => [
                'analytics.traffic_summary',
                'analytics.top_pages',
                'analytics.traffic_referrers',
                'analytics.audience_breakdown',
            ],
            'allowed_modules' => ['analytics'],
            'rate_limit_per_minute' => 60,
            'is_active' => true,
        ]);
        $this->billingClient = $manager->createClient([
            'name' => 'Billing MCP Client',
            'allowed_scopes' => ['mcp:access', 'mcp:admin'],
            'allowed_tools' => [
                'billing.subscription_lookup',
                'billing.plan_summary',
                'billing.account_status',
                'billing.metrics_summary',
            ],
            'allowed_modules' => ['billing'],
            'rate_limit_per_minute' => 60,
            'is_active' => true,
        ]);
        $this->invoiceClient = $manager->createClient([
            'name' => 'Invoice MCP Client',
            'allowed_scopes' => ['mcp:access', 'mcp:admin'],
            'allowed_tools' => [
                'billing.invoice_lookup',
                'billing.invoice_detail',
                'billing.invoice_unpaid_summary',
                'billing.invoice_customer_history',
            ],
            'allowed_modules' => ['billing'],
            'rate_limit_per_minute' => 60,
            'is_active' => true,
        ]);
        $this->mediaClient = $manager->createClient([
            'name' => 'Media MCP Client',
            'allowed_scopes' => ['mcp:access', 'mcp:admin'],
            'allowed_tools' => [
                'media.lookup',
                'media.asset_detail',
                'media.storage_health',
            ],
            'allowed_modules' => ['media'],
            'rate_limit_per_minute' => 60,
            'is_active' => true,
        ]);
        $this->paymentClient = $manager->createClient([
            'name' => 'Payment MCP Client',
            'allowed_scopes' => ['mcp:access', 'mcp:admin'],
            'allowed_tools' => [
                'billing.payment_lookup',
                'billing.payment_detail',
                'billing.payment_provider_health',
                'billing.payment_webhook_health',
            ],
            'allowed_modules' => ['billing'],
            'rate_limit_per_minute' => 60,
            'is_active' => true,
        ]);
        $this->shippingTaxClient = $manager->createClient([
            'name' => 'Shipping Tax MCP Client',
            'allowed_scopes' => ['mcp:access', 'mcp:admin'],
            'allowed_tools' => [
                'shipping.method_lookup',
                'shipping.zone_summary',
                'tax.rule_lookup',
                'tax.preview',
            ],
            'allowed_modules' => ['shipping', 'tax'],
            'rate_limit_per_minute' => 60,
            'is_active' => true,
        ]);
        $this->formsClient = $manager->createClient([
            'name' => 'Forms MCP Client',
            'allowed_scopes' => ['mcp:access', 'mcp:admin'],
            'allowed_tools' => [
                'forms.form_lookup',
                'forms.submission_search',
                'forms.submission_detail',
                'forms.activity_summary',
            ],
            'allowed_modules' => ['forms'],
            'rate_limit_per_minute' => 60,
            'is_active' => true,
        ]);
        $this->newsletterClient = $manager->createClient([
            'name' => 'Newsletter MCP Client',
            'allowed_scopes' => ['mcp:access', 'mcp:admin'],
            'allowed_tools' => [
                'newsletter.campaign_lookup',
                'newsletter.subscriber_lookup',
                'newsletter.template_lookup',
                'newsletter.automation_status',
            ],
            'allowed_modules' => ['newsletter'],
            'rate_limit_per_minute' => 60,
            'is_active' => true,
        ]);

        $this->fullAccessToken = $manager->issueToken($this->fullAccessClient, 'full-access', ['mcp:access', 'mcp:admin']);
        $this->missingScopeToken = $manager->issueToken($this->fullAccessClient, 'missing-scope', ['content:read']);
        $this->missingAdminScopeToken = $manager->issueToken($this->limitedToolClient, 'missing-admin', ['mcp:access']);
        $this->limitedToolToken = $manager->issueToken($this->limitedToolClient, 'limited-tool', ['mcp:access', 'mcp:admin']);
        $this->analyticsToken = $manager->issueToken($this->analyticsClient, 'analytics-only', ['mcp:access', 'mcp:admin']);
        $this->billingToken = $manager->issueToken($this->billingClient, 'billing-only', ['mcp:access', 'mcp:admin']);
        $this->billingNoAdminToken = $manager->issueToken($this->billingClient, 'billing-no-admin', ['mcp:access']);
        $this->invoiceToken = $manager->issueToken($this->invoiceClient, 'invoice-only', ['mcp:access', 'mcp:admin']);
        $this->mediaToken = $manager->issueToken($this->mediaClient, 'media-only', ['mcp:access', 'mcp:admin']);
        $this->paymentToken = $manager->issueToken($this->paymentClient, 'payment-only', ['mcp:access', 'mcp:admin']);
        $this->shippingTaxToken = $manager->issueToken($this->shippingTaxClient, 'shipping-tax-only', ['mcp:access', 'mcp:admin']);
        $this->formsToken = $manager->issueToken($this->formsClient, 'forms-only', ['mcp:access', 'mcp:admin']);
        $this->newsletterToken = $manager->issueToken($this->newsletterClient, 'newsletter-only', ['mcp:access', 'mcp:admin']);
        $this->revokedToken = $manager->issueToken($this->fullAccessClient, 'revoked', ['mcp:access', 'mcp:admin']);
        $manager->revokeToken($this->revokedToken->token, null, 'Revoked in test setup');
    }

    #[Test]
    public function client_token_can_initialize_the_mcp_endpoint_and_updates_usage_tracking(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->fullAccessToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 1,
                'method' => 'initialize',
                'params' => [
                    'clientInfo' => [
                        'name' => 'test-client',
                        'version' => '1.0.0',
                    ],
                ],
            ]);

        $response->assertOk()
            ->assertJson([
                'jsonrpc' => '2.0',
                'id' => 1,
                'result' => [
                    'protocolVersion' => '2025-03-26',
                    'serverInfo' => [
                        'name' => 'Microweber AI MCP',
                        'version' => '0.1.0',
                    ],
                    'transport' => 'http-jsonrpc',
                ],
            ]);

        $this->fullAccessToken->token->refresh();
        $this->fullAccessClient->refresh();

        $this->assertNotNull($this->fullAccessToken->token->last_used_at);
        $this->assertNotNull($this->fullAccessClient->last_used_at);
        $this->assertDatabaseHas('mcp_client_token_events', [
            'mcp_client_id' => $this->fullAccessClient->id,
            'mcp_client_token_id' => $this->fullAccessToken->token->id,
            'action' => 'token.used',
        ]);
        $this->assertFalse((bool) data_get($response->json(), 'result.capabilities.tools.listChanged'));
    }

    #[Test]
    public function client_token_can_request_the_initial_tools_list(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->fullAccessToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 'tools-1',
                'method' => 'tools/list',
            ]);

        $response->assertOk()
            ->assertJson([
                'jsonrpc' => '2.0',
                'id' => 'tools-1',
            ]);

        $tools = collect($response->json('result.tools'));

        $this->assertSame([
            'content.lookup',
            'content.get',
            'product.lookup',
            'order.lookup',
            'settings.read',
            'media.lookup',
            'media.asset_detail',
            'media.storage_health',
            'analytics.traffic_summary',
            'analytics.top_pages',
            'analytics.traffic_referrers',
            'analytics.audience_breakdown',
            'forms.form_lookup',
            'forms.submission_search',
            'forms.submission_detail',
            'forms.activity_summary',
            'billing.subscription_lookup',
            'billing.plan_summary',
            'billing.account_status',
            'billing.metrics_summary',
            'billing.invoice_lookup',
            'billing.invoice_detail',
            'billing.invoice_unpaid_summary',
            'billing.invoice_customer_history',
            'billing.payment_lookup',
            'billing.payment_detail',
            'billing.payment_provider_health',
            'billing.payment_webhook_health',
            'shipping.method_lookup',
            'shipping.zone_summary',
            'tax.rule_lookup',
            'tax.preview',
            'newsletter.campaign_lookup',
            'newsletter.subscriber_lookup',
            'newsletter.template_lookup',
            'newsletter.automation_status',
        ], $tools->pluck('name')->all());
        $this->assertSame('object', data_get($tools->firstWhere('name', 'content.lookup'), 'inputSchema.type'));
        $this->assertTrue((bool) data_get($tools->firstWhere('name', 'settings.read'), 'annotations.readOnlyHint'));
    }

    #[Test]
    public function tools_list_only_returns_tools_allowed_for_the_client(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->limitedToolToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 91,
                'method' => 'tools/list',
            ]);

        $response->assertOk();

        $this->assertSame(
            ['content.lookup', 'order.lookup'],
            collect($response->json('result.tools'))->pluck('name')->all()
        );
    }

    #[Test]
    public function media_client_only_receives_media_tools(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->mediaToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 'media-tools',
                'method' => 'tools/list',
            ]);

        $response->assertOk();

        $this->assertSame([
            'media.lookup',
            'media.asset_detail',
            'media.storage_health',
        ], collect($response->json('result.tools'))->pluck('name')->all());
    }

    #[Test]
    public function analytics_client_only_receives_analytics_tools(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->analyticsToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 'analytics-tools',
                'method' => 'tools/list',
            ]);

        $response->assertOk();

        $this->assertSame([
            'analytics.traffic_summary',
            'analytics.top_pages',
            'analytics.traffic_referrers',
            'analytics.audience_breakdown',
        ], collect($response->json('result.tools'))->pluck('name')->all());
    }

    #[Test]
    public function billing_client_only_receives_billing_tools(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->billingToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 'billing-tools',
                'method' => 'tools/list',
            ]);

        $response->assertOk();

        $this->assertSame([
            'billing.subscription_lookup',
            'billing.plan_summary',
            'billing.account_status',
            'billing.metrics_summary',
        ], collect($response->json('result.tools'))->pluck('name')->all());
    }

    #[Test]
    public function invoice_client_only_receives_invoice_tools(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->invoiceToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 'invoice-tools',
                'method' => 'tools/list',
            ]);

        $response->assertOk();

        $this->assertSame([
            'billing.invoice_lookup',
            'billing.invoice_detail',
            'billing.invoice_unpaid_summary',
            'billing.invoice_customer_history',
        ], collect($response->json('result.tools'))->pluck('name')->all());
    }

    #[Test]
    public function payment_client_only_receives_payment_tools(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->paymentToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 'payment-tools',
                'method' => 'tools/list',
            ]);

        $response->assertOk();

        $this->assertSame([
            'billing.payment_lookup',
            'billing.payment_detail',
            'billing.payment_provider_health',
            'billing.payment_webhook_health',
        ], collect($response->json('result.tools'))->pluck('name')->all());
    }

    #[Test]
    public function shipping_tax_client_only_receives_shipping_and_tax_tools(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->shippingTaxToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 'shipping-tax-tools',
                'method' => 'tools/list',
            ]);

        $response->assertOk();

        $this->assertSame([
            'shipping.method_lookup',
            'shipping.zone_summary',
            'tax.rule_lookup',
            'tax.preview',
        ], collect($response->json('result.tools'))->pluck('name')->all());
    }

    #[Test]
    public function forms_client_only_receives_forms_tools(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->formsToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 'forms-tools',
                'method' => 'tools/list',
            ]);

        $response->assertOk();

        $this->assertSame([
            'forms.form_lookup',
            'forms.submission_search',
            'forms.submission_detail',
            'forms.activity_summary',
        ], collect($response->json('result.tools'))->pluck('name')->all());
    }

    #[Test]
    public function newsletter_client_only_receives_newsletter_tools(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->newsletterToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 'newsletter-tools',
                'method' => 'tools/list',
            ]);

        $response->assertOk();

        $this->assertSame([
            'newsletter.campaign_lookup',
            'newsletter.subscriber_lookup',
            'newsletter.template_lookup',
            'newsletter.automation_status',
        ], collect($response->json('result.tools'))->pluck('name')->all());
    }

    #[Test]
    public function mcp_endpoint_requires_authentication(): void
    {
        $response = $this->postJson(route('api.ai.mcp'), [
            'jsonrpc' => '2.0',
            'id' => 7,
            'method' => 'initialize',
        ]);

        $response->assertUnauthorized();
    }

    #[Test]
    public function mcp_endpoint_rejects_invalid_bearer_tokens(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer invalid-token')
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 8,
                'method' => 'initialize',
            ]);

        $response->assertUnauthorized();
    }

    #[Test]
    public function mcp_endpoint_rejects_tokens_without_the_required_scope(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->missingScopeToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 9,
                'method' => 'initialize',
            ]);

        $response->assertForbidden();
        $this->assertDatabaseHas('mcp_client_token_events', [
            'mcp_client_id' => $this->fullAccessClient->id,
            'mcp_client_token_id' => $this->missingScopeToken->token->id,
            'action' => 'token.denied',
        ]);
    }

    #[Test]
    public function mcp_endpoint_rejects_revoked_tokens(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->revokedToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 10,
                'method' => 'initialize',
            ]);

        $response->assertUnauthorized();
    }

    #[Test]
    public function mcp_endpoint_rejects_tools_outside_the_clients_allowed_list(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->limitedToolToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 11,
                'method' => 'tools/call',
                'params' => [
                    'name' => 'settings.read',
                ],
            ]);

        $response->assertForbidden();
    }

    #[Test]
    public function mcp_endpoint_enforces_admin_only_tool_scope_when_configured(): void
    {
        config([
            'modules.ai.mcp.auth.admin_only_tools' => ['order.lookup'],
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->missingAdminScopeToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 12,
                'method' => 'tools/call',
                'params' => [
                    'name' => 'order.lookup',
                ],
            ]);

        $response->assertForbidden();
        $this->assertDatabaseHas('mcp_client_token_events', [
            'mcp_client_id' => $this->limitedToolClient->id,
            'mcp_client_token_id' => $this->missingAdminScopeToken->token->id,
            'action' => 'token.denied',
        ]);
    }

    #[Test]
    public function mcp_endpoint_applies_per_client_rate_limits(): void
    {
        /** @var McpClientTokenManager $manager */
        $manager = app(McpClientTokenManager::class);

        $rateLimitedClient = $manager->createClient([
            'name' => 'Rate Limited Client',
            'allowed_scopes' => ['mcp:access'],
            'allowed_tools' => ['*'],
            'allowed_modules' => ['*'],
            'rate_limit_per_minute' => 1,
            'is_active' => true,
        ]);
        $rateLimitedToken = $manager->issueToken($rateLimitedClient, 'rate-limited', ['mcp:access']);

        $headers = ['Authorization' => 'Bearer ' . $rateLimitedToken->plainTextToken];
        $payload = [
            'jsonrpc' => '2.0',
            'id' => 13,
            'method' => 'initialize',
        ];

        $this->withHeaders($headers)->postJson(route('api.ai.mcp'), $payload)->assertOk();
        $this->withHeaders($headers)->postJson(route('api.ai.mcp'), $payload)->assertStatus(429);

        $this->assertDatabaseHas('mcp_client_token_events', [
            'mcp_client_id' => $rateLimitedClient->id,
            'mcp_client_token_id' => $rateLimitedToken->token->id,
            'action' => 'token.rate_limited',
        ]);
    }

    #[Test]
    public function content_lookup_returns_plain_text_results_for_matching_content(): void
    {
        Content::factory()->create([
            'title' => 'MCP Knowledge Base',
            'description' => 'Reference page for MCP integrations.',
            'content_type' => 'page',
            'is_active' => 1,
            'is_deleted' => 0,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->limitedToolToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 14,
                'method' => 'tools/call',
                'params' => [
                    'name' => 'content.lookup',
                    'arguments' => [
                        'search_term' => 'MCP Knowledge',
                        'content_type' => 'page',
                    ],
                ],
            ]);

        $response->assertOk()
            ->assertJsonPath('result.isError', false);

        $this->assertStringContainsString('MCP Knowledge Base', data_get($response->json(), 'result.content.0.text', ''));
    }

    #[Test]
    public function product_lookup_returns_plain_text_results_for_matching_products(): void
    {
        Product::factory()->create([
            'title' => 'MCP Commerce Product',
            'description' => 'A product exposed through the MCP catalog.',
            'is_active' => 1,
            'is_deleted' => 0,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->fullAccessToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 15,
                'method' => 'tools/call',
                'params' => [
                    'name' => 'product.lookup',
                    'arguments' => [
                        'search_term' => 'Commerce Product',
                    ],
                ],
            ]);

        $response->assertOk()
            ->assertJsonPath('result.isError', false);

        $this->assertStringContainsString('MCP Commerce Product', data_get($response->json(), 'result.content.0.text', ''));
    }

    #[Test]
    public function order_lookup_returns_plain_text_results_for_matching_orders(): void
    {
        Order::factory()->create([
            'order_reference_id' => 'MCP-ORDER-1001',
            'first_name' => 'Ada',
            'last_name' => 'Lovelace',
            'email' => 'ada@example.com',
            'order_status' => 'processing',
            'amount' => 99.95,
            'currency' => 'USD',
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->limitedToolToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 16,
                'method' => 'tools/call',
                'params' => [
                    'name' => 'order.lookup',
                    'arguments' => [
                        'search_term' => 'MCP-ORDER-1001',
                    ],
                ],
            ]);

        $response->assertOk()
            ->assertJsonPath('result.isError', false);

        $text = data_get($response->json(), 'result.content.0.text', '');
        $this->assertStringContainsString('MCP-ORDER-1001', $text);
        $this->assertStringContainsString('Ada Lovelace', $text);
    }

    #[Test]
    public function settings_read_returns_plain_text_for_non_sensitive_options(): void
    {
        save_option([
            'option_key' => 'site_name',
            'option_value' => 'Microweber MCP Demo',
            'option_group' => 'website',
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->fullAccessToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 17,
                'method' => 'tools/call',
                'params' => [
                    'name' => 'settings.read',
                    'arguments' => [
                        'option_group' => 'website',
                        'option_key' => 'site_name',
                    ],
                ],
            ]);

        $response->assertOk()
            ->assertJsonPath('result.isError', false);

        $text = data_get($response->json(), 'result.content.0.text', '');
        $this->assertStringContainsString('site_name', $text);
        $this->assertStringContainsString('Microweber MCP Demo', $text);
    }

    #[Test]
    public function settings_read_marks_sensitive_options_as_tool_errors(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->fullAccessToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 18,
                'method' => 'tools/call',
                'params' => [
                    'name' => 'settings.read',
                    'arguments' => [
                        'option_group' => 'ai',
                        'option_key' => 'openai_api_key',
                    ],
                ],
            ]);

        $response->assertOk()
            ->assertJsonPath('result.isError', true);

        $this->assertStringContainsString('sensitive', data_get($response->json(), 'result.content.0.text', ''));
    }

    #[Test]
    public function analytics_traffic_summary_returns_aggregated_metrics(): void
    {
        $this->seedAnalyticsFixtures();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->analyticsToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 19,
                'method' => 'tools/call',
                'params' => [
                    'name' => 'analytics.traffic_summary',
                    'arguments' => [
                        'period' => 'daily',
                    ],
                ],
            ]);

        $response->assertOk()
            ->assertJsonPath('result.isError', false);

        $text = data_get($response->json(), 'result.content.0.text', '');
        $this->assertStringContainsString('Visitors', $text);
        $this->assertStringContainsString('Landing Page', $text);
        $this->assertStringContainsString('google.com', $text);
    }

    #[Test]
    public function analytics_top_pages_returns_ranked_pages(): void
    {
        $this->seedAnalyticsFixtures();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->analyticsToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 20,
                'method' => 'tools/call',
                'params' => [
                    'name' => 'analytics.top_pages',
                    'arguments' => [
                        'period' => 'daily',
                        'limit' => 5,
                    ],
                ],
            ]);

        $response->assertOk()
            ->assertJsonPath('result.isError', false);

        $text = data_get($response->json(), 'result.content.0.text', '');
        $this->assertStringContainsString('Landing Page', $text);
        $this->assertStringContainsString('/landing-page', $text);
        $this->assertStringContainsString('Sessions', $text);
    }

    #[Test]
    public function analytics_traffic_referrers_returns_domain_and_path_summaries(): void
    {
        $this->seedAnalyticsFixtures();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->analyticsToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 25,
                'method' => 'tools/call',
                'params' => [
                    'name' => 'analytics.traffic_referrers',
                    'arguments' => [
                        'period' => 'daily',
                        'limit' => 5,
                    ],
                ],
            ]);

        $response->assertOk()
            ->assertJsonPath('result.isError', false);

        $text = data_get($response->json(), 'result.content.0.text', '');
        $this->assertStringContainsString('google.com', $text);
        $this->assertStringContainsString('/search', $text);
        $this->assertStringContainsString('External', $text);
    }

    #[Test]
    public function analytics_audience_breakdown_returns_country_and_device_data(): void
    {
        $this->seedAnalyticsFixtures();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->analyticsToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 26,
                'method' => 'tools/call',
                'params' => [
                    'name' => 'analytics.audience_breakdown',
                    'arguments' => [
                        'period' => 'daily',
                        'breakdown' => 'both',
                    ],
                ],
            ]);

        $response->assertOk()
            ->assertJsonPath('result.isError', false);

        $text = data_get($response->json(), 'result.content.0.text', '');
        $this->assertStringContainsString('United States', $text);
        $this->assertStringContainsString('Desktop', $text);
        $this->assertStringContainsString('Mobile', $text);
    }

    #[Test]
    public function billing_metrics_tool_is_hidden_without_admin_scope_when_configured(): void
    {
        config([
            'modules.ai.mcp.auth.admin_only_tools' => ['billing.metrics_summary'],
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->billingNoAdminToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 'billing-tools-no-admin',
                'method' => 'tools/list',
            ]);

        $response->assertOk();

        $this->assertSame([
            'billing.subscription_lookup',
            'billing.plan_summary',
            'billing.account_status',
        ], collect($response->json('result.tools'))->pluck('name')->all());
    }

    #[Test]
    public function media_lookup_returns_asset_rows(): void
    {
        $this->seedMediaFixtures();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->mediaToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 30,
                'method' => 'tools/call',
                'params' => [
                    'name' => 'media.lookup',
                    'arguments' => [
                        'search_term' => 'sunset',
                        'file_type' => 'image',
                    ],
                ],
            ]);

        $response->assertOk()
            ->assertJsonPath('result.isError', false);

        $text = data_get($response->json(), 'result.content.0.text', '');
        $this->assertStringContainsString('Homepage Sunset Banner', $text);
        $this->assertStringContainsString('uploads/mcp-media/gallery/sunset-banner.jpg', $text);
        $this->assertStringContainsString('Image', $text);
        $this->assertStringNotContainsString('https://cdn.example.com/assets/sunset-banner.jpg', $text);
    }

    #[Test]
    public function media_asset_detail_returns_safe_metadata_summary(): void
    {
        $fixtures = $this->seedMediaFixtures();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->mediaToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 31,
                'method' => 'tools/call',
                'params' => [
                    'name' => 'media.asset_detail',
                    'arguments' => [
                        'media_id' => $fixtures['primaryImage']->id,
                        'include_metadata' => 'yes',
                    ],
                ],
            ]);

        $response->assertOk()
            ->assertJsonPath('result.isError', false);

        $text = data_get($response->json(), 'result.content.0.text', '');
        $this->assertStringContainsString('Media asset detail', $text);
        $this->assertStringContainsString('Marketing / Homepage Hero', $text);
        $this->assertStringContainsString('width, height, alt', $text);
        $this->assertStringContainsString('Stored', $text);
        $this->assertStringNotContainsString('https://cdn.example.com/assets/sunset-banner.jpg', $text);
    }

    #[Test]
    public function media_storage_health_returns_aggregate_stats(): void
    {
        $this->seedMediaFixtures();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->mediaToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 32,
                'method' => 'tools/call',
                'params' => [
                    'name' => 'media.storage_health',
                    'arguments' => [
                        'path' => 'uploads/mcp-media',
                        'include_webp_cache' => 'no',
                        'limit' => 5,
                    ],
                ],
            ]);

        $response->assertOk()
            ->assertJsonPath('result.isError', false);

        $text = data_get($response->json(), 'result.content.0.text', '');
        $this->assertStringContainsString('Media storage health', $text);
        $this->assertStringContainsString('uploads/mcp-media', $text);
        $this->assertStringContainsString('Top media folders', $text);
        $this->assertStringContainsString('Top public disk directories', $text);
        $this->assertStringContainsString('Image: 1', $text);
    }

    #[Test]
    public function billing_subscription_lookup_returns_subscription_rows(): void
    {
        $this->seedBillingFixtures();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->billingToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 31,
                'method' => 'tools/call',
                'params' => [
                    'name' => 'billing.subscription_lookup',
                    'arguments' => [
                        'search_term' => 'billing-mcp-primary@example.com',
                    ],
                ],
            ]);

        $response->assertOk()
            ->assertJsonPath('result.isError', false);

        $text = data_get($response->json(), 'result.content.0.text', '');
        $this->assertStringContainsString('Business Monthly', $text);
        $this->assertStringContainsString('bi*****************@example.com', $text);
        $this->assertStringContainsString('active', $text);
    }

    #[Test]
    public function billing_plan_summary_returns_pricing_and_features(): void
    {
        $this->seedBillingFixtures();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->billingToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 32,
                'method' => 'tools/call',
                'params' => [
                    'name' => 'billing.plan_summary',
                    'arguments' => [
                        'group_sku' => 'HOSTING',
                    ],
                ],
            ]);

        $response->assertOk()
            ->assertJsonPath('result.isError', false);

        $text = data_get($response->json(), 'result.content.0.text', '');
        $this->assertStringContainsString('Business Monthly', $text);
        $this->assertStringContainsString('storage', $text);
        $this->assertStringContainsString('29.99 USD', $text);
    }

    #[Test]
    public function billing_account_status_masks_payment_details(): void
    {
        $fixtures = $this->seedBillingFixtures();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->billingToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 33,
                'method' => 'tools/call',
                'params' => [
                    'name' => 'billing.account_status',
                    'arguments' => [
                        'customer_id' => $fixtures['primaryCustomer']->id,
                    ],
                ],
            ]);

        $response->assertOk()
            ->assertJsonPath('result.isError', false);

        $text = data_get($response->json(), 'result.content.0.text', '');
        $this->assertStringContainsString('****4242', $text);
        $this->assertStringContainsString('bi*****************@example.com', $text);
        $this->assertStringNotContainsString('4242 4242', $text);
    }

    #[Test]
    public function billing_metrics_summary_reports_mrr_and_churn(): void
    {
        $this->seedBillingFixtures();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->billingToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 34,
                'method' => 'tools/call',
                'params' => [
                    'name' => 'billing.metrics_summary',
                    'arguments' => [
                        'period_days' => 30,
                        'include_breakdown' => 'yes',
                    ],
                ],
            ]);

        $response->assertOk()
            ->assertJsonPath('result.isError', false);

        $text = data_get($response->json(), 'result.content.0.text', '');
        $this->assertStringContainsString('39.99 USD', $text);
        $this->assertStringContainsString('Status breakdown', $text);
        $this->assertStringContainsString('canceled', $text);
    }

    #[Test]
    public function invoice_lookup_returns_invoice_rows(): void
    {
        $this->seedInvoiceFixtures();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->invoiceToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 35,
                'method' => 'tools/call',
                'params' => [
                    'name' => 'billing.invoice_lookup',
                    'arguments' => [
                        'search_term' => 'INV-MCP',
                        'paid_status' => InvoiceRecord::STATUS_UNPAID,
                    ],
                ],
            ]);

        $response->assertOk()
            ->assertJsonPath('result.isError', false);

        $text = data_get($response->json(), 'result.content.0.text', '');
        $this->assertStringContainsString('INV-MCP-001', $text);
        $this->assertStringContainsString('in*****************@example.com', $text);
        $this->assertStringContainsString('1,250.00 USD', $text);
    }

    #[Test]
    public function invoice_detail_returns_items_and_masked_customer_data(): void
    {
        $fixtures = $this->seedInvoiceFixtures();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->invoiceToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 36,
                'method' => 'tools/call',
                'params' => [
                    'name' => 'billing.invoice_detail',
                    'arguments' => [
                        'invoice_id' => $fixtures['primaryInvoice']->id,
                    ],
                ],
            ]);

        $response->assertOk()
            ->assertJsonPath('result.isError', false);

        $text = data_get($response->json(), 'result.content.0.text', '');
        $this->assertStringContainsString('INV-MCP-001', $text);
        $this->assertStringContainsString('in*****************@example.com', $text);
        $this->assertStringContainsString('Premium support retainer', $text);
        $this->assertStringContainsString('ORDER-2001', $text);
    }

    #[Test]
    public function invoice_unpaid_summary_reports_overdue_balances(): void
    {
        $this->seedInvoiceFixtures();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->invoiceToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 37,
                'method' => 'tools/call',
                'params' => [
                    'name' => 'billing.invoice_unpaid_summary',
                    'arguments' => [
                        'overdue_only' => 'yes',
                        'sort_by' => 'days_overdue',
                    ],
                ],
            ]);

        $response->assertOk()
            ->assertJsonPath('result.isError', false);

        $text = data_get($response->json(), 'result.content.0.text', '');
        $this->assertStringContainsString('Outstanding balance', $text);
        $this->assertStringContainsString('INV-MCP-002', $text);
        $this->assertStringContainsString('Days overdue', $text);
    }

    #[Test]
    public function invoice_customer_history_summarizes_customer_balance(): void
    {
        $fixtures = $this->seedInvoiceFixtures();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->invoiceToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 38,
                'method' => 'tools/call',
                'params' => [
                    'name' => 'billing.invoice_customer_history',
                    'arguments' => [
                        'customer_id' => $fixtures['primaryCustomer']->id,
                        'months_back' => 24,
                    ],
                ],
            ]);

        $response->assertOk()
            ->assertJsonPath('result.isError', false);

        $text = data_get($response->json(), 'result.content.0.text', '');
        $this->assertStringContainsString('Lifetime total', $text);
        $this->assertStringContainsString('INV-MCP-001', $text);
        $this->assertStringContainsString('INV-MCP-002', $text);
        $this->assertStringContainsString('in*****************@example.com', $text);
    }

    #[Test]
    public function payment_lookup_returns_transaction_rows(): void
    {
        $this->seedPaymentFixtures();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->paymentToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 39,
                'method' => 'tools/call',
                'params' => [
                    'name' => 'billing.payment_lookup',
                    'arguments' => [
                        'search_term' => 'ch_payment_primary',
                        'provider' => 'stripe',
                    ],
                ],
            ]);

        $response->assertOk()
            ->assertJsonPath('result.isError', false);

        $text = data_get($response->json(), 'result.content.0.text', '');
        $this->assertStringContainsString('stripe', $text);
        $this->assertStringContainsString('Payment #', $text);
        $this->assertStringContainsString('ch_payme...mary', $text);
        $this->assertStringContainsString('Completed', $text);
    }

    #[Test]
    public function payment_detail_hides_raw_payloads_and_secrets(): void
    {
        $fixtures = $this->seedPaymentFixtures();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->paymentToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 40,
                'method' => 'tools/call',
                'params' => [
                    'name' => 'billing.payment_detail',
                    'arguments' => [
                        'payment_id' => $fixtures['primaryPayment']->id,
                    ],
                ],
            ]);

        $response->assertOk()
            ->assertJsonPath('result.isError', false);

        $text = data_get($response->json(), 'result.content.0.text', '');
        $this->assertStringContainsString('Hidden for safety', $text);
        $this->assertStringContainsString('Stripe Gateway', $text);
        $this->assertStringNotContainsString('sk_live_secret', $text);
        $this->assertStringNotContainsString('4111111111111111', $text);
    }

    #[Test]
    public function payment_provider_health_reports_success_rates(): void
    {
        $this->seedPaymentFixtures();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->paymentToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 41,
                'method' => 'tools/call',
                'params' => [
                    'name' => 'billing.payment_provider_health',
                    'arguments' => [
                        'provider' => 'stripe',
                        'include_breakdown' => 'yes',
                    ],
                ],
            ]);

        $response->assertOk()
            ->assertJsonPath('result.isError', false);

        $text = data_get($response->json(), 'result.content.0.text', '');
        $this->assertStringContainsString('Stripe Gateway', $text);
        $this->assertStringContainsString('Success rate', $text);
        $this->assertStringContainsString('Completed volume', $text);
        $this->assertStringContainsString('Failed', $text);
    }

    #[Test]
    public function payment_webhook_health_sanitizes_failure_messages(): void
    {
        $this->seedPaymentFixtures();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->paymentToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 42,
                'method' => 'tools/call',
                'params' => [
                    'name' => 'billing.payment_webhook_health',
                    'arguments' => [
                        'provider' => 'stripe',
                        'limit' => 5,
                    ],
                ],
            ]);

        $response->assertOk()
            ->assertJsonPath('result.isError', false);

        $text = data_get($response->json(), 'result.content.0.text', '');
        $this->assertStringContainsString('checkout.session.completed', $text);
        $this->assertStringContainsString('api_key=[redacted]', $text);
        $this->assertStringNotContainsString('sk_live_secret', $text);
        $this->assertStringNotContainsString('4111111111111111', $text);
    }

    #[Test]
    public function shipping_method_lookup_returns_safe_provider_summaries(): void
    {
        $this->seedShippingTaxFixtures();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->shippingTaxToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 43,
                'method' => 'tools/call',
                'params' => [
                    'name' => 'shipping.method_lookup',
                    'arguments' => [
                        'provider' => 'shipping_to_country',
                    ],
                ],
            ]);

        $response->assertOk()
            ->assertJsonPath('result.isError', false);

        $text = data_get($response->json(), 'result.content.0.text', '');
        $this->assertStringContainsString('Country Shipping', $text);
        $this->assertStringContainsString('country zone', strtolower($text));
        $this->assertStringNotContainsString('Please select your shipping country', $text);
    }

    #[Test]
    public function shipping_zone_summary_reports_country_cost_rules(): void
    {
        $this->seedShippingTaxFixtures();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->shippingTaxToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 44,
                'method' => 'tools/call',
                'params' => [
                    'name' => 'shipping.zone_summary',
                    'arguments' => [
                        'country' => 'United States',
                    ],
                ],
            ]);

        $response->assertOk()
            ->assertJsonPath('result.isError', false);

        $text = data_get($response->json(), 'result.content.0.text', '');
        $this->assertStringContainsString('United States', $text);
        $this->assertStringContainsString('Per item', $text);
        $this->assertStringContainsString('Base 7.50 USD', $text);
    }

    #[Test]
    public function tax_rule_lookup_includes_modern_and_legacy_rules(): void
    {
        $this->seedShippingTaxFixtures();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->shippingTaxToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 45,
                'method' => 'tools/call',
                'params' => [
                    'name' => 'tax.rule_lookup',
                    'arguments' => [
                        'country_code' => 'US',
                        'include_legacy' => 'yes',
                    ],
                ],
            ]);

        $response->assertOk()
            ->assertJsonPath('result.isError', false);

        $text = data_get($response->json(), 'result.content.0.text', '');
        $this->assertStringContainsString('California Sales Tax', $text);
        $this->assertStringContainsString('Legacy VAT', $text);
        $this->assertStringContainsString('Location rule', $text);
        $this->assertStringContainsString('Legacy fallback', $text);
    }

    #[Test]
    public function tax_preview_returns_breakdown_for_matching_location(): void
    {
        $this->seedShippingTaxFixtures();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->shippingTaxToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 46,
                'method' => 'tools/call',
                'params' => [
                    'name' => 'tax.preview',
                    'arguments' => [
                        'amount' => '100',
                        'country_code' => 'US',
                        'state_code' => 'CA',
                        'zip_code' => '90001',
                    ],
                ],
            ]);

        $response->assertOk()
            ->assertJsonPath('result.isError', false);

        $text = data_get($response->json(), 'result.content.0.text', '');
        $this->assertStringContainsString('Tax amount', $text);
        $this->assertStringContainsString('California Sales Tax', $text);
        $this->assertStringContainsString('8.25 USD', $text);
        $this->assertStringContainsString('US, CA, ZIP 90001', $text);
    }

    #[Test]
    public function forms_form_lookup_returns_form_activity(): void
    {
        $fixtures = $this->seedFormFixtures();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->formsToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 27,
                'method' => 'tools/call',
                'params' => [
                    'name' => 'forms.form_lookup',
                    'arguments' => [
                        'search_term' => 'Support',
                    ],
                ],
            ]);

        $response->assertOk()
            ->assertJsonPath('result.isError', false);

        $text = data_get($response->json(), 'result.content.0.text', '');
        $this->assertStringContainsString('Support Contact Form', $text);
        $this->assertStringContainsString('2', $text);
        $this->assertStringContainsString('recipient', $text);
    }

    #[Test]
    public function forms_submission_search_masks_personal_data(): void
    {
        $this->seedFormFixtures();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->formsToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 28,
                'method' => 'tools/call',
                'params' => [
                    'name' => 'forms.submission_search',
                    'arguments' => [
                        'search_term' => 'Need help',
                        'read_status' => 'unread',
                    ],
                ],
            ]);

        $response->assertOk()
            ->assertJsonPath('result.isError', false);

        $text = data_get($response->json(), 'result.content.0.text', '');
        $this->assertStringContainsString('Support Contact Form', $text);
        $this->assertStringContainsString('ja*********@example.com', $text);
        $this->assertStringNotContainsString('jane.reader@example.com', $text);
        $this->assertStringContainsString('Unread', $text);
    }

    #[Test]
    public function forms_submission_detail_masks_sensitive_fields_and_normalizes_attachments(): void
    {
        $fixtures = $this->seedFormFixtures();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->formsToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 29,
                'method' => 'tools/call',
                'params' => [
                    'name' => 'forms.submission_detail',
                    'arguments' => [
                        'submission_id' => $fixtures['supportSubmission']->id,
                    ],
                ],
            ]);

        $response->assertOk()
            ->assertJsonPath('result.isError', false);

        $text = data_get($response->json(), 'result.content.0.text', '');
        $this->assertStringContainsString('ja*********@example.com', $text);
        $this->assertStringContainsString('***-***-1234', $text);
        $this->assertStringContainsString('[file] brief.pdf', $text);
        $this->assertStringContainsString('203.0.x.x', $text);
        $this->assertStringNotContainsString('203.0.113.10', $text);
    }

    #[Test]
    public function forms_activity_summary_reports_recent_submission_totals(): void
    {
        $this->seedFormFixtures();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->formsToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 30,
                'method' => 'tools/call',
                'params' => [
                    'name' => 'forms.activity_summary',
                    'arguments' => [
                        'period' => 'recent_30d',
                    ],
                ],
            ]);

        $response->assertOk()
            ->assertJsonPath('result.isError', false);

        $text = data_get($response->json(), 'result.content.0.text', '');
        $this->assertStringContainsString('Forms activity summary', $text);
        $this->assertStringContainsString('Support Contact Form', $text);
        $this->assertStringContainsString('3', $text);
    }

    #[Test]
    public function newsletter_campaign_lookup_returns_campaign_metrics(): void
    {
        $list = NewsletterList::factory()->create(['name' => 'Launch Audience']);
        $campaign = NewsletterCampaign::factory()->create([
            'list_id' => $list->id,
            'name' => 'Launch Sequence',
            'subject' => 'Welcome to the launch',
            'status' => NewsletterCampaign::STATUS_QUEUED,
            'campaign_type' => NewsletterCampaign::CAMPAIGN_TYPE_AUTOMATION,
            'trigger_event' => NewsletterCampaign::TRIGGER_CART_ABANDONED,
            'recipients_from' => 'specific_list',
        ]);

        $subscriber = NewsletterSubscriber::factory()->create();
        NewsletterSubscriberList::query()->create([
            'subscriber_id' => $subscriber->id,
            'list_id' => $list->id,
        ]);
        NewsletterCampaignPixel::query()->create(['campaign_id' => $campaign->id, 'email' => 'reader@example.com']);
        NewsletterCampaignClickedLink::query()->create([
            'campaign_id' => $campaign->id,
            'email' => 'reader@example.com',
            'link' => 'https://example.com/offer',
        ]);
        NewsletterCampaignsSendLog::query()->create([
            'campaign_id' => $campaign->id,
            'subscriber_id' => $subscriber->id,
            'is_sent' => 1,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->newsletterToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 21,
                'method' => 'tools/call',
                'params' => [
                    'name' => 'newsletter.campaign_lookup',
                    'arguments' => [
                        'search_term' => 'Launch Sequence',
                    ],
                ],
            ]);

        $response->assertOk()
            ->assertJsonPath('result.isError', false);

        $text = data_get($response->json(), 'result.content.0.text', '');
        $this->assertStringContainsString('Launch Sequence', $text);
        $this->assertStringContainsString('queued', $text);
        $this->assertStringContainsString('opened 1', $text);
        $this->assertStringContainsString('clicked 1', $text);
    }

    #[Test]
    public function newsletter_subscriber_lookup_masks_email_addresses(): void
    {
        $list = NewsletterList::factory()->create(['name' => 'VIP Readers']);
        $subscriber = NewsletterSubscriber::factory()->create([
            'name' => 'Alex Reader',
            'email' => 'alex.reader@example.com',
            'status' => 'active',
        ]);
        NewsletterSubscriberList::query()->create([
            'subscriber_id' => $subscriber->id,
            'list_id' => $list->id,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->newsletterToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 22,
                'method' => 'tools/call',
                'params' => [
                    'name' => 'newsletter.subscriber_lookup',
                    'arguments' => [
                        'search_term' => 'alex.reader@example.com',
                    ],
                ],
            ]);

        $response->assertOk()
            ->assertJsonPath('result.isError', false);

        $text = data_get($response->json(), 'result.content.0.text', '');
        $this->assertStringContainsString('Alex Reader', $text);
        $this->assertStringContainsString('al*********@example.com', $text);
        $this->assertStringNotContainsString('alex.reader@example.com', $text);
        $this->assertStringContainsString('VIP Readers', $text);
    }

    #[Test]
    public function newsletter_template_lookup_returns_template_usage_counts(): void
    {
        $template = NewsletterTemplate::factory()->create([
            'title' => 'Automation Template',
            'text' => '<h1>Automation Template</h1><p>Used by campaigns.</p>',
        ]);
        NewsletterCampaign::factory()->create([
            'email_template_id' => $template->id,
            'name' => 'Template Driven Campaign',
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->newsletterToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 23,
                'method' => 'tools/call',
                'params' => [
                    'name' => 'newsletter.template_lookup',
                    'arguments' => [
                        'search_term' => 'Automation Template',
                    ],
                ],
            ]);

        $response->assertOk()
            ->assertJsonPath('result.isError', false);

        $text = data_get($response->json(), 'result.content.0.text', '');
        $this->assertStringContainsString('Automation Template', $text);
        $this->assertStringContainsString('1 linked campaign(s)', $text);
    }

    #[Test]
    public function newsletter_automation_status_returns_queue_and_workflow_health(): void
    {
        $workflow = Workflow::factory()->create([
            'name' => 'Cart Recovery Flow',
            'trigger_event' => Workflow::TRIGGER_CART_ABANDONED,
        ]);
        WorkflowExecution::factory()->completed()->create([
            'workflow_id' => $workflow->id,
            'current_step' => 3,
            'total_steps' => 3,
        ]);
        WorkflowExecution::factory()->failed()->create([
            'workflow_id' => $workflow->id,
            'error_message' => 'API token=secret-value was rejected',
        ]);

        $campaign = NewsletterCampaign::factory()->create([
            'name' => 'Recovery Campaign',
            'trigger_event' => NewsletterCampaign::TRIGGER_CART_ABANDONED,
        ]);
        NewsletterAutomationQueue::factory()->forCartAbandoned()->scheduledForPast()->create([
            'campaign_id' => $campaign->id,
            'status' => NewsletterAutomationQueue::STATUS_PENDING,
        ]);
        NewsletterAutomationQueue::factory()->failed()->forCartAbandoned()->create([
            'campaign_id' => $campaign->id,
            'error_message' => 'api_key=very-secret-value timed out',
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->newsletterToken->plainTextToken)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 24,
                'method' => 'tools/call',
                'params' => [
                    'name' => 'newsletter.automation_status',
                    'arguments' => [
                        'view' => 'summary',
                        'trigger_event' => Workflow::TRIGGER_CART_ABANDONED,
                    ],
                ],
            ]);

        $response->assertOk()
            ->assertJsonPath('result.isError', false);

        $text = data_get($response->json(), 'result.content.0.text', '');
        $this->assertStringContainsString('Newsletter automation queue summary', $text);
        $this->assertStringContainsString('Ready to send', $text);
        $this->assertStringContainsString('Cart Recovery Flow', $text);
        $this->assertStringContainsString('api_key=[redacted]', $text);
    }

    #[Test]
    public function rollout_path_supports_pass_backed_ai_config_and_a_first_mcp_session(): void
    {
        config([
            'modules.ai.secret_store.driver' => 'pass',
            'modules.ai.secret_store.pass.enabled' => true,
            'modules.ai.secret_store.pass.path_prefix' => 'microweber',
            'modules.ai.secret_store.pass.environment' => 'testing',
        ]);

        save_option([
            'option_key' => 'openai_api_key',
            'option_value' => 'pass://microweber/testing/ai/openai',
            'option_group' => 'ai',
        ]);

        $runner = $this->createMock(PassCommandRunner::class);
        $runner->expects($this->once())
            ->method('run')
            ->with(['show', 'microweber/testing/ai/openai'], null)
            ->willReturn('stored-openai-key');

        $this->app->instance(PassCommandRunner::class, $runner);
        $this->app->singleton(PassSecretStore::class, fn ($app) => new PassSecretStore($app->make(PassCommandRunner::class)));

        $provider = new AiServiceProvider($this->app);
        $provider->setAiConfig();

        $this->assertSame('stored-openai-key', config('modules.ai.drivers.openai.api_key'));
        $this->assertSame('pass://microweber/testing/ai/openai', get_option('openai_api_key', 'ai'));

        Content::factory()->create([
            'title' => 'Rollout MCP Page',
            'description' => 'Rollout validation content for MCP.',
            'content_type' => 'page',
            'is_active' => 1,
            'is_deleted' => 0,
        ]);

        $headers = ['Authorization' => 'Bearer ' . $this->limitedToolToken->plainTextToken];

        $this->withHeaders($headers)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 'rollout-init',
                'method' => 'initialize',
                'params' => [
                    'clientInfo' => [
                        'name' => 'rollout-check',
                        'version' => '1.0.0',
                    ],
                ],
            ])
            ->assertOk()
            ->assertJsonPath('result.serverInfo.name', 'Microweber AI MCP');

        $toolsListResponse = $this->withHeaders($headers)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 'rollout-tools',
                'method' => 'tools/list',
            ]);

        $toolsListResponse->assertOk();
        $this->assertSame(
            ['content.lookup', 'order.lookup'],
            collect($toolsListResponse->json('result.tools'))->pluck('name')->all()
        );
        $this->assertSame(
            ['search_term'],
            data_get($toolsListResponse->json(), 'result.tools.0.inputSchema.required')
        );

        $toolCallResponse = $this->withHeaders($headers)
            ->postJson(route('api.ai.mcp'), [
                'jsonrpc' => '2.0',
                'id' => 'rollout-call',
                'method' => 'tools/call',
                'params' => [
                    'name' => 'content.lookup',
                    'arguments' => [
                        'search_term' => 'Rollout MCP',
                        'content_type' => 'page',
                    ],
                ],
            ]);

        $toolCallResponse->assertOk()
            ->assertJsonPath('result.isError', false);

        $this->assertStringContainsString(
            'Rollout MCP Page',
            data_get($toolCallResponse->json(), 'result.content.0.text', '')
        );
        $this->assertSame(
            3,
            DB::table('mcp_client_token_events')
                ->where('mcp_client_id', $this->limitedToolClient->id)
                ->where('mcp_client_token_id', $this->limitedToolToken->token->id)
                ->where('action', 'token.used')
                ->count()
        );
    }

    private function seedAnalyticsFixtures(): void
    {
        $landingContent = Content::factory()->create([
            'title' => 'Landing Page',
            'content_type' => 'page',
            'is_active' => 1,
            'is_deleted' => 0,
        ]);
        $pricingContent = Content::factory()->create([
            'title' => 'Pricing Page',
            'content_type' => 'page',
            'is_active' => 1,
            'is_deleted' => 0,
        ]);

        $landingUrl = StatsUrl::query()->create([
            'url' => site_url('landing-page'),
            'content_id' => $landingContent->id,
            'url_hash' => md5('landing-page'),
            'updated_at' => now(),
        ]);
        $pricingUrl = StatsUrl::query()->create([
            'url' => site_url('pricing-page'),
            'content_id' => $pricingContent->id,
            'url_hash' => md5('pricing-page'),
            'updated_at' => now(),
        ]);

        $referrerDomain = ReferrersDomains::query()->create([
            'referrer_domain' => 'google.com',
            'updated_at' => now(),
        ]);
        $referrerPath = ReferrersPaths::query()->create([
            'referrer_domain_id' => $referrerDomain->id,
            'referrer_path' => '/search',
            'updated_at' => now(),
        ]);
        $referrer = Referrers::query()->create([
            'referrer' => 'https://google.com/search?q=microweber',
            'referrer_hash' => md5('google-referrer'),
            'referrer_domain_id' => $referrerDomain->id,
            'referrer_path_id' => $referrerPath->id,
            'is_internal' => 0,
            'updated_at' => now(),
        ]);

        $desktopBrowser = Browsers::query()->create([
            'browser_agent' => 'Desktop Browser',
            'browser_agent_hash' => md5('desktop-browser'),
            'platform' => 'Windows',
            'browser' => 'Chrome',
            'device' => 'Desktop',
            'is_desktop' => 1,
            'is_mobile' => 0,
            'is_phone' => 0,
            'is_tablet' => 0,
            'is_robot' => 0,
            'updated_at' => now(),
        ]);
        $mobileBrowser = Browsers::query()->create([
            'browser_agent' => 'Mobile Browser',
            'browser_agent_hash' => md5('mobile-browser'),
            'platform' => 'iOS',
            'browser' => 'Safari',
            'device' => 'Phone',
            'is_desktop' => 0,
            'is_mobile' => 1,
            'is_phone' => 1,
            'is_tablet' => 0,
            'is_robot' => 0,
            'updated_at' => now(),
        ]);

        $unitedStates = Geoip::query()->create([
            'country_code' => 'US',
            'country_name' => 'United States',
            'updated_at' => now(),
        ]);
        $unitedKingdom = Geoip::query()->create([
            'country_code' => 'GB',
            'country_name' => 'United Kingdom',
            'updated_at' => now(),
        ]);

        $desktopSession = SiteStatsSession::query()->create([
            'session_id' => 'desktop-session',
            'browser_id' => $desktopBrowser->id,
            'referrer_id' => $referrer->id,
            'referrer_domain_id' => $referrerDomain->id,
            'referrer_path_id' => $referrerPath->id,
            'geoip_id' => $unitedStates->id,
            'language' => 'en',
            'updated_at' => now(),
        ]);
        $mobileSession = SiteStatsSession::query()->create([
            'session_id' => 'mobile-session',
            'browser_id' => $mobileBrowser->id,
            'referrer_id' => $referrer->id,
            'referrer_domain_id' => $referrerDomain->id,
            'referrer_path_id' => $referrerPath->id,
            'geoip_id' => $unitedKingdom->id,
            'language' => 'en',
            'updated_at' => now(),
        ]);

        SiteStatsLog::query()->create([
            'url_id' => $landingUrl->id,
            'referrer_id' => $referrer->id,
            'view_count' => 1,
            'session_id_key' => $desktopSession->id,
            'updated_at' => now()->subMinutes(5),
        ]);
        SiteStatsLog::query()->create([
            'url_id' => $landingUrl->id,
            'referrer_id' => $referrer->id,
            'view_count' => 2,
            'session_id_key' => $desktopSession->id,
            'updated_at' => now()->subMinutes(1),
        ]);
        SiteStatsLog::query()->create([
            'url_id' => $pricingUrl->id,
            'referrer_id' => $referrer->id,
            'view_count' => 1,
            'session_id_key' => $mobileSession->id,
            'updated_at' => now()->subMinutes(2),
        ]);
    }

    /**
     * @return array{primaryProvider: PaymentProvider, primaryPayment: Payment}
     */
    private function seedPaymentFixtures(): array
    {
        $stripeProvider = PaymentProvider::factory()->create([
            'name' => 'Stripe Gateway',
            'provider' => 'stripe',
            'is_active' => 1,
            'is_default' => 1,
            'settings' => [
                'secret_key' => 'sk_live_secret',
                'webhook_secret' => 'whsec_hidden',
            ],
        ]);

        $paypalProvider = PaymentProvider::factory()->create([
            'name' => 'PayPal Gateway',
            'provider' => 'paypal',
            'is_active' => 1,
            'is_default' => 0,
            'settings' => [
                'client_secret' => 'paypal-secret',
            ],
        ]);

        $primaryPayment = Payment::factory()->create([
            'rel_id' => '2001',
            'rel_type' => 'order',
            'amount' => 129.99,
            'currency' => 'USD',
            'status' => 'completed',
            'payment_provider' => 'stripe',
            'payment_provider_id' => (string) $stripeProvider->id,
            'transaction_id' => 'ch_payment_primary',
            'payment_data' => [
                'card_last_four' => '4242',
                'card_number' => '4111111111111111',
                'secret_key' => 'sk_live_secret',
            ],
            'created_at' => now()->subHours(2),
            'updated_at' => now()->subHours(2),
        ]);

        Payment::factory()->create([
            'rel_id' => '2002',
            'rel_type' => 'order',
            'amount' => 89.50,
            'currency' => 'USD',
            'status' => 'failed',
            'payment_provider' => 'stripe',
            'payment_provider_id' => (string) $stripeProvider->id,
            'transaction_id' => 'ch_payment_failed',
            'payment_data' => [
                'failure_code' => 'card_declined',
                'api_key' => 'sk_live_secret',
            ],
            'created_at' => now()->subHour(),
            'updated_at' => now()->subHour(),
        ]);

        Payment::factory()->create([
            'rel_id' => '3001',
            'rel_type' => 'order',
            'amount' => 59.00,
            'currency' => 'USD',
            'status' => 'pending',
            'payment_provider' => 'paypal',
            'payment_provider_id' => (string) $paypalProvider->id,
            'transaction_id' => 'pp_payment_pending',
            'payment_data' => [
                'payer_id' => 'payer-1',
            ],
            'created_at' => now()->subMinutes(30),
            'updated_at' => now()->subMinutes(30),
        ]);

        WebhookLog::factory()->completed()->create([
            'provider' => 'stripe',
            'event_type' => 'checkout.session.completed',
            'event_id' => 'evt_checkout_completed',
            'payload' => [
                'secret_key' => 'sk_live_secret',
                'card_number' => '4111111111111111',
            ],
            'attempts' => 1,
            'processed_at' => now()->subMinutes(20),
        ]);

        WebhookLog::factory()->failed()->create([
            'provider' => 'stripe',
            'event_type' => 'payment_intent.payment_failed',
            'event_id' => 'evt_payment_failed',
            'payload' => [
                'api_key' => 'sk_live_secret',
                'customer_email' => 'buyer@example.com',
            ],
            'error_message' => 'api_key=sk_live_secret payment failed for card 4111111111111111',
            'attempts' => 2,
        ]);

        WebhookLog::factory()->pending()->create([
            'provider' => 'paypal',
            'event_type' => 'payment.capture.pending',
            'event_id' => 'evt_paypal_pending',
            'attempts' => 0,
        ]);

        return [
            'primaryProvider' => $stripeProvider,
            'primaryPayment' => $primaryPayment,
        ];
    }

    private function seedShippingTaxFixtures(): void
    {
        ShippingProvider::query()->create([
            'name' => 'Country Shipping',
            'provider' => 'shipping_to_country',
            'is_active' => 1,
            'is_default' => 1,
            'position' => 1,
            'settings' => [
                'countries' => [
                    [
                        'shipping_country' => 'United States',
                        'shipping_type' => 'per_item',
                        'shipping_cost' => 7.50,
                        'shipping_price_per_item' => 2.25,
                        'is_active' => true,
                    ],
                    [
                        'shipping_country' => 'Worldwide',
                        'shipping_type' => 'fixed',
                        'shipping_cost' => 15.00,
                        'is_active' => true,
                    ],
                ],
                'shipping_instructions' => 'Please select your shipping country to calculate shipping costs.',
            ],
        ]);

        ShippingProvider::query()->create([
            'name' => 'Weight Shipping',
            'provider' => 'weight_based',
            'is_active' => 1,
            'is_default' => 0,
            'position' => 2,
            'settings' => [
                'base_shipping_cost' => 4.00,
                'cost_per_weight_unit' => 1.50,
                'max_shipping_cost' => 25.00,
                'free_shipping_threshold' => 50.00,
                'shipping_instructions' => 'Weight-based shipping available.',
            ],
        ]);

        TaxRate::query()->create([
            'name' => 'California Sales Tax',
            'description' => 'CA base sales tax',
            'country_code' => 'US',
            'state_code' => 'CA',
            'zip_code_pattern' => '900*',
            'type' => 'percentage',
            'rate' => 8.25,
            'priority' => 20,
            'is_default' => false,
            'is_active' => true,
        ]);

        TaxRate::query()->create([
            'name' => 'US Default Tax',
            'description' => 'Fallback US tax',
            'country_code' => 'US',
            'type' => 'percentage',
            'rate' => 5.00,
            'priority' => 5,
            'is_default' => true,
            'is_active' => true,
        ]);

        TaxType::query()->create([
            'name' => 'Legacy VAT',
            'type' => 'percent',
            'rate' => 15.00,
            'description' => 'Legacy global tax fallback',
        ]);
    }

    /**
     * @return array{primaryImage: Media, document: Media, audio: Media}
     */
    private function seedMediaFixtures(): array
    {
        $marketingFolder = MediaFolder::query()->create([
            'name' => 'Marketing',
            'slug' => 'marketing',
            'description' => 'Marketing assets',
            'sort_order' => 1,
        ]);

        $heroFolder = MediaFolder::query()->create([
            'name' => 'Homepage Hero',
            'slug' => 'homepage-hero',
            'description' => 'Homepage hero images',
            'parent_id' => $marketingFolder->id,
            'sort_order' => 1,
        ]);

        Storage::disk('public')->put('uploads/mcp-media/gallery/sunset-banner.jpg', 'sunset-image');
        Storage::disk('public')->put('uploads/mcp-media/docs/product-specs.pdf', 'product-pdf');
        Storage::disk('public')->put('uploads/mcp-media/audio/theme-song.mp3', 'audio-track');

        $primaryImage = Media::query()->create([
            'folder_id' => $heroFolder->id,
            'title' => 'Homepage Sunset Banner',
            'description' => 'Primary homepage hero image',
            'filename' => 'uploads/mcp-media/gallery/sunset-banner.jpg',
            'media_type' => 'image/jpeg',
            'rel_type' => 'content',
            'rel_id' => '1001',
            'created_by' => 7,
            'metadata' => [
                'width' => 1600,
                'height' => 900,
                'alt' => 'Homepage sunset banner',
            ],
            'cdn_url' => 'https://cdn.example.com/assets/sunset-banner.jpg',
            'cdn_provider' => 'cloudfront',
            'cdn_metadata' => [
                'distribution' => 'dist-123',
                'cache_status' => 'warm',
            ],
            'is_synced_to_cdn' => true,
            'file_size' => 204800,
            'file_hash' => 'hash-sunset-banner',
        ]);

        $document = Media::query()->create([
            'folder_id' => $marketingFolder->id,
            'title' => 'Product Specs PDF',
            'description' => 'Latest product specifications sheet',
            'filename' => 'uploads/mcp-media/docs/product-specs.pdf',
            'media_type' => 'application/pdf',
            'rel_type' => 'product',
            'rel_id' => '2002',
            'created_by' => 7,
            'metadata' => [
                'pages' => 6,
                'language' => 'en',
            ],
            'file_size' => 51200,
        ]);

        $audio = Media::query()->create([
            'folder_id' => null,
            'title' => 'Theme Song',
            'description' => 'Brand intro audio',
            'filename' => 'uploads/mcp-media/audio/theme-song.mp3',
            'media_type' => 'audio/mpeg',
            'rel_type' => 'layout',
            'rel_id' => 'home',
            'created_by' => 9,
            'file_size' => 4096,
        ]);

        return [
            'primaryImage' => $primaryImage,
            'document' => $document,
            'audio' => $audio,
        ];
    }

    /**
     * @return array{primaryCustomer: Customer, primaryInvoice: InvoiceRecord, overdueInvoice: InvoiceRecord}
     */
    private function seedInvoiceFixtures(): array
    {
        $primaryCustomer = Customer::factory()->create([
            'first_name' => 'Invoice',
            'last_name' => 'Primary',
            'email' => 'invoice-mcp-primary@example.com',
            'phone' => '+1-555-000-1111',
        ]);

        $secondaryCustomer = Customer::factory()->create([
            'first_name' => 'Invoice',
            'last_name' => 'Secondary',
            'email' => 'invoice-mcp-secondary@example.com',
            'phone' => '+1-555-000-2222',
        ]);

        $primaryInvoice = InvoiceRecord::factory()->withCustomer($primaryCustomer)->create([
            'invoice_number' => 'INV-MCP-001',
            'reference_number' => 'ORDER-2001',
            'invoice_date' => now()->subDays(10)->toDateString(),
            'due_date' => now()->addDays(5)->toDateString(),
            'status' => InvoiceRecord::STATUS_SENT,
            'paid_status' => InvoiceRecord::STATUS_UNPAID,
            'sub_total' => 120000,
            'discount' => 5000,
            'discount_type' => 'fixed',
            'discount_val' => 5000,
            'total' => 125000,
            'due_amount' => 125000,
            'tax' => 10.00,
            'notes' => 'Outstanding support invoice',
        ]);

        InvoiceItem::query()->create([
            'invoice_id' => $primaryInvoice->id,
            'name' => 'Premium support retainer',
            'description' => 'Monthly engineering support',
            'price' => 100000,
            'quantity' => 1,
            'tax' => 0,
        ]);
        InvoiceItem::query()->create([
            'invoice_id' => $primaryInvoice->id,
            'name' => 'Onboarding workshop',
            'description' => 'Implementation planning and training',
            'price' => 20000,
            'quantity' => 1,
            'tax' => 0,
        ]);

        $overdueInvoice = InvoiceRecord::factory()->withCustomer($primaryCustomer)->overdue()->create([
            'invoice_number' => 'INV-MCP-002',
            'reference_number' => 'ORDER-2002',
            'invoice_date' => now()->subDays(40)->toDateString(),
            'due_date' => now()->subDays(12)->toDateString(),
            'status' => InvoiceRecord::STATUS_OVERDUE,
            'paid_status' => InvoiceRecord::STATUS_PARTIALLY_PAID,
            'sub_total' => 80000,
            'discount' => 0,
            'discount_type' => 'fixed',
            'discount_val' => 0,
            'total' => 80000,
            'due_amount' => 30000,
            'tax' => 0,
            'notes' => 'Follow up with finance team',
        ]);

        InvoiceItem::query()->create([
            'invoice_id' => $overdueInvoice->id,
            'name' => 'Migration consulting',
            'description' => 'Invoice remaining balance',
            'price' => 80000,
            'quantity' => 1,
            'tax' => 0,
        ]);

        InvoiceRecord::factory()->withCustomer($secondaryCustomer)->paid()->create([
            'invoice_number' => 'INV-MCP-003',
            'reference_number' => 'ORDER-2003',
            'invoice_date' => now()->subDays(20)->toDateString(),
            'due_date' => now()->subDays(3)->toDateString(),
            'status' => InvoiceRecord::STATUS_PAID,
            'paid_status' => InvoiceRecord::STATUS_PAID,
            'sub_total' => 45000,
            'discount' => 0,
            'discount_type' => 'fixed',
            'discount_val' => 0,
            'total' => 45000,
            'due_amount' => 0,
            'tax' => 0,
            'notes' => 'Settled invoice',
        ]);

        return [
            'primaryCustomer' => $primaryCustomer,
            'primaryInvoice' => $primaryInvoice,
            'overdueInvoice' => $overdueInvoice,
        ];
    }

    /**
     * @return array{form: ContactForm, list: FormList, supportSubmission: FormData}
     */
    private function seedFormFixtures(): array
    {
        $list = new FormList();
        $list->created_at = now()->subDays(5);
        $list->created_by = 1;
        $list->title = 'Support Inbox';
        $list->description = 'Contact form support queue';
        $list->module_name = 'contact_form';
        $list->save();

        $supportForm = ContactForm::query()->create([
            'name' => 'Support Contact Form',
            'slug' => 'support-contact-form',
            'list_id' => $list->id,
            'module_id' => 1001,
            'description' => 'Primary support form',
            'confirmation_message' => 'Thanks for reaching out.',
            'emails_notifications' => 'support@example.com,team@example.com',
            'emails_notifications_subject' => 'New support inquiry',
            'is_active' => 1,
        ]);

        $salesForm = ContactForm::query()->create([
            'name' => 'Sales Form',
            'slug' => 'sales-form',
            'list_id' => null,
            'module_id' => 2002,
            'description' => 'Sales inquiries',
            'confirmation_message' => 'We will contact you soon.',
            'emails_notifications' => 'sales@example.com',
            'emails_notifications_subject' => 'New sales inquiry',
            'is_active' => 1,
        ]);

        $supportSubmission = new FormData();
        $supportSubmission->created_at = now()->subHours(2);
        $supportSubmission->updated_at = now()->subHours(2);
        $supportSubmission->created_by = 1;
        $supportSubmission->rel_type = 'module';
        $supportSubmission->rel_id = (string) $supportForm->module_id;
        $supportSubmission->list_id = $list->id;
        $supportSubmission->form_values = '';
        $supportSubmission->module_name = 'contact_form';
        $supportSubmission->module_id = (string) $supportForm->module_id;
        $supportSubmission->url = 'https://example.com/contact';
        $supportSubmission->user_ip = '203.0.113.10';
        $supportSubmission->is_read = 0;
        $supportSubmission->save();

        FormDataValue::query()->create([
            'form_data_id' => $supportSubmission->id,
            'field_type' => 'text',
            'field_key' => 'name',
            'field_name' => 'Name',
            'field_value' => 'Jane Reader',
        ]);
        FormDataValue::query()->create([
            'form_data_id' => $supportSubmission->id,
            'field_type' => 'email',
            'field_key' => 'email',
            'field_name' => 'Email',
            'field_value' => 'jane.reader@example.com',
        ]);
        FormDataValue::query()->create([
            'form_data_id' => $supportSubmission->id,
            'field_type' => 'phone',
            'field_key' => 'phone',
            'field_name' => 'Phone',
            'field_value' => '+1 (555) 000-1234',
        ]);
        FormDataValue::query()->create([
            'form_data_id' => $supportSubmission->id,
            'field_type' => 'textarea',
            'field_key' => 'message',
            'field_name' => 'Message',
            'field_value' => 'Need help with the checkout flow on mobile devices.',
        ]);
        FormDataValue::query()->create([
            'form_data_id' => $supportSubmission->id,
            'field_type' => 'file',
            'field_key' => 'attachment',
            'field_name' => 'Attachment',
            'field_value' => '/var/www/storage/uploads/support/brief.pdf',
        ]);

        $legacySubmission = new FormData();
        $legacySubmission->created_at = now()->subDay();
        $legacySubmission->updated_at = now()->subDay();
        $legacySubmission->created_by = 1;
        $legacySubmission->rel_type = 'module';
        $legacySubmission->rel_id = (string) $supportForm->module_id;
        $legacySubmission->list_id = $list->id;
        $legacySubmission->form_values = json_encode([
            'name' => 'Legacy Writer',
            'email' => 'legacy.writer@example.com',
            'message' => 'Legacy entry stored in form_values only.',
        ], JSON_THROW_ON_ERROR);
        $legacySubmission->module_name = 'contact_form';
        $legacySubmission->module_id = (string) $supportForm->module_id;
        $legacySubmission->url = 'https://example.com/contact?legacy=1';
        $legacySubmission->user_ip = '198.51.100.22';
        $legacySubmission->is_read = 1;
        $legacySubmission->save();

        $salesSubmission = new FormData();
        $salesSubmission->created_at = now()->subHours(4);
        $salesSubmission->updated_at = now()->subHours(4);
        $salesSubmission->created_by = 1;
        $salesSubmission->rel_type = 'module';
        $salesSubmission->rel_id = (string) $salesForm->module_id;
        $salesSubmission->list_id = 0;
        $salesSubmission->form_values = '';
        $salesSubmission->module_name = 'contact_form';
        $salesSubmission->module_id = (string) $salesForm->module_id;
        $salesSubmission->url = 'https://example.com/request-demo';
        $salesSubmission->user_ip = '192.0.2.11';
        $salesSubmission->is_read = 0;
        $salesSubmission->save();

        FormDataValue::query()->create([
            'form_data_id' => $salesSubmission->id,
            'field_type' => 'text',
            'field_key' => 'company_name',
            'field_name' => 'Company Name',
            'field_value' => 'Acme Corp',
        ]);
        FormDataValue::query()->create([
            'form_data_id' => $salesSubmission->id,
            'field_type' => 'email',
            'field_key' => 'email',
            'field_name' => 'Email',
            'field_value' => 'sales.lead@example.com',
        ]);
        FormDataValue::query()->create([
            'form_data_id' => $salesSubmission->id,
            'field_type' => 'textarea',
            'field_key' => 'message',
            'field_name' => 'Message',
            'field_value' => 'Interested in enterprise pricing and onboarding.',
        ]);

        return [
            'form' => $supportForm,
            'list' => $list,
            'supportSubmission' => $supportSubmission,
        ];
    }

    /**
     * @return array{primaryCustomer: SubscriptionCustomer, primarySubscription: BillingSubscription}
     */
    private function seedBillingFixtures(): array
    {
        $group = SubscriptionPlanGroup::factory()->create([
            'name' => 'Hosting Plans',
            'sku' => 'HOSTING',
        ]);

        $monthlyPlan = SubscriptionPlan::factory()->forGroup($group)->create([
            'name' => 'Business Monthly',
            'sku' => 'BIZ-MONTHLY',
            'price' => 29.99,
            'currency' => 'USD',
            'billing_interval' => 'monthly',
            'trial_days' => 14,
            'is_active' => true,
        ]);
        SubscriptionPlanFeature::query()->create([
            'subscription_plan_id' => $monthlyPlan->id,
            'key' => 'storage',
            'description' => 'Storage allowance',
            'value' => '100 GB',
            'limit' => '100',
            'position' => 1,
        ]);

        $yearlyPlan = SubscriptionPlan::factory()->forGroup($group)->create([
            'name' => 'Business Annual',
            'sku' => 'BIZ-YEARLY',
            'price' => 120.00,
            'currency' => 'USD',
            'billing_interval' => 'yearly',
            'trial_days' => 0,
            'is_active' => true,
        ]);

        $primaryUser = User::factory()->create([
            'email' => 'billing-mcp-primary@example.com',
            'first_name' => 'Billing',
            'last_name' => 'Primary',
        ]);
        $secondaryUser = User::factory()->create([
            'email' => 'billing-mcp-secondary@example.com',
            'first_name' => 'Billing',
            'last_name' => 'Secondary',
        ]);

        $primaryCustomer = SubscriptionCustomer::query()->create([
            'user_id' => $primaryUser->id,
            'name' => 'Billing Primary',
            'first_name' => 'Billing',
            'last_name' => 'Primary',
            'email' => 'billing-mcp-primary@example.com',
            'stripe_id' => 'cus_billing_primary',
            'pm_type' => 'card',
            'pm_last_four' => '4242',
            'status' => 'active',
            'trial_ends_at' => now()->addDays(10),
        ]);
        $secondaryCustomer = SubscriptionCustomer::query()->create([
            'user_id' => $secondaryUser->id,
            'name' => 'Billing Secondary',
            'first_name' => 'Billing',
            'last_name' => 'Secondary',
            'email' => 'billing-mcp-secondary@example.com',
            'stripe_id' => 'cus_billing_secondary',
            'pm_type' => 'card',
            'pm_last_four' => '1111',
            'status' => 'active',
        ]);

        $primarySubscription = BillingSubscription::factory()
            ->forCustomer($primaryCustomer)
            ->forPlan($monthlyPlan)
            ->active()
            ->create([
                'stripe_id' => 'sub_billing_primary_active',
                'starts_at' => now()->subDays(12),
                'trial_ends_at' => now()->addDays(2),
            ]);

        BillingSubscription::factory()
            ->forCustomer($secondaryCustomer)
            ->forPlan($yearlyPlan)
            ->active()
            ->create([
                'stripe_id' => 'sub_billing_secondary_active',
                'starts_at' => now()->subMonths(2),
                'trial_ends_at' => null,
            ]);

        BillingSubscription::factory()
            ->forCustomer($primaryCustomer)
            ->forPlan($monthlyPlan)
            ->canceled()
            ->create([
                'stripe_id' => 'sub_billing_primary_canceled',
                'created_at' => now()->subDays(60),
                'updated_at' => now()->subDays(5),
            ]);

        return [
            'primaryCustomer' => $primaryCustomer,
            'primarySubscription' => $primarySubscription,
        ];
    }
}
