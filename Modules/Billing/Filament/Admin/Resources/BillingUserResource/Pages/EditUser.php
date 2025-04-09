<?php

namespace Modules\Billing\Filament\Admin\Resources\BillingUserResource\Pages;

use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\Billing\Filament\Admin\Resources\BillingUserResource;
use Modules\Billing\Filament\Resources\BillingUserResource\Pages\UserDemo;
use Modules\Billing\Models\SubscriptionManual;
use Modules\Billing\Models\SubscriptionPlan;

class EditUser extends EditRecord
{
    protected static string $resource = BillingUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function save(bool $shouldRedirect = true, bool $shouldSendSavedNotification = true): void
    {
        $data = $this->form->getState();
        $user = $this->record;

        $activeSubscriptionId = $data['subscription_plan_id'];
        $autoActivateFreeTrialAfterDate = $data['auto_activate_free_trial_after_date'];
        $activateFreeTrialAfterDate = $data['activate_free_trial_after_date'];
        $userId = $user->id;


        $findUser = User::where('id', $userId)->first();
        if ($findUser) {
            if ($activeSubscriptionId == 'no_plan') {
                $findSubscriptionManual = SubscriptionManual::where('user_id', $userId)->first();
                if ($findSubscriptionManual) {
                    $findSubscriptionManual->delete();
                }
            } else if ($activeSubscriptionId == 'free_trial') {

                $activeSubscription = getUserActiveSubscriptionPlanBySKU($userId, 'hosting');
                if (!$activeSubscription) {
                    $findSubscriptionManual = SubscriptionManual::where('user_id', $userId)->first();
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
                $findSubscriptionPlan = SubscriptionPlan::where('id', $activeSubscriptionId)->first();
                if ($findSubscriptionPlan) {
                    $findSubscriptionManual = SubscriptionManual::where('user_id', $userId)->first();
                    if ($findSubscriptionManual) {
                        $findSubscriptionManual->auto_activate_free_trial_after_date = $autoActivateFreeTrialAfterDate;
                        $findSubscriptionManual->activate_free_trial_after_date = $activateFreeTrialAfterDate;
                        $findSubscriptionManual->subscription_plan_id = $activeSubscriptionId;
                        $findSubscriptionManual->save();
                    } else {
                        $newSubscriptionManual = new SubscriptionManual();
                        $newSubscriptionManual->auto_activate_free_trial_after_date = $autoActivateFreeTrialAfterDate;
                        $newSubscriptionManual->activate_free_trial_after_date = $activateFreeTrialAfterDate;
                        $newSubscriptionManual->user_id = $userId;
                        $newSubscriptionManual->subscription_plan_id = $activeSubscriptionId;
                        $newSubscriptionManual->save();
                    }
                }
            }
        }

        $this->notify('success', 'Subscription updated successfully');

        if ($shouldRedirect) {
            $this->redirect($this->getResource()::getUrl('index'));
        }
    }
}
