<?php

namespace Modules\Billing\Tests\Unit\Livewire;

use Livewire\Livewire;
use Modules\Billing\Http\Livewire\Admin\SubscriptionPlans;
use Modules\Billing\Models\SubscriptionPlan;
use Modules\Billing\Models\SubscriptionPlanGroup;
use Modules\Billing\Tests\Unit\BillingTestCase;

class SubscriptionPlansTest extends BillingTestCase
{
    /** @test */
    public function it_can_render_subscription_plans_component()
    {
        $component = Livewire::test(SubscriptionPlans::class);
        $component->assertStatus(200);
    }

    /** @test */
    public function it_can_create_new_subscription_plan()
    {
        $group = SubscriptionPlanGroup::create(['name' => 'Test Group']);
        
        Livewire::test(SubscriptionPlans::class)
            ->set('name', 'Test Plan')
            ->set('price', 9.99)
            ->set('billing_cycle', 'monthly')
            ->set('selectedGroupId', $group->id)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('subscription_plans', [
            'name' => 'Test Plan',
            'price' => 9.99,
            'subscription_plan_group_id' => $group->id
        ]);
    }

    /** @test */
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

    /** @test */
    public function it_validates_price_format()
    {
        Livewire::test(SubscriptionPlans::class)
            ->set('price', 'invalid')
            ->call('save')
            ->assertHasErrors(['price' => 'numeric']);
    }

    /** @test */
    public function it_can_delete_subscription_plan()
    {
        $plan = SubscriptionPlan::create([
            'name' => 'Plan to Delete',
            'price' => 19.99,
            'billing_cycle' => 'monthly'
        ]);

        Livewire::test(SubscriptionPlans::class)
            ->call('deletePlan', $plan->id)
            ->assertDispatchedBrowserEvent('plan-deleted');

        $this->assertDatabaseMissing('subscription_plans', [
            'id' => $plan->id
        ]);
    }
}