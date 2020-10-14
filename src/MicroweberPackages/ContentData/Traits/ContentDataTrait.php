<?php

namespace MicroweberPackages\ContentData\Traits;


use MicroweberPackages\ContentData\Models\ContentData;

trait ContentDataTrait
{
    public $contentDataToSave = [];

    public function initializeContentDataTrait()
    {
        $this->appends[] = 'contentData';
    }

    public function getContentDataAttribute()
    {
        return $this->contentData()->get();
    }

    public function contentData()
    {
        return $this->morphMany(ContentData::class, 'rel');
    }

    public function setContentData($values)
    {
        foreach ($values as $key => $val) {
                $this->contentData()->where('field_name',$key)->updateOrCreate([ 'field_name' => $key],
                    ['field_name' => $key, 'field_value' => $val]);
        }
    }

    public function getContentData($values = [])
    {
        $res = [];
        $arrData = !empty($this->contentData) ? $this->contentData->toArray() : [];

        if (empty($values)) {
           return $this->contentData->pluck('field_value', 'field_name')->toArray();
        }

        foreach ($values as $value) {
            if (array_key_exists($value, $this->contentDataToSave)) {
                $res[$value] = $this->contentDataToSave[$value];
            } else {
                foreach ($arrData as $key => $val) {
                    if ($val['field_name'] == $value) {
                        $res[$value] = $val['field_value'];
                    }
                }
            }
        }

        return $res;
    }

    public function deleteContentData(array $values)
    {

        foreach ($this->data as $key => $contentDataInstance) {
            if (in_array($contentDataInstance->field_name, $values)) {
                $contentDataInstance->delete();
                $this->refresh();
            }
        }
        foreach ($this->contentDataToSave as $key => $value) {
            foreach ($values as $del_key => $del_value) {
                if (isset($this->contentDataToSave[$del_key])) {
                    unset($this->contentDataToSave[$del_key]);
                }
            }
        }

    }

    public function scopeWhereContentData($query, $whereArr)
    {
        $query->whereHas('data', function($query) use ($whereArr){
            foreach($whereArr as $fieldName => $fieldValue) {
                $query->where('field_name', $fieldName)->where('field_value', $fieldValue);
            }
        });

        return $query;
    }

}