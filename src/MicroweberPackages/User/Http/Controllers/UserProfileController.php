<?php

namespace MicroweberPackages\User\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Customer\Models\Address;
use Modules\Customer\Models\Customer;

class UserProfileController extends Controller
{
    /**
     * Create a new controller instance.
     * @return void
     */
    public function __construct()
    {
        $this->middleware('throttle:10,1')->only('verify', 'resend');
    }

    public function update(Request $request) {

        $userId = Auth::id();
        if(!$userId){
            return response()->json(['error' => 'You are not logged in.'], 401);
        }

        $name = $request->post('name');
        $firstName = $request->post('first_name');
        $lastName = $request->post('last_name');
        $email = $request->post('email');
        $phone = $request->post('phone');

        $findCustomer = Customer::where('user_id',$userId)->first();
        if ($findCustomer == null) {
            $findCustomer = new Customer();
            $findCustomer->user_id = $userId;
        }

        $findCustomer->name = $name;
        $findCustomer->first_name = $firstName;
        $findCustomer->last_name = $lastName;
        $findCustomer->email = $email;
        $findCustomer->phone = $phone;
        $findCustomer->active = 1;
        $findCustomer->save();

        $addresses = $request->post('addresses');

        if (!empty($addresses)) {
            foreach ($addresses as $address) {
                $findAddress = Address::where('type', $address['type'])->where('customer_id', $findCustomer->id)->first();
                if ($findAddress == null) {
                    $findAddress = new Address();
                    $findAddress->type = $address['type'];
                    $findAddress->customer_id = $findCustomer->id;
                }
                $findAddress->name = $address['name'];

                $findAddress->address_street_1 = $address['address_street_1'];
                if (isset($address['address_street_2'])) {
                    $findAddress->address_street_2 = $address['address_street_2'];
                }

                $findAddress->city = $address['city'];
                $findAddress->state = $address['state'];

                if (isset($address['country_id'])) {
                    $findAddress->country_id = $address['country_id'];
                }

                $findAddress->zip = $address['zip'];
                $findAddress->save();
            }
        }

    }
}
