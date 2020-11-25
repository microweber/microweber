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

        if ($validator->fails()) {
            return ['errors'=>$validator->messages()->toArray()];
        }

    }

}