<?php
namespace MicroweberPackages\Content;

use Conner\Tagging\Taggable;
use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\ContentData\Traits\ContentDataTrait;
use MicroweberPackages\CustomField\HasCustomFieldsTrait;
use MicroweberPackages\Tag\Tag;



class Content extends Model
{
    use Taggable;
    use ContentDataTrait;
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