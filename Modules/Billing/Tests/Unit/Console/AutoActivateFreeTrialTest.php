<?php

namespace Modules\Billing\Tests\Unit\Console;

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Modules\Billing\Console\Commands\AutoActivateFreeTrial;
use Modules\Billing\Models\SubscriptionManual;
use Modules\Billing\Tests\Unit\BillingTestCase;
use PHPUnit\Framework\Attributes\Test;

class AutoActivateFreeTrialTest extends BillingTestCase
{


    #[Test]
    public function it_fails_when_activating_pending_trials_due_to_constraints()
    {
        SubscriptionManual::truncate();

        $user = User::factory()->create();
        $subscription = SubscriptionManual::create([
            'user_id' => $user->id,
            'activate_free_trial_after_date' => now()->subDay(),
            'auto_activate_free_trial_after_date' => now()->subDay()
        ]);

        try {
            Artisan::call(AutoActivateFreeTrial::class);
         } catch (\Illuminate\Database\QueryException $e) {
            $this->assertStringContainsString('NOT NULL constraint', $e->getMessage());

            // Verify no changes were made
            $this->assertDatabaseHas('subscriptions_manual', [
                'id' => $subscription->id,
                'activate_free_trial_after_date' => $subscription->activate_free_trial_after_date->format('Y-m-d H:i:s'),
                'auto_activate_free_trial_after_date' => $subscription->auto_activate_free_trial_after_date->format('Y-m-d H:i:s')
            ]);
        }
    }

    #[Test]
    public function it_skips_already_activated_trials()
    {
        SubscriptionManual::truncate();
        $user = User::factory()->create();
        $subscription = SubscriptionManual::create([
            'user_id' => $user->id,
            'activate_free_trial_after_date' => null, // Already activated
            'auto_activate_free_trial_after_date' => null
        ]);

        $exitCode = Artisan::call(AutoActivateFreeTrial::class);
        $this->assertEquals(0, $exitCode);

        $updatedSubscription = SubscriptionManual::find($subscription->id);
        $this->assertNull($updatedSubscription->activate_free_trial_after_date);
        $this->assertNull($updatedSubscription->auto_activate_free_trial_after_date);
    }

    #[Test]
    public function it_handles_no_pending_trials()
    {
        SubscriptionManual::truncate();

        User::factory()->create(); // User with no subscription record

        $exitCode = Artisan::call(AutoActivateFreeTrial::class);
        $this->assertEquals(0, $exitCode);

        $this->assertDatabaseCount('subscriptions_manual', 0);
    }
}
