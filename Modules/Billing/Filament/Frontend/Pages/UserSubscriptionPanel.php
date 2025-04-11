<?php

namespace Modules\Billing\Filament\Frontend\Pages;

use Filament\Actions\ActionGroup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Http\Request;
use Modules\Billing\Http\Controllers\SubscribeToPlanController;
use Modules\Billing\Models\SubscriptionPlan;
use Modules\Billing\Models\Subscription;
use JaOcero\RadioDeck\Forms\Components\RadioDeck;

class UserSubscriptionPanel extends Page
{
    protected static string $view = 'modules.billing::filament.pages.user-subscription-panel';

    protected static ?string $title = 'New Subscription';

    protected static ?string $slug = 'new-subscription';

    public ?string $plan = null;

    public $activeSubscriptions = [];

    public function mount()
    {
        $user = auth()->user();
        if ($user) {
            $this->activeSubscriptions = Subscription::with('plan')
                ->where('user_id', $user->id)
                ->where('stripe_status', 'active')
                ->get();
        }
    }

    protected function getFormSchema(): array
    {
        $plans = SubscriptionPlan::with('group')->get();

        $groupedPlans = [];

        foreach ($plans as $plan) {
            $groupName = $plan->group->name ?? 'Plans';
            $groupedPlans[$groupName][] = $plan;
        }

        $disabledSkus = [];
        foreach ($this->activeSubscriptions as $subscription) {
            if ($subscription->plan && $subscription->plan->sku) {
                $disabledSkus[] = $subscription->plan->sku;
            }
        }

        $schema = [];

        foreach ($groupedPlans as $groupName => $plansInGroup) {
            $options = [];
            $descriptions = [];
            $icons = [];

            foreach ($plansInGroup as $plan) {
                $options[$plan->sku] = $plan->name;
                $descriptions[$plan->sku] = $plan->description ?? '';
                $icons[$plan->sku] = 'heroicon-m-currency-dollar';
            }

            $schema[] = Forms\Components\Section::make($groupName)
                ->schema([
                    RadioDeck::make('plan')
                        ->label(function () use ($groupName) {
                            if($groupName != 'Plans') {
                                return 'Select a plan from ' . $groupName;
                            } else {
                                return 'Select a plan';
                            }
                        })
                        ->options($options)
                        ->descriptions($descriptions)
                        ->icons($icons)
                        ->disableOptionWhen(fn($value) => in_array($value, $disabledSkus))
                        ->required()
                        ->columns(1),
                ]);
        }

        return $schema;
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

        $request = request();
        $request->merge(['sku' => $planSku]);

        $controller = new SubscribeToPlanController();
        $response = $controller->subscribeToPlan($request);

        return $response;
    }

    protected function getActions(): array
    {
        return [
            ActionGroup::make([
                Forms\Components\Actions\Action::make('save')
                    ->label('Continue to Payment')
                    ->action('submit')
                    ->color('primary'),
            ]),
        ];
    }
}
