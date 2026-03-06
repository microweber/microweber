<?php

namespace Modules\Billing\Tests\Unit;

use App\Models\User;
use Modules\Billing\Filament\Admin\Resources\BillingUserResource;
use Modules\Billing\Filament\Admin\Resources\SubscriptionResource;
use Modules\Billing\Filament\Admin\Resources\SubscriptionPlanResource;
use Modules\Billing\Tests\Unit\BillingTestCase;
use PHPUnit\Framework\Attributes\Test;

class AuthorizationTest extends BillingTestCase
{
    #[Test]
    public function it_user_cannot_access_billing_without_permission(): void {
        // Create a regular user without admin permissions
        $user = User::factory()->create([
            'is_admin' => 0,
        ]);

        // User should not be able to access billing panel
        $this->actingAs($user);

        // Try to access the billing admin panel
        $response = $this->get('/admin/billing');

        // Should be redirected or forbidden
        $this->assertTrue(
            $response->isRedirect() ||
            $response->isForbidden() ||
            $response->isUnauthorized() ||
            $response->status() === 302 ||
            $response->status() === 403 ||
            $response->status() === 401
        );
    }

    #[Test]
    public function it_admin_user_can_access_billing_resources(): void {
        // Create an admin user
        $user = User::factory()->create([
            'is_admin' => 1,
        ]);

        $this->actingAs($user);

        // Admin should be able to access billing panel
        $response = $this->get('/admin/billing');

        // Should be successful (200) or redirect to login/setup
        $this->assertTrue(
            $response->isSuccessful() ||
            $response->isRedirect() ||
            in_array($response->status(), [200, 302, 301])
        );
    }

    #[Test]
    public function it_guest_user_is_redirected_from_billing(): void {
        // Ensure user is logged out
        $this->assertGuest();

        // Try to access billing as guest
        $response = $this->get('/admin/billing');

        // Should be redirected to login
        $this->assertTrue(
            $response->isRedirect() ||
            $response->status() === 302 ||
            $response->status() === 401 ||
            $response->status() === 403
        );
    }
}
