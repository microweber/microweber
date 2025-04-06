<?php

namespace Modules\Billing\Tests\Unit\Livewire;

use Livewire\Livewire;
use Modules\Billing\Http\Livewire\Admin\SubscriptionPlans;
use Modules\Billing\Models\SubscriptionPlan;
use Modules\Billing\Models\SubscriptionPlanGroup;
use Modules\Billing\Tests\Unit\BillingTestCase;
use PHPUnit\Framework\Attributes\Test;

class SubscriptionPlansTest extends BillingTestCase
{
    #[Test]
    public function it_can_render_subscription_plans_component()
    {
        $component = Livewire::test(SubscriptionPlans::class);
        $component->assertStatus(200);
    }

    #[Test]
    public function it_can_create_new_subscription_plan()
    {
        $group = SubscriptionPlanGroup::create(['name' => 'Test Group']);

        Livewire::test(SubscriptionPlans::class)
            ->set('name', 'Test Plan')
            ->set('price', 9.99)
            ->set('billing_interval', 'monthly')
            ->set('selectedGroupId', $group->id)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('subscription_plans', [
            'name' => 'Test Plan',
            'price' => 9.99,
            'subscription_plan_group_id' => $group->id
        ]);
    }

    #[Test]
    public function it_validates_required_fields()
    {
        Livewire::test(SubscriptionPlans::class)
            ->set('name', '')
            ->set('price', '')
            ->call('save')
            ->assertHasErrors([
                'name' => 'required',
                'price' => 'required'
            ]);
    }

    #[Test]
    public function it_validates_price_format()
    {
        Livewire::test(SubscriptionPlans::class)
            ->set('price', 'invalid')
            ->call('save')
            ->assertHasErrors(['price' => 'numeric']);
    }

    #[Test]
    public function it_can_delete_subscription_plan()
    {
        $plan = SubscriptionPlan::create([
            'name' => 'Plan to Delete',
            'price' => 19.99,
            'billing_interval' => 'monthly'
        ]);

        Livewire::test(SubscriptionPlans::class)
            ->call('deletePlan', $plan->id)
            ->assertDispatchedBrowserEvent('plan-deleted');

        $this->assertDatabaseMissing('subscription_plans', [
            'id' => $plan->id
        ]);
    }
}
