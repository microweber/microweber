<?php
namespace MicroweberPackages\Content;

use Conner\Tagging\Taggable;
use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\ContentData\HasContentDataTrait;
use MicroweberPackages\CustomField\HasCustomFieldsTrait;
use MicroweberPackages\Tag\Tag;
use MicroweberPackages\CustomField\CustomField;
use MicroweberPackages\CustomField\CustomFieldValue;

class Content extends Model
{
    use Taggable;
    use HasContentDataTrait;
    use HasCustomFieldsTrait;

    protected $table = 'content';

    protected $content_type = 'content';



    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }




//    public function save(array $options = [])
//    {
//
//        //@todo move to trait
//        foreach($this->contentData as $key => $value) {
//            $this->data()->where('field_name',$key)->updateOrCreate([ 'field_name' => $key],
//                ['field_name' => $key, 'field_value' => $value]);
//        }
//
//        parent::save($options);
//    }
}