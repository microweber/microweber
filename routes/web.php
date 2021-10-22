<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*
Route::get('get-m', function () {

    $getFormsData = \MicroweberPackages\Form\Models\FormData::all();
    if ($getFormsData->count() >0) {
        foreach ($getFormsData as $formData) {
            $findFormDataValues = \MicroweberPackages\Form\Models\FormDataValue::where('form_data_id', $formData->id)->first();
            if ($findFormDataValues == null) {

                $formDataFormValues = $formData->form_values;
                if (!empty($formDataFormValues)) {
                    foreach ($formDataFormValues as $dataKey=>$dataValue) {

                        $formDataValue = new \MicroweberPackages\Form\Models\FormDataValue();
                        $formDataValue->form_data_id = $formData->id;
                        $formDataValue->field_type = 'text';
                        $formDataValue->field_key = $dataKey;
                        $formDataValue->field_name = $dataKey;
                        $formDataValue->field_value = $dataValue;
                        $formDataValue->save();

                    }
                }
            }
        }
    }

});*/
