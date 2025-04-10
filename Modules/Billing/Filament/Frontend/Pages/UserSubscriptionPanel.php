<?php

namespace Modules\Billing\Filament\Frontend\Pages;

use Filament\Actions\ActionGroup;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Contracts\View\View;
use Modules\Billing\Models\SubscriptionPlan;
use Modules\Billing\Services\SubscriptionManager;

class UserSubscriptionPanel extends Page
{
   // protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static string $view = 'modules.billing::filament.pages.user-subscription-panel';

    protected static ?string $title = 'My Subscription';

    protected static ?string $slug = 'user-subscription';

    public ?string $selectedPlanSku = null;
    public ?string $plan = null;



    protected function getCurrentPlanSku(): ?string
    {
        $user = auth()->user();
        if (!$user) {
            return null;
        }
        $activePlan = getUserActiveSubscriptionPlan($user->id);
        return $activePlan['sku'] ?? null;
    }

    protected function getFormSchema(): array
    {
        return [
            Select::make('plan')
                ->label('Select Subscription Plan')
                ->options(
                    SubscriptionPlan::query()
                        ->pluck('name', 'sku')
                        ->toArray()
                )
                ->searchable()
                ->required(),
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema($this->getFormSchema())
            ->statePath('data');
    }

    public function submit()
    {
        $user = auth()->user();
        if (!$user) {
            Notification::make()
                ->title('User not authenticated')
                ->danger()
                ->send();
            return;
        }

        $planSku = $this->form->getState()['plan'] ?? null;
        if (!$planSku) {
            Notification::make()
                ->title('Please select a plan')
                ->danger()
                ->send();
            return;
        }

        /** @var SubscriptionManager $subscriptionManager */
        $subscriptionManager = app(SubscriptionManager::class);
        $result = $subscriptionManager->subscribeToPlan($planSku);

        if (isset($result['error'])) {
            Notification::make()
                ->title('Error: ' . $result['error'])
                ->danger()
                ->send();
        } else {
            Notification::make()
                ->title('Subscription updated successfully')
                ->success()
                ->send();
        }
    }

    protected function getActions(): array
    {
        return [
            ActionGroup::make([
                Forms\Components\Actions\Action::make('save')
                    ->label('Update Subscription')
                    ->action('submit')
                    ->color('primary'),
            ]),
        ];
    }


}
