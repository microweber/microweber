<?php

namespace MicroweberPackages\Product\Models;

use MicroweberPackages\Cart\Models\Cart;
use MicroweberPackages\Content\Scopes\ProductScope;
use MicroweberPackages\Content\Models\Content;
use MicroweberPackages\ContentData\Models\ContentData;
use MicroweberPackages\ContentDataVariant\Models\ContentDataVariant;
use MicroweberPackages\CustomField\Models\CustomField;
use MicroweberPackages\CustomField\Models\CustomFieldValue;
use MicroweberPackages\Offer\Models\Offer;
use MicroweberPackages\Order\Models\Order;
use MicroweberPackages\Product\CartesianProduct;
use MicroweberPackages\Product\Events\ProductWasUpdated;
use MicroweberPackages\Product\Models\ModelFilters\ProductFilter;
use MicroweberPackages\Product\Traits\CustomFieldPriceTrait;
use MicroweberPackages\Shop\FrontendFilter\ShopFilter;
use function Clue\StreamFilter\fun;

class Product extends Content
{

//    protected $dispatchesEvents = [
//        'updated' =>  ProductWasUpdated::class,
//     //   'updating' =>  ProductWasUpdated::class,
//      //  'updating' =>  ProductWasUpdated::class,
//     //   'deleted' => ProductWasUpdated::class,
//    ];


    /**
     * @method filter(array $filter)
     * @see ProductFilter
     */

    use CustomFieldPriceTrait;

    protected $table = 'content';

    protected $appends = ['price', 'qty', 'sku'];

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


    public static $customFields = [
        [
            'type' => 'price',
            'name' => 'Price',
            'value' => [0]
        ]
    ];

    public static $contentDataDefault = [
        'qty' => 'nolimit',
        'sku' => '',
        'barcode' => '',
        'track_quantity' => '',
        'max_quantity_per_order' => '',
        'sell_oos' => '',
        'physical_product' => '',
        'free_shipping' => '',
        'shipping_fixed_cost' => '',
        'weight_type' => 'kg',
        'params_in_checkout' => 0,
        'has_special_price' => 0,
        'weight' => '',
        'width' => '',
        'height' => '',
        'depth' => ''
    ];

    public $sortable = [
        'id' => [
            'title' => 'Product'
        ]
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
        foreach ($this->customField as $customFieldRow) {
            if ($customFieldRow->type == $type) {
                if (isset($customFieldRow->fieldValue[0]->value)) { //the value field must be only one
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
        foreach ($this->customField as $customFieldRow) {
            if ($customFieldRow->name_key == $name) {
                if (isset($customFieldRow->fieldValue[0]->value)) { //the value field must be only one
                    if ($returnAsObject) {
                        return $customFieldRow;
                    }
                    return $customFieldRow->fieldValue[0]->value;
                }
            }
        }

        return null;
    }

    public function getPriceAttribute()
    {
        $price = $this->fetchSingleAttributeByType('price');
        if (is_numeric($price)) {
            return $price;
        }

        return 0;
    }

    public function getPriceModelAttribute()
    {
        // This must return only object model, DON'T CHANGE IT!
        return $this->fetchSingleAttributeByType('price', true);
    }

    public function getQtyAttribute()
    {
        return $this->getContentDataByFieldName('qty');
    }

    public function getSkuAttribute()
    {
        $sku = $this->getContentDataByFieldName('sku');
        if ($sku) {
            return $sku;
        }
        return '';
    }

    public function hasSpecialPrice()
    {
        $specialPrice = $this->getContentDataByFieldName('special_price');
        if ($specialPrice > 0) {
            return true;
        }
        return false;
    }

    public function getDiscountPercentage() : int
    {
        $originalPrice = $this->getPriceAttribute();
        $specialPrice = $this->getSpecialPriceAttribute();
        if(!$originalPrice or !$specialPrice){
            return 0;
        }
        $item = [];
        $item['original_price'] = $originalPrice;
        $item['price'] = $specialPrice;

        $newFigure = floatval($item['original_price']);
        $oldFigure = floatval($item['price']);
        $percentChange = 0;
        if ($oldFigure < $newFigure) {
            $percentChange = (1 - $oldFigure / $newFigure) * 100;
        }
        if ($percentChange > 0) {
            return intval($percentChange);
        } else {
            return 0;
        }
    }


    public function getSpecialPriceAttribute()
    {
        return $this->getContentDataByFieldName('special_price');
    }

    public function getOrdersCountAttribute()
    {

        $cartQuery = Cart::query();
        $cartQuery->where('rel_type', 'content');
        $cartQuery->where('rel_id', $this->getAttribute('id'));
        $cartQuery->whereHas('order');
        return $cartQuery->count();

    }

    public function cart()
    {
        return $this->hasMany(Cart::class, 'rel_id', 'id');
    }

    public function offer()
    {
        return $this->hasOne(Offer::class, 'product_id', 'id');
    }

    public function getInStockAttribute()
    {
        $sellWhenIsOos = $this->getContentDataByFieldName('sell_oos');
        $qty = $this->getContentDataByFieldName('qty');
        if ($sellWhenIsOos == '1') {
            return true;
        }

        if ($qty === null) {
            return true;
        }

        if ($qty == 'nolimit') {
            return true;
        }

        if ($qty > 0) {
            return true;
        }

        return false;
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'parent');
    }

    public function generateVariants()
    {
        //   clearcache();
        $getVariants = $this->variants()->get();
        $getCustomFields = $this->customField()->where('type', 'radio')->get();

        // Get all custom fields for variations
        $productCustomFieldsMap = [];
        foreach ($getCustomFields as $customField) {
            $customFieldValues = [];
            $getCustomFieldValues = $customField->fieldValue()->get();
            foreach ($getCustomFieldValues as $getCustomFieldValue) {
                $customFieldValues[] = $getCustomFieldValue->id;
            }
            if (empty($customFieldValues)) {
                continue;
            }
            $productCustomFieldsMap[$customField->id] = $customFieldValues;
        }

        // Make combinations ofr custom fields
        $cartesianProduct = new CartesianProduct($productCustomFieldsMap);
        $cartesianProductDetailed = [];
        foreach ($cartesianProduct->asArray() as $cartesianProductIndex => $cartesianProductCustomFields) {
            foreach ($cartesianProductCustomFields as $customFieldId => $customFieldValueId) {
                $contentDataVariant = [
                    'custom_field_id' => $customFieldId,
                    'custom_field_value_id' => $customFieldValueId,
                ];
                $cartesianProductDetailed[$cartesianProductIndex]['content_data_variant'][] = $contentDataVariant;
            }
        }

        /*  // Match old variants with new cartesian variants
          if ($getVariants->count() > 0) {
              foreach ($getVariants as $variant) {
                  $matchWithCartesian = [];
                  $getContentDataVariant = $variant->contentDataVariant()->get();
                  if ($getContentDataVariant->count() > 0) {
                      foreach ($getContentDataVariant as $contentDataVariant) {
                          foreach ($cartesianProductDetailed as $cartesianProduct) {
                              foreach ($cartesianProduct['content_data_variant'] as $cartesianContentDataVariant) {
                                  if ($cartesianContentDataVariant['custom_field_value_id'] == $contentDataVariant['custom_field_value_id']
                                      && $cartesianContentDataVariant['custom_field_value_id'] == $contentDataVariant['custom_field_value_id']) {
                                      $matchWithCartesian = $cartesianProduct;
                                      break 2;
                                  }
                              }
                          }
                      }
                  }
                  if (!empty($matchWithCartesian)) {
                   foreach ($matchWithCartesian['content_data_variant'] as $contentDataVariant) {
                         $findContentDataVariant = ContentDataVariant::where('rel_id', $variant->id)
                             ->where('rel_type', 'content')
                             ->where('custom_field_id', $contentDataVariant['custom_field_id'])
                             ->where('custom_field_value_id', $contentDataVariant['custom_field_value_id'])
                             ->first();
                         if ($findContentDataVariant == null) {
                             $findContentDataVariant = new ContentDataVariant();
                             $findContentDataVariant->rel_id = $variant->id;
                             $findContentDataVariant->rel_type = 'content';
                             $findContentDataVariant->custom_field_id = $contentDataVariant['custom_field_id'];
                             $findContentDataVariant->custom_field_value_id = $contentDataVariant['custom_field_value_id'];
                             $findContentDataVariant->save();
                         }
                     }
                  }
              }
          }*/

        $updatedProductVariantIds = [];
        foreach ($cartesianProductDetailed as $cartesianProduct) {

            $cartesianProductVariantValues = [];
            foreach ($cartesianProduct['content_data_variant'] as $contentDataVariant) {
                $getCustomFieldValue = CustomFieldValue::where('id', $contentDataVariant['custom_field_value_id'])->first();
                $cartesianProductVariantValues[] = $getCustomFieldValue->value;
            }

            $productVariant = ProductVariant::where('parent', $this->id)->whereContentDataVariant($cartesianProduct['content_data_variant'])->first();

            if ($productVariant == null) {
                $productVariant = new ProductVariant();
                $productVariant->parent = $this->id;
            }

            $productVariantUrl = $this->url . '-' . str_slug(implode('-', $cartesianProductVariantValues));
            $productVariant->title = 'id->' . $productVariant->id . '-' . $this->title . ' - ' . implode(', ', $cartesianProductVariantValues);
            $productVariant->url = $productVariantUrl;
            $productVariant->save();

            foreach ($cartesianProduct['content_data_variant'] as $contentDataVariant) {

                $findContentDataVariant = ContentDataVariant::where('rel_id', $productVariant->id)
                    ->where('rel_type', 'content')
                    ->where('custom_field_id', $contentDataVariant['custom_field_id'])
                    ->where('custom_field_value_id', $contentDataVariant['custom_field_value_id'])
                    ->first();
                if ($findContentDataVariant == null) {
                    $findContentDataVariant = new ContentDataVariant();
                    $findContentDataVariant->rel_id = $productVariant->id;
                    $findContentDataVariant->rel_type = 'content';
                    $findContentDataVariant->custom_field_id = $contentDataVariant['custom_field_id'];
                    $findContentDataVariant->custom_field_value_id = $contentDataVariant['custom_field_value_id'];
                    $findContentDataVariant->save();
                }
            }

            $updatedProductVariantIds[] = $productVariant->id;
        }

        // Delete old variants
        if ($getVariants->count() > 0) {
            foreach ($getVariants as $productVariant) {
                if (!in_array($productVariant->id, $updatedProductVariantIds)) {
                    $productVariant->contentDataVariant()->delete();
                    $productVariant->delete();
                }
            }
        }
    }

    public function getContentData($values = [])
    {
        $defaultKeys = self::$contentDataDefault;
        $contentData = parent::getContentData($values);

        foreach ($contentData as $key => $value) {
            $defaultKeys[$key] = $value;
        }

        return $defaultKeys;
    }

    public function scopeFrontendFilter($query, $params)
    {
        $filter = new ShopFilter();
        $filter->setModel($this);
        $filter->setQuery($query);
        $filter->setParams($params);

        return $filter->apply();
    }


    public function orders()
    {
        return $this->hasManyThrough(
            Order::class,
            Cart::class,
            'rel_id',
            'id',
            'id',
            'order_id',
        );
    }
}
