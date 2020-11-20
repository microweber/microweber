<?php
namespace MicroweberPackages\Product\Models;

use MicroweberPackages\Content\Scopes\ProductScope;
use MicroweberPackages\Content\Content;
use MicroweberPackages\CustomField\CustomField;
use MicroweberPackages\Product\Models\ModelFilters\ProductFilter;

class Product extends Content
{
    protected $table = 'content';
    protected $attributes = [
        'content_type' => 'product',
        'subtype' => 'product'
    ];

    protected $appends = ['price','qty','sku'];

    public $fillable = [
        "subtype",
        "subtype_value",
        "content_type",
        "parent",
        "layout_file",
        "active_site_template",
        "title",
        "url",
        "content_meta_title",
        "content",
        "description",
        "content_body",
        "content_meta_keywords",
        "original_link",
        "require_login",
        "created_by",
        "is_home",
        "is_shop",
        "is_active"
    ];

    public $translatable = ['title','url','description','content','content_body'];

    public static $customFields = [
        [
            'type' => 'price',
            'name' => 'Price',
            'value' => [0]
        ]
    ];

    public static $contentDataDefault = [
        'sku'=>'',
        'barcode'=>'',
        'quantity'=>'1',
        'track_quantity'=>'0',
        'max_quantity_per_order'=>'0',
        'sell_oos'=>'0',
        'physical_product'=>'0',
        'free_shipping'=>'0',
        'fixed_cost'=>'0.00',
        'weight_type'=>'kg',
        'params_in_checkout'=>0,
        'weight'=>'0',
        'width'=>'0',
        'height'=>'0',
        'depth'=>'0'
    ];



    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new ProductScope());
    }


    public function modelFilter()
    {
        return $this->provideFilter(ProductFilter::class);
    }

    private function fetchSingleAttributeByName($name, $returnAsObject = false)
    {
        foreach($this->customField as $customFieldRow) {
            if($customFieldRow->name_key == $name) {
                if(isset($customFieldRow->fieldValue[0]->value)) { //the value field must be only one
                    if ($returnAsObject) {
                        return $customFieldRow;
                    }
                    return $customFieldRow->fieldValue[0]->value;
                }
            }
        }

        return null;
    }

    private function fetchSingleContentDataByName($name)
    {
        foreach($this->contentData as $contentDataRow) {
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

    public function getPriceModelAttribute()
    {
        return $this->fetchSingleAttributeByName('price', true);
    }

    public function getQtyAttribute()
    {
        return $this->fetchSingleContentDataByName('qty');
    }

    public function getSkuAttribute()
    {
        return $this->fetchSingleContentDataByName('sku');
    }

    public function getContentData($values = [])
    {
        $defaultKeys = self::$contentDataDefault;
        $contentData = parent::getContentData($values);

        foreach ($contentData as $key=>$value) {
            $defaultKeys[$key] = $value;
        }

        return $defaultKeys;
    }
}
