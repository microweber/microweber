<?php

namespace Modules\Billing\Filament\Admin\Resources\BillingUserResource\Pages;

use App\Models\User;
use Filament\Actions;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\EditRecord;
use Modules\Billing\Filament\Admin\Resources\BillingUserResource;
use Modules\Billing\Models\BillingUser;
use Modules\Billing\Models\SubscriptionManual;
use Modules\Billing\Models\SubscriptionPlan;
use Modules\SaasPanel\UserDemo;

class EditUser extends EditRecord
{
    protected static string $resource = BillingUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\Action::make('impersonate')
                ->label('Impersonate')
                ->icon('heroicon-o-user-circle')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Impersonate User')
                ->modalDescription('You will be logged in as this user. Continue?')
                ->modalSubmitActionLabel('Yes, Impersonate')
                ->action(function () {
                    $record = $this->record;
                    session()->put('impersonate_user_id', $record->id);
                    return redirect()->to('/');
                })
                ->visible(fn () => auth()->user()->can('impersonate_users')),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Current Subscription Summary')
                    ->description('Overview of user subscription status')
                    ->schema([
                        TextEntry::make('subscription_name')
                            ->label('Active Subscription')
                            ->state(function (BillingUser $record): string {
                                return $record->getSubscriptionName();
                            })
                            ->color(function (BillingUser $record): string {
                                return $record->hasActiveSubscription() ? 'success' : 'danger';
                            }),
                        TextEntry::make('subscription_status')
                            ->label('Status')
                            ->state(function (BillingUser $record): string {
                                return $record->hasActiveSubscription() ? 'Active' : 'Inactive';
                            })
                            ->badge()
                            ->color(function (BillingUser $record): string {
                                return $record->hasActiveSubscription() ? 'success' : 'danger';
                            }),
                        TextEntry::make('trial_info')
                            ->label('Trial Information')
                            ->state(function (BillingUser $record): string {
                                if ($record->demo_started_at && !$record->demo_expired_at) {
                                    return 'Trial Active (started: ' . $record->demo_started_at->format('Y-m-d') . ')';
                                }
                                if ($record->demo_expired_at) {
                                    return 'Trial Expired (' . $record->demo_expired_at->format('Y-m-d') . ')';
                                }
                                return 'No Trial';
                            })
                            ->visible(fn (BillingUser $record) => $record->demo_started_at || $record->demo_expired_at),
                    ])
                    ->collapsible()
                    ->persistCollapsed(),
            ]);
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
