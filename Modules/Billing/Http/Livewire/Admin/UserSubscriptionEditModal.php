<?php

namespace Modules\Billing\Http\Livewire\Admin;

use Carbon\Carbon;
use LivewireUI\Modal\ModalComponent;
use Modules\Billing\Models\SubscriptionManual;
use Modules\Billing\Models\SubscriptionPlan;
use Modules\SaasPanel\UserDemo;
use MicroweberPackages\User\Models\User;


/* @deprecated */
class UserSubscriptionEditModal extends ModalComponent
{

    public $userEmail = '';
    public $userId =0;
    public $subscriptionPlans;
    public $activeSubscriptionId = null;

    public $autoActivateFreeTrialAfterDate = null;
    public $activateFreeTrialAfterDate = null;


    public function render()
    {
        return view('modules.billing::admin.livewire.user-subscription-edit-modal');
    }

    public function save()
    {
        $this->validate([
            'userId' => 'required',
            'activeSubscriptionId' => 'required',
        ]);

        $findUser = User::where('id', $this->userId)->first();
        if ($findUser) {
            if ($this->activeSubscriptionId == 'no_plan') {
                $findSubscriptionManual = SubscriptionManual::where('user_id', $this->userId)->first();
                if ($findSubscriptionManual) {
                    $findSubscriptionManual->delete();
                }
            } else if ($this->activeSubscriptionId == 'free_trial') {

                $activeSubscription = getUserActiveSubscriptionPlanBySKU($this->userId, 'hosting');
                if (!$activeSubscription) {
                    $findSubscriptionManual = SubscriptionManual::where('user_id', $this->userId)->first();
                    if ($findSubscriptionManual) {
                        $findSubscriptionManual->delete();
                    }

                    $findUser->demo_expired = null;
                    $findUser->demo_expired_at = null;
                    $findUser->demo_started_at = null;
                    $findUser->save();

                    UserDemo::activate($findUser->id);
                }

            } else {
                $findSubscriptionPlan = SubscriptionPlan::where('id', $this->activeSubscriptionId)->first();
                if ($findSubscriptionPlan) {
                    $findSubscriptionManual = SubscriptionManual::where('user_id', $this->userId)->first();
                    if ($findSubscriptionManual) {
                        $findSubscriptionManual->auto_activate_free_trial_after_date = $this->autoActivateFreeTrialAfterDate;
                        $findSubscriptionManual->activate_free_trial_after_date = $this->activateFreeTrialAfterDate;
                        $findSubscriptionManual->subscription_plan_id = $this->activeSubscriptionId;
                        $findSubscriptionManual->save();
                    } else {
                        $newSubscriptionManual = new SubscriptionManual();
                        $newSubscriptionManual->auto_activate_free_trial_after_date = $this->autoActivateFreeTrialAfterDate;
                        $newSubscriptionManual->activate_free_trial_after_date = $this->activateFreeTrialAfterDate;
                        $newSubscriptionManual->user_id = $this->userId;
                        $newSubscriptionManual->subscription_plan_id = $this->activeSubscriptionId;
                        $newSubscriptionManual->save();
                    }
                }
            }
        }

        $this->emit('userSubscriptionUpdated');
        $this->closeModal();
    }

    public function mount($userId  = 0)
    {
        $getUser = User::where('id', $userId)->first();
        if ($getUser) {
            $this->userId = $userId;
            $this->userEmail = $getUser->email;

            $activeSubscription = getUserActiveSubscriptionPlanBySKU($getUser->id, 'hosting');
            if ($activeSubscription) {
                $this->activeSubscriptionId = $activeSubscription['id'];
            }

            $findSubscriptionManual = SubscriptionManual::where('user_id', $this->userId)->first();
            if ($findSubscriptionManual) {
                $this->autoActivateFreeTrialAfterDate = $findSubscriptionManual->auto_activate_free_trial_after_date;
                if ($findSubscriptionManual->activate_free_trial_after_date) {
                    $this->activateFreeTrialAfterDate = Carbon::createFromDate($findSubscriptionManual->activate_free_trial_after_date)->format('Y-m-d');
                }
                $this->activeSubscriptionId = $findSubscriptionManual->subscription_plan_id;
            }

        }

        $subscriptionPlans = SubscriptionPlan::all();

       $this->subscriptionPlans = $subscriptionPlans;
    }


}
