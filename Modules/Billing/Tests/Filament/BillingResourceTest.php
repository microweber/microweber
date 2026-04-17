<?php

namespace Modules\Billing\Tests\Filament;

use Livewire\Livewire;
use Modules\Billing\Filament\Admin\Resources\SubscriptionResource;
use Modules\Billing\Filament\Admin\Resources\SubscriptionPlanResource;
use Modules\Billing\Filament\Admin\Resources\SubscriptionPlanGroupsResource;
use Modules\Billing\Filament\Admin\Resources\BillingUserResource;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;
use Tests\TestCase;

class BillingResourceTest extends TestCase
{
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel('billing');
    }

    #[Test]
    public function it_subscription_resource_exists(): void
    {
        $this->assertTrue(class_exists(SubscriptionResource::class));
    }

    #[Test]
    public function it_subscription_plan_resource_exists(): void
    {
        $this->assertTrue(class_exists(SubscriptionPlanResource::class));
    }

    #[Test]
    public function it_subscription_plan_groups_resource_exists(): void
    {
        $this->assertTrue(class_exists(SubscriptionPlanGroupsResource::class));
    }

    #[Test]
    public function it_billing_user_resource_exists(): void
    {
        $this->assertTrue(class_exists(BillingUserResource::class));
    }

    #[Test]
    public function it_subscription_resource_has_model(): void
    {
        $this->assertNotNull(SubscriptionResource::getModel());
    }

    #[Test]
    public function it_subscription_plan_resource_has_model(): void
    {
        $this->assertNotNull(SubscriptionPlanResource::getModel());
    }
}
