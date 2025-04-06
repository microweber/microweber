<?php

namespace Modules\Billing\Tests\Unit\Filament;

use Filament\Facades\Filament;
use Livewire\Livewire;
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
        $this->loginAsAdmin();
        $group = SubscriptionPlanGroup::factory()->create();
        $plans = SubscriptionPlan::factory()
            ->count(3)
            ->create(['subscription_plan_group_id' => $group->id]);

        $this->get(SubscriptionPlanResource::getUrl('index', [], false, 'admin-billing'))
            ->assertSuccessful()
            ->assertSee($plans[0]->name)
            ->assertSee($group->name);
    }

    #[Test]
    public function it_can_render_create_page()
    {
        $this->loginAsAdmin();
        $this->get(SubscriptionPlanResource::getUrl('create', [], false, 'admin-billing'))
            ->assertSuccessful();
    }

    #[Test]
    public function it_can_create_subscription_plan()
    {
        $this->loginAsAdmin();
        $group = SubscriptionPlanGroup::factory()->create();
        $planData = SubscriptionPlan::factory()
            ->make(['subscription_plan_group_id' => $group->id]);

        $group->save();
        $planData->save();

        Filament::setCurrentPanel(
            Filament::getPanel('admin-billing'),
        );

        Livewire::test(\Modules\Billing\Filament\Resources\SubscriptionPlanResource\Pages\CreateSubscriptionPlan::class)
            ->fillForm([
                'name' => $planData->name,
                'sku' => $planData->sku,
                'subscription_plan_group_id' => $group->id,
                'remote_provider' => $planData->remote_provider,
                'remote_provider_price_id' => $planData->remote_provider_price_id,
                'price' => $planData->price,
                'billing_interval' => $planData->billing_interval,
                'description' => $planData->description,
                'sort_order' => $planData->sort_order,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('subscription_plans', [
            'name' => $planData->name,
            'subscription_plan_group_id' => $group->id,
            'price' => $planData->price
        ]);
    }

    #[Test]
    public function it_can_render_edit_page()
    {
        $this->loginAsAdmin();
        $plan = SubscriptionPlan::factory()->create();

        $this->get(SubscriptionPlanResource::getUrl('edit', [
            'record' => $plan
        ], false, 'admin-billing'))->assertSuccessful()
            ->assertSee($plan->name);
    }

    #[Test]
    public function it_can_update_subscription_plan()
    {
        $this->loginAsAdmin();

        // Create an initial plan
        $group = SubscriptionPlanGroup::factory()->create();
        $plan = SubscriptionPlan::factory()->create([
            'subscription_plan_group_id' => $group->id
        ]);

        // New data for updating the plan
        $updatedPlanData = SubscriptionPlan::factory()->make([
            'subscription_plan_group_id' => $group->id
        ]);

        Filament::setCurrentPanel(
            Filament::getPanel('admin-billing'),
        );

        Livewire::test(\Modules\Billing\Filament\Resources\SubscriptionPlanResource\Pages\EditSubscriptionPlan::class, [
            'record' => $plan->id,
        ])
            ->fillForm([
                'name' => $updatedPlanData->name,
                'sku' => $updatedPlanData->sku,
                'subscription_plan_group_id' => $group->id,
                'remote_provider' => $updatedPlanData->remote_provider,
                'remote_provider_price_id' => $updatedPlanData->remote_provider_price_id,
                'price' => $updatedPlanData->price,
                'billing_interval' => $updatedPlanData->billing_interval,
                'description' => $updatedPlanData->description,
                'sort_order' => $updatedPlanData->sort_order,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('subscription_plans', [
            'id' => $plan->id,
            'name' => $updatedPlanData->name,
            'subscription_plan_group_id' => $group->id,
            'price' => $updatedPlanData->price
        ]);
    }

    #[Test]
    public function it_validates_required_fields()
    {
        $this->loginAsAdmin();

        Filament::setCurrentPanel(
            Filament::getPanel('admin-billing'),
        );

        Livewire::test(\Modules\Billing\Filament\Resources\SubscriptionPlanResource\Pages\CreateSubscriptionPlan::class)
            ->fillForm([
                'name' => '',
                'sku' => '',
                'price' => null,
                'billing_interval' => '',
            ])
            ->call('create')
            ->assertHasFormErrors([
                'name' => 'required',
                'sku' => 'required',
                'price' => 'required',
                'billing_interval' => 'required',
            ]);
    }
}
