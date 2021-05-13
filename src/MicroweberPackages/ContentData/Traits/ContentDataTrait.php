<?php

namespace MicroweberPackages\ContentData\Traits;


use MicroweberPackages\ContentData\Models\ContentData;

trait ContentDataTrait
{

    private $_addContentData = [];

    public function initializeContentDataTrait()
    {
       $this->appends[] = 'contentData';
       $this->fillable[] = 'content_data';
    }

    public static function bootContentDataTrait()
    {
        static::saving(function ($model)  {

            if (isset($model->attributes['content_data'])) {
                $model->_addContentData = $model->attributes['content_data'];
                unset($model->attributes['content_data']);
            }
        });



//        if ($model->attributes and array_key_exists("price", $model->attributes)) {
//            if (isset($model->attributes['price'])) {
//                $model->_addPriceField = $model->attributes['price'];
//            } else {
//                $model->_removePriceField = true;
//            }
//            unset($model->attributes['price']);
//
//        }


        static::saved(function($model) {

            $model->setContentData($model->_addContentData);
        });
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
            foreach ($arrData as $key => $val) {
                if ($val['field_name'] == $value) {
                    $res[$value] = $val['field_value'];
                }
            }
        }

        return $res;
    }

    public function deleteContentData(array $values)
    {
        foreach ($this->contentData as $key => $contentDataInstance) {
            if (in_array($contentDataInstance->field_name, $values)) {
                $contentDataInstance->delete();
                $this->refresh();
            }
        }
    }

    public function scopeWhereContentData($query, $whereArr)
    {
        $query->whereHas('contentData', function($query) use ($whereArr){
            foreach($whereArr as $fieldName => $fieldValue) {
                $query->where('field_name', $fieldName)->where('field_value', $fieldValue);
            }
        });

        return $query;
    }

}