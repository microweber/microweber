<?php
namespace MicroweberPackages\Form\Models;

use Illuminate\Database\Eloquent\Model;

class FormData extends Model
{
    protected $table = 'forms_data';

    public function formDataValues()
    {
        return $this->hasMany(FormDataValue::class);
    }

    /**
     * This functions is backward compatibility with old format data values
     * @return array
     */
    public function getFormValuesAttribute()
    {
        $values = [];
        $formDataValues = $this->formDataValues()->get();

        if ($formDataValues->count() > 0) {
            foreach ($formDataValues as $formDataValue) {
                if (is_array($formDataValue->field_value_json) && !empty($formDataValue->field_value_json)) {
                    $values[$formDataValue->field_name] = $formDataValue->field_value_json;
                } else {
                    $values[$formDataValue->field_name] = $formDataValue->field_value;
                }
            }
        }
        return $values;
    }
}
