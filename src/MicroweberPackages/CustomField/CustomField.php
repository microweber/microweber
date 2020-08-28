<?php
namespace MicroweberPackages\CustomField;

use Illuminate\Database\Eloquent\Model;

class CustomField extends Model
{
    protected $table = 'custom_fields';

    public $timestamps = true;

//    protected $fillable = [
//        'rel_type',
//        'rel_id',
//        'type',
//        'name',
//        'name_key',
//        'placeholder',
//        'error_text',
//        'options',
//        'show_label',
//        'is_active',
//        'required',
//    ];

    public function fieldValue()
    {
        return $this->hasMany(CustomFieldValue::class, 'custom_field_id', 'id');
    }

    public function save(array $options = [])
    {
        if (isset($this->value)) {

            CustomFieldValue::where('custom_field_id', $this->id)->delete();

            if (is_array($this->value)) {
                foreach ($this->value as $val) {
                    $findValue = new CustomFieldValue();
                    $findValue->custom_field_id = $this->id;
                    $findValue->value = $val;
                    $findValue->save();
                }
            } else {
                $findValue = new CustomFieldValue();
                $findValue->custom_field_id = $this->id;
                $findValue->value = $this->value;
                $findValue->save();
            }

            unset($this->value);
        }

        return parent::save($options);
    }
}
