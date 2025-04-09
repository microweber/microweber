<?php

namespace Modules\Billing\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Billing\Models\SubscriptionCustomer;
use Modules\Billing\Services\SubscriptionManager;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class SubscriptionManagerTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_subscription_customer_returns_error_when_not_authenticated()
    {
        $manager = new SubscriptionManager();
        $result = $manager->getSubscriptionCustomer();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('error', $result);
        $this->assertEquals('User not found', $result['error']);
    }

    public function test_get_subscription_customer_creates_customer_for_authenticated_user()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $manager = new SubscriptionManager();
        $customer = $manager->getSubscriptionCustomer();

        $this->assertInstanceOf(SubscriptionCustomer::class, $customer);
        $this->assertEquals($user->id, $customer->user_id);
    }

    public function test_subscribe_to_plan_returns_error_for_invalid_sku()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $manager = new SubscriptionManager();
        $result = $manager->subscribeToPlan('nonexistent-sku');

        $this->assertIsArray($result);
        $this->assertArrayHasKey('error', $result);
        $this->assertEquals('Plan not found', $result['error']);
    }


}
