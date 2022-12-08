<?php
namespace MicroweberPackages\Checkout\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        $data['checkout_session'] = session_get('checkout_v2');

        session_del('errors');

        return $this->_renderView('checkout::shipping_method', $data);
    }

    public function shippingMethodChange(Request $request) {

        app()->shipping_manager->setDefaultDriver($request->get('shipping_gw'));

        session_append_array('checkout_v2', [
            'shipping_gw'=> $request->get('shipping_gw')
        ]);
        return ['success'=>true];
    }

    public function shippingMethodSave(Request $request) {

        if (is_array($request->get('Address'))) {
            $request->merge([
                'city'=>$request->get('Address')['city'],
                'zip'=>$request->get('Address')['zip'],
                'state'=>$request->get('Address')['state'],
                'address'=>$request->get('Address')['address'],
            ]);
        }

        $rules = [];
        $rules['shipping_gw'] = 'max:500';
        $rules['city'] = 'max:500';
        $rules['address'] = 'max:500';
        $rules['country'] = 'max:500';
        $rules['state'] = 'max:500';
        $rules['zip'] = 'max:500';
        $rules['other_info'] = 'max:500';

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->messages()->toArray();
            session_set('errors', $errors);
            return redirect(route('checkout.shipping_method'));
        }

        session_append_array('checkout_v2', [
            'shipping_gw'=> $request->get('shipping_gw'),
            'city'=> $request->get('city'),
            'address'=> $request->get('address'),
            'country'=> $request->get('country'),
            'state'=> $request->get('state'),
            'zip'=> $request->get('zip'),
            'other_info'=> $request->get('other_info'),
        ]);

        $checkIfShippingEnabled = app()->shipping_manager->getShippingModules(true);
        if ($checkIfShippingEnabled) {
            $validate = $this->_validateShippingMethod();
            if ($validate['valid'] == false) {
                session_set('errors', $validate['errors']);
                return redirect(route('checkout.shipping_method'));
            }
        }

        // Success
        return redirect(route('checkout.payment_method'));
    }

    private function _validateShippingMethod()
    {
        $checkout_session = session_get('checkout_v2');
        if (empty($checkout_session['shipping_gw'])) {
            return ['valid' => false, 'errors' => [
                'payment_errors'=>['error'=>_e('Must select shipping method', true)]
            ]];
        }

        try {
            return app()->shipping_manager->driver($checkout_session['shipping_gw'])->validate($checkout_session);
        } catch (\Exception $e) {
            return ['valid' => false, 'errors' => [
                'payment_errors'=>['error'=>_e('Must select shipping method', true)]
            ]];
        }
    }
}
