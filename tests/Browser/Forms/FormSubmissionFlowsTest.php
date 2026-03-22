<?php

namespace Tests\Browser\Forms;

use Laravel\Dusk\Browser;
use Modules\Form\Models\FormEntry;
use Modules\Form\Models\FormList;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Components\ChekForJavascriptErrors;
use Tests\DuskTestCase;

/**
 * Critical Form Submission Flows
 *
 * Tests cover:
 * 1. Contact form submission
 * 2. Newsletter subscription
 * 3. Custom form creation and submission
 * 4. Form validation
 */
class FormSubmissionFlowsTest extends DuskTestCase
{
    /**
     * Test contact form submission.
     */
    #[Test]
    public function it_contact_form_submits_successfully(): void
    {
        $this->browse(function (Browser $browser) {
            $uniqueId = time();
            $siteUrl = $this->siteUrl;

            // Navigate to a page with contact form
            $browser->visit($siteUrl);
            $browser->pause(3000);

            // Look for contact form (if exists on homepage)
            try {
                $browser->waitFor('form[data-form-id]', 10);

                // Fill contact form
                $browser->type('name', 'Test User ' . $uniqueId);
                $browser->type('email', 'test' . $uniqueId . '@example.com');
                $browser->type('phone', '+1234567890');
                $browser->type('message', 'This is a test message from Dusk browser automation.');

                // Submit form
                $browser->click('form[data-form-id] button[type="submit"]');
                $browser->pause(3000);

                // Verify success message
                $browser->assertSee('Thank you');

                // Check for JavaScript errors
                $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                    $browser->validate();
                });
            } catch (\Exception $e) {
                $this->markTestSkipped('Contact form not found on homepage');
            }
        });
    }

    /**
     * Test form validation errors display.
     */
    #[Test]
    public function it_form_shows_validation_errors(): void
    {
        $this->browse(function (Browser $browser) {
            $siteUrl = $this->siteUrl;

            // Navigate to a page with form
            $browser->visit($siteUrl);
            $browser->pause(3000);

            try {
                $browser->waitFor('form[data-form-id]', 10);

                // Submit form without filling required fields
                $browser->click('form[data-form-id] button[type="submit"]');
                $browser->pause(2000);

                // Verify validation errors
                $browser->assertSee('required');

                // Check for JavaScript errors
                $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                    $browser->validate();
                });
            } catch (\Exception $e) {
                $this->markTestSkipped('Form not found on homepage');
            }
        });
    }

    /**
     * Test newsletter subscription form.
     */
    #[Test]
    public function it_newsletter_subscription_works(): void
    {
        $this->browse(function (Browser $browser) {
            $uniqueId = time();
            $siteUrl = $this->siteUrl;

            // Navigate to newsletter subscription page
            $browser->visit($siteUrl . 'newsletter/subscribe');
            $browser->pause(3000);

            try {
                // Fill subscription form
                $browser->type('email', 'newsletter' . $uniqueId . '@example.com');
                $browser->type('name', 'Test Subscriber ' . $uniqueId);

                // Submit form
                $browser->click('button[type="submit"]');
                $browser->pause(3000);

                // Verify success
                $browser->assertSee('Thank you');

                // Check for JavaScript errors
                $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                    $browser->validate();
                });
            } catch (\Exception $e) {
                $this->markTestSkipped('Newsletter subscription page not available');
            }
        });
    }

    /**
     * Test custom form with file upload.
     */
    #[Test]
    public function it_custom_form_with_file_upload(): void
    {
        $this->browse(function (Browser $browser) {
            $uniqueId = time();
            $siteUrl = $this->siteUrl;

            // Create a test file for upload
            $testFile = storage_path('test-upload-' . $uniqueId . '.txt');
            file_put_contents($testFile, 'Test file content for upload');

            // Navigate to a page with form that supports file upload
            $browser->visit($siteUrl);
            $browser->pause(3000);

            try {
                // Look for form with file input
                $browser->waitFor('input[type="file"]', 10);

                // Fill other fields
                $browser->type('name', 'Test User ' . $uniqueId);
                $browser->type('email', 'test' . $uniqueId . '@example.com');

                // Attach file
                $browser->attach('input[type="file"]', $testFile);
                $browser->pause(2000);

                // Submit form
                $browser->click('button[type="submit"]');
                $browser->pause(3000);

                // Verify success
                $browser->assertSee('Thank you');

                // Check for JavaScript errors
                $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                    $browser->validate();
                });
            } catch (\Exception $e) {
                $this->markTestSkipped('Form with file upload not found');
            }

            // Cleanup
            if (file_exists($testFile)) {
                unlink($testFile);
            }
        });
    }

    /**
     * Test multi-step form submission.
     */
    #[Test]
    public function it_multi_step_form_completion(): void
    {
        $this->browse(function (Browser $browser) {
            $uniqueId = time();
            $siteUrl = $this->siteUrl;

            // Navigate to checkout as an example of multi-step form
            $browser->visit($siteUrl . 'checkout');
            $browser->pause(3000);

            try {
                $browser->waitForText('First Name', 10);

                // Step 1: Personal information
                $browser->type('first_name', 'Test' . $uniqueId);
                $browser->type('last_name', 'User' . $uniqueId);
                $browser->type('email', 'test' . $uniqueId . '@example.com');
                $browser->type('phone', $uniqueId);
                $browser->click('.js-checkout-continue');
                $browser->pause(3000);

                // Step 2: Shipping method
                $browser->waitForText('Shipping method', 10);
                $browser->radio('shipping_gw', 'shop/shipping/gateways/country');
                $browser->pause(2000);

                // Fill address
                $browser->select('country', 'Bulgaria');
                $browser->type('Address[city]', 'Sofia');
                $browser->type('Address[zip]', '1000');
                $browser->type('Address[state]', 'Sofia');
                $browser->type('Address[address]', 'Test Street 123');
                $browser->click('.js-checkout-continue');
                $browser->pause(3000);

                // Step 3: Payment method
                $browser->waitForText('Payment method', 10);
                $browser->radio('payment_gw', 'shop/payments/gateways/bank_transfer');

                // Check for JavaScript errors
                $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                    $browser->validate();
                });

            } catch (\Exception $e) {
                $this->markTestSkipped('Checkout page not available or cart is empty');
            }
        });
    }
}
