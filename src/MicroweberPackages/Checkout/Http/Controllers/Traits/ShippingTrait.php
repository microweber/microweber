<?php
namespace MicroweberPackages\Checkout\Http\Controllers\Traits;

use Illuminate\Http\Request;

trait ShippingTrait {

    public function shippingMethod() {

        // Validate Contact Information
        $validateContactInformation = $this->_validateContactInformation();;
        if ($validateContactInformation['valid'] == false) {
            session_set('errors', $validateContactInformation['errors']);
            return redirect(route('checkout.contact_information'));
        }

        $data = [];
        $data['errors'] = session_get('errors');
        $data['checkout_session'] = session_get('checkout');

        return $this->_renderView('checkout::shipping_method', $data);
    }

    public function shippingMethodSave(Request $request) {

        session_del('errors');

        session_set('checkout', [
            'shipping_gw'=> $request->get('shipping_gw'),
            'city'=> $request->get('city'),
            'address'=> $request->get('address'),
            'country'=> $request->get('country'),
            'state'=> $request->get('state'),
            'zip'=> $request->get('zip'),
            'other_info'=> $request->get('other_info'),
        ]);

        $validate = $this->_validateShippingMethod();
        if ($validate['valid'] == false) {
            session_set('errors', $validate['errors']);
            return redirect()->back();
        }

        // Success
        return redirect(route('checkout.payment_method'));
    }

    private function _validateShippingMethod()
    {
        if (get_option('shop_require_state', 'website') == 1) {
            $rules['state'] = 'required';
        }

        if (get_option('shop_require_country', 'website') == 1) {
            $rules['country'] = 'required';
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

       // $rules['state-test'] = 'required';

        if (empty($rules)) {
            return ['valid'=>true];
        }

        $validator = \Validator::make(session_get('checkout'), $rules);

        if ($validator->fails()) {
            $errors = $validator->messages()->toArray();
            return ['valid'=>false,'errors'=>$errors];
        }

        return ['valid'=>true];
    }
}
