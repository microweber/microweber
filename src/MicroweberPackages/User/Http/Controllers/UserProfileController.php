<?php

namespace MicroweberPackages\User\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use MicroweberPackages\Customer\Models\Address;
use MicroweberPackages\User\Models\User;

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

        $addresses = $request->post('addresses');

        if (!empty($addresses)) {
            foreach ($addresses as $address) {
                $findAddress = Address::where('type', $address['type'])->first();
                if ($findAddress == null) {
                    $findAddress = new Address();
                    $findAddress->type = $address['type'];
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
