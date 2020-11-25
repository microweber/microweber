<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 11/25/2020
 * Time: 11:14 AM
 */

namespace MicroweberPackages\Checkout\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CheckoutController extends Controller {

    public function validate(Request $request)
    {

        $validator = \Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'shipping_gw' => 'required',
            'payment_gw' => 'required',
            'city' => 'required',
            'address' => 'required',
        ]);

        session_set('checkout', [
            'first_name'=> $request->get('first_name'),
            'last_name'=> $request->get('last_name'),
            'email'=> $request->get('email'),
            'phone'=> $request->get('phone'),
            'shipping_gw'=> $request->get('shipping_gw'),
            'payment_gw'=> $request->get('payment_gw'),
            'city'=> $request->get('city'),
            'address'=> $request->get('address'),
            'country'=> $request->get('country'),
            'state'=> $request->get('state'),
            'zip'=> $request->get('zip'),
            'other_info'=> $request->get('other_info'),
        ]);

        if ($validator->fails()) {
            return ['errors'=>$validator->messages()->toArray()];
        }

    }

}