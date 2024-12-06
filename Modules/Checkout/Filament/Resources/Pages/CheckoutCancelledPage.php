<?php

namespace Modules\Checkout\Filament\Resources\Pages;

use Filament\Resources\Pages\Page;
use Modules\Checkout\Filament\Resources\CheckoutResource;

class CheckoutCancelledPage extends Page
{
    protected static string $resource = CheckoutResource::class;

    protected static string $view = 'modules.checkout::filament.pages.checkout-cancelled';
    public function getBreadcrumb(): string
    {
        return '';
    }
    public function getTitle(): string
    {
        return 'Cancelled';
    }
    public function mount(): void
    {

        $order_id = app()->user_manager->session_get('order_id');

        if($order_id) {

            app()->cart_manager->recover_cart($order_id);
        }

    }
}
