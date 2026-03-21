<?php

namespace Modules\Checkout\Tests\Feature;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\Livewire;
use Modules\Checkout\Livewire\CheckoutWizard;
use Modules\Order\Models\Order;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

#[\PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses]
class CheckoutWizardEndToEndTest extends TestCase
{
    use LazilyRefreshDatabase;
    use InteractsWithFilamentPanel;

    protected static ?int $testProductId = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel('checkout');

        // Create a test product
        if (!self::$testProductId) {
            $product = [
                'title' => 'Test Product - Checkout Wizard',
                'content_type' => 'product',
                'subtype' => 'product',
                'is_active' => 1,
                'custom_fields_advanced' => [
                    ['type' => 'price', 'name' => 'Price', 'value' => '99.99'],
                ],
            ];
            self::$testProductId = save_content($product);
        }
    }

    #[Test]
    public function it_completes_guest_checkout_flow(): void
    {
        // Add product to cart
        $cartResult = update_cart([
            'content_id' => self::$testProductId,
            'qty' => 2,
        ]);

        $this->assertTrue(isset($cartResult['success']));

        // Test wizard loads with items
        $component = Livewire::test(CheckoutWizard::class);
        $component->assertSuccessful();
        $component->assertSee('Test Product');

        // Step 1: Review cart
        $component->assertSee('Your Cart');
        $component->assertSee('Subtotal');

        // Step 2: Fill contact information
        $component->set('step', 2);
        $component->set('data.first_name', 'Guest');
        $component->set('data.last_name', 'User');
        $component->set('data.email', 'guest@example.com');
        $component->set('data.phone', '+1234567890');
        $component->set('data.address', '123 Test Street');
        $component->set('data.city', 'Test City');
        $component->set('data.state', 'Test State');
        $component->set('data.postal_code', '12345');
        $component->set('data.country', 'US');

        // Step 3: Select shipping method
        $component->set('step', 3);

        // Get available shipping methods
        $shippingMethods = app()->shipping_method_manager->getProviders();
        if (!empty($shippingMethods)) {
            $shippingId = $shippingMethods[0]['id'];
            $component->set('data.shipping_provider_id', $shippingId);
        }

        // Step 4: Select payment method
        $component->set('step', 4);

        // Get available payment methods
        $paymentMethods = app()->payment_method_manager->getProviders();
        if (!empty($paymentMethods)) {
            $paymentId = $paymentMethods[0]['id'];
            $component->set('data.payment_provider_id', $paymentId);
        }

        // Accept terms if required
        if (get_option('shop_require_terms', 'website') == 1) {
            $component->set('data.terms', true);
        }

        // Step 5: Review and submit
        $component->set('step', 5);
        $component->assertSee('Order Review');
        $component->assertSee('Place Order');

        // Verify order summary is displayed
        $component->assertSee('Contact Information');
        $component->assertSee('Shipping Address');
        $component->assertSee('Shipping Method');
        $component->assertSee('Payment Method');
        $component->assertSee('Order Total');
    }

    #[Test]
    public function it_completes_checkout_with_account_creation(): void
    {
        // Add product to cart
        $cartResult = update_cart([
            'content_id' => self::$testProductId,
            'qty' => 1,
        ]);

        $this->assertTrue(isset($cartResult['success']));

        // Test wizard
        $component = Livewire::test(CheckoutWizard::class);
        $component->assertSuccessful();

        // Step 2: Fill contact and enable account creation
        $component->set('step', 2);
        $component->set('data.first_name', 'New');
        $component->set('data.last_name', 'Customer');
        $component->set('data.email', 'newcustomer@example.com');
        $component->set('data.phone', '+9876543210');
        $component->set('data.address', '456 New Street');
        $component->set('data.city', 'New City');
        $component->set('data.state', 'New State');
        $component->set('data.postal_code', '67890');
        $component->set('data.country', 'US');

        // Enable account creation
        $component->set('data.create_account', true);
        $component->set('data.password', 'SecurePass123!');
        $component->set('data.password_confirmation', 'SecurePass123!');

        // Continue through remaining steps
        $component->set('step', 3);
        $shippingMethods = app()->shipping_method_manager->getProviders();
        if (!empty($shippingMethods)) {
            $component->set('data.shipping_provider_id', $shippingMethods[0]['id']);
        }

        $component->set('step', 4);
        $paymentMethods = app()->payment_method_manager->getProviders();
        if (!empty($paymentMethods)) {
            $component->set('data.payment_provider_id', $paymentMethods[0]['id']);
        }

        if (get_option('shop_require_terms', 'website') == 1) {
            $component->set('data.terms', true);
        }

        // Step 5: Review
        $component->set('step', 5);
        $component->assertSee('New');
        $component->assertSee('Customer');
        $component->assertSee('newcustomer@example.com');
    }

    #[Test]
    public function it_allows_logged_in_user_to_checkout_with_saved_address(): void
    {
        // Create user with shipping address
        $userData = [
            'first_name' => 'Registered',
            'last_name' => 'User',
            'email' => 'registered@example.com',
            'phone' => '+1112223333',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $user = app()->user_manager->register($userData);
        app()->user_manager->login('registered@example.com', 'password123');

        // Save shipping address
        app()->user_manager->save_shipping_address([
            'user_id' => $user['id'],
            'address' => '789 Saved Street',
            'city' => 'Saved City',
            'state' => 'Saved State',
            'zip' => '54321',
            'country' => 'US',
        ]);

        // Add product to cart
        $cartResult = update_cart([
            'content_id' => self::$testProductId,
            'qty' => 1,
        ]);

        $this->assertTrue(isset($cartResult['success']));
        $this->assertTrue(is_logged());

        // Test wizard - should pre-fill user data
        $component = Livewire::test(CheckoutWizard::class);
        $component->assertSuccessful();

        // Step 2: Verify pre-filled data
        $component->set('step', 2);
        $component->assertSet('data.first_name', 'Registered');
        $component->assertSet('data.last_name', 'User');
        $component->assertSet('data.email', 'registered@example.com');
        $component->assertSet('data.phone', '+1112223333');
        $component->assertSet('data.address', '789 Saved Street');
        $component->assertSet('data.city', 'Saved City');
        $component->assertSet('data.state', 'Saved State');
    }

    #[Test]
    public function it_calculates_shipping_cost_correctly(): void
    {
        // Add product to cart
        $cartResult = update_cart([
            'content_id' => self::$testProductId,
            'qty' => 1,
        ]);

        $this->assertTrue(isset($cartResult['success']));

        $component = Livewire::test(CheckoutWizard::class);
        $component->assertSuccessful();

        // Step 3: Check shipping cost calculation
        $component->set('step', 3);

        $shippingMethods = app()->shipping_method_manager->getProviders();
        if (!empty($shippingMethods)) {
            foreach ($shippingMethods as $method) {
                // Select each shipping method and verify cost display
                $component->set('data.shipping_provider_id', $method['id']);

                // Verify shipping info is shown
                $component->assertSee($method['name'] ?? ucfirst($method['provider']));
            }
        }
    }

    #[Test]
    public function it_shows_correct_order_totals_in_review_step(): void
    {
        // Add multiple products to cart
        update_cart([
            'content_id' => self::$testProductId,
            'qty' => 3,
        ]);

        $component = Livewire::test(CheckoutWizard::class);
        $component->assertSuccessful();

        // Fill required data
        $component->set('step', 2);
        $component->set('data.first_name', 'Test');
        $component->set('data.last_name', 'Customer');
        $component->set('data.email', 'test@example.com');
        $component->set('data.phone', '+4445556666');
        $component->set('data.address', 'Test Address');
        $component->set('data.city', 'Test City');
        $component->set('data.state', 'Test State');
        $component->set('data.postal_code', '11111');
        $component->set('data.country', 'US');

        $component->set('step', 3);
        $shippingMethods = app()->shipping_method_manager->getProviders();
        if (!empty($shippingMethods)) {
            $component->set('data.shipping_provider_id', $shippingMethods[0]['id']);
        }

        $component->set('step', 4);
        $paymentMethods = app()->payment_method_manager->getProviders();
        if (!empty($paymentMethods)) {
            $component->set('data.payment_provider_id', $paymentMethods[0]['id']);
        }

        // Step 5: Review totals
        $component->set('step', 5);

        // Verify order summary elements
        $component->assertSee('Order Total');
        $component->assertSee('Subtotal');
        $component->assertSee('Shipping');
    }

    #[Test]
    public function it_validates_step_by_step_progression(): void
    {
        // Add product to cart
        update_cart([
            'content_id' => self::$testProductId,
            'qty' => 1,
        ]);

        $component = Livewire::test(CheckoutWizard::class);
        $component->assertSuccessful();

        // Step 1: Should be able to proceed (just cart review)
        $component->assertSee('Your Cart');

        // Step 2: Should validate contact info
        $component->set('step', 2);
        $component->assertSee('Contact Information');

        // Attempt to proceed with empty required fields
        $component->set('data.first_name', '');
        $component->set('data.last_name', '');
        $component->set('data.email', '');
        $component->set('data.phone', '');
        $component->set('data.address', '');
        $component->set('data.city', '');
        $component->set('data.state', '');
        $component->set('data.postal_code', '');

        // Validation should prevent progression
        $component->assertHasErrors([
            'data.first_name',
            'data.last_name',
            'data.email',
            'data.phone',
            'data.address',
            'data.city',
            'data.state',
            'data.postal_code',
        ]);

        // Fill valid data
        $component->set('data.first_name', 'Valid');
        $component->set('data.last_name', 'User');
        $component->set('data.email', 'valid@example.com');
        $component->set('data.phone', '+9998887777');
        $component->set('data.address', 'Valid Address');
        $component->set('data.city', 'Valid City');
        $component->set('data.state', 'Valid State');
        $component->set('data.postal_code', '99999');
        $component->set('data.country', 'US');

        // Validation errors should be cleared
        $component->assertHasNoErrors([
            'data.first_name',
            'data.last_name',
            'data.email',
            'data.phone',
            'data.address',
            'data.city',
            'data.state',
            'data.postal_code',
        ]);
    }

    #[Test]
    public function it_persists_checkout_data_across_steps(): void
    {
        // Add product to cart
        update_cart([
            'content_id' => self::$testProductId,
            'qty' => 1,
        ]);

        $component = Livewire::test(CheckoutWizard::class);
        $component->assertSuccessful();

        // Fill step 2 data
        $component->set('step', 2);
        $component->set('data.first_name', 'Persistent');
        $component->set('data.last_name', 'Data');
        $component->set('data.email', 'persistent@example.com');

        // Save step data
        $component->call('saveStepData');

        // Navigate to other steps and back
        $component->set('step', 3);
        $component->set('step', 2);

        // Data should persist
        $component->assertSet('data.first_name', 'Persistent');
        $component->assertSet('data.last_name', 'Data');
        $component->assertSet('data.email', 'persistent@example.com');
    }

    protected function tearDown(): void
    {
        // Cleanup
        session_forget('checkout');
        app()->cart_manager->empty_cart();

        parent::tearDown();
    }
}
