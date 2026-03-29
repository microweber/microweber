<?php

namespace Modules\Billing\Tests\Feature;

use PHPUnit\Framework\Attributes\Test;

use App\Models\User;
use Modules\Billing\Models\SubscriptionCustomer;
use Modules\Billing\Services\SubscriptionManager;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class SubscriptionManagerTest extends TestCase
{

    #[Test]

    public function it_get_subscription_customer_returns_error_when_not_authenticated(): void {
        $manager = new SubscriptionManager();
        $result = $manager->getSubscriptionCustomer();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('error', $result);
        $this->assertEquals('User not found', $result['error']);
    }

    #[Test]

    public function it_get_subscription_customer_creates_customer_for_authenticated_user(): void {
        $user = User::factory()->create();
        $this->actingAs($user);

        $manager = new SubscriptionManager();
        $customer = $manager->getSubscriptionCustomer();

        $this->assertInstanceOf(SubscriptionCustomer::class, $customer);
        $this->assertEquals($user->id, $customer->user_id);
    }

    #[Test]

    public function it_subscribe_to_plan_returns_error_for_invalid_sku(): void {
        $user = User::factory()->create();
        $this->actingAs($user);

        $manager = new SubscriptionManager();
        $result = $manager->subscribeToPlan('nonexistent-sku');

        $this->assertIsArray($result);
        $this->assertArrayHasKey('error', $result);
        $this->assertEquals('Plan not found', $result['error']);
    }


}
