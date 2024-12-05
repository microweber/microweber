<?php

namespace Modules\Checkout\Livewire;

use Livewire\Component;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;

class CartItems extends Component
{
    use InteractsWithForms;

    public function mount()
    {
        $this->cartItems = app()->cart_manager->get() ?? [];
    }

    public function updateQuantity($itemId, $quantity)
    {

        $item = ['id' => $itemId, 'qty' => $quantity];
        app()->cart_manager->update_item_qty($item);
        $this->dispatch('cart-updated');
    }

    public function removeItem($itemId)
    {
        app()->cart_manager->remove_item($itemId);
        $this->dispatch('cart-updated');
    }

    public function render()
    {
        return view('modules.checkout::livewire.cart-items', [
            'cartItems' => app()->cart_manager->get() ?? []
        ]);
    }
}
