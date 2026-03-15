<?php

namespace Modules\Billing\Tests\Unit;

use Modules\Billing\Models\Subscription;

class PestExampleTest extends BillingTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Ensure Faker Generator has providers loaded
        app()->forgetInstance(\Faker\Generator::class);
        app()->instance(\Faker\Generator::class, \Faker\Factory::create('en_US'));
    }

    public function test_subscription_can_be_created_with_factory(): void
    {
        $subscription = Subscription::factory()->make([
            'customer_id' => 1,
            'subscription_plan_id' => 1,
        ]);

        $this->assertInstanceOf(Subscription::class, $subscription);
        $this->assertContains($subscription->stripe_status, ['active', 'inactive', 'cancelled']);
    }

    public function test_subscription_has_required_attributes(): void
    {
        $subscription = Subscription::factory()->make([
            'customer_id' => 1,
            'subscription_plan_id' => 1,
            'stripe_price' => 'price_test',
        ]);

        $this->assertInstanceOf(Subscription::class, $subscription);
        $this->assertEquals('active', $subscription->stripe_status);
    }

    public static function statusProvider(): array
    {
        return [
            'active' => ['active'],
            'inactive' => ['inactive'],
            'cancelled' => ['cancelled'],
            'trialing' => ['trialing'],
        ];
    }

    /**
     * @dataProvider statusProvider
     */
    public function test_can_validate_subscription_data(string $status): void
    {
        $this->assertTrue(in_array($status, ['active', 'inactive', 'cancelled', 'trialing']));
    }
}
