<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Two storefront defects found while exercising the demo shop.
 *
 * task-2026-06-06-cartdispatch — add-to-cart threw on every storefront.
 *   mw.reload_modules() (fired by mw.cart.after_modify on add/remove) called
 *   mw.top().app.dispatch('moduleReloaded', …), but on the PUBLIC frontend
 *   mw.top().app is undefined (the `app` only exists in the admin / live-edit
 *   window) → "Cannot read properties of undefined (reading 'dispatch')".
 *   Each call is now guarded; the dispatch is admin/live-edit-only anyway.
 *
 * task-2026-06-06-nopaymethod — checkout dead-ended with no message when no
 *   payment provider was configured: the Payment step rendered an empty,
 *   required "Payment Options*" radio the shopper could never satisfy. It now
 *   shows a clear empty-state explanation instead. The message text keeps its
 *   trailing period OUTSIDE __() (a translation key ending in '.' is treated as
 *   a namespace separator and returns an empty string — which rendered the box
 *   blank on the first attempt).
 */
class ShopCartAndCheckoutFixesContractTest extends TestCase
{
    #[Test]
    public function reload_module_dispatch_is_guarded_for_the_public_frontend(): void
    {
        foreach ([
            'packages/frontend-assets/resources/assets/core/ajax.js',
            'packages/frontend-assets/resources/assets/core/reload-module.js',
        ] as $rel) {
            $src = (string) file_get_contents(base_path($rel));

            // Every moduleReloaded dispatch must have a matching app-exists
            // guard. Counting both proves none was left bare (the guard's
            // `typeof …dispatch === 'function'` check sits one line above each
            // actual call).
            $dispatches = substr_count($src, ".app.dispatch('moduleReloaded'");
            $guards = substr_count($src, "typeof mw.top().app.dispatch === 'function'");
            $this->assertGreaterThan(0, $dispatches, "{$rel} should still dispatch moduleReloaded.");
            $this->assertSame(
                $dispatches,
                $guards,
                "Every moduleReloaded dispatch in {$rel} must sit behind an app-exists guard."
            );
        }
    }

    #[Test]
    public function built_frontend_bundle_carries_the_dispatch_guard(): void
    {
        $bundle = base_path('public/vendor/microweber-packages/frontend-assets/build/frontend.js');
        if (! is_file($bundle)) {
            $this->markTestSkipped('Built frontend bundle not present.');
        }
        $js = (string) file_get_contents($bundle);
        $this->assertStringContainsString('mw.top()&&mw.top().app&&', $js,
            'The served frontend bundle must contain the guarded moduleReloaded dispatch.');
    }

    #[Test]
    public function checkout_renders_empty_state_when_no_payment_methods(): void
    {
        $src = (string) file_get_contents(base_path('Modules/Checkout/Livewire/CheckoutWizard.php'));

        $this->assertMatchesRegularExpression(
            '/\$paymentSchema\s*=\s*empty\(\$options\)/',
            $src,
            'The payment step must branch on empty($options) to render an empty-state.'
        );
        $this->assertStringContainsString("Placeholder::make('no_payment_methods')", $src,
            'A no_payment_methods placeholder must be rendered when there are no payment options.');
        $this->assertStringContainsString('mw-checkout-empty-state', $src,
            'The empty-state must carry the .mw-checkout-empty-state chrome class.');
    }

    #[Test]
    public function empty_state_message_does_not_end_a_translation_key_with_a_period(): void
    {
        $src = (string) file_get_contents(base_path('Modules/Checkout/Livewire/CheckoutWizard.php'));
        // __('…right now.') with a trailing period returns '' from Laravel.
        $this->assertDoesNotMatchRegularExpression(
            '/__\([\'"][^\'"]*\.[\'"]\)/',
            $src,
            'No __() translation key in the checkout wizard may end with a period (Laravel returns empty).'
        );
        // The user-facing periods must still be present (appended in PHP,
        // outside __(), as the closing of each <p>).
        $this->assertStringContainsString("right now')) . '.</p>'", $src,
            'The empty-state sentence must append its period outside __().');
    }
}
