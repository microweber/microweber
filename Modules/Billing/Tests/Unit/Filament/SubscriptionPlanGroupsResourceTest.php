<?php

namespace Modules\Billing\Tests\Unit\Filament;

use Filament\Facades\Filament;
use Livewire\Livewire;
use Modules\Billing\Filament\Admin\Resources\SubscriptionPlanGroupsResource;
use Modules\Billing\Models\SubscriptionPlanGroup;
use Modules\Billing\Models\SubscriptionPlan;
use Modules\Billing\Tests\Unit\BillingTestCase;
use PHPUnit\Framework\Attributes\Test;

class SubscriptionPlanGroupsResourceTest extends BillingTestCase
{
    #[Test]
    public function it_can_list_subscription_plan_groups()
    {
        SubscriptionPlanGroup::query()->delete();

        $this->loginAsAdmin();
        $groups = SubscriptionPlanGroup::factory()->count(3)->create();

        $this->get(SubscriptionPlanGroupsResource::getUrl('index', [], false, 'admin-billing'))
            ->assertSuccessful()
            ->assertSee($groups[0]->name)
            ->assertSee($groups[1]->name)
            ->assertSee($groups[2]->name);
    }

    #[Test]
    public function it_can_render_create_page()
    {
        $this->loginAsAdmin();
        $this->get(SubscriptionPlanGroupsResource::getUrl('create', [], false, 'admin-billing'))
            ->assertSuccessful();
    }

    #[Test]
    public function it_can_create_subscription_plan_group()
    {
        $this->loginAsAdmin();

        Filament::setCurrentPanel(
            Filament::getPanel('admin-billing'),
        );

        Livewire::test(SubscriptionPlanGroupsResource\Pages\CreateSubscriptionPlanGroups::class)
            ->fillForm([
                'name' => 'Test Plan Group',
                'sku' => 'TEST-SKU-001',
                'description' => 'Test description',
                'type' => 'premium',
                'position' => 1,
                'icon' => 'star',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('subscription_plans_groups', [
            'name' => 'Test Plan Group',
            'sku' => 'TEST-SKU-001',
            'type' => 'premium',
        ]);
    }

    #[Test]
    public function it_can_render_edit_page()
    {
        $this->loginAsAdmin();
        $group = SubscriptionPlanGroup::factory()->create();

        $this->get(SubscriptionPlanGroupsResource::getUrl('edit', [
            'record' => $group
        ], false, 'admin-billing'))->assertSuccessful()
            ->assertSee($group->name);
    }

    #[Test]
    public function it_can_update_subscription_plan_group()
    {
        $this->loginAsAdmin();
        $group = SubscriptionPlanGroup::factory()->create();

        Filament::setCurrentPanel(
            Filament::getPanel('admin-billing'),
        );

        Livewire::test(SubscriptionPlanGroupsResource\Pages\EditSubscriptionPlanGroups::class, [
            'record' => $group->id,
        ])
            ->fillForm([
                'name' => 'Updated Group Name',
                'sku' => 'UPDATED-SKU',
                'description' => 'Updated description',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('subscription_plans_groups', [
            'id' => $group->id,
            'name' => 'Updated Group Name',
            'sku' => 'UPDATED-SKU',
        ]);
    }

    #[Test]
    public function it_can_render_view_page()
    {
        $this->loginAsAdmin();
        $group = SubscriptionPlanGroup::factory()->create();

        $this->get(SubscriptionPlanGroupsResource::getUrl('view', [
            'record' => $group
        ], false, 'admin-billing'))->assertSuccessful()
            ->assertSee($group->name)
            ->assertSee($group->sku);
    }

    #[Test]
    public function it_validates_required_fields()
    {
        $this->loginAsAdmin();

        Filament::setCurrentPanel(
            Filament::getPanel('admin-billing'),
        );

        Livewire::test(SubscriptionPlanGroupsResource\Pages\CreateSubscriptionPlanGroups::class)
            ->fillForm([
                'name' => '',
                'sku' => '',
            ])
            ->call('create')
            ->assertHasFormErrors([
                'name' => 'required',
                'sku' => 'required',
            ]);
    }

    #[Test]
    public function it_validates_unique_sku()
    {
        $this->loginAsAdmin();
        $existingGroup = SubscriptionPlanGroup::factory()->create(['sku' => 'UNIQUE-SKU']);

        Filament::setCurrentPanel(
            Filament::getPanel('admin-billing'),
        );

        Livewire::test(SubscriptionPlanGroupsResource\Pages\CreateSubscriptionPlanGroups::class)
            ->fillForm([
                'name' => 'Another Group',
                'sku' => 'UNIQUE-SKU',
            ])
            ->call('create')
            ->assertHasFormErrors([
                'sku' => 'unique',
            ]);
    }

    #[Test]
    public function it_shows_plans_relation_manager()
    {
        $this->loginAsAdmin();
        $group = SubscriptionPlanGroup::factory()->create();
        $plan = SubscriptionPlan::factory()->create([
            'subscription_plan_group_id' => $group->id,
        ]);

        Filament::setCurrentPanel(
            Filament::getPanel('admin-billing'),
        );

        Livewire::test(SubscriptionPlanGroupsResource\RelationManagers\PlansRelationManager::class, [
            'ownerRecord' => $group,
            'pageClass' => SubscriptionPlanGroupsResource\Pages\EditSubscriptionPlanGroups::class,
        ])
            ->assertSuccessful()
            ->assertCanSeeTableRecords([$plan]);
    }
}
