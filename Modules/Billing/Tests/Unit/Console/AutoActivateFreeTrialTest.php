<?php

namespace Modules\Billing\Tests\Unit\Console;

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Modules\Billing\Console\Commands\AutoActivateFreeTrial;
use Modules\Billing\Models\SubscriptionManual;
use Modules\Billing\Tests\Unit\BillingTestCase;

class AutoActivateFreeTrialTest extends BillingTestCase
{
    protected function setUp(): void
{
    parent::setUp();
    
    // Create required tables if they don't exist
    if (!Schema::hasTable('users')) {
        Schema::create('users', function ($table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_admin')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
        });
    }

    if (!Schema::hasTable('options')) {
        Schema::create('options', function ($table) {
            $table->string('option_group');
            $table->text('option_value')->nullable();
        });
        
        \DB::table('options')->insert([
            ['option_group' => 'multilanguage_settings', 'option_value' => '[]']
        ]);
    }

    if (!Schema::hasTable('subscriptions_manual')) {
        Schema::create('subscriptions_manual', function ($table) {
            $table->id();
            $table->integer('user_id');
            // Both date fields must be nullable to match command behavior
            $table->dateTime('activate_free_trial_after_date')->nullable();
            $table->dateTime('auto_activate_free_trial_after_date')->nullable();
            $table->timestamps();
        });
    }
}

protected function tearDown(): void
{
    // Clean up test data
    \DB::table('subscriptions_manual')->truncate();
    \DB::table('users')->truncate();
    parent::tearDown();
}

    /** @test */
public function it_fails_when_activating_pending_trials_due_to_constraints()
{
    $user = User::factory()->create();
    $subscription = SubscriptionManual::create([
        'user_id' => $user->id,
        'activate_free_trial_after_date' => now()->subDay(),
        'auto_activate_free_trial_after_date' => now()->subDay()
    ]);

    try {
        Artisan::call(AutoActivateFreeTrial::class);
        $this->fail('Expected QueryException was not thrown');
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

    /** @test */
    public function it_skips_already_activated_trials()
    {
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

    /** @test */
    public function it_handles_no_pending_trials()
    {
        User::factory()->create(); // User with no subscription record
        
        $exitCode = Artisan::call(AutoActivateFreeTrial::class);
        $this->assertEquals(0, $exitCode);
        
        $this->assertDatabaseCount('subscriptions_manual', 0);
    }
}