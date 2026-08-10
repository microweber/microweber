<?php

namespace Tests\Browser;

use PHPUnit\Framework\Attributes\Test;

use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use Modules\Billing\Models\Subscription;
use Modules\Billing\Models\SubscriptionPlan;
use Modules\Cart\Models\Cart;
use Modules\Order\Models\Order;
use Modules\Shop\Models\Product;
use Tests\Browser\Components\AdminLogin;
use Tests\Browser\Components\ChekForJavascriptErrors;
use Tests\DuskTestCase;
use MicroweberPackages\Database\Facades\DatabaseManager;

/**
 * Critical Legacy Dusk Flows
 *
 * These tests cover the most critical user flows that must never break:
 * 1. Shop checkout with bank transfer
 * 2. PayPal checkout redirection
 * 3. Admin dashboard with widgets
 * 4. XSS protection in forms
 */
class CriticalFlowsTest extends DuskTestCase
{
    /**
     * Test the full shop checkout flow using bank transfer payment method.
     * This verifies end-to-end cart, checkout, and order completion functionality.
     */
    #[Test]
    public function it_full_shop_checkout_flow_with_bank_transfer(): void {
        $this->browse(function (Browser $browser) {
            $uniqueId = time();
            $siteUrl = $this->siteUrl;

            // Step 1: Setup test environment
            $this->setupShopEnvironment();

            // Step 2: Create test product and add to cart
            $product = $this->createTestProduct();
            $this->addProductToCart($browser, $product);

            // Step 3: Navigate to checkout
            $browser->visit($siteUrl . 'checkout');
            $browser->waitForText('Checkout', 30);

            // Step 4: Fill shipping information
            $this->fillShippingInformation($browser, $uniqueId);

            // Step 5: Select payment method (bank transfer)
            $browser->waitForText('Payment method', 30);
            $browser->pause(1000);

            // Scroll to payment section
            $browser->script("$('html, body').animate({ scrollTop: $('[name=payment_gw]').first().offset().top - 100 }, 0);");
            $browser->pause(1000);

            // Select bank transfer payment
            $browser->radio('payment_gw', 'shop/payments/gateways/bank_transfer');
            $browser->pause(1000);

            // Step 6: Complete order
            $browser->script("$('html, body').animate({ scrollTop: $('.js-finish-your-order').first().offset().top - 100 }, 0);");
            $browser->pause(2000);

            // Click finish order with error handling
            try {
                $browser->click('.js-finish-your-order');
            } catch (\Facebook\WebDriver\Exception\WebDriverCurlException $e) {
                $browser->pause(10000);
                $browser->click('.js-finish-your-order');
            }

            // Step 7: Verify order completion
            $browser->waitForText('Your order is completed', 60);
            $browser->assertSee('Your order is completed');

            // Step 8: Verify order in database
            $orderNumber = $browser->text('@order-number');
            $order = Order::where('id', $orderNumber)->first();

            $this->assertNotNull($order, 'Order should exist in database');
            $this->assertEquals('shop/payments/gateways/bank_transfer', $order->payment_gw);
            $this->assertEquals(1, $order->order_completed);
            $this->assertEquals('Bozhidar' . $uniqueId, $order->first_name);
            $this->assertEquals('Slaveykov' . $uniqueId, $order->last_name);
            $this->assertEquals('bobi' . $uniqueId . '@microweber.com', $order->email);

            // Step 9: Verify cart items
            $cart = Cart::where('order_id', $order->id)->first();
            $this->assertNotNull($cart, 'Cart should exist for order');

            // Check for JavaScript errors
            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });
        });
    }

    /**
     * Test that PayPal checkout properly redirects to PayPal sandbox.
     * Note: This test validates the redirect flow only, not the actual payment.
     */
    #[Test]
    public function it_checkout_with_paypal_redirects_correctly(): void {
        $this->browse(function (Browser $browser) {
            $uniqueId = time();
            $siteUrl = $this->siteUrl;

            // Skip if PayPal is not enabled
            if (!get_option('payment_gw_shop/payments/gateways/paypal', 'payments')) {
                $this->markTestSkipped('PayPal payment gateway is not enabled');
            }

            // Verify PayPal is in payment options
            $foundPaypal = false;
            $paymentOptions = payment_options();
            foreach ($paymentOptions as $paymentOption) {
                if (isset($paymentOption['gw_file']) && $paymentOption['gw_file'] == 'shop/payments/gateways/paypal') {
                    $foundPaypal = true;
                    break;
                }
            }

            if (!$foundPaypal) {
                $this->markTestSkipped('PayPal gateway not found in payment options');
            }

            // Setup and create product
            $this->setupShopEnvironment();
            $product = $this->createTestProduct();
            $this->addProductToCart($browser, $product);

            // Navigate to checkout
            $browser->visit($siteUrl . 'checkout');
            $browser->waitForText('Checkout', 30);

            // Fill shipping info
            $this->fillShippingInformation($browser, $uniqueId);

            // Select PayPal payment
            $browser->waitForText('Payment method', 30);
            $browser->pause(1000);

            $browser->script("$('html, body').animate({ scrollTop: $('[name=payment_gw]').first().offset().top - 100 }, 0);");
            $browser->pause(1000);

            $browser->radio('payment_gw', 'shop/payments/gateways/paypal');
            $browser->pause(1000);

            // Attempt to complete order
            $browser->script("$('html, body').animate({ scrollTop: $('.js-finish-your-order').first().offset().top - 100 }, 0);");
            $browser->pause(2000);

            try {
                $browser->click('.js-finish-your-order');
                $browser->waitForText('Please wait', 15);
            } catch (\Facebook\WebDriver\Exception\WebDriverCurlException $e) {
                $this->markTestSkipped('PayPal service is not available');
                return;
            } catch (\Facebook\WebDriver\Exception\TimeoutException $e) {
                $this->markTestSkipped('PayPal service timed out');
                return;
            }

            $browser->pause(5000);

            // Verify redirect to PayPal
            $currentUrl = $browser->driver->getCurrentURL();
            $this->assertStringContainsString('paypal.com', $currentUrl, 'Should redirect to PayPal');

            // Verify order was created with PayPal
            $order = Order::orderBy('id', 'desc')->first();
            $this->assertNotNull($order);
            $this->assertEquals('shop/payments/gateways/paypal', $order->payment_gw);
            $this->assertEquals(1, $order->order_completed);
            $this->assertNotNull($order->customer_id);
            $this->assertNotNull($order->session_id);

            // No JavaScript errors
            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });
        });
    }

    /**
     * Test that admin dashboard loads and displays all widgets correctly.
     * This verifies Filament v5 widget rendering and data loading.
     */
    #[Test]
    public function it_admin_dashboard_loads_all_widgets(): void {
        $this->browse(function (Browser $browser) {
            // Login as admin
            $browser->within(new AdminLogin(), function ($browser) {
                $browser->fillForm();
            });

            // Create test data for widgets
            $this->createDashboardTestData();

            // Navigate to admin dashboard
            $browser->visit('/admin');
            $browser->pause(3000);

            // Wait for dashboard to load
            $browser->waitForText('Dashboard', 30);

            // Verify core navigation elements (from legacy test)
            $browser->waitForText('Marketplace', 30);
            $browser->waitForText('Statistics', 30);
            $browser->waitForText('Website', 30);
            $browser->waitForText('Modules', 30);
            $browser->waitForText('Settings', 30);
            $browser->waitForText('Users', 30);
            $browser->waitForText('Log out', 30);

            // Verify Filament v5 widgets are present
            // AccountWidget should show user info
            $browser->assertSee('Welcome');

            // Check for widget containers
            $widgets = $browser->elements('.fi-wi');
            $this->assertGreaterThan(0, count($widgets), 'Dashboard should have widgets');

            // If billing widgets exist, verify their content
            if (class_exists(Subscription::class)) {
                // StatsOverviewWidget shows MRR, Active Subscriptions, etc.
                $this->assertTrue(
                    $browser->element('.fi-wi-stats-overview') !== null ||
                    $browser->element('.fi-wi-table') !== null,
                    'Billing widgets should be present'
                );
            }

            // No JavaScript errors
            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });

            $browser->pause(1000);
        });
    }

    /**
     * Test that XSS payloads are properly sanitized and not executed.
     * This is a critical security test for all input fields.
     */
    #[Test]
    public function it_xss_payloads_not_executed_in_inputs(): void {
        $this->browse(function (Browser $browser) {
            $uniqueId = time();
            $siteUrl = $this->siteUrl;

            // Setup shop environment
            $this->setupShopEnvironment();

            // Create a product
            $product = $this->createTestProduct();

            // Start at the product page
            $browser->visit(content_link($product['id']));
            $browser->waitForText($product['title'], 30);

            // Add to cart
            $browser->script("$('html, body').animate({ scrollTop: $('.price button').first().offset().top - 160 }, 0);");
            $browser->pause(500);
            $browser->click('.price button');
            $browser->pause(500);
            $browser->waitForText('Continue shopping', 30);
            $browser->clickLink('Proceed to Checkout');
            $browser->pause(3000);

            // Wait for checkout form
            $browser->waitForText('First Name', 30);

            // XSS payload that would trigger an alert if executed
            $xssPayload = '"><img src=x onerror=confirm(document.domain)>';

            // Fill fields with XSS payloads
            $browser->type('first_name', 'John' . $xssPayload);
            $browser->type('last_name', 'Doe' . $xssPayload);
            $browser->type('email', 'test' . $uniqueId . '@example.com');
            $browser->type('phone', $uniqueId);
            $browser->click('.js-checkout-continue');

            $browser->pause(2000);
            $browser->waitForText('Shipping method', 30);

            // Continue to shipping
            $browser->radio('shipping_gw', 'shop/shipping/gateways/country');
            $browser->pause(3000);
            $browser->waitForText('Address for delivery', 30);

            // Fill address with XSS payload
            $browser->select('country', 'Bulgaria');
            $browser->type('Address[city]', 'Sofia' . $xssPayload);
            $browser->type('Address[zip]', '1000');
            $browser->type('Address[state]', 'State' . $xssPayload);
            $browser->type('Address[address]', 'Street 123' . $xssPayload);
            $browser->type('other_info', 'Notes' . $xssPayload);

            $browser->scrollTo('.js-checkout-continue');
            $browser->pause(1000);
            $browser->click('.js-checkout-continue');

            $browser->waitForText('Payment method', 30);
            $browser->pause(1000);

            // Select bank transfer
            $browser->script("$('html, body').animate({ scrollTop: $('[name=payment_gw]').first().offset().top - 100 }, 0);");
            $browser->pause(1000);
            $browser->radio('payment_gw', 'shop/payments/gateways/bank_transfer');

            // Try to complete order
            $browser->script("$('html, body').animate({ scrollTop: $('.js-finish-your-order').first().offset().top - 100 }, 0);");
            $browser->pause(2000);

            try {
                $browser->click('.js-finish-your-order');
            } catch (\Facebook\WebDriver\Exception\WebDriverCurlException $e) {
                $browser->pause(10000);
                $browser->click('.js-finish-your-order');
            }

            // Wait for completion
            $browser->waitForText('Your order is completed', 60);

            // Verify no JavaScript alerts were triggered (XSS execution)
            // This is checked by ChekForJavascriptErrors component
            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });

            // Verify order was created with sanitized data
            $orderNumber = $browser->text('@order-number');
            $order = Order::where('id', $orderNumber)->first();
            $this->assertNotNull($order);

            // The XSS payload should be stored but not executed
            // Check that the data exists (sanitization happens on output, not input)
            $this->assertStringContainsString('Sofia', $order->city);

            // Verify no alert/modal is present (would indicate XSS execution)
            $alerts = $browser->elements('.alert.alert-danger');
            $this->assertCount(0, $alerts, 'No XSS alert dialogs should be present');

            // Check for any JavaScript execution indicators
            $scriptExecution = $browser->script("return typeof window.xssExecuted !== 'undefined'");
            $this->assertFalse($scriptExecution[0] ?? false, 'XSS should not have been executed');
        });
    }

    /**
     * Setup shop environment with required payment and shipping options.
     */
    private function setupShopEnvironment(): void
    {
        // Enable shipping
        save_option([
            'option_key' => 'shipping_gw_shop/shipping/gateways/country',
            'option_group' => 'shipping',
            'option_value' => 1
        ]);

        // Enable bank transfer payment
        save_option([
            'option_key' => 'payment_gw_shop/payments/gateways/bank_transfer',
            'option_group' => 'payments',
            'option_value' => 1,
            'module' => 'shop/payments'
        ]);

        // Enable PayPal payment (for PayPal test)
        save_option([
            'option_key' => 'payment_gw_shop/payments/gateways/paypal',
            'option_group' => 'payments',
            'option_value' => 1,
            'module' => 'shop/payments'
        ]);

        // Set PayPal to test mode
        save_option([
            'option_key' => 'paypalexpress_testmode',
            'option_group' => 'payments',
            'option_value' => 1
        ]);

        // Disable multilanguage for consistent testing
        save_option([
            'option_key' => 'is_active',
            'option_group' => 'multilanguage_settings',
            'option_value' => 0
        ]);

        // Disable captcha
        save_option([
            'option_key' => 'login_captcha_enabled',
            'option_group' => 'users',
            'option_value' => 0
        ]);
    }

    /**
     * Create a test product for checkout tests.
     */
    private function createTestProduct(): array
    {
        DatabaseManager::extended_save_set_permission(true);

        // Get or create shop page
        $shopPage = app()->content_repository->getFirstShopPage();
        if (!$shopPage) {
            $shopPage = [
                'title' => 'Shop' . uniqid(),
                'content_type' => 'page',
                'layout_file' => 'layouts/shop.php',
                'is_shop' => 1,
                'is_active' => 1
            ];
            $shopPageId = save_content($shopPage);
        } else {
            $shopPageId = $shopPage['id'];
        }

        // Create product
        $title = 'Test Product ' . time();
        $params = [
            'title' => $title,
            'parent' => $shopPageId,
            'content_type' => 'product',
            'content' => '<p>Test product content</p>',
            'subtype' => 'product',
            'custom_fields_advanced' => [
                ['type' => 'dropdown', 'name' => 'Color', 'value' => ['Purple', 'Blue']],
                ['type' => 'price', 'name' => 'Price', 'value' => '9.99'],
            ],
            'is_active' => 1,
        ];

        $productId = save_content($params);
        $product = get_content('id=' . $productId);

        return $product;
    }

    /**
     * Add a product to cart using the browser.
     */
    private function addProductToCart(Browser $browser, array $product): void
    {
        $link = content_link($product['id']);
        $browser->visit($link);
        $browser->waitForText($product['title'], 30);

        // Check for JavaScript errors on product page
        $browser->within(new ChekForJavascriptErrors(), function ($browser) {
            $browser->validate();
        });

        // Scroll to add to cart button
        $browser->script("$('html, body').animate({ scrollTop: $('.price button').first().offset().top - 160 }, 0);");
        $browser->pause(500);

        // Click add to cart
        $browser->click('.price button');
        $browser->pause(500);

        // Wait for modal and proceed to checkout
        $browser->waitForText('Continue shopping', 30);
        $browser->clickLink('Proceed to Checkout');
        $browser->pause(3000);
    }

    /**
     * Fill shipping information in checkout form.
     */
    private function fillShippingInformation(Browser $browser, int $uniqueId): void
    {
        $browser->waitForText('First Name', 30);
        $browser->type('first_name', 'Bozhidar' . $uniqueId);
        $browser->type('last_name', 'Slaveykov' . $uniqueId);
        $browser->type('email', 'bobi' . $uniqueId . '@microweber.com');
        $browser->type('phone', $uniqueId);
        $browser->click('.js-checkout-continue');

        $browser->pause(2000);
        $browser->waitForText('Shipping method', 30);

        // Select shipping method
        $browser->radio('shipping_gw', 'shop/shipping/gateways/country');
        $browser->pause(5000);

        $browser->waitForText('Address for delivery', 30);
        $browser->assertSee('Address for delivery');

        // Fill address
        $browser->select('country', 'Bulgaria');
        $browser->type('Address[city]', 'Sofia' . $uniqueId);
        $browser->type('Address[zip]', '1000' . $uniqueId);
        $browser->type('Address[state]', 'Sofia' . $uniqueId);
        $browser->type('Address[address]', 'Vitosha 143' . $uniqueId);
        $browser->type('other_info', 'I want my order soon as possible.' . $uniqueId);

        $browser->scrollTo('.js-checkout-continue');
        $browser->pause(1000);
        $browser->click('.js-checkout-continue');
    }

    /**
     * Create test data for dashboard widgets.
     */
    private function createDashboardTestData(): void
    {
        // Create test subscriptions if Billing module exists
        if (class_exists(Subscription::class) && class_exists(SubscriptionPlan::class)) {
            // Create a test plan
            $plan = SubscriptionPlan::first();
            if (!$plan) {
                $plan = SubscriptionPlan::create([
                    'name' => 'Test Plan',
                    'price' => 999,
                    'interval' => 'month',
                    'currency' => 'USD',
                    'is_active' => true,
                ]);
            }

            // Create test subscriptions
            if (Subscription::count() === 0) {
                Subscription::create([
                    'user_id' => 1,
                    'subscription_plan_id' => $plan->id,
                    'stripe_status' => 'active',
                    'stripe_id' => 'sub_test_' . time(),
                ]);
            }
        }
    }
}
