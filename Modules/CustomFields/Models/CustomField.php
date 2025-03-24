<?php

namespace Modules\CustomFields\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;
use MicroweberPackages\Database\Traits\HasCreatedByFieldsTrait;

class CustomField extends Model
{
    //use MaxPositionTrait;
    use CacheableQueryBuilderTrait;
    use HasCreatedByFieldsTrait;

    /// use HasMultilanguageTrait;

    protected $fillable = [
        'rel_id',
        'rel_type',
        'type',
        'options',
        'name',
        'name_key',
        'value',
        'session_id',
        'position',
        'created_by'
    ];

    protected $table = 'custom_fields';
    public $timestamps = true;

    public $translatable = ['name', 'placeholder', 'error_text'];

    public $cacheTagsToClear = ['repositories', 'content'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    public $casts = [
        'options' => 'json',
    ];

    protected $attributes = [
        'is_active' => 1,
    ];

//    protected string $relType = '';
//    protected string $relId = '';
//
//    public function queryForRelTypeRelId(string $relType = '',string $relId = ''): Builder
//    {
//       // static::$relType = $relType;
//        //static::$relId = $relId;
//
//        $query = static::query();
//       // if (static::$relType) {
//            $query->where('rel_type', $relType);
//       // }
//        //if (static::$relId) {
//            $query->where('rel_id', $relId);
//       // }
//
//        return $query;
//    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $attributes = $model->getAttributes();
            $relType = $attributes['rel_type'] ?? '';
            $relId = $attributes['rel_id'] ?? '';

            if (!empty($relType)) {
                $model->rel_type = $relType;
            }
            if (!empty($relId)) {
                $model->rel_id = $relId;
            }

        });
    }

    public function fieldValue()
    {
        return $this->hasMany(CustomFieldValue::class, 'custom_field_id', 'id')
            ->orderBy('position');
    }

    public function fieldValueSingle()
    {
        return $this->hasOne(CustomFieldValue::class, 'custom_field_id', 'id');
    }

    public function fieldValueText()
    {
        $fieldValues = $this->fieldValue()->pluck('value');
        if ($fieldValues->count() == 1) {
            return $fieldValues->first();
        }

        return implode(', ', $fieldValues->toArray());
    }

    public function getValueAttribute()
    {
        if (isset($this->attributes['value']) and $this->attributes['value'] != '') {
            return $this->attributes['value'];
        }
        $getValue = $this->fieldValue()->pluck('value');
        if ($getValue and $getValue->count() != 0) {
            return $getValue->first();
        }
    }

    public function getValuesAttribute()
    {
        if (isset($this->attributes['values']) and $this->attributes['values'] != '') {
            return $this->attributes['values'];
        }

        $getValues = $this->fieldValue()->pluck('value');
        if ($getValues and $getValues->count() != 0) {
            return $getValues->toArray();
        }
    }


    public function fieldValuePrice()
    {
        return $this->hasMany(CustomFieldValue::class, 'custom_field_id', 'id')
            ->where('type', 'price')
            ->orderBy('position');
    }

    public function save(array $options = [])
    {
        $customFieldValueToSave = null;
        $setBackValueAttrbuteAfterSave = null;
        $setBackMultileValuesAttrbuteAfterSave = null;

        if (isset($this->value) and !empty($this->value)) {
            //cleaup the old value
            CustomFieldValue::where('custom_field_id', $this->id)->delete();

            $setBackValueAttrbuteAfterSave = $this->value;
            if (is_collection($this->value)) {
                $customFieldValueToSave = $this->value->toArray();
            } else {
                $customFieldValueToSave = $this->value;
            }


            unset($this->value);
        }

        if (isset($this->values) and !empty($this->values)) {

            if (is_collection($this->values)) {
                $customFieldValueToSave = $this->values->toArray();
            } else {
                $customFieldValueToSave = $this->values;
            }
            $setBackMultileValuesAttrbuteAfterSave = $this->values;
            unset($this->values);
        }

        if (isset($this->options) and is_string($this->options) and $this->options != '') {
            $this->options = @json_decode($this->options, true);
        }

        if (isset($this->type)) {
            $this->type = strtolower($this->type);
        }
        if (isset($this->name)) {
            $this->name_key = Str::slug($this->name, '-');
        }

        if ($this->rel_id == 0 and !isset($this->session_id)) {
            $this->session_id = app()->user_manager->session_id();
        }

        $saved = parent::save($options);

        if (isset($customFieldValueToSave) and !empty($customFieldValueToSave)) {


            if (is_array($customFieldValueToSave)) {

                $this->createCustomFieldValueMultiple($this->id, $customFieldValueToSave);
            } else {
                $this->createCustomFieldValue($this->id, $customFieldValueToSave);
            }
        }

        if ($setBackValueAttrbuteAfterSave) {
            $this->value = $setBackValueAttrbuteAfterSave;
        }
        if ($setBackMultileValuesAttrbuteAfterSave) {
            $this->values = $setBackMultileValuesAttrbuteAfterSave;
        }

        return $saved;
    }

    private function createCustomFieldValueMultiple($customFieldId, $vals)
    {
        CustomFieldValue::where('custom_field_id', $customFieldId)->delete();

        foreach ($vals as $val) {
            $findValue = new CustomFieldValue();
            $findValue->custom_field_id = $customFieldId;
            $findValue->value = $val;
            $findValue->save();
        }


    }

    private function createCustomFieldValue($customFieldId, $val)
    {

        $findValue = CustomFieldValue::where('custom_field_id', $customFieldId)->first();
        if (!$findValue) {
            $findValue = new CustomFieldValue();
        }

        $findValue->custom_field_id = $customFieldId;
        $findValue->value = $val;
        $findValue->save();
    }
}
