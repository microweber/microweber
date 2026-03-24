<?php

namespace Tests\Browser;

use Facebook\WebDriver\WebDriverBy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Dusk\Browser;
use MicroweberPackages\User\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\DuskTestCase;

/**
 * Admin Pages Smoke Tests
 *
 * Tests that all admin pages load without JavaScript errors
 * when logged in as an admin user.
 */
class AdminPagesTest extends DuskTestCase
{
    protected static $adminUser = null;

    protected function setUp(): void
    {
        parent::setUp();

        // Create admin user if not exists
        if (static::$adminUser === null) {
            static::$adminUser = User::factory()->create([
                'is_admin' => 1,
                'username' => 'test_admin',
                'email' => 'testadmin@example.com',
                'password' => \Hash::make('password123'),
            ]);
        }

        // Disable login captcha for tests
        save_option([
            'option_value' => 'n',
            'option_key' => 'login_captcha_enabled',
            'option_group' => 'users',
        ]);
    }

    /**
     * Login as admin user
     */
    protected function loginAsAdmin(Browser $browser): void
    {
        $browser->visit('/admin/login')
            ->waitFor('form', 10)
            ->type('email', 'testadmin@example.com')
            ->type('password', 'password123')
            ->click('button[type="submit"]')
            ->waitForLocation('/admin', 30)
            ->pause(1000);
    }

    /**
     * Check for JavaScript errors
     */
    protected function assertNoJsErrors(Browser $browser, string $pageName): void
    {
        $logs = $browser->driver->manage()->getLog('browser');
        $errors = array_filter($logs, fn($log) => $log['level'] === 'SEVERE');
        $this->assertEmpty($errors, "JavaScript errors found on {$pageName}: " . json_encode($errors));
    }

    /**
     * Test admin dashboard loads
     */
    #[Test]
    public function it_admin_dashboard_loads_without_js_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin')
                ->waitFor('body', 10);

            $this->assertNoJsErrors($browser, 'admin dashboard');
        });
    }

    /**
     * Test content pages list loads
     */
    #[Test]
    public function it_content_pages_list_loads_without_js_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/pages')
                ->waitFor('body', 10);

            $this->assertNoJsErrors($browser, 'content pages list');
        });
    }

    /**
     * Test products list loads
     */
    #[Test]
    public function it_products_list_loads_without_js_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/products')
                ->waitFor('body', 10);

            $this->assertNoJsErrors($browser, 'products list');
        });
    }

    /**
     * Test orders list loads
     */
    #[Test]
    public function it_orders_list_loads_without_js_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/orders')
                ->waitFor('body', 10);

            $this->assertNoJsErrors($browser, 'orders list');
        });
    }

    /**
     * Test customers list loads
     */
    #[Test]
    public function it_customers_list_loads_without_js_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/customers')
                ->waitFor('body', 10);

            $this->assertNoJsErrors($browser, 'customers list');
        });
    }

    /**
     * Test categories list loads
     */
    #[Test]
    public function it_categories_list_loads_without_js_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/categories')
                ->waitFor('body', 10);

            $this->assertNoJsErrors($browser, 'categories list');
        });
    }

    /**
     * Test comments list loads
     */
    #[Test]
    public function it_comments_list_loads_without_js_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/comments')
                ->waitFor('body', 10);

            $this->assertNoJsErrors($browser, 'comments list');
        });
    }

    /**
     * Test media library loads
     */
    #[Test]
    public function it_media_library_loads_without_js_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/media')
                ->waitFor('body', 10);

            $this->assertNoJsErrors($browser, 'media library');
        });
    }

    /**
     * Test coupons list loads
     */
    #[Test]
    public function it_coupons_list_loads_without_js_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/coupons')
                ->waitFor('body', 10);

            $this->assertNoJsErrors($browser, 'coupons list');
        });
    }

    /**
     * Test tax rates list loads
     */
    #[Test]
    public function it_tax_rates_list_loads_without_js_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/tax-rates')
                ->waitFor('body', 10);

            $this->assertNoJsErrors($browser, 'tax rates list');
        });
    }

    /**
     * Test shipping providers list loads
     */
    #[Test]
    public function it_shipping_providers_list_loads_without_js_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/shipping-providers')
                ->waitFor('body', 10);

            $this->assertNoJsErrors($browser, 'shipping providers list');
        });
    }

    /**
     * Test payment providers list loads
     */
    #[Test]
    public function it_payment_providers_list_loads_without_js_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/payment-providers')
                ->waitFor('body', 10);

            $this->assertNoJsErrors($browser, 'payment providers list');
        });
    }

    /**
     * Test invoices list loads
     */
    #[Test]
    public function it_invoices_list_loads_without_js_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/invoices')
                ->waitFor('body', 10);

            $this->assertNoJsErrors($browser, 'invoices list');
        });
    }

    /**
     * Test newsletter campaigns list loads
     */
    #[Test]
    public function it_newsletter_campaigns_list_loads_without_js_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/newsletter/campaigns')
                ->waitFor('body', 10);

            $this->assertNoJsErrors($browser, 'newsletter campaigns list');
        });
    }

    /**
     * Test users list loads
     */
    #[Test]
    public function it_users_list_loads_without_js_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/users')
                ->waitFor('body', 10);

            $this->assertNoJsErrors($browser, 'users list');
        });
    }

    /**
     * Test roles list loads
     */
    #[Test]
    public function it_roles_list_loads_without_js_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/roles')
                ->waitFor('body', 10);

            $this->assertNoJsErrors($browser, 'roles list');
        });
    }

    /**
     * Test marketplace loads
     */
    #[Test]
    public function it_marketplace_loads_without_js_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/marketplace')
                ->waitFor('body', 10);

            $this->assertNoJsErrors($browser, 'marketplace');
        });
    }

    /**
     * Test settings pages load
     */
    #[Test]
    public function it_settings_pages_load_without_js_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            // Test general settings
            $browser->visit('/admin/settings')
                ->waitFor('body', 10);

            $this->assertNoJsErrors($browser, 'settings page');

            // Test SEO settings
            $browser->visit('/admin/settings/seo')
                ->waitFor('body', 10);

            $this->assertNoJsErrors($browser, 'SEO settings page');

            // Test email settings
            $browser->visit('/admin/settings/email')
                ->waitFor('body', 10);

            $this->assertNoJsErrors($browser, 'email settings page');
        });
    }

    /**
     * Test modules list loads
     */
    #[Test]
    public function it_modules_list_loads_without_js_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/modules')
                ->waitFor('body', 10);

            $this->assertNoJsErrors($browser, 'modules list');
        });

    }

    /**
     * Test templates list loads
     */
    #[Test]
    public function it_templates_list_loads_without_js_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/templates')
                ->waitFor('body', 10);

            $this->assertNoJsErrors($browser, 'templates list');
        });
    }

    /**
     * Test backup history loads
     */
    #[Test]
    public function it_backup_history_loads_without_js_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/backup-history')
                ->waitFor('body', 10);

            $this->assertNoJsErrors($browser, 'backup history');
        });
    }

    /**
     * Test error tracking loads
     */
    #[Test]
    public function it_error_tracking_loads_without_js_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/error-tracking')
                ->waitFor('body', 10);

            $this->assertNoJsErrors($browser, 'error tracking');
        });
    }

    /**
     * Test currency resources load
     */
    #[Test]
    public function it_currency_resources_load_without_js_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            // Currencies
            $browser->visit('/admin/currencies')
                ->waitFor('body', 10);
            $this->assertNoJsErrors($browser, 'currencies list');

            // Exchange rates
            $browser->visit('/admin/exchange-rates')
                ->waitFor('body', 10);
            $this->assertNoJsErrors($browser, 'exchange rates list');
        });
    }

    /**
     * Test product variant attributes load
     */
    #[Test]
    public function it_product_variant_attributes_load_without_js_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/product-variant-attributes')
                ->waitFor('body', 10);

            $this->assertNoJsErrors($browser, 'product variant attributes');
        });
    }

    /**
     * Test product inventory loads
     */
    #[Test]
    public function it_product_inventory_loads_without_js_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/product-inventory')
                ->waitFor('body', 10);

            $this->assertNoJsErrors($browser, 'product inventory');
        });
    }

    /**
     * Test product pricing rules load
     */
    #[Test]
    public function it_product_pricing_rules_load_without_js_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/product-pricing-rules')
                ->waitFor('body', 10);

            $this->assertNoJsErrors($browser, 'product pricing rules');
        });
    }

    /**
     * Test tags list loads
     */
    #[Test]
    public function it_tags_list_loads_without_js_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/tags')
                ->waitFor('body', 10);

            $this->assertNoJsErrors($browser, 'tags list');
        });
    }

    /**
     * Test AI chat list loads
     */
    #[Test]
    public function it_ai_chat_list_loads_without_js_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/ai/agent-chats')
                ->waitFor('body', 10);

            $this->assertNoJsErrors($browser, 'AI chat list');
        });
    }

    /**
     * Test mail templates list loads
     */
    #[Test]
    public function it_mail_templates_list_loads_without_js_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/mail-templates')
                ->waitFor('body', 10);

            $this->assertNoJsErrors($browser, 'mail templates list');
        });
    }

    /**
     * Test checkout resource loads
     */
    #[Test]
    public function it_checkout_resource_loads_without_js_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/checkouts')
                ->waitFor('body', 10);

            $this->assertNoJsErrors($browser, 'checkout resource');
        });
    }

    /**
     * Test FAQ resource loads
     */
    #[Test]
    public function it_faq_resource_loads_without_js_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/faqs')
                ->waitFor('body', 10);

            $this->assertNoJsErrors($browser, 'FAQ resource');
        });
    }

    /**
     * Test offers resource loads
     */
    #[Test]
    public function it_offers_resource_loads_without_js_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/offers')
                ->waitFor('body', 10);

            $this->assertNoJsErrors($browser, 'offers resource');
        });
    }

    /**
     * Test billing resources load
     */
    #[Test]
    public function it_billing_resources_load_without_js_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            // Subscriptions
            $browser->visit('/admin/subscriptions')
                ->waitFor('body', 10);
            $this->assertNoJsErrors($browser, 'subscriptions list');

            // Subscription plans
            $browser->visit('/admin/subscription-plans')
                ->waitFor('body', 10);
            $this->assertNoJsErrors($browser, 'subscription plans list');

            // Billing users
            $browser->visit('/admin/billing-users')
                ->waitFor('body', 10);
            $this->assertNoJsErrors($browser, 'billing users list');
        });
    }

    /**
     * Test module configuration loads
     */
    #[Test]
    public function it_module_configuration_loads_without_js_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/module-configurations')
                ->waitFor('body', 10);

            $this->assertNoJsErrors($browser, 'module configuration');
        });
    }

    /**
     * Test module dependencies load
     */
    #[Test]
    public function it_module_dependencies_load_without_js_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/module-dependencies')
                ->waitFor('body', 10);

            $this->assertNoJsErrors($browser, 'module dependencies');
        });
    }

    /**
     * Test workflow resources load
     */
    #[Test]
    public function it_workflow_resources_load_without_js_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/newsletter/workflows')
                ->waitFor('body', 10);

            $this->assertNoJsErrors($browser, 'newsletter workflows');
        });
    }

    /**
     * Test newsletter lists load
     */
    #[Test]
    public function it_newsletter_lists_load_without_js_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/newsletter/lists')
                ->waitFor('body', 10);

            $this->assertNoJsErrors($browser, 'newsletter lists');
        });
    }

    /**
     * Test newsletter subscribers load
     */
    #[Test]
    public function it_newsletter_subscribers_load_without_js_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/newsletter/subscribers')
                ->waitFor('body', 10);

            $this->assertNoJsErrors($browser, 'newsletter subscribers');
        });
    }

    /**
     * Test newsletter sender accounts load
     */
    #[Test]
    public function it_newsletter_sender_accounts_load_without_js_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/newsletter/sender-accounts')
                ->waitFor('body', 10);

            $this->assertNoJsErrors($browser, 'newsletter sender accounts');
        });
    }

    /**
     * Test newsletter templates load
     */
    #[Test]
    public function it_newsletter_templates_load_without_js_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/newsletter/templates')
                ->waitFor('body', 10);

            $this->assertNoJsErrors($browser, 'newsletter templates');
        });
    }

    /**
     * Test backup schedules load
     */
    #[Test]
    public function it_backup_schedules_load_without_js_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/backup-schedules')
                ->waitFor('body', 10);

            $this->assertNoJsErrors($browser, 'backup schedules');
        });
    }

    /**
     * Test permissions list loads
     */
    #[Test]
    public function it_permissions_list_loads_without_js_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/permissions')
                ->waitFor('body', 10);

            $this->assertNoJsErrors($browser, 'permissions list');
        });
    }

    /**
     * Test resource permissions list loads
     */
    #[Test]
    public function it_resource_permissions_list_loads_without_js_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser);
            
            $browser->visit('/admin/resource-permissions')
                ->waitFor('body', 10);

            $this->assertNoJsErrors($browser, 'resource permissions list');
        });
    }
}
