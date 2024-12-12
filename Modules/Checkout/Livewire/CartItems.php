<?php

namespace Modules\Checkout\Livewire;

use Filament\Actions\Concerns\InteractsWithActions;
use Livewire\Attributes\On;
use Livewire\Component;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;

class CartItems extends Component implements HasForms
{
    use InteractsWithForms;
    use InteractsWithActions;

    public array $cartItems = [];
    public array $cartTotals = [];

    public function mount()
    {
        $this->cartItems = app()->cart_manager->get() ?? [];
        $this->cartTotals = mw()->cart_manager->totals();
    }

    #[On('reload-cart')]
    public function reloadCart()
    {
        $this->cartItems = app()->cart_manager->get() ?? [];
        $this->cartTotals = mw()->cart_manager->totals();
    }

    public function updateQuantity($itemId, $quantity)
    {
        $item = ['id' => $itemId, 'qty' => $quantity];
        app()->cart_manager->update_item_qty($item);
        $this->cartTotals = mw()->cart_manager->totals();
        $this->dispatch('cart-updated');
    }

    public function removeItem($itemId)
    {
        app()->cart_manager->remove_item($itemId);
        $this->cartTotals = mw()->cart_manager->totals();
        $this->dispatch('cart-updated');
    }

    public function render()
    {
        return view('modules.checkout::livewire.cart-items', [
            'cartItems' => app()->cart_manager->get() ?? [],
            'cartTotals' => mw()->cart_manager->totals()
        ]);
    }
}
