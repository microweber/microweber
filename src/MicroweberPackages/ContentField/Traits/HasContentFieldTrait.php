<?php
namespace MicroweberPackages\ContentField\Traits;

use MicroweberPackages\ContentField\Models\ContentField;

trait HasContentFieldTrait
{
    private $_addContentFields = [];
    private $_addContentFieldsMl = [];

    public function initializeHasContentFieldTrait()
    {
        $this->fillable[] = 'content_fields';
    }

    public static function bootHasContentFieldTrait()
    {
        static::saving(function ($model) {
            if (isset($model->attributes['content_fields'])) {
                $model->_addContentFields = $model->attributes['content_fields'];
                unset($model->attributes['content_fields']);
            }
            if (isset($model->attributes['multilanguage']['content_fields'])) {
                $model->_addContentFieldsMl = $model->attributes['multilanguage']['content_fields'];
                unset($model->attributes['multilanguage']['content_fields']);
            }
        });

        static::saved(function ($model) {

            if (!empty($model->_addContentFields) && is_array($model->_addContentFields)) {
                foreach($model->_addContentFields as $fieldName=>$fieldValue) {

                    $findContentData = ContentField::where('rel_id', $model->id)
                        ->where('rel_type', $model->getMorphClass())
                        ->where('field', $fieldName)
                        ->first();

                    if ($findContentData == null) {
                        $findContentData = new ContentField();
                        $findContentData->rel_id = $model->id;
                        $findContentData->field = $fieldName;
                        $findContentData->rel_type = $model->getMorphClass();
                    }

                    if (isset($model->_addContentFieldsMl[$fieldName])
                        && !empty(isset($model->_addContentFieldsMl[$fieldName]))) {
                        $multilanguageFields = [];
                        foreach ($model->_addContentFieldsMl[$fieldName] as $mlLocale => $mlLocaleValue) {
                            $multilanguageFields['value'][$mlLocale] = $mlLocaleValue;
                        }
                        $findContentData->multilanguage = $multilanguageFields;
                    }

                    $findContentData->value = $fieldValue;
                    $findContentData->save();
                }
            }
        });

        static::deleting(function($model) {
            $model->contentField()->delete();
        });
    }


    public function contentField()
    {
        return $this->morphMany(ContentField::class, 'rel');
    }

}
