<?php

namespace MicroweberPackages\CustomField\Traits;

use MicroweberPackages\CustomField\Models\CustomField;
use MicroweberPackages\CustomField\Models\CustomFieldValue;

trait  CustomFieldsTrait {

    private $_addCustomFields = [];

    public function initializeCustomFieldsTrait()
    {
        //$this->appends[] = 'customField';
        $this->fillable[] = 'custom_fields';
    }

    public static function bootCustomFieldsTrait()
    {
        static::saving(function ($model)  {
            if (isset($model->attributes['custom_fields'])) {
                $model->_addCustomFields = $model->attributes['custom_fields'];
                unset($model->attributes['custom_fields']);
            }
        });

        static::saved(function($model) {
            $model->setCustomFields($model->_addCustomFields);
        });
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
        );
    }

    public function scopeWhereCustomField($query, $whereArr)
    {
        foreach($whereArr as $fieldName => $fieldValue) {
            $query->whereHas('customField', function ($query) use ($whereArr,$fieldName,$fieldValue) {
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
        foreach ($customFields as $key=>$value) {
            if (empty($key) || empty($value)) {
                continue;
            }
            $findCustomField = $this->customField()->where('name_key', \Str::slug($key, '-'))->first();
            if ($findCustomField) {
                $findCustomField->value = $value;
                $findCustomField->save();
            } else {
                $this->customField()->create([
                    'value'=>$value,
                    'name_key' => \Str::slug($key, '-'),
                ]);
            }

            return $this;
        }
    }

    public function customField()
    {
        return $this->morphMany(CustomField::class, 'rel');
    }

}