<?php

namespace MicroweberPackages\CustomField\Traits;

use Illuminate\Contracts\Session\Session;
use MicroweberPackages\CustomField\Models\CustomField;
use MicroweberPackages\CustomField\Models\CustomFieldValue;

trait CustomFieldsTrait
{

    private $_addCustomFields = [];

    public function initializeCustomFieldsTrait()
    {
        //  $this->appends[] = 'customField';
        $this->fillable[] = 'custom_fields'; // related with boot custom fields trait
    }

    public static function bootCustomFieldsTrait()
    {
        static::saving(function ($model) {
            if (isset($model->attributes['custom_fields'])) {
                foreach ($model->attributes['custom_fields'] as $key => $value) {

                    $customField = [
                        'name' => $key,
                        'name_key' => $key,
                        'value' => [$value]
                    ];

                    // @todo must refactor for price
                    if ($key == 'price') {
                        $customField['type'] = 'price';
                    }

                    $model->_addCustomFields[] = $customField;
                }
                unset($model->attributes['custom_fields']);
            }
        });

        static::saved(function ($model) {


            //$sessionId = app()->user_manager->session_id();

            if(isset($model->attributes['session_id']) and $model->attributes['session_id']){
                $sessionId = $model->attributes['session_id'];
            } else {
                $sessionId = app()->user_manager->session_id();
            }


            $relId = $model->id ?? $model->attributes['id'] ?? null;
            $relType = $model->getMorphClass();



            $appendByUserId = user_id();
            if($appendByUserId){
                CustomField::where('rel_id', 0)
                    ->where('rel_type', $relType)
                    ->where('created_by', $appendByUserId)
                    ->update(['rel_id' => $relId]);
            }

            // Append custom fields to content when content is created
            CustomField::where('rel_id', 0)
                ->where('session_id', $sessionId)//ERROR: Non-static method Illuminate\Contracts\Session\Session::getId() cannot be called statically
                ->where('rel_type', $relType)
                ->update(['rel_id' => $relId]);

            if (!empty($model->_addCustomFields)) {
                foreach ($model->_addCustomFields as $customField) {

                    if (empty($customField['name_key'])) {
                        $customField['name_key'] = \Str::slug($customField['name'], '-');
                    } else {
                        $customField['name_key'] = \Str::slug($customField['name_key'], '-');
                    }

                    $findCustomField = $model->customField()->where('name_key', $customField['name_key'])->first();

                    if ($findCustomField) {
                        $findCustomField->value = $customField['value'];
                        $findCustomField->save();
                    } else {

                        $createCustomField = [
                            'value' => $customField['value'],
                            'name' => $customField['name'],
                            'name_key' => $customField['name_key']
                        ];

                        if (isset($customField['type'])) {
                            $createCustomField['type'] = $customField['type'];
                        }

                        $model->customField()->create($createCustomField);
                    }
                }
                $model->refresh();
            }

        });
    }

    public function getCustomFieldValueByName($name)
    {
        $getCustomField = $this->customField()->where('name', $name)->with('fieldValue')->first();
        if ($getCustomField !== null) {
            $fieldValue = $getCustomField->fieldValue->first();
            if ($fieldValue !== null) {
                return $fieldValue->value;
            }
        }
        return false;
    }



    public function getCustomFieldValueByType($type)
    {
        $getCustomField = $this->customField()->where('type', $type)
            ->orderBy('position', 'asc')->limit(1)
            ->with('fieldValue')->first();
        if ($getCustomField !== null) {
            $fieldValue = $getCustomField->fieldValue->first();
            if ($fieldValue !== null) {
                return $fieldValue->value;
            }
        }
        return false;
    }

    public function customFieldsPrices()
    {
        return $this->hasManyThrough(
            CustomFieldValue::class,
            CustomField::class,
            'rel_id',
            'custom_field_id',
            'id',
            'id'
        )->where('custom_fields.type', '=', 'price')
            ->where('custom_fields.rel_type', $this->getMorphClass());

    }

    public function customFieldsValues()
    {
        return $this->hasManyThrough(
            CustomFieldValue::class,
            CustomField::class,
            'rel_id',
            'custom_field_id',
            'id',
            'id'
        )->where('rel_type', $this->getMorphClass());

    }

    public function scopeWhereCustomField($query, $whereArr)
    {
         foreach ($whereArr as $fieldName => $fieldValue) {

            $query->whereHas('customField', function ($query) use ($whereArr, $fieldName, $fieldValue) {

                $query->where('name_key', \Str::slug($fieldName, '-'))->whereHas('fieldValue', function ($query) use ($fieldValue) {
                    if (is_array($fieldValue)) {
                        $query->whereIn('value', $fieldValue);
                    } else {
                        $query->where('value', $fieldValue);
                    }
                });
            });
        }

        return $query;
    }

    public function setCustomFields($customFields)
    {
        $this->_addCustomFields = $customFields;

        return $this;
    }

    public function setCustomField($customField)
    {
        $this->_addCustomFields[] = $customField;

        return $this;
    }

    public function customField()
    {
        return $this->morphMany(CustomField::class, 'rel' , 'rel_type', 'rel_id')
            ->where('custom_fields.rel_type', $this->getMorphClass())
            ->orderBy('position','asc');
    }

}
