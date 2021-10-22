<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MigrateOldFormsData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
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
                            $formDataValue->field_key = str_slug($dataKey);
                            $formDataValue->field_name = $dataKey;

                            if (is_array($dataValue)) {
                                $formDataValue->field_value_json = $dataValue;
                            } else {
                                $formDataValue->field_value = $dataValue;
                            }

                            $formDataValue->save();

                        }
                    }
                }
            }
        }
    }
}
