<?php

namespace Modules\Billing\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Modules\Billing\Models\SubscriptionManual;

class AutoActivateFreeTrial  extends Command
{

    protected $signature = 'billing:auto-activate-free-trial';

    protected $description = 'Auto activate free trial for users that have not activated it yet.';

    public function handle()
    {
        $subscriptions = SubscriptionManual::where('activate_free_trial_after_date', '<', date('Y-m-d H:i:s'))->get();
        if ($subscriptions->count() > 0) {
            foreach ($subscriptions as $subscription) {

                $user = User::where('id', $subscription->user_id)->first();
                if ($user) {

                    $subscription->auto_activate_free_trial_after_date = null;
                    $subscription->activate_free_trial_after_date = null;
                    $subscription->save();

                    // Check is user already have active subscription
                    $checkForSubscription = checkUserIsSubscribedToPlanBySKU($user->id, 'hosting');
                    if ($checkForSubscription) {
                        $this->info('User already have active subscription: ' . $user->email);
                        continue;
                    }

                    $user->demo_expired = null;
                    $user->demo_expired_at = null;
                    $user->demo_started_at = null;
                    $user->save();

                    \Modules\SaasPanel\UserDemo::activate($user->id);

                    $this->info('Free trial auto activated for user: ' . $user->email);

                }
            }
        }
    }


}
