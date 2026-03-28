<?php

namespace Modules\Checkout\Tests\Unit\Livewire;

use Livewire\Livewire;
use Modules\Checkout\Livewire\CheckoutWizard;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class CheckoutWizardTest extends TestCase
{
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel('checkout');
    }

    #[Test]
    public function it_checkout_wizard_component_loads(): void
    {
        Livewire::test(CheckoutWizard::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_has_five_wizard_steps(): void
    {
        $component = Livewire::test(CheckoutWizard::class);

        $component->assertSuccessful();

        // Check wizard has 5 steps
        $component->assertSee('Cart Review');
        $component->assertSee('Contact Information');
        $component->assertSee('Shipping');
        $component->assertSee('Payment');
        $component->assertSee('Review & Confirm');
    }

    #[Test]
    public function it_displays_cart_items_in_first_step(): void
    {
        // Create a test product and add to cart
        $product = [
            'title' => 'Test Product',
            'content_type' => 'product',
            'subtype' => 'product',
            'is_active' => 1,
        ];

        $productId = save_content($product);

        // Add to cart
        $cartResult = update_cart([
            'content_id' => $productId,
            'qty' => 1,
        ]);

        $this->assertTrue(isset($cartResult['success']));

        // Check wizard displays cart items
        $component = Livewire::test(CheckoutWizard::class);
        $component->assertSuccessful();

        // Should see cart review section
        $component->assertSee('Your Cart');
    }

    #[Test]
    public function it_prefills_user_data_for_logged_in_users(): void
    {
        // Create and login a user
        $userData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '+1234567890',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $user = app()->user_manager->register($userData);
        app()->user_manager->login('john@example.com', 'password123');

        $this->assertTrue(is_logged());

        // Check wizard pre-fills user data
        $component = Livewire::test(CheckoutWizard::class);
        $component->assertSuccessful();

        // Navigate to contact step
        $component->set('step', 2);

        // Check that form has user data
        $component->assertSet('data.first_name', 'John');
        $component->assertSet('data.last_name', 'Doe');
        $component->assertSet('data.email', 'john@example.com');
        $component->assertSet('data.phone', '+1234567890');
    }

    #[Test]
    public function it_shows_guest_checkout_options_for_non_logged_in_users(): void
    {
        $this->assertFalse(is_logged());

        $component = Livewire::test(CheckoutWizard::class);
        $component->assertSuccessful();

        // Navigate to contact step
        $component->set('step', 2);

        // Guest should see account creation option
        $component->assertSee('Account Options');
        $component->assertSee('Create an account');
    }

    #[Test]
    public function it_displays_shipping_methods_in_third_step(): void
    {
        $component = Livewire::test(CheckoutWizard::class);
        $component->assertSuccessful();

        // Navigate to shipping step
        $component->set('step', 3);

        // Should see shipping method section
        $component->assertSee('Shipping Method');
        $component->assertSee('Choose how you want your order delivered');
    }

    #[Test]
    public function it_displays_payment_methods_in_fourth_step(): void
    {
        $component = Livewire::test(CheckoutWizard::class);
        $component->assertSuccessful();

        // Navigate to payment step
        $component->set('step', 4);

        // Should see payment method section
        $component->assertSee('Payment Method');
        $component->assertSee('Select how you want to pay');
    }

    #[Test]
    public function it_shows_order_review_in_final_step(): void
    {
        $component = Livewire::test(CheckoutWizard::class);
        $component->assertSuccessful();

        // Navigate to review step
        $component->set('step', 5);

        // Should see review sections
        $component->assertSee('Order Review');
        $component->assertSee('Place Order');
    }

    #[Test]
    public function it_validates_required_fields_in_contact_step(): void
    {
        $component = Livewire::test(CheckoutWizard::class);
        $component->assertSuccessful();

        // Navigate to contact step
        $component->set('step', 2);

        // Try to submit empty form
        $component->set('data.first_name', '');
        $component->set('data.last_name', '');
        $component->set('data.email', '');
        $component->set('data.phone', '');

        // Validation should fail
        $component->assertHasErrors(['data.first_name', 'data.last_name', 'data.email', 'data.phone']);
    }

    #[Test]
    public function it_validates_email_format(): void
    {
        $component = Livewire::test(CheckoutWizard::class);
        $component->assertSuccessful();

        // Navigate to contact step
        $component->set('step', 2);

        // Set invalid email
        $component->set('data.email', 'invalid-email');

        // Should have email validation error
        $component->assertHasErrors(['data.email']);
    }

    #[Test]
    public function it_saves_checkout_data_to_session(): void
    {
        $component = Livewire::test(CheckoutWizard::class);
        $component->assertSuccessful();

        // Fill contact information
        $component->set('data.first_name', 'Jane');
        $component->set('data.last_name', 'Smith');
        $component->set('data.email', 'jane@example.com');
        $component->set('data.phone', '+9876543210');

        // Trigger save
        $component->call('saveStepData');

        // Verify data is saved to session
        $this->assertEquals('Jane', checkout_get_user_info('first_name'));
        $this->assertEquals('Smith', checkout_get_user_info('last_name'));
        $this->assertEquals('jane@example.com', checkout_get_user_info('email'));
        $this->assertEquals('+9876543210', checkout_get_user_info('phone'));
    }

    #[Test]
    public function it_redirects_when_cart_is_empty(): void
    {
        // Ensure cart is empty
        app()->cart_manager->empty_cart();

        $component = Livewire::test(CheckoutWizard::class);

        // Should redirect to home page when cart is empty
        $component->assertRedirect(site_url());
    }

    #[Test]
    public function it_allows_navigation_between_steps(): void
    {
        $component = Livewire::test(CheckoutWizard::class);
        $component->assertSuccessful();

        // Navigate to different steps
        $component->set('step', 1);
        $this->assertEquals(1, $component->get('step'));

        $component->set('step', 2);
        $this->assertEquals(2, $component->get('step'));

        $component->set('step', 3);
        $this->assertEquals(3, $component->get('step'));

        $component->set('step', 4);
        $this->assertEquals(4, $component->get('step'));

        $component->set('step', 5);
        $this->assertEquals(5, $component->get('step'));
    }

    #[Test]
    public function it_displays_order_summary_with_shipping_costs(): void
    {
        // Add product to cart
        $product = [
            'title' => 'Test Product',
            'content_type' => 'product',
            'subtype' => 'product',
            'is_active' => 1,
            'price' => 100,
        ];

        $productId = save_content($product);

        update_cart([
            'content_id' => $productId,
            'qty' => 1,
        ]);

        $component = Livewire::test(CheckoutWizard::class);
        $component->assertSuccessful();

        // Navigate to payment step where order summary is shown
        $component->set('step', 4);

        // Should see order summary section
        $component->assertSee('Order Summary');
        $component->assertSee('Subtotal');
    }

    #[Test]
    public function it_handles_coupon_application(): void
    {
        $component = Livewire::test(CheckoutWizard::class);
        $component->assertSuccessful();

        // Navigate to payment step
        $component->set('step', 4);

        // Check that coupon functionality exists
        // This depends on the cart_manager having coupon support
        $this->assertTrue(function_exists('coupon_get_applied'));
        $this->assertTrue(function_exists('cart_get_discount'));
    }

    #[Test]
    public function it_requires_terms_acceptance_when_enabled(): void
    {
        // Enable terms requirement
        save_option('shop_require_terms', 1, 'website');

        $component = Livewire::test(CheckoutWizard::class);
        $component->assertSuccessful();

        // Navigate to payment step
        $component->set('step', 4);

        // Should see terms checkbox
        $component->assertSee('Terms and Conditions');
        $component->assertSee('I agree to the terms and conditions');
    }

    protected function tearDown(): void
    {
        // Clear checkout session
        \Illuminate\Support\Facades\Session::forget('checkout');

        parent::tearDown();
    }
}
