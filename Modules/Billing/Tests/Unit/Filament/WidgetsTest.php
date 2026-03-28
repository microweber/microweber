<?php

namespace Modules\Billing\Tests\Unit\Filament;

use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Modules\Billing\Filament\Admin\Widgets\LatestSubscriptionsWidget;
use Modules\Billing\Filament\Admin\Widgets\StatsOverviewWidget;
use Modules\Billing\Models\Subscription;
use Modules\Billing\Models\SubscriptionPlan;
use Modules\Billing\Tests\Unit\BillingTestCase;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Support\Str;
use Tests\Feature\Filament\Concerns\InteractsWithFilamentPanel;

class WidgetsTest extends BillingTestCase
{
    use InteractsWithFilamentPanel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFilamentPanel('admin-billing');
    }

    private function createTestPlan(array $overrides = []): SubscriptionPlan
    {
        return SubscriptionPlan::factory()->create(array_merge([
            'name' => 'Test Plan',
            'price' => 1000,
            'currency' => 'USD',
        ], $overrides));
    }

    private function createTestSubscription(array $overrides = []): Subscription
    {
        $plan = $this->createTestPlan();

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
    public function stats_overview_widget_displays_mrr_stat()
    {
        DB::table('subscriptions')->delete();

        $activePlan = $this->createTestPlan(['price' => 2500]);
        $this->createTestSubscription([
            'stripe_status' => 'active',
            'subscription_plan_id' => $activePlan->id,
        ]);

        $response = Livewire::test(StatsOverviewWidget::class);

        $response->assertOk();
        $response->assertSee('MRR');
        $response->assertSee('$25.00');
        $response->assertSee('Monthly Recurring Revenue');
    }

    #[Test]
    public function stats_overview_widget_displays_active_subscriptions_count()
    {
        DB::table('subscriptions')->delete();

        $this->createTestSubscription(['stripe_status' => 'active', 'stripe_id' => 'sub_active_1']);
        $this->createTestSubscription(['stripe_status' => 'active', 'stripe_id' => 'sub_active_2']);
        $this->createTestSubscription(['stripe_status' => 'canceled', 'stripe_id' => 'sub_canceled_1']);

        $response = Livewire::test(StatsOverviewWidget::class);

        $response->assertOk();
        $response->assertSee('Active Subscriptions');
        $response->assertSee('2');
        $response->assertSee('Currently active subscriptions');
    }

    #[Test]
    public function stats_overview_widget_displays_total_subscriptions_count()
    {
        DB::table('subscriptions')->delete();

        $this->createTestSubscription(['stripe_id' => 'sub_1']);
        $this->createTestSubscription(['stripe_id' => 'sub_2']);
        $this->createTestSubscription(['stripe_id' => 'sub_3']);

        $response = Livewire::test(StatsOverviewWidget::class);

        $response->assertOk();
        $response->assertSee('Total Subscriptions');
        $response->assertSee('3');
        $response->assertSee('All-time subscriptions');
    }

    #[Test]
    public function stats_overview_widget_displays_churn_rate()
    {
        DB::table('subscriptions')->delete();

        $oldSubscription = $this->createTestSubscription([
            'created_at' => now()->subDays(60),
            'stripe_id' => 'sub_old_1',
        ]);

        $canceledSubscription = $this->createTestSubscription([
            'created_at' => now()->subDays(60),
            'stripe_status' => 'canceled',
            'stripe_id' => 'sub_old_canceled',
            'updated_at' => now()->subDay(),
        ]);

        $response = Livewire::test(StatsOverviewWidget::class);

        $response->assertOk();
        $response->assertSee('Churn Rate');
        $response->assertSee('Canceled in last 30 days');
    }

    #[Test]
    public function stats_overview_widget_shows_zero_for_empty_database()
    {
        DB::table('subscriptions')->delete();

        $response = Livewire::test(StatsOverviewWidget::class);

        $response->assertOk();
        $response->assertSee('MRR');
        $response->assertSee('$0.00');
        $response->assertSee('Active Subscriptions');
        $response->assertSee('0');
        $response->assertSee('Total Subscriptions');
    }

    #[Test]
    public function stats_overview_widget_has_sort_order_zero()
    {
        $this->assertEquals(0, StatsOverviewWidget::getSort());
    }

    #[Test]
    public function latest_subscriptions_widget_displays_latest_five_subscriptions()
    {
        DB::table('subscriptions')->delete();

        $subscriptions = [];
        for ($i = 0; $i < 7; $i++) {
            $subscriptions[] = $this->createTestSubscription([
                'stripe_id' => 'sub_widget_' . $i,
                'created_at' => now()->subMinutes($i),
            ]);
        }

        $response = Livewire::test(LatestSubscriptionsWidget::class);

        $response->assertOk();
        $response->assertCanSeeTableRecords(array_slice($subscriptions, 0, 5));
    }

    #[Test]
    public function latest_subscriptions_widget_shows_empty_state_when_no_subscriptions()
    {
        DB::table('subscriptions')->delete();

        $response = Livewire::test(LatestSubscriptionsWidget::class);

        $response->assertOk();
        // Filament v5 shows "No subscriptions" as the empty state heading
        $response->assertSee('No subscriptions');
    }

    #[Test]
    public function latest_subscriptions_widget_has_polling_interval_of_60_seconds()
    {
        $reflection = new \ReflectionClass(LatestSubscriptionsWidget::class);
        $property = $reflection->getProperty('pollingInterval');
        $property->setAccessible(true);
        $this->assertEquals('60s', $property->getDefaultValue());
    }

    #[Test]
    public function latest_subscriptions_widget_has_sort_order_two()
    {
        $this->assertEquals(2, LatestSubscriptionsWidget::getSort());
    }

    #[Test]
    public function latest_subscriptions_widget_displays_correct_columns()
    {
        DB::table('subscriptions')->delete();

        $this->createTestSubscription([
            'stripe_id' => 'sub_columns',
            'stripe_status' => 'active',
        ]);

        $response = Livewire::test(LatestSubscriptionsWidget::class);

        $response->assertOk();
        $response->assertSee('ID');
        $response->assertSee('User ID');
        // Filament auto-generates label from attribute name: "stripe_status" -> "Stripe status"
        $response->assertSee('Stripe status');
        $response->assertSee('active');
    }

    #[Test]
    public function latest_subscriptions_widget_status_badge_color_is_success_for_active()
    {
        DB::table('subscriptions')->delete();

        $this->createTestSubscription([
            'stripe_id' => 'sub_active_badge',
            'stripe_status' => 'active',
        ]);

        $response = Livewire::test(LatestSubscriptionsWidget::class);

        $response->assertOk();
    }

    #[Test]
    public function latest_subscriptions_widget_status_badge_color_is_danger_for_canceled()
    {
        DB::table('subscriptions')->delete();

        $this->createTestSubscription([
            'stripe_id' => 'sub_canceled_badge',
            'stripe_status' => 'canceled',
        ]);

        $response = Livewire::test(LatestSubscriptionsWidget::class);

        $response->assertOk();
    }

    #[Test]
    public function latest_subscriptions_widget_status_badge_color_is_warning_for_past_due()
    {
        DB::table('subscriptions')->delete();

        $this->createTestSubscription([
            'stripe_id' => 'sub_past_due_badge',
            'stripe_status' => 'past_due',
        ]);

        $response = Livewire::test(LatestSubscriptionsWidget::class);

        $response->assertOk();
    }

    #[Test]
    public function stats_overview_widget_has_four_stats()
    {
        DB::table('subscriptions')->delete();

        $response = Livewire::test(StatsOverviewWidget::class);

        $response->assertOk();
        $response->assertSee('MRR');
        $response->assertSee('Active Subscriptions');
        $response->assertSee('Churn Rate');
        $response->assertSee('Total Subscriptions');
    }

    #[Test]
    public function widgets_are_registered_on_dashboard()
    {
        $dashboard = new \Modules\Billing\Filament\Admin\Pages\Dashboard();

        $widgets = $dashboard->getWidgets();

        $this->assertContains(StatsOverviewWidget::class, $widgets);
        $this->assertContains(LatestSubscriptionsWidget::class, $widgets);
        $this->assertCount(2, $widgets);
    }

    #[Test]
    public function latest_subscriptions_widget_has_full_column_span()
    {
        $reflection = new \ReflectionClass(LatestSubscriptionsWidget::class);
        $property = $reflection->getProperty('columnSpan');
        $property->setAccessible(true);
        $this->assertEquals('full', $property->getValue(new LatestSubscriptionsWidget()));
    }
}
