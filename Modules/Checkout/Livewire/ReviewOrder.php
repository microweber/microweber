<?php

namespace Modules\Checkout\Livewire;

use Livewire\Component;

class ReviewOrder extends Component
{
    protected $listeners = ['cart-updated' => '$refresh'];

    public function render()
    {
        $cartItems = app()->cart_manager->get() ?? [];
        $cartTotal = cart_total();

        return view('modules.checkout::livewire.review-order', [
            'cartItems' => $cartItems,
            'cartTotal' => $cartTotal
        ]);
    }
}
