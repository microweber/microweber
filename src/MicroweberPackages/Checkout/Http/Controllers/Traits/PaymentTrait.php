<?php
namespace MicroweberPackages\Checkout\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use MicroweberPackages\Checkout\Events\AddPaymentInfoEvent;
use MicroweberPackages\Checkout\Events\AddShippingInfoEvent;

trait PaymentTrait {

    public function paymentMethod() {

        // Validate Contact Information
        $validateContactInformation = $this->_validateContactInformation();;
        if ($validateContactInformation['valid'] == false) {
            session_set('errors', $validateContactInformation['errors']);
            return redirect(route('checkout.contact_information'));
        }

        // Validate Shipping Method
        $checkIfShippingEnabled = app()->shipping_manager->getShippingModules(true);
        if ($checkIfShippingEnabled) {
            $validateShippingMethod = $this->_validateShippingMethod();
            if ($validateShippingMethod['valid'] == false) {
                session_set('errors', $validateShippingMethod['errors']);
                return redirect(route('checkout.shipping_method'));
            }
        }
        $data = [];
        $data['errors'] = session_get('errors');
        $data['checkout_session'] = session_get('checkout_v2');

        session_del('errors');

        return $this->_renderView('checkout::payment_method', $data);
    }

    public function paymentMethodChange(Request $request) {

        session_append_array('checkout_v2', [
            'payment_gw'=> $request->get('payment_gw')
        ]);

        event(new AddPaymentInfoEvent([
            'paymentGateway'=> $request->get('payment_gw')
        ]));

        return ['success'=>true];
    }

    public function paymentMethodSave(Request $request) {

        session_append_array('checkout_v2', $request->all());

        $checkoutData = session_get('checkout_v2');
        // Add new session to old session
        session_set('checkout', $checkoutData);

        if (empty($checkoutData['payment_gw'])) {
            // check if we have any payment options enabled
            $payment_options = payment_options();
            if ($payment_options) {
                session_set('errors', [
                    'payment_errors' => ['error' => _e('Must select payment method', true)]
                ]);
                return redirect(route('checkout.payment_method'));
            }
        }

        try {
            $sendCheckout = app()->checkout_manager->checkout($checkoutData);
        } catch (\Exception $e) {
            session_set('errors', [
                'payment_errors'=>['error'=>$e->getMessage()]
            ]);
            return redirect(route('checkout.payment_method'));
        }

        if (isset($sendCheckout['redirect'])) {
            return redirect($sendCheckout['redirect']);
        }



        if (isset($sendCheckout['success']) and !empty($sendCheckout['success'])) {
            if(is_ajax()) {
                return $sendCheckout['success'];
            } else {
                return redirect(route('checkout.finish', $sendCheckout['id']))->with('success',$sendCheckout['success']);
            }

        }

        // Payment error
        if (isset($sendCheckout['error'])) {
            session_set('errors', [
                'payment_errors'=>['error'=>$sendCheckout['error']]
            ]);
            return redirect(route('checkout.payment_method'))->with('error',$sendCheckout['error']);
        }


        // Cart is empty
        if (isset($sendCheckout['error']['cart_empty'])) {
            session_set('errors', [
                'payment_errors'=>['error'=>$sendCheckout['error']['cart_empty']]
            ]);
            return redirect(route('checkout.cart'));
        }

        session_del('checkout_v2');
        if (function_exists('coupons_delete_session')) {
            coupons_delete_session();
        }

        return redirect(route('checkout.finish', $sendCheckout['id']));
    }
}
