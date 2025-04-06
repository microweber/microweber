<?php

namespace Modules\Billing\Tests\Unit\Filament;

use Modules\Billing\Filament\Resources\SubscriptionPlanResource;
use Modules\Billing\Models\SubscriptionPlan;
use Modules\Billing\Models\SubscriptionPlanGroup;
use Modules\Billing\Tests\Unit\BillingTestCase;
use PHPUnit\Framework\Attributes\Test;

class SubscriptionPlanResourceTest extends BillingTestCase
{
    #[Test]
    public function it_can_list_subscription_plans()
    {
        $group = SubscriptionPlanGroup::factory()->create();
        $plans = SubscriptionPlan::factory()
            ->count(3)
            ->create(['subscription_plan_group_id' => $group->id]);

        $this->get(SubscriptionPlanResource::getUrl('index'))
            ->assertSuccessful()
            ->assertSee($plans[0]->name)
            ->assertSee($group->name);
    }

    #[Test]
    public function it_can_render_create_page()
    {
        $this->get(SubscriptionPlanResource::getUrl('create'))
            ->assertSuccessful();
    }

    #[Test]
    public function it_can_create_subscription_plan()
    {
        $group = SubscriptionPlanGroup::factory()->create();
        $data = SubscriptionPlan::factory()
            ->make(['subscription_plan_group_id' => $group->id])
            ->toArray();

        $this->post(SubscriptionPlanResource::getUrl('create'), $data)
            ->assertRedirect(SubscriptionPlanResource::getUrl('index'));

        $this->assertDatabaseHas('subscription_plans', [
            'name' => $data['name'],
            'subscription_plan_group_id' => $group->id
        ]);
    }

    #[Test]
    public function it_can_render_edit_page()
    {
        $plan = SubscriptionPlan::factory()->create();

        $this->get(SubscriptionPlanResource::getUrl('edit', [
            'record' => $plan
        ]))->assertSuccessful()
            ->assertSee($plan->name);
    }

    #[Test]
    public function it_can_update_subscription_plan()
    {
        $plan = SubscriptionPlan::factory()->create();
        $newData = ['name' => 'Updated Plan', 'price' => 49.99];

        $this->put(
            SubscriptionPlanResource::getUrl('edit', ['record' => $plan]),
            $newData
        )->assertRedirect(SubscriptionPlanResource::getUrl('index'));

        $this->assertDatabaseHas('subscription_plans', [
            'id' => $plan->id,
            'name' => 'Updated Plan'
        ]);
    }

    #[Test]
    public function it_validates_required_fields()
    {
        $response = $this->post(
            SubscriptionPlanResource::getUrl('create'),
            ['name' => '']
        );

        $response->assertSessionHasErrors(['name', 'price']);
    }
}
