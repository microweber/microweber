<?php

namespace Modules\Billing\Http\Controllers;



use Illuminate\Http\Request;

class CustomerProfileController
{

    public function saveCustomerProfile(Request $request){

        $customer = getSubscriptionCustomer();

        if (!$customer) {
            abort('403', 'Customer not found');
        }

        return redirect()->back();
    }
}
