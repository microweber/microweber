<?php

namespace MicroweberPackages\Customer\Listeners;

use MicroweberPackages\Customer\Models\Address;
use MicroweberPackages\Customer\Models\Customer;
use MicroweberPackages\Order\Models\Order;

class CreateCustomerFromOrderListener
{
    public function handle($event)
    {
        $order = $event->getModel();
        if ($order) {

            $findCustomer = false;
            $findCustomerByEmail = Customer::where('email', $order->email)->first();
            if ($findCustomerByEmail) {
                $findCustomer = $findCustomerByEmail;
            }

            if (!$findCustomer) {
                $findCustomerByPhone = Customer::where('phone', $order->phone)->first();
                if ($findCustomerByPhone) {
                    $findCustomer = $findCustomerByPhone;
                }
            }

            if (!$findCustomer) {
                $createNewCustomer = Customer::create([
                    'user_id' => $order->created_by,
                    'name' => $order->first_name,
                    'first_name' => $order->first_name,
                    'last_name' => $order->last_name,
                    'email' => $order->email,
                    'phone' => $order->phone
                ]);
                $findCustomer = $createNewCustomer;
            }

            $findOrder = Order::where('id', $order->id)->first();
            if ($findOrder) {
                $findOrder->customer_id = $findCustomer->id;
                $findOrder->save();
            }

            $findCustomerAddressByCustomerId = Address::where('customer_id', $findCustomer->id)
                ->where('city', $order->city)
                ->where('address_street_1', $order->address)
                ->first();
            if (!$findCustomerAddressByCustomerId) {
                Address::create([
                    'name' => 'Default',
                    'type' => 'shipping',
                    'customer_id' => $findCustomer->id,
                    'city' => $order->city,
                    'phone' => $order->phone,
                    'address_street_1' => $order->address,
                    'address_street_2' => $order->address2,
                    'state' => $order->state,
                    'zip' => $order->zip
                ]);
            }

        }

    }

}
