<?php

namespace Modules\Content\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Concerns\HasEvents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Content\Database\Factories\ContentFactory;
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
use MicroweberPackages\Repository\Traits\FilterableByParams;
use Modules\Tag\Traits\TaggableTrait;
use Spatie\Translatable\HasTranslations;

//use Kirschbaum\PowerJoins\PowerJoins;

class Content extends Model
{
    use HasFactory;
    use TaggableTrait;
    use ContentDataTrait;
    use CustomFieldsTrait;
    use CategoryTrait;
    use HasContentFieldTrait;
    use HasSlugTrait;
    use HasSearchableTrait;
    use FilterableByParams;
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

    protected static function newFactory()
    {
        return ContentFactory::new();
    }

    protected $table = 'content';
    protected $content_type = 'content';

    protected array $filterMethods = [
        'category' => 'whereCategoryIds',
        'categories' => 'whereCategoryIds',
    ];
    public $additionalData = [];

    public $cacheTagsToClear = ['repositories', 'content', 'content_fields_drafts', 'menu', 'content_fields', 'categories', 'custom_fields', 'custom_fields_values'];

    public $translatable = ['title', 'url', 'description', 'content', 'content_body', 'content_meta_title', 'content_meta_keywords', 'content_meta_description', 'og_title', 'og_description', 'twitter_title', 'twitter_description'];


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
        'content_meta_description',
        'og_title',
        'og_description',
        'twitter_title',
        'twitter_description',
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
        'content_meta_description',
        'og_title',
        'og_description',
        'twitter_title',
        'twitter_description',
        'robots_meta',
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
        "content_meta_description",
        "og_title",
        "og_description",
        "og_image",
        "og_type",
        "twitter_title",
        "twitter_description",
        "twitter_image",
        "twitter_card",
        "canonical_url",
        "robots_meta",
        "sitemap_priority",
        "sitemap_changefreq",
        "exclude_from_sitemap",
        "original_link",
        "require_login",
        "created_by",
        "is_home",
        "is_shop",
        "is_active",
        "is_deleted",
        "is_featured",
        "session_id",
        "updated_at",
        "created_at",
        "posted_at",
        "low_stock_threshold",
    ];

    protected function casts(): array
    {
        return [
            'posted_at' => 'datetime',
        ];
    }

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
        return app()->shop_manager->get_product_prices($this->id, false);

    }

    public function getPriceDisplayAttribute()
    {
        $originalPrice = $this->getPriceAttribute();
        $specialPrice = $this->getSpecialPriceAttribute();

        if ($specialPrice) {
            return currency_format((float) $specialPrice);
        }

        return currency_format((float) $originalPrice);
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

    public function getDiscountPercentage(): int
    {
        $originalPrice = $this->getPriceAttribute();
        $specialPrice = $this->getSpecialPriceAttribute();
        if (!$originalPrice or !$specialPrice) {
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
        if (function_exists('offers_get_price')) {
            $priceModel = $this->getPriceModelAttribute();

            if ($priceModel && $priceModel->id) {
                $productId = $this->id;
                $offers_get_price = offers_get_price($productId, $priceModel->id);

                if ($offers_get_price and isset($offers_get_price['offer_price']) and $offers_get_price['offer_price'] != '') {
                    return $offers_get_price['offer_price'];
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

    public function getContentData()
    {
        $defaultKeys = [];
        if (isset(self::$contentDataDefault) and is_array(self::$contentDataDefault)) {
            $defaultKeys = self::$contentDataDefault;
        }


        $savedData = app()->content_repository->getContentData($this->id);


        $ready = [];
        if (isset($savedData) and is_array($savedData)) {
            foreach ($savedData as $key => $value) {
                if (isset($value['field_name']) and isset($value['field_value'])) {
                    $ready[$value['field_name']] = $value['field_value'];
                }
            }
        }
        if ($defaultKeys) {
            foreach ($defaultKeys as $key => $value) {
                if (!isset($ready[$key])) {
                    $ready[$key] = $value;
                }
            }
        }
        return $ready;


        /*


           $contentData = self::contentData($values);

           if ($contentData) {
               $contentData = $contentData->toArray();
               dd($contentData);
           }
           foreach ($contentData as $key => $value) {
               $defaultKeys[$key] = $value;
           }

           return $defaultKeys;*/
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

    /**
     * Get the parent IDs of a given content ID by walking up the parent chain.
     *
     * @param int $id
     * @return array|false
     */
    public static function getParentIds($id): array|false
    {
        $id = intval($id);
        if ($id <= 0) {
            return false;
        }

        $parentIds = [];
        $content = DB::table('content')->select('id', 'parent')->where('id', $id)->first();

        if (!$content) {
            return false;
        }

        $parentId = intval($content->parent);
        if ($parentId > 0 && $parentId != $content->id) {
            $parentIds[] = $parentId;
            $previousParents = static::getParentIds($parentId);
            if ($previousParents) {
                $parentIds = array_merge($parentIds, $previousParents);
            }
        }

        $parentIds = array_filter(array_unique($parentIds));

        return $parentIds ?: false;
    }

    /**
     * Get all descendant IDs of a given content ID.
     *
     * @param int $id
     * @return array|false
     */
    public static function getChildrenIds($id = 0): array|false
    {
        if (!intval($id)) {
            return false;
        }

        $ids = [$id];

        $children = DB::table('content')->select('id', 'parent')->where('parent', $id)->get();

        foreach ($children as $child) {
            if ($id != $child->id) {
                $ids[] = $child->id;
                if (intval($child->parent) && $child->parent != $child->id) {
                    $descendantIds = static::getChildrenIds($child->id);
                    if ($descendantIds) {
                        $ids = array_merge($ids, $descendantIds);
                    }
                }
            }
        }

        return $ids ? array_unique($ids) : false;
    }

    /**
     * Get the first parent content ID that has a layout template.
     *
     * @param int $id
     * @return int|false
     */
    public static function getInheritedParentId($id): int|false
    {
        $parentIds = static::getParentIds($id);

        if (empty($parentIds)) {
            return false;
        }

        foreach ($parentIds as $parentId) {
            $parent = DB::table('content')->where('id', $parentId)->first();
            if ($parent && isset($parent->id)) {
                return intval($parent->id);
            }
        }

        return false;
    }

    /**
     * Retrieve the categories associated with a given content ID.
     *
     * @param mixed $id
     * @return array
     */
    public static function getCategoriesForContent($id): array
    {
        $categoryIds = [];
        $getCategoryItems = DB::table('categories_items')
            ->select('parent_id')
            ->where('rel_type', morph_name(Content::class))
            ->where('rel_id', $id)
            ->groupBy('parent_id')
            ->get();

        if ($getCategoryItems) {
            foreach ($getCategoryItems as $categoryItem) {
                $categoryIds[] = $categoryItem->parent_id;
            }
        }

        if (empty($categoryIds)) {
            return [];
        }

        $ready = [];
        $categories = DB::table('categories')
            ->whereIn('id', $categoryIds)
            ->get()
            ->keyBy('id');

        foreach ($categoryIds as $categoryId) {
            if ($category = $categories->get($categoryId)) {
                $ready[] = (array) $category;
            }
        }

        return $ready;
    }

    /**
     * Retrieve the tags associated with the specified content.
     *
     * @param bool|int $contentId
     * @param bool $returnFullTagsData
     * @return array|false
     */
    public static function getTagsForContent($contentId = false, $returnFullTagsData = false): array|false
    {
        $query = DB::table('tagging_tagged');
        $query->where('taggable_type', morph_name(Content::class));
        if ($contentId) {
            $query->where('taggable_id', $contentId);
        }

        $getTagged = $query->get();
        $getTagged = collect($getTagged)->map(function ($item) {
            return (array) $item;
        })->toArray();

        if ($returnFullTagsData) {
            return $getTagged;
        }

        $tagNames = [];
        foreach ($getTagged as $tagged) {
            $tagNames[] = $tagged['tag_name'];
        }

        return $tagNames;
    }

    /**
     * Get or create the default shop page.
     *
     * @return static|array
     */
    public static function createDefaultShopPage(): static|array
    {
        $shopPage = get_pages('content_type=page&is_shop=1&is_deleted=0&single=1');
        if (!$shopPage) {
            $shopPage = new static();
            $shopPage->title = 'Shop';
            $shopPage->content_type = 'page';
            $shopPage->is_shop = 1;
            $shopPage->save();
        }
        return $shopPage;
    }

    /**
     * Get or create the default blog page.
     *
     * @return static|array|null
     */
    public static function createDefaultBlogPage(): static|array|null
    {
        $blogPage = get_pages('content_type=page&subtype=dynamic&is_shop=0&single=1');
        if (!$blogPage) {
            // task-2026-05-17-05a3bc / AI-843 — race-condition
            // hardening (AI-791 lineage; preventative complement to
            // the AI-791 Slice D + AI-792b cleanup migration).
            //
            // 3 call sites (TemplateInstaller.php:159 + ContentResource
            // .php:620 + ContentRepository.php:264) can fire this
            // method concurrently in the same install bootstrap.
            // Pre-fix the get_pages() null-check above + the new
            // static() save below carried a race window where TWO
            // concurrent calls both observed get_pages() = null and
            // both proceeded to save a Blog page — producing the
            // orphan `Blog{14-digit-timestamp}` rows that AI-791
            // Slice D migration cleans up reactively.
            //
            // Option B fix (designer-validated, smallest diff):
            // re-check via a different query path (Content::where on
            // the canonical 'Blog' URL) BEFORE the save. Catches the
            // race window — if Call A's save completed between Call
            // B's get_pages() and Call B's where(), Call B's where()
            // returns true and we return early without re-saving.
            //
            // Belt + suspenders: the small remaining window between
            // where() and save() (1 SQL roundtrip) is covered by the
            // AI-791 Slice D cleanup migration as a fallback safety
            // net. Application-level prevention here + reactive
            // cleanup migration there = two-layer defence.
            //
            // Options A (Schema::table->lockForUpdate) + C (Cache
            // ::lock) noted in AI-843 ticket body as alternatives if
            // PM prefers DB-level or cache-level locking; Option B
            // chosen for smallest code surface.
            if (static::where('url', 'Blog')->exists()) {
                return null;
            }
            $blogPage = new static();
            $blogPage->title = 'Blog';
            $blogPage->content_type = 'page';
            $blogPage->subtype = 'dynamic';
            // AI-792 (task-2026-05-17-4e9d1b) — bind the Blog page to
            // the active template's blog.blade.php layout. Without
            // this, the renderer falls back to the default home
            // template (which is the marketing hero) and visitors to
            // /Blog see the home page instead of the post archive.
            // Templates that lack blog.blade.php still get a
            // dynamic-subtype page; the resolver downgrades cleanly
            // to the next-best match.
            $blogPage->layout_file = 'blog.blade.php';
            $blogPage->save();
        }
        return $blogPage;
    }

    /**
     * Get the first shop page.
     *
     * @return array|null
     */
    public static function getFirstShopPage(): array|null
    {
        return get_pages('content_type=page&is_shop=1&is_deleted=0&single=1') ?: null;
    }

    /**
     * Get all shop pages.
     *
     * @return array
     */
    public static function getAllShopPages(): array
    {
        return get_pages('content_type=page&is_deleted=0&is_shop=1') ?: [];
    }

    /**
     * Get all blog pages.
     *
     * @return array
     */
    public static function getAllBlogPages(): array
    {
        return get_pages('content_type=page&subtype=dynamic&is_deleted=0&is_shop=0') ?: [];
    }

    /**
     * Get the first blog page.
     *
     * @return array|null
     */
    public static function getFirstBlogPage(): array|null
    {
        return get_pages('content_type=page&subtype=dynamic&is_shop=0&single=1') ?: null;
    }

    /**
     * Get all media for a content ID as an array.
     *
     * @param mixed $contentId
     * @return array
     */
    public static function getMediaByContentId($contentId): array
    {
        $media = DB::table('media')
            ->where('rel_id', $contentId)
            ->where('rel_type', morph_name(static::class))
            ->orderBy('position', 'asc')
            ->get();

        if ($media->isEmpty()) {
            return [];
        }

        return $media->map(fn($item) => (array) $item)->toArray();
    }

    /**
     * Get related content IDs for a given content ID.
     *
     * @param mixed $id
     * @return array
     */
    public static function getRelatedContentIdsByContentId($id): array
    {
        $related = DB::table('content_related')
            ->select('related_content_id')
            ->where('content_id', $id)
            ->orderBy('position', 'asc')
            ->get();

        if ($related->isEmpty()) {
            return [];
        }

        return $related->pluck('related_content_id')->toArray();
    }

    /**
     * Get custom fields of a specific type for a given relationship ID.
     *
     * @param mixed $relId
     * @param string $type
     * @return array
     */
    public static function getCustomFieldsByRelIdAndType($relId, string $type): array
    {
        $fields = static::getCustomFieldsByRelId($relId);
        if (empty($fields)) {
            return [];
        }

        return array_values(array_filter($fields, fn($field) => isset($field['type']) && $field['type'] === $type));
    }

    /**
     * Get an editable content field by field name, rel_type, and optional rel_id.
     *
     * @param string $field
     * @param string $rel_type
     * @param mixed $rel_id
     * @return array|false
     */
    public static function getEditFieldData(string $field, string $rel_type, $rel_id = false): array|false
    {
        $check = DB::table('content_fields');
        $check->where('field', $field);
        $check->where('rel_type', $rel_type);
        if ($rel_id) {
            $check->where('rel_id', $rel_id);
        }
        $check = $check->first();

        if ($check && !empty($check)) {
            $check = (array) $check;
            $check = app()->url_manager->replace_site_url_back($check);
            return $check;
        }

        return false;
    }
}
