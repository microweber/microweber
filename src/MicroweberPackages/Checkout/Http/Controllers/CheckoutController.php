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

        $rules = [];

        if (get_option('shop_require_first_name', 'website') == 1) {
            $rules['first_name'] = 'required';
        }

        if (get_option('shop_require_last_name', 'website') == 1) {
            $rules['last_name'] = 'required';
        }

        if (get_option('shop_require_email', 'website') == 1) {
            $rules['email'] = 'required|email';
        }

        if (get_option('shop_require_state', 'website') == 1) {
            $rules['state'] = 'required';
        }

        if (get_option('shop_require_country', 'website') == 1) {
            $rules['country'] = 'required';
        }

        if (get_option('shop_require_phone', 'website') == 1) {
            $rules['phone'] = 'required';
        }

        if (get_option('shop_require_address', 'website') == 1) {
            $rules['address'] = 'required';
        }

        if (get_option('shop_require_city', 'website') == 1) {
            $rules['city'] = 'required';
        }

        if (get_option('shop_require_zip', 'website') == 1) {
            $rules['zip'] = 'required';
        }

        if (get_option('shop_require_state', 'website') == 1) {
            $rules['state'] = 'required';
        }

        if (empty($rules)) {
            return ['valid'=>true];
        }

        $validator = \Validator::make($request->all(), $rules);

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

            $response = \Response::make(['errors'=>$validator->messages()->toArray()]);
            $response->setStatusCode(422);

            return $response;
        }

    }

}