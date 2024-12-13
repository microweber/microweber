<?php

namespace Modules\Checkout\Filament\Resources\Pages;

use Filament\Resources\Pages\Page;
use Modules\Checkout\Filament\Resources\CheckoutResource;

class CheckoutSuccessPage extends Page
{
    protected static string $resource = CheckoutResource::class;

    protected static string $view = 'modules.checkout::filament.pages.checkout-success';
    public function getBreadcrumb(): string
    {
        return '';
    }
    public function getTitle(): string
    {
        return 'Success';
    }

    public function mount(): void
    {

//        // Clear checkout session data
//        app()->user_manager->session_del('checkout_first_name');
//        app()->user_manager->session_del('checkout_last_name');
//        app()->user_manager->session_del('checkout_email');
//        app()->user_manager->session_del('checkout_phone');
//        app()->user_manager->session_del('checkout_country');
//        app()->user_manager->session_del('checkout_city');
//        app()->user_manager->session_del('checkout_state');
//        app()->user_manager->session_del('checkout_postal_code');
//        app()->user_manager->session_del('checkout_address');
//        app()->user_manager->session_del('shipping_provider');
//        app()->user_manager->session_del('payment_provider');
    }
}
