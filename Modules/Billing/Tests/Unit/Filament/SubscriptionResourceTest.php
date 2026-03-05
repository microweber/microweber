<?php

namespace Modules\Billing\Tests\Unit\Filament;

use Filament\Facades\Filament;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Modules\Billing\Filament\Admin\Resources\SubscriptionResource;
use Modules\Billing\Models\Subscription;
use Modules\Billing\Models\SubscriptionPlan;
use Modules\Billing\Tests\Unit\BillingTestCase;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Support\Str;

class SubscriptionResourceTest extends BillingTestCase
{
    private function createTestSubscription(array $overrides = []): Subscription
    {
        $plan = SubscriptionPlan::factory()->create();

        // Create a customer record directly
        $customerId = DB::table('customers')->insertGetId([
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $subscriptionData = array_merge([
            'customer_id' => $customerId,
            'user_id' => null,
            'subscription_plan_id' => $plan->id,
            'subscription_customer_id' => null,
            'type' => 'stripe',
            'name' => 'Test Plan',
            'stripe_id' => 'sub_' . Str::random(10),
            'stripe_status' => 'active',
            'stripe_price' => 'price_' . Str::random(10),
            'quantity' => 1,
            'trial_ends_at' => null,
            'starts_at' => now(),
            'ends_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ], $overrides);

        $subscriptionId = DB::table('subscriptions')->insertGetId($subscriptionData);

        return Subscription::find($subscriptionId);
    }

    #[Test]
    public function it_can_list_subscriptions()
    {
        DB::table('subscriptions')->truncate();

        $this->loginAsAdmin();

        $subscription = $this->createTestSubscription([
            'name' => 'Test Subscription',
            'stripe_status' => 'active',
        ]);

        $this->get(SubscriptionResource::getUrl('index', [], false, 'admin-billing'))
            ->assertSuccessful()
            ->assertSee('Test Subscription')
            ->assertSee('active');
    }

    #[Test]
    public function it_can_filter_by_active_status()
    {
        $this->loginAsAdmin();

        $activeSubscription = $this->createTestSubscription([
            'stripe_status' => 'active',
            'stripe_id' => 'sub_active_' . Str::random(5),
        ]);

        $canceledSubscription = $this->createTestSubscription([
            'stripe_status' => 'canceled',
            'stripe_id' => 'sub_canceled_' . Str::random(5),
            'ends_at' => now()->subDay(),
        ]);

        Filament::setCurrentPanel(
            Filament::getPanel('admin-billing'),
        );

        Livewire::test(SubscriptionResource\Pages\ListSubscriptions::class)
            ->assertCanSeeTableRecords([$activeSubscription, $canceledSubscription])
            ->filterTable('stripe_status', 'active')
            ->assertCanSeeTableRecords([$activeSubscription])
            ->assertCanNotSeeTableRecords([$canceledSubscription]);
    }

    #[Test]
    public function it_can_filter_by_canceled_status()
    {
        $this->loginAsAdmin();

        $activeSubscription = $this->createTestSubscription([
            'stripe_status' => 'active',
            'stripe_id' => 'sub_active_' . Str::random(5),
        ]);

        $canceledSubscription = $this->createTestSubscription([
            'stripe_status' => 'canceled',
            'stripe_id' => 'sub_canceled_' . Str::random(5),
            'ends_at' => now()->subDay(),
        ]);

        Filament::setCurrentPanel(
            Filament::getPanel('admin-billing'),
        );

        Livewire::test(SubscriptionResource\Pages\ListSubscriptions::class)
            ->filterTable('stripe_status', 'canceled')
            ->assertCanSeeTableRecords([$canceledSubscription])
            ->assertCanNotSeeTableRecords([$activeSubscription]);
    }

    #[Test]
    public function it_can_filter_by_trialing_status()
    {
        $this->loginAsAdmin();

        $trialingSubscription = $this->createTestSubscription([
            'stripe_status' => 'trialing',
            'stripe_id' => 'sub_trialing_' . Str::random(5),
            'trial_ends_at' => now()->addDays(7),
        ]);

        $activeSubscription = $this->createTestSubscription([
            'stripe_status' => 'active',
            'stripe_id' => 'sub_active_' . Str::random(5),
        ]);

        Filament::setCurrentPanel(
            Filament::getPanel('admin-billing'),
        );

        Livewire::test(SubscriptionResource\Pages\ListSubscriptions::class)
            ->filterTable('stripe_status', 'trialing')
            ->assertCanSeeTableRecords([$trialingSubscription])
            ->assertCanNotSeeTableRecords([$activeSubscription]);
    }

    #[Test]
    public function it_can_filter_by_expired_status()
    {
        $this->loginAsAdmin();

        $expiredSubscription = $this->createTestSubscription([
            'stripe_status' => 'incomplete_expired',
            'stripe_id' => 'sub_expired_' . Str::random(5),
            'ends_at' => now()->subDay(),
        ]);

        $activeSubscription = $this->createTestSubscription([
            'stripe_status' => 'active',
            'stripe_id' => 'sub_active_' . Str::random(5),
        ]);

        Filament::setCurrentPanel(
            Filament::getPanel('admin-billing'),
        );

        Livewire::test(SubscriptionResource\Pages\ListSubscriptions::class)
            ->filterTable('stripe_status', 'incomplete_expired')
            ->assertCanSeeTableRecords([$expiredSubscription])
            ->assertCanNotSeeTableRecords([$activeSubscription]);
    }

    #[Test]
    public function it_can_filter_active_subscriptions()
    {
        $this->loginAsAdmin();

        $activeSubscription = $this->createTestSubscription([
            'stripe_status' => 'active',
            'stripe_id' => 'sub_active_' . Str::random(5),
        ]);

        $canceledSubscription = $this->createTestSubscription([
            'stripe_status' => 'canceled',
            'stripe_id' => 'sub_canceled_' . Str::random(5),
            'ends_at' => now()->subDay(),
        ]);

        $trialingSubscription = $this->createTestSubscription([
            'stripe_status' => 'trialing',
            'stripe_id' => 'sub_trialing_' . Str::random(5),
        ]);

        Filament::setCurrentPanel(
            Filament::getPanel('admin-billing'),
        );

        Livewire::test(SubscriptionResource\Pages\ListSubscriptions::class)
            ->filterTable('active_subscriptions')
            ->assertCanSeeTableRecords([$activeSubscription, $trialingSubscription])
            ->assertCanNotSeeTableRecords([$canceledSubscription]);
    }

    #[Test]
    public function it_can_render_view_page_with_next_billing_date()
    {
        $this->loginAsAdmin();

        $plan = SubscriptionPlan::factory()->create(['billing_interval' => 'monthly']);
        $subscription = $this->createTestSubscription([
            'subscription_plan_id' => $plan->id,
            'stripe_status' => 'active',
            'starts_at' => now(),
        ]);

        $this->get(SubscriptionResource::getUrl('view', ['record' => $subscription], false, 'admin-billing'))
            ->assertSuccessful()
            ->assertSee($subscription->stripe_id)
            ->assertSee('Next Billing Date');
    }

    #[Test]
    public function it_shows_subscription_timeline_on_view_page()
    {
        $this->loginAsAdmin();

        $subscription = $this->createTestSubscription([
            'stripe_status' => 'active',
            'starts_at' => now()->subMonth(),
            'trial_ends_at' => now()->addDays(7),
        ]);

        $this->get(SubscriptionResource::getUrl('view', ['record' => $subscription], false, 'admin-billing'))
            ->assertSuccessful()
            ->assertSee('Timeline')
            ->assertSee('Started At')
            ->assertSee('Trial Ends At');
    }

    #[Test]
    public function it_can_render_edit_page()
    {
        $this->loginAsAdmin();

        $subscription = $this->createTestSubscription([
            'stripe_status' => 'active',
        ]);

        $this->get(SubscriptionResource::getUrl('edit', ['record' => $subscription], false, 'admin-billing'))
            ->assertSuccessful();
    }

    #[Test]
    public function it_can_update_subscription_status()
    {
        $this->loginAsAdmin();

        $subscription = $this->createTestSubscription([
            'stripe_status' => 'active',
        ]);

        Filament::setCurrentPanel(
            Filament::getPanel('admin-billing'),
        );

        Livewire::test(SubscriptionResource\Pages\EditSubscription::class, [
            'record' => $subscription->id,
        ])
            ->fillForm([
                'stripe_status' => 'paused',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('subscriptions', [
            'id' => $subscription->id,
            'stripe_status' => 'paused',
        ]);
    }

    #[Test]
    public function it_displays_navigation_badge_for_active_subscriptions()
    {
        $this->loginAsAdmin();

        // Create active subscriptions
        $this->createTestSubscription([
            'stripe_status' => 'active',
            'stripe_id' => 'sub_1_' . Str::random(5),
        ]);
        $this->createTestSubscription([
            'stripe_status' => 'active',
            'stripe_id' => 'sub_2_' . Str::random(5),
        ]);
        $this->createTestSubscription([
            'stripe_status' => 'active',
            'stripe_id' => 'sub_3_' . Str::random(5),
        ]);

        $badge = SubscriptionResource::getNavigationBadge();

        $this->assertEquals(3, $badge);
    }

    #[Test]
    public function it_includes_trialing_in_active_navigation_badge()
    {
        $this->loginAsAdmin();

        $this->createTestSubscription([
            'stripe_status' => 'active',
            'stripe_id' => 'sub_active_1_' . Str::random(5),
        ]);
        $this->createTestSubscription([
            'stripe_status' => 'active',
            'stripe_id' => 'sub_active_2_' . Str::random(5),
        ]);
        $this->createTestSubscription([
            'stripe_status' => 'trialing',
            'stripe_id' => 'sub_trialing_1_' . Str::random(5),
        ]);
        $this->createTestSubscription([
            'stripe_status' => 'trialing',
            'stripe_id' => 'sub_trialing_2_' . Str::random(5),
        ]);

        $badge = SubscriptionResource::getNavigationBadge();

        $this->assertEquals(4, $badge);
    }
}
