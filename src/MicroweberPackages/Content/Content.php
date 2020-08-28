<?php
namespace MicroweberPackages\Content;

use Conner\Tagging\Taggable;
use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Tag\Tag;
use MicroweberPackages\CustomField\CustomField;
use MicroweberPackages\CustomField\CustomFieldValue;

class Content extends Model
{
    use Taggable;

    protected $table = 'content';

    protected $content_type = 'content';

    public  $contentData = [];

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function customFields()
    {
        return $this->hasMany(CustomField::class, 'rel_id');
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

    public function setContentData($values)
    {
        foreach($values as $key => $val) {
            $this->contentData[$key] = $val;
        }
    }

    public function getContentData($values)
    {
        $res = [];
        $arrData = !empty($this->data) ? $this->data->toArray() : [];

        foreach($values as $value) {
            if(array_key_exists($value, $this->contentData)) {
                $res[$value] =  $this->contentData[$value];
            } else {
                foreach($arrData as $key => $val) {
                    if($val['field_name']  == $value){
                        $res[$value] =  $val['field_value'];
                    }
                }
            }
        }

        return $res;
    }

    public function deleteContentData($values)
    {
        foreach($this->data as $contentDataInstance) {
            if(in_array($contentDataInstance->field_name, $values)) {
                $contentDataInstance->delete();
            }
        }
    }

    public function save(array $options = [])
    {
        foreach($this->contentData as $key => $value) {
            $this->data()->where('field_name',$key)->updateOrCreate([ 'field_name' => $key],
                ['field_name' => $key, 'field_value' => $value]);
        }

        parent::save($options);
    }
}