<?php

namespace Modules\Billing\Filament\Pages;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Modules\Billing\Models\SubscriptionCustomer;
use Modules\Order\Models\Order;
use Modules\Order\Models\OrderCancelReason;

class PurchaseCancelPage extends Page
{
    use InteractsWithForms;

    protected static ?string $title = 'Purchase Cancelled';

    protected static ?string $slug = 'purchase-cancel';

    protected static bool $shouldRegisterNavigation = false;

    protected string $view = 'modules.billing::filament.pages.purchase-cancel';

    public ?array $data = [];

    public ?array $checkoutSession = null;

    public ?array $order = null;

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
                ];

                // Get order details from metadata
                if (isset($session->metadata->internal_order_id)) {
                    $order = Order::find($session->metadata->internal_order_id);
                    if ($order) {
                        $this->order = [
                            'id' => $order->id,
                            'amount' => $order->amount,
                            'currency' => $order->currency ?? 'USD',
                            'created_at' => $order->created_at?->toFormattedDateString(),
                        ];
                    }
                }
            }
        } catch (\Exception $e) {
            // Silent fail - user can still submit feedback
        }

        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
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
            OrderCancelReason::create([
                'user_id' => $user->id,
                'order_id' => $this->order['id'] ?? null,
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
        return 'Purchase Cancelled';
    }
}
