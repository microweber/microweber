<?php
namespace MicroweberPackages\Product;

use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Content\Scopes\ProductScope;
use MicroweberPackages\Content\Content;
use MicroweberPackages\CustomField\CustomField;
use MicroweberPackages\ContentData\Models\ContentData;

class Product extends Content
{
    protected $table = 'content';

    protected $attributes = [
        'content_type' => 'product',
        'subtype' => 'product'
    ];

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

    private function fetchSingleAttributeByName ($name)
    {
        foreach($this->customField as $customFieldRow) {
            if($customFieldRow->type == $name) {
                if(isset($customFieldRow->fieldValue[0]->value)) { //the value field must be only one
                    return $customFieldRow->fieldValue[0]->value;
                }
            }
        }

        return null;
    }

    private function fetchSingleContentDataByName($name)
    {
        foreach($this->data as $contentDataRow) {
            if($contentDataRow->field_name == $name) {
                return $contentDataRow->field_value;
            }
        }

        return null;
    }

    public function getPriceAttribute()
    {
        return $this->fetchSingleAttributeByName('price');
    }

    public function getQtyAttribute()
    {
        return $this->fetchSingleContentDataByName('qty');
    }


}
