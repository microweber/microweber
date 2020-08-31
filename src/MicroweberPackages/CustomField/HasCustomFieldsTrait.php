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

    public function addCustomField($customFieldArr)
    {
//        $customField = new customField();
//        $customField->type =  $customFieldArr['type'] ?? 'text';
//        $customField->name =  $customFieldArr['name'];
//        $customField->value = $customFieldArr['value'];
//        $customField->options = $customFieldArr['options'];
//
//        $customField->save();



       // dd($this->getMorphClass());
      //  dd($this->id);

        $newCf = $this->customField()->create([
                    'value' => $customFieldArr['value'],
                    'type' => $customFieldArr['type'],
                    'options' => $customFieldArr['options'],
                    'name' => $customFieldArr['name'],
                 ]
            );

        $this->newCustoFieldsToAssoc[] = $newCf;
        //dd($new_cf->id);



//        $customField = new CustomField();
//        $customField->value = $customFieldArr['value'];
//        $customField->type = $customFieldArr['type'] ?? 'text';
//        $customField->options =  $customFieldArr['options'];
//        $customField->name = $customFieldArr['name'];
//        //$customField->rel_type = $customFieldArr['rel_type'] ?? NULL;
//        //$customField->rel_id = $customFieldArr['rel_id'] ?? NULL;;
//        $customField->save();

        //$this->customField()->associate($customField);
      //  $this->customField()->associate($customField);

     //   $this->customFieldsValues()->associate($customField);
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