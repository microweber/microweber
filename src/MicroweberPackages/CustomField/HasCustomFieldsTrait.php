<?php

namespace MicroweberPackages\CustomField;


trait  HasCustomFieldsTrait {

//    public function customFields()
//    {
//        return $this->hasMany(CustomField::class, 'rel_id');
//    }

    private $newCustoFieldsToAssoc = []; //When enter in bootHasCustomFieldsTrait

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

        $newCf = $this->customField()->create([
                    'value' => $customFieldArr['value'],
                    'type' => $customFieldArr['type'],
                    'options' => $customFieldArr['options'],
                    'name' => $customFieldArr['name'],
                 ]
            );

        $this->newCustoFieldsToAssoc[] = $newCf;
    }

    public function setCustomField($customFieldArr)
    {
        $newCf = $this->customField()->where('name_key', \Str::slug($customFieldArr['name'], '-'))->updateOrCreate([ 'name_key' => \Str::slug($customFieldArr['name'])], [
                'value' => $customFieldArr['value'],
                'type' => $customFieldArr['type'],
                'options' => $customFieldArr['options'],
                'name' => $customFieldArr['name'],
            ]
        );

        $this->newCustoFieldsToAssoc[] = $newCf;
    }

    public function customField()
    {
      //  return $this->hasMany(CustomField::class, 'rel_id');
        return $this->morphMany(CustomField::class, 'rel');

    }

    public static function bootHasCustomFieldsTrait()
    {
        static::saved(function ($model)  {
            foreach($model->newCustoFieldsToAssoc as $customField) {
                $model->customField()->save($customField);
            }

            $model->newCustoFieldsToAssoc = []; //empty the array
            $model->refresh();
        });
    }
}