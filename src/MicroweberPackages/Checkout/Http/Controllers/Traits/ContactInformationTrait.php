<?php
namespace MicroweberPackages\Checkout\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

trait ContactInformationTrait {

    public function contactInformation() {

        $data = [];
        $data['errors'] = session_get('errors');
        $data['checkout_session'] = session_get('checkout');

        return $this->_renderView('checkout::contact_information',$data);
    }

    public function contactInformationSave(Request $request) {

        session_del('errors');

        session_set('checkout', [
            'first_name'=> $request->get('first_name'),
            'last_name'=> $request->get('last_name'),
            'email'=> $request->get('email'),
            'phone'=> $request->get('phone')
        ]);

        $validate = $this->_validateContactInformation($request->all());
        if ($validate['valid'] == false) {
            session_set('errors', $validate['errors']);
            return redirect()->back();
        }

        // Success
        return redirect(route('checkout.shipping_method'));
    }

    private function _validateContactInformation($inputData = [])
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

        // $rules['phone-testing'] = 'required';

        if (empty($rules)) {
            return ['valid'=>true];
        }

        if (empty($inputData)) {
            $inputData = session_get('checkout');
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
