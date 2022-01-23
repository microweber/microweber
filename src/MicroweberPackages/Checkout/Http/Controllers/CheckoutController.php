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
use MicroweberPackages\App\Traits\LiveEditTrait;
use MicroweberPackages\Checkout\Http\Controllers\Traits\ContactInformationTrait;
use MicroweberPackages\Checkout\Http\Controllers\Traits\PaymentTrait;
use MicroweberPackages\Checkout\Http\Controllers\Traits\ShippingTrait;
use MicroweberPackages\Order\Models\Order;

class CheckoutController extends Controller {

    use LiveEditTrait;
    use ContactInformationTrait;
    use ShippingTrait;
    use PaymentTrait;

    public function login() {

        if (is_logged()) {
            return redirect(route('checkout.contact_information'));
        }

        return $this->_renderView('checkout::login');
    }

    public function forgotPassword() {

        if (is_logged()) {
            return redirect(route('checkout.contact_information'));
        }

        return $this->_renderView('checkout::forgot_password');
    }

    public function register() {

        if (is_logged()) {
            return redirect(route('checkout.contact_information'));
        }

        return $this->_renderView('checkout::register');
    }

    public function index() {

        return redirect(route('checkout.contact_information'));
        //return $this->_renderView('checkout::index');
    }

    public function finish($id) {

        $id = intval($id);
        if ($id < 1) {
            return redirect(site_url());
        }

        $findOrder = Order::where('id', $id)->first();
        if ($findOrder == null) {
            return redirect(site_url());
        }

        return $this->_renderView('checkout::finish', ['order'=>[
            'id'=>$findOrder->id
        ]]);
    }

    public function _renderView($view, $data = [])
    {
        $html = view($view, $data);

        // Append api js
        $html = app()->template->append_api_js_to_layout($html);

        $html = $this->appendLiveEdit($html);

        return app()->parser->process($html);
    }

    /**
     * Description: THIS METHOD IS FOR OLD VERSION OF CHECKOUT MODULE
     * @param Request $request
     * @return bool[]
    */
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

        if (get_option('shop_require_phone', 'website') == 1) {
            $rules['phone'] = 'required';
        }

        if (empty($rules)) {
            return ['valid'=>true];
        }

        $validator = \Validator::make($request->all(), $rules);

        session_append_array('checkout', [
            'first_name'=> $request->get('first_name'),
            'last_name'=> $request->get('last_name'),
            'email'=> $request->get('email'),
            'phone'=> $request->get('phone')
        ]);

        if ($validator->fails()) {

            $response = \Response::make(['errors'=>$validator->messages()->toArray()]);
            $response->setStatusCode(422);

            return $response;
        }

    }

}
