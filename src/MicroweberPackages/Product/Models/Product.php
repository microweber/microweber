<?php
namespace MicroweberPackages\Product\Models;

use MicroweberPackages\Content\Scopes\ProductScope;
use MicroweberPackages\Content\Content;
use MicroweberPackages\Product\Models\ModelFilters\ProductFilter;
use MicroweberPackages\Product\Traits\CustomFieldPriceTrait;

class Product extends Content
{
    use CustomFieldPriceTrait;

    protected $table = 'content';

    protected $appends = ['price','qty','sku'];

  //  public $timestamps = false;

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
        "is_active",
        "updated_at",
        "created_at",
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
        'qty'=>'nolimit',
        'track_quantity'=>'',
        'max_quantity_per_order'=>'',
        'sell_oos'=>'',
        'physical_product'=>'',
        'free_shipping'=>'',
        'fixed_cost'=>'',
        'weight_type'=>'kg',
        'params_in_checkout'=>0,
        'weight'=>'',
        'width'=>'',
        'height'=>'',
        'depth'=>''
    ];


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->attributes['content_type'] = 'product';
        $this->attributes['subtype'] = 'product';
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


    public function modelFilter()
    {
        return $this->provideFilter(ProductFilter::class);
    }

    private function fetchSingleAttributeByType($type, $returnAsObject = false)
    {
        foreach($this->customField as $customFieldRow) {
            if($customFieldRow->type == $type) {
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
        return $this->fetchSingleAttributeByType('price');
    }

    public function getPriceModelAttribute()
    {
        return $this->fetchSingleAttributeByType('price', true);
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
