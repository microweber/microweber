<?php
namespace MicroweberPackages\CustomField;

use Illuminate\Database\Eloquent\Model;

class CustomField extends Model
{
    protected $table = 'custom_fields';
    protected $primaryKey = 'id';

    protected $fillable = [
        'rel_type',
        'rel_id',
        'type',
        'name',
        'name_key',
        'placeholder',
        'error_text',
        'options',
        'show_label',
        'is_active',
        'required',
    ];

    public function value()
    {
        return $this->morphMany(CustomFieldValue::class,'custom_field_id', 'id');
    }

    public function save(array $options = [])
    {
        if (isset($this->value)) {
           $findValue = CustomFieldValue::where('custom_field_id', $this->id)->where('value', $this->value)->first();
           if (!$findValue) {
               $findValue = new CustomFieldValue();
           }
           $findValue->custom_field_id = $this->id;
           $findValue->value = $this->value;
           $findValue->save();

        }

        unset($this->value);

        return parent::save($options);
    }
}
