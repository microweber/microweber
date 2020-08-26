<?php
namespace MicroweberPackages\Product;

use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Content\Scopes\ProductScope;
use MicroweberPackages\ContentData\ContentData;
use MicroweberPackages\Database\Traits\CreatedByTrait;

class Product extends Model
{
    protected $table = 'content';

    public  $contentData = [];

    public function getMorphClass()
    {
        return 'content';
    }


    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new ProductScope());
    }

    public function data()
    {
        return $this->morphMany(ContentData::class, 'rel');
    }

    public function getPriceAttribute($value)
    {
        return '5';
        //SQL QUERY
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

    public function save(array $options = [])
    {
        $this->content_type = 'product';

        foreach($this->contentData as $key => $value) {
            $this->data()->where('field_name',$key)->updateOrCreate([ 'field_name' => $key],
                ['field_name' => $key, 'field_value' => $value]);
        }

        parent::save($options);
    }
}
