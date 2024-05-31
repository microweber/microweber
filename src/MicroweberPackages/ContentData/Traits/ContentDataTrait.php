<?php

namespace MicroweberPackages\ContentData\Traits;


use MicroweberPackages\Content\Models\Content;
use MicroweberPackages\ContentData\Models\ContentData;

trait ContentDataTrait
{

    private $_addContentData = [];

    public function initializeContentDataTrait()
    {
        //  $this->appends[] = 'contentData';
        $this->fillable[] = 'content_data';
    }

    public static function bootContentDataTrait()
    {
        static::saving(function ($model) {
            if (isset($model->attributes['content_data'])) {
                $model->_addContentData = $model->attributes['content_data'];
                unset($model->attributes['content_data']);
            }
        });


        static::saved(function ($model) {
            if (!empty($model->_addContentData) && is_array($model->_addContentData)) {

                foreach($model->_addContentData as $fieldName=>$fieldValue) {

                    $findContentData = ContentData::where('rel_id', $model->id)
                        ->where('rel_type', $model->getMorphClass())
                        ->where('field_name', $fieldName)
                        ->first();
                    if ($findContentData == null) {
                        $findContentData = new ContentData();
                        $findContentData->rel_id = $model->id;
                        $findContentData->field_name = $fieldName;
                        $findContentData->rel_type = $model->getMorphClass();
                    }
                    $findContentData->field_value = $fieldValue;
                    $findContentData->save();
                }
            }
        });

        static::deleting(function($model) {
            $model->contentData()->delete();
        });
    }


    public function getContentDataAttribute()
    {
        $keyValueMap = [];
        $contentData = $this->contentData()
            ->get()
            ->pluck('field_value', 'field_name')
            ->toArray();
        if (!empty($contentData) && is_array($contentData)) {
            $keyValueMap = $contentData;
        }

        return $keyValueMap;
    }

    public function contentData()
    {
        return $this->morphMany(ContentData::class, 'rel');
    }

    /**
     * Set content data can be used only if parent model allready have a created resource.
     * @param $values
     */
    public function setContentData($values)
    {
        $this->_addContentData = $values;
        return $this;
    }

    public function getContentDataByFieldName($name)
    {
        $contentData = $this->contentData()->where('field_name', $name)->first();

        if ($contentData) {
            return $contentData->field_value;
        }

        return null;
    }

    /**
     * @param $values is filter array of field names
     * @return array
     */
    public function getContentData($values = [])
    {
        $keyValueMap = $this->getContentDataAttribute();
        if (!empty($values) && is_array($values)) {
            $keyValueMap = array_intersect_key($keyValueMap, array_flip($values));
        }

        return $keyValueMap;
    }

    public function deleteContentData(array $values)
    {
        $items = $this->contentData()->get();
        if ($items) {
            foreach ($items as $key => $contentDataInstance) {
                if ($contentDataInstance and in_array($contentDataInstance->field_name, $values)) {
                    $contentDataInstance->delete();
                    $this->refresh();
                }
            }
        }
    }

    public function scopeWhereContentData($query, $whereArr)
    {
        // If you want to select multiple fields, we must use whereHas in foreach
        foreach ($whereArr as $fieldName => $fieldValue) {
            $query->whereHas('contentData', function ($query) use ($fieldName, $fieldValue) {
                $query->where('field_name', $fieldName)->where('field_value', $fieldValue);
            });
        }

        return $query;
    }
}
