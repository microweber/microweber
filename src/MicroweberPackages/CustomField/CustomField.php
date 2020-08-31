<?php
namespace MicroweberPackages\CustomField;

use Illuminate\Database\Eloquent\Model;


class CustomField extends Model
{
    protected $fillable = [
        'value',
        'type',
        'options',
        'name',

        // 'edited_by',
        // 'created_by'
    ];

    protected $table = 'custom_fields';

    public $timestamps = true;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'options' => 'json',
    ];

    protected $attributes = [
        'is_active' => 1,
    ];

    public function fieldValue()
    {
        return $this->hasMany(CustomFieldValue::class, 'custom_field_id', 'id')->orderBy('position');
    }

    public function save(array $options = [])
    {
        $customFieldValueToSave = null;
        if (isset($this->value)) {
            $customFieldValueToSave = $this->value;

            unset($this->value);
        }

        if(!isset($this->id) && isset($this->rel_id) && isset($this->rel_type)) {
            //Create
            $position = CustomField::where([
                                    ['rel_id', '=', $this->rel_id],
                                    ['rel_type', '=', $this->rel_type]
                                    ])
                                    ->max('position');

            $this->position = $position + 1;
        }

        if(isset($this->name)) {
            $this->name_key = \Str::slug($this->name, '-');
        }

        $saved = parent::save($options);

        if (isset($customFieldValueToSave)) {
            //@todo   try to update instead of delete
            CustomFieldValue::where('custom_field_id', $this->id)->delete();

            if (is_array($customFieldValueToSave)) {
                foreach ($customFieldValueToSave as $val) {
                   $this->createCustomFieldValue($val);
                }
            } else {
                $this->createCustomFieldValue($customFieldValueToSave);
            }

        }

        return $saved;
     }

     private function createCustomFieldValue($val){
         $findValue = new CustomFieldValue();
         $findValue->value = $val;
         $findValue->customField()->associate($this);
         $findValue->save();
     }
}
