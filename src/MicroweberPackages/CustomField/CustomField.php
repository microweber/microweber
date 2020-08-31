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
        $cf_values_to_save = null;
        if (isset($this->value)) {
            $cf_values_to_save = $this->value;

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

        if (isset($cf_values_to_save)) {

            CustomFieldValue::where('custom_field_id', $this->id)->delete();

            if (is_array($cf_values_to_save)) {
                foreach ($cf_values_to_save as $val) {
                    $findValue = new CustomFieldValue();
                    $findValue->value = $val;
                    $findValue->customField()->associate($this);
                    $findValue->save();
                }
            } else {
                $findValue = new CustomFieldValue();
                $findValue->value = $cf_values_to_save;
                $findValue->customField()->associate($this);
                $findValue->save();
            }

        }

        return $saved;
     }
}
