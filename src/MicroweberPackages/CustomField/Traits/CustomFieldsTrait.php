<?php
namespace MicroweberPackages\CustomField\Traits;

use MicroweberPackages\CustomField\Models\CustomField;
use MicroweberPackages\CustomField\Models\CustomFieldValue;

trait CustomFieldsTrait {

    private $_newCustomFieldsToAssociate = []; //When enter in bootHasCustomFieldsTrait

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

    public function addCustomField($customFieldArr)
    {
        $this->_newCustomFieldsToAssociate[] = $customFieldArr;
        return $this;
    }

    public function setCustomField($customFieldArr)
    {
        $this->_newCustomFieldsToAssociate[] = $this->customField()->where('name_key', \Str::slug($customFieldArr['name'], '-'))
            ->updateOrCreate(
                ['name_key' => \Str::slug($customFieldArr['name'])],
                $customFieldArr
            );
        return $this;
    }

    public function customField()
    {
      //  return $this->hasMany(CustomField::class, 'rel_id');
        return $this->morphMany(CustomField::class, 'rel');

    }

    public static function bootCustomFieldsTrait()
    {
        static::saved(function ($model)  {
            foreach($model->_newCustomFieldsToAssociate as $customField) {
                $model->customField()->create($customField);
            }

            $model->_newCustomFieldsToAssociate = []; //empty the array
            $model->refresh();
        });
    }
}