<?php

namespace Modules\Payment\Livewire;

use Livewire\Component;
use Modules\Payment\Drivers\PayPal;
use Modules\Payment\Drivers\Stripe;
use Modules\Payment\Drivers\PayOnDelivery;

class PaymentMethodSelector extends Component
{
    public $selectedMethod = '';
    public $availableMethods = [];

    public function mount()
    {
        $this->availableMethods = payment_options();
        $selected = app()->user_manager->session_get('payment_provider');
        // Set default selected method
        if (!$selected and !empty($this->availableMethods)) {
            $this->selectedMethod = array_key_first($this->availableMethods);
        }
    }

    public function selectMethod($method) : void
    {
        $this->selectedMethod = $method;
        $this->dispatch('payment-method-changed', method: $method);
    }

    public function render()
    {
        return view('modules.payment::livewire.payment-method-selector', [
            'availableMethods' => $this->availableMethods,
            'selectedMethod' => $this->selectedMethod,
        ]);
    }
}
