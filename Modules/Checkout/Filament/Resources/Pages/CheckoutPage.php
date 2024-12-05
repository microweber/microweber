<?php

namespace Modules\Checkout\Filament\Resources\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Checkout\Filament\Resources\CheckoutResource;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Event;

class CheckoutPage extends CreateRecord
{


    protected static string $resource = CheckoutResource::class;

    protected static string $view = 'modules.checkout::filament.pages.checkout';

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
        $this->form->fill();
    }

    public function submit()
    {
        $data = $this->form->getState();

        try {
            // Save contact information
            app()->user_manager->session_set('checkout_first_name', $data['first_name']);
            app()->user_manager->session_set('checkout_last_name', $data['last_name']);
            app()->user_manager->session_set('checkout_email', $data['email']);
            app()->user_manager->session_set('checkout_phone', $data['phone']);

            // Save shipping information
            app()->user_manager->session_set('checkout_address', $data['address']);
            app()->user_manager->session_set('checkout_city', $data['city']);
            app()->user_manager->session_set('checkout_state', $data['state']);
            app()->user_manager->session_set('checkout_postal_code', $data['postal_code']);
            app()->user_manager->session_set('checkout_country', $data['country']);

            // Process payment
            Event::dispatch('checkout.payment.before');

            // Create order
            $order = app()->order_manager->create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'city' => $data['city'],
                'state' => $data['state'],
                'postal_code' => $data['postal_code'],
                'country' => $data['country'],
            ]);

            Event::dispatch('checkout.payment.after', ['order' => $order]);

            Notification::make()
                ->title('Order placed successfully')
                ->success()
                ->send();

            return redirect()->to('/thank-you');

        } catch (\Exception $e) {
            Notification::make()
                ->title('Error processing order')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
