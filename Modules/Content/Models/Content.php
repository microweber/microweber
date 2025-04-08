<?php

namespace Modules\Content\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Concerns\HasEvents;
use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Core\Models\HasSearchableTrait;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;
use MicroweberPackages\Database\Traits\HasCreatedByFieldsTrait;
use MicroweberPackages\Database\Traits\HasSlugTrait;
use MicroweberPackages\Database\Traits\MaxPositionTrait;
use MicroweberPackages\Database\Traits\ParentCannotBeSelfTrait;
use MicroweberPackages\Multilanguage\Models\Traits\HasMultilanguageTrait;
use Modules\Cart\Models\Cart;
use Modules\Category\Traits\CategoryTrait;
use Modules\Content\Models\ModelFilters\ContentFilter;
use Modules\Content\Scopes\ProductScope;
use Modules\ContentData\Traits\ContentDataTrait;
use Modules\ContentDataVariant\Models\ContentDataVariant;
use Modules\ContentField\Concerns\HasContentFieldTrait;
use Modules\CustomFields\Models\CustomFieldValue;
use Modules\CustomFields\Traits\CustomFieldsTrait;
use Modules\Media\Traits\MediaTrait;
use Modules\Menu\Concerns\HasMenuItem;
use Modules\Offer\Models\Offer;
use Modules\Order\Models\Order;
use Modules\Product\FrontendFilter\ShopFilter;
use Modules\Product\Models\ModelFilters\ProductFilter;
use Modules\Product\Models\ProductVariant;
use Modules\Product\Support\CartesianProduct;
use Modules\Product\Traits\CustomFieldPriceTrait;
use Modules\Tag\Traits\TaggableTrait;
use Spatie\Translatable\HasTranslations;

//use Kirschbaum\PowerJoins\PowerJoins;

class Content extends Model
{
    use TaggableTrait;
    use ContentDataTrait;
    use CustomFieldsTrait;
    use CategoryTrait;
    use HasContentFieldTrait;
    use HasSlugTrait;
    use HasSearchableTrait;
    use HasMenuItem;
    use MediaTrait;
    use Filterable;
    use HasCreatedByFieldsTrait;
    use CacheableQueryBuilderTrait;

    //   use PowerJoins;
    use HasEvents;
    use HasMultilanguageTrait;
    use MaxPositionTrait;
    use ParentCannotBeSelfTrait;
    /**
     * @method filter(array $filter)
     * @see ProductFilter
     */

    use CustomFieldPriceTrait;
    protected $table = 'content';
    protected $content_type = 'content';
    public $additionalData = [];

    public $cacheTagsToClear = ['repositories', 'content', 'content_fields_drafts', 'menu', 'content_fields', 'categories', 'custom_fields', 'custom_fields_values'];

    public $translatable = ['title', 'url', 'description', 'content', 'content_body', 'content_meta_title', 'content_meta_keywords'];


    protected $attributes = [
        'is_active' => '1',
        'is_deleted' => '0',
        'is_shop' => '0',
        'is_home' => '0',
    ];
    protected $searchableByKeyword = [
        'title',
        'content',
        'content_body',
        'description',
        'url',
        'content_meta_title',
        'content_meta_keywords',
    ];
    protected $searchable = [
        'id',
        'title',
        'content',
        'content_body',
        'content_type',
        'subtype',
        'description',
        'is_home',
        'is_shop',
        'is_deleted',
        'is_active',
        'subtype',
        'subtype_value',
        'parent',
        'layout_file',
        'active_site_template',
        'url',
        'content_meta_title',
        'content_meta_keywords',
    ];

    protected $fillable = [
        "id",
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
        "is_deleted",
        "session_id",
        "updated_at",
        "created_at",
    ];

    public function scopeActive($query)
    {
        return $query
            ->where('is_active', 1)
            ->where(function ($subQuery) {
                $subQuery
                    ->whereNull('is_deleted')
                    ->orWhere('is_deleted', 0);
            });
    }

    public function scopeInactive($query)
    {
        return $query
            ->where('is_active', 0)
            ->where(function ($subQuery) {
                $subQuery
                    ->whereNull('is_deleted')
                    ->orWhere('is_deleted', 0);
            });
    }

    public function scopeTrashed($query)
    {
        return $query->where('is_deleted', 1);
    }

    public function related()
    {
        return $this->hasMany(ContentRelated::class, 'content_id', 'id')->orderBy('position', 'ASC');
    }

    public function modelFilter()
    {
        return $this->provideFilter(ContentFilter::class);
    }

    public function getMorphClass()
    {
        // TODO
        return Content::class;
    }

    public function getImageAttribute()
    {
        return content_picture($this->id);
    }

    public function getLinkAttribute()
    {
        return $this->link();
    }

    public function link()
    {

        return content_link($this->id);
    }



    public function editLink()
    {
        return content_edit_link($this->id);
    }

    public function liveEditLink()
    {
        return content_link($this->id) . '?editmode=y';
    }

    public function getDescriptionAttribute($value)
    {
        if (is_string($value) and $value) {
            return strip_tags($value);
        }
    }

    public function shortDescription($limit = 224, $end = '...')
    {
        if (empty($this->description)) {
            return false;
        }

        $shortDescription = $this->description;
        $shortDescription = strip_tags($shortDescription);
        $shortDescription = trim($shortDescription);
        $shortDescription = str_limit($shortDescription, $limit, $end);

        return $shortDescription;
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



    /* PRODUCT related functions */

    public function getPriceAttribute()
    {
        $price = $this->fetchSingleAttributeByType('price');

        if ($price) {
            return $price;
        }

        return 0;
    }


    public function getPricesAttribute()
    {
        return   app()->shop_manager->get_product_prices($this->id, false);

    }
    public function getPriceDisplayAttribute()
    {
        $originalPrice = $this->getPriceAttribute();
        $specialPrice = $this->getSpecialPriceAttribute();

        if ($specialPrice) {
            return currency_format($specialPrice);
        }

        return currency_format($originalPrice);
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

    public function hasLabel()
    {
        if ($this->getLabelType()) {
            return true;
        } else {
            return false;
        }
    }

    public function getLabelType()
    {
        $labelType = $this->getContentDataByFieldName('label-type');
        return $labelType;

    }

    public function getLabelText(): string
    {
        $labelType = $this->getLabelType();
        if ($labelType == 'percent') {
            return $this->getDiscountPercentage() . '%';
        }

        if ($labelType == 'text') {
            return $this->getContentDataByFieldName('label');
        }

        return '';
    }

    public function getLabelColor(): string
    {
        $color = $this->getContentDataByFieldName('label-color');
        if ($color) {
            return $color;
        }
        return '';
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
        if(function_exists('offers_get_price')){
            $priceModel = $this->getPriceModelAttribute();
            if($priceModel && $priceModel->id) {
                $productId = $this->id;
                $offers_get_price = offers_get_price($productId, $priceModel->id);
                if($offers_get_price and isset($offers_get_price['offer_price']) and $offers_get_price['offer_price'] != '') {
                    return  $offers_get_price['offer_price'];
                }
            }
        }

    }

    public function getOrdersCountAttribute()
    {

        $cartQuery = \Modules\Cart\Models\Cart::query();
        $cartQuery->where('rel_type', morph_name(\Modules\Content\Models\Content::class));
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
                             ->where('rel_type', morph_name(\Modules\Content\Models\Content::class))
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

                $findContentDataVariant = \Modules\ContentDataVariant\Models\ContentDataVariant::where('rel_id', $productVariant->id)
                    ->where('rel_type', morph_name(\Modules\Content\Models\Content::class))
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
            \Modules\Cart\Models\Cart::class,
            'rel_id',
            'id',
            'id',
            'order_id',
        );
    }
}
