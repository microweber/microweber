<?php

namespace Modules\Billing\Filament\Pages;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Forms\Schema;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Modules\Billing\Models\Subscription;
use Modules\Billing\Models\SubscriptionCancelReason;
use Modules\Billing\Models\SubscriptionCustomer;

class SubscriptionCancelPage extends Page
{
    use InteractsWithForms;

    protected static ?string $title = 'Subscription Cancelled';

    protected static ?string $slug = 'subscription-cancel';

    protected static bool $shouldRegisterNavigation = false;

    protected string $view = 'modules.billing::filament.pages.subscription-cancel';

    public ?array $data = [];

    public ?array $checkoutSession = null;

    public ?array $subscription = null;

    public function mount(): void
    {
        $user = Auth::user();
        if (!$user) {
            $this->redirect(route('login'));
            return;
        }

        $sessionId = request()->get('session_id');
        if (!$sessionId) {
            return;
        }

        $customer = SubscriptionCustomer::firstOrCreate([
            'user_id' => $user->id,
            'email' => $user->email,
        ]);

        try {
            $stripe = $customer->stripe();
            $session = $stripe->checkout->sessions->retrieve($sessionId);

            if ($session) {
                $this->checkoutSession = [
                    'id' => $session->id,
                    'payment_status' => $session->payment_status,
                    'subscription' => $session->subscription ?? null,
                ];

                // Get local subscription record
                if ($session->subscription) {
                    $localSubscription = Subscription::where('stripe_id', $session->subscription)
                        ->where('user_id', $user->id)
                        ->with('plan')
                        ->first();

                    if ($localSubscription) {
                        $this->subscription = [
                            'id' => $localSubscription->id,
                            'stripe_id' => $localSubscription->stripe_id,
                            'plan_name' => $localSubscription->plan?->name ?? 'Unknown Plan',
                            'status' => $localSubscription->stripe_status,
                        ];
                    }
                }
            }
        } catch (\Exception $e) {
            // Silent fail - user can still submit feedback
        }

        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Textarea::make('reason')
                    ->label('Please tell us why you\'re cancelling (optional)')
                    ->placeholder('Your feedback helps us improve our service...')
                    ->rows(4)
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    public function submitReason(): void
    {
        $data = $this->form->getState();

        $user = Auth::user();

        // Save cancellation reason
        if (!empty($data['reason'])) {
            SubscriptionCancelReason::create([
                'user_id' => $user->id,
                'subscription_id' => $this->subscription['id'] ?? null,
                'stripe_session_id' => $this->checkoutSession['id'] ?? null,
                'reason' => $data['reason'],
                'ip_address' => request()->ip(),
            ]);
        }

        Notification::make()
            ->title('Feedback submitted')
            ->body('Thank you for your feedback. We\'re sorry to see you go.')
            ->success()
            ->send();

        $this->redirect(route('filament.billing.home'));
    }

    public function getBreadcrumb(): string
    {
        return '';
    }

    public function getTitle(): string
    {
        return 'Subscription Cancelled';
    }
}
