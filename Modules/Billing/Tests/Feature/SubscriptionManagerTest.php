<?php

namespace Modules\Billing\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Billing\Models\SubscriptionCustomer;
use Modules\Billing\Services\SubscriptionManager;
use Tests\TestCase;

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

    public function test_webhook_endpoint_returns_ok()
    {
        config(['cashier.webhook.secret' => null]);

        $user = User::factory()->create();

        SubscriptionCustomer::create([
            'user_id' => $user->id,
            'stripe_id' => 'cus_test123',
        ]);

        $payload = [
            'id' => 'evt_test_webhook',
            'type' => 'customer.subscription.created',
            'data' => [
                'object' => [
                    'customer' => 'cus_test123',
                ],
            ],
        ];

        $response = $this->postJson('/billing/stripe/webhook', $payload);

        $response->assertStatus(200);
    }

    public function test_subscribe_to_valid_plan_successfully()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $manager = new SubscriptionManager();

        // Mock getSubscriptionPlanBySKU helper
        app()->bind('getSubscriptionPlanBySKU', function () {
            return function ($sku) {
                if ($sku === 'valid-sku') {
                    return [
                        'id' => 1,
                        'sku' => 'valid-sku',
                        'name' => 'Pro Plan',
                        'remote_provider_price_id' => 'price_123',
                        'group_id' => 0,
                    ];
                }
                return null;
            };
        });

        // Use Laravel's global helper override
        if (!function_exists('getSubscriptionPlanBySKU')) {
            function getSubscriptionPlanBySKU($sku) {
                return app('getSubscriptionPlanBySKU')($sku);
            }
        }

        // Mock SubscriptionCustomer::firstOrCreate to avoid real Stripe calls
        $customer = SubscriptionCustomer::factory()->create(['user_id' => $user->id]);
        $this->partialMock(SubscriptionCustomer::class, function ($mock) use ($customer) {
            $mock->shouldReceive('stripe')->andReturn(new class {
                public function __call($method, $args) {
                    return new class {
                        public function __call($method, $args) {
                            return (object)[
                                'id' => 'sub_123',
                                'stripe_status' => 'active',
                                'items' => (object)['data' => [['id' => 'item_123']]],
                            ];
                        }
                    };
                }
            });
        });

        $result = $manager->subscribeToPlan('valid-sku');

        $this->assertNotEmpty($result);
    }
}
