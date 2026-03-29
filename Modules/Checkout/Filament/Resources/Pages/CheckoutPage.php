<?php

namespace Modules\Checkout\Filament\Resources\Pages;

use Filament\Resources\Pages\Page;
use Modules\Checkout\Filament\Resources\CheckoutResource;

class CheckoutPage extends Page
{
    protected static string $resource = CheckoutResource::class;

    protected string $view = 'modules.checkout::filament.pages.checkout-wizard-page';

    public function getBreadcrumb(): string
    {
        return '';
    }

    public function getTitle(): string
    {
        return 'Checkout';
    }

    public function mount(): void
    {
        // Check if cart is empty
        $cart = get_cart();
        $order_id = app()->user_manager->session_get('order_id');

        if (!$cart && $order_id) {
            app()->cart_manager->recover_cart($order_id);
        }

        // Redirect to cart if still empty
        if (!app()->cart_manager->get()) {
            $cartUrl = \Route::has('shop.cart') ? route('shop.cart') : '/cart';
            $this->redirect($cartUrl);
        }
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
