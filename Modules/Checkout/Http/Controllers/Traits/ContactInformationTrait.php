<?php
namespace Modules\Checkout\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Checkout\Events\BeginCheckoutEvent;

trait ContactInformationTrait {

    public function contactInformation() {

        $data = [];
        $data['errors'] = session_get('errors');
        $data['checkout_session'] = session_get('checkout_v2');

        if (is_logged()) {
            $user = get_user();
            if (empty($data['checkout_session']['first_name'])) {
                $data['checkout_session']['first_name'] = $user['first_name'];
            }
            if (empty($data['checkout_session']['last_name'])) {
                $data['checkout_session']['last_name'] = $user['last_name'];

            }
            if (empty($data['checkout_session']['email'])) {
                $data['checkout_session']['email'] = $user['email'];
            }
            if (empty($data['checkout_session']['phone'])) {
                $data['checkout_session']['phone'] = $user['phone'];
            }
        }

        session_del('errors');

        event(new BeginCheckoutEvent([
            'cart'=>get_cart(),
            'total'=>cart_total(),
            'discount'=>cart_get_discount(),
            'currency'=>get_currency_code(),
        ]));

        return $this->_renderView('modules.checkout::contact_information',$data);
    }

    public function contactInformationSave(Request $request) {

        $firstName = strip_tags($request->get('first_name'));
        $lastName = strip_tags($request->get('last_name'));
        $email = strip_tags($request->get('email'));
        $phone = strip_tags($request->get('phone'));

        session_append_array('checkout_v2', [
            'first_name'=> $firstName,
            'last_name'=> $lastName,
            'email'=> $email,
            'phone'=> $phone
        ]);

        $validate = $this->_validateContactInformation($request->all());
        if ($validate['valid'] == false) {
            session_set('errors', $validate['errors']);
            return redirect(route('checkout.contact_information'));
        }

        // Success
        return redirect(route('checkout.shipping_method'));
    }

    private function _validateContactInformation($inputData = [])
    {
        $rules = [];

        $rules['first_name'] = 'max:500';
        $rules['last_name'] = 'max:500';
        $rules['email'] = 'email|max:500';
        $rules['phone'] = 'max:500';

        if (get_option('shop_require_first_name', 'website') == 1) {
            $rules['first_name'] .= '|required';
        }

        if (get_option('shop_require_last_name', 'website') == 1) {
            $rules['last_name'] .= '|required';
        }

        if (get_option('shop_require_email', 'website') == 1) {
            $rules['email'] .= '|required';;
        }

        if (get_option('shop_require_phone', 'website') == 1) {
            $rules['phone'] .= '|required';;
        }

        if (empty($rules)) {
            return ['valid'=>true];
        }

        if (empty($inputData)) {
            $inputData = session_get('checkout_v2');
        }

        if (empty($inputData)) {
            return [
                'valid'=>false,
                'errors'=>[
                   'form_errors'=>['error'=>_e('Please, fill the contact information data.', true)]
                ]
            ];
        }

        $validator = Validator::make($inputData, $rules);

        if ($validator->fails()) {
            $errors = $validator->messages()->toArray();
            return ['valid'=>false,'errors'=>$errors];
        }

        return ['valid'=>true];
    }
}
