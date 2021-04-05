<?php
namespace MicroweberPackages\Checkout\Http\Controllers\Traits;

use Illuminate\Http\Request;

trait PaymentTrait {

    public function paymentMethod() {

        // Validate Contact Information
        $validateContactInformation = $this->_validateContactInformation();;
        if ($validateContactInformation['valid'] == false) {
            session_set('errors', $validateContactInformation['errors']);
            return redirect(route('checkout.contact_information'));
        }

        // Validate Shipping Method
        $validateShippingMethod = $this->_validateShippingMethod();;
        if ($validateShippingMethod['valid'] == false) {
            session_set('errors', $validateShippingMethod['errors']);
            return redirect(route('checkout.shipping_method'));
        }

        return $this->_renderView('checkout::payment_method');
    }

    public function paymentMethodSave(Request $request) {
        return redirect(route('checkout.finish'));
    }
}
