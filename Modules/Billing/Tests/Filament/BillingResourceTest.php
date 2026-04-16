<?php

namespace Modules\Billing\Tests\Filament;

use Livewire\Livewire;
use Modules\Billing\Filament\Admin\Resources\SubscriptionResource;
use Modules\Billing\Filament\Admin\Resources\SubscriptionResource\Pages\ListSubscriptions;
use Modules\Billing\Filament\Admin\Resources\SubscriptionPlanResource;
use Modules\Billing\Filament\Admin\Resources\SubscriptionPlanResource\Pages\ListSubscriptionPlans;
use Modules\Billing\Filament\Admin\Resources\SubscriptionPlanGroupsResource;
use Modules\Billing\Filament\Admin\Resources\SubscriptionPlanGroupsResource\Pages\ListSubscriptionPlanGroups;
use Modules\Billing\Filament\Admin\Resources\BillingUserResource;
use Modules\Billing\Filament\Admin\Resources\BillingUserResource\Pages\ListUsers;
use Tests\Feature\Filament\FilamentResourceTestCase;
use PHPUnit\Framework\Attributes\Test;

class BillingResourceTest extends FilamentResourceTestCase
{
    protected function getResourceClass(): string
    {
        return SubscriptionResource::class;
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel('admin-billing');
    }

    #[Test]
    public function it_can_render_subscriptions_list_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(ListSubscriptions::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_can_render_subscription_plans_list_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(ListSubscriptionPlans::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_can_render_plan_groups_list_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(ListSubscriptionPlanGroups::class)
            ->assertSuccessful();
    }

    #[Test]
    public function it_can_render_billing_users_list_page(): void
    {
        $this->actingAsAdmin();

        Livewire::test(ListUsers::class)
            ->assertSuccessful();
    }
}
