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
use JaOcero\RadioDeck\Forms\Components\RadioDeck;

class UserSubscriptionPanel extends Page
{
    protected static string $view = 'modules.billing::filament.pages.user-subscription-panel';

    protected static ?string $title = 'My Subscription';

    protected static ?string $slug = 'user-subscription';


    public ?string $plan = null;

    protected function getFormSchema(): array
    {
        $plans = SubscriptionPlan::query()->get();

        $options = [];
        $descriptions = [];
        $icons = [];
        $plansOfTheUser = getUserActiveSubscriptionPlans();

        if($plans) {
            foreach ($plans as $plan) {
                $options[$plan->sku] = $plan->name;
                $descriptions[$plan->sku] = $plan->description ?? '';
                $icons[$plan->sku] = 'heroicon-m-currency-dollar';
            }
        }
        return [
            RadioDeck::make('plan')
                ->label('Choose a Subscription Plan')
                ->options($options)
                ->descriptions($descriptions)
                ->icons($icons)
                ->required()
                ->columns(3),
        ];
    }

//    public function form(Form $form): Form
//    {
//        return $form
//            ->schema($this->getFormSchema())
//            ->statePath('data');
//    }

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
