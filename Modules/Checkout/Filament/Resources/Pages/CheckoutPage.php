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

        // Redirect to cart if still empty.
        //
        // task-2026-05-17-7c3881 / AI-851 — destination now carries the
        // `notice=empty-cart-no-checkout` query param so the AI-796 cart
        // empty-state view shows the "You tried to check out but your
        // cart is empty" banner. Matches the bare /checkout short-circuit
        // in Modules/Checkout/Http/Middleware/RedirectEmptyCheckoutToCart;
        // users reaching this URL via bookmarks/direct-nav land on the
        // same destination with the same notice as users typing /checkout.
        if (!app()->cart_manager->get()) {
            $cartUrl = \Route::has('shop.cart') ? route('shop.cart') : '/cart';
            $cartUrl .= '?' . \Modules\Checkout\Http\Middleware\RedirectEmptyCheckoutToCart::NOTICE_PARAM
                . '=' . \Modules\Checkout\Http\Middleware\RedirectEmptyCheckoutToCart::NOTICE_VALUE;
            $this->redirect($cartUrl);
        }
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
