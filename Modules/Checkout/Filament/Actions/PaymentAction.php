<?php

namespace Modules\Checkout\Filament\Actions;

use Filament\Actions\Action;
use Closure;

class PaymentAction extends Action
{
    public ?Closure $request = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->requiresConfirmation();
        $this->icon('heroicon-s-credit-card');
        $this->iconButton();
        $this->color('info');

        $this->modalHeading('Process Payment');
        $this->modalDescription('Please confirm your payment details before proceeding.');

        $this->form([
            \Filament\Forms\Components\View::make('modules.payment::livewire.payment-method-selector')
                ->columnSpan('full'),
        ]);

//        $this->action(function (array $data) {
//            if ($this->request) {
//                return call_user_func($this->request, $data);
//            }
//
//            // Default payment processing
//            try {
//                Event::dispatch('checkout.payment.before');
//
//                $paymentMethod = app()->user_manager->session_get('payment_provider');
//                if (!$paymentMethod) {
//                    $this->failure('Please select a payment method');
//                    return;
//                }
//
//                // Process payment with selected method
//                $result = app()->payment_manager->process([
//                    'payment_method' => $paymentMethod,
//                    'amount' => app()->cart_manager->total()['total'],
//                ]);
//
//                if ($result['success']) {
//                    Event::dispatch('checkout.payment.after', ['result' => $result]);
//                    $this->success('Payment processed successfully');
//                    return;
//                }
//
//                $this->failure('Payment processing failed');
//            } catch (\Exception $e) {
//                $this->failure($e->getMessage());
//            }
//        });
    }

    public function onRequest(Closure $callback): static
    {
        $this->request = $callback;
        return $this;
    }
}
