<?php

namespace Modules\Category\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Kirschbaum\PowerJoins\PowerJoins;
use MicroweberPackages\Core\Models\HasSearchableTrait;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;
use MicroweberPackages\Database\Traits\HasCreatedByFieldsTrait;
use MicroweberPackages\Database\Traits\HasSlugTrait;
use MicroweberPackages\Database\Traits\MaxPositionTrait;
use MicroweberPackages\Multilanguage\Models\Traits\HasMultilanguageTrait;
use Modules\Category\Models\ModelFilters\CategoryFilter;
use Modules\Category\Traits\SetParentIdToCategoryTrait;
use Modules\ContentData\Traits\ContentDataTrait;
use Modules\ContentField\Concerns\HasContentFieldTrait;
use Modules\Media\Traits\MediaTrait;

class Category extends Model
{
    // use HasAttributes;

    use HasFactory;
    use HasContentFieldTrait;
    use CacheableQueryBuilderTrait;
    use Filterable;
    use HasSearchableTrait;
    use ContentDataTrait;
    use HasCreatedByFieldsTrait;
    use MaxPositionTrait;
    use MediaTrait;
    use HasSlugTrait;
    use HasMultilanguageTrait;
    use SetParentIdToCategoryTrait;

    protected $table = 'categories';

    //  public $timestamps = false;

    /**
     * The model's default values for attributes.
     * @var array
     */
    protected $attributes = [
        'data_type' => 'category',
        'rel_type' => \Modules\Content\Models\Content::class,
        'is_active' => '1',
        'is_deleted' => '0',
        'is_hidden' => '0',
        'parent_id' => '0',
    ];

    /* A list of fields that can be mass assigned. */
    public $fillable = [
        "id",
        "rel_type",
        "rel_id",
        "data_type",
        "parent_id",
        "title",
        "content",
        "description",
        // "category-parent-selector",
        "position",
        //  "thumbnail",
        "url",
        "users_can_create_content",
        "category_subtype",
        "category_subtype_settings",
        "category_meta_title",
        "category_meta_description",
        "is_hidden",
        "is_active",
        "is_deleted",
        "is_hidden",
        "category_meta_keywords"
    ];

    public $casts = [
        'category_subtype_settings' => 'array',
        'position' => 'integer',
        'parent_id' => 'integer',
        'is_active' => 'integer',
        'is_hidden' => 'integer',
        'is_deleted' => 'integer',
    ];

    protected $searchable = [
        "id",
        "rel_type",
        "rel_id",
        "data_type",
        "parent_id",
        "title",
        "content",
        "description",
        "position",
        "url",
        "is_hidden",
        "is_deleted",
        "users_can_create_content",
        "users_can_create_content_allowed_usergroups",
        "category_subtype",
        "category_meta_title",
        "category_meta_description",
        "category_meta_keywords"
    ];

    public $cacheTagsToClear = ['content', 'content_fields_drafts', 'menu', 'content_fields', 'content_data', 'categories'];

    public $translatable = ['title', 'url', 'description', 'content', 'category_meta_keywords', 'category_meta_description', 'category_meta_title'];


    public function modelFilter()
    {
        return $this->provideFilter(CategoryFilter::class);
    }

    public function items()
    {
        return $this->hasMany(CategoryItem::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }

    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }


    public function link()
    {
        return category_link($this->id);
    }

//    public static function getLinks()
//    {
//        $allCategories =  self::all();
//
//        return $allCategories;
//    }

//    public function getMorphClass()
//    {
//        return 'category';
//    }


    /**
     * Get the full category tree starting from root categories.
     *
     * @return array|false
     */
    public static function tree()
    {
        $roots = DB::table('categories')
            ->where('data_type', 'category')
            ->where('parent_id', 0)
            ->get();

        if ($roots === null || $roots->isEmpty()) {
            return false;
        }

        return $roots->map(function ($item) {
            $item->childs = static::getChildsTree($item->id);
            return (array) $item;
        })->toArray();
    }

    /**
     * Recursively get child categories as a nested tree.
     *
     * @param int|array $categoryId
     * @return array|false
     */
    public static function getChildsTree($categoryId)
    {
        $query = DB::table('categories')->where('data_type', 'category');

        if (is_array($categoryId)) {
            $query->whereIn('parent_id', $categoryId);
        } else {
            $query->where('parent_id', $categoryId);
        }

        $categories = $query->get();

        if ($categories === null || $categories->isEmpty()) {
            return false;
        }

        return $categories->map(function ($item) {
            $item->childs = static::getChildsTree($item->id);
            return (array) $item;
        })->toArray();
    }

    /**
     * Get direct sub-categories (id and parent_id only, non-recursive).
     *
     * @param int|array $categoryId
     * @return array|false
     */
    public static function getSubCategories($categoryId)
    {
        $query = DB::table('categories')
            ->select(['id', 'parent_id'])
            ->where('data_type', 'category');

        if (is_array($categoryId)) {
            $query->whereIn('parent_id', $categoryId);
        } else {
            $query->where('parent_id', $categoryId);
        }

        $categories = $query->get();

        if ($categories === null || $categories->isEmpty()) {
            return false;
        }

        return $categories->map(function ($item) {
            return (array) $item;
        })->toArray();
    }

    /**
     * Get count of in-stock products grouped by category id.
     *
     * @return array
     */
    public static function getItemsInStockCountAll()
    {
        $realTableName = app()->database_manager->real_table_name('content');
        $query = CategoryItem::query()
            ->leftJoin('content', function ($join) {
                $join->on('content.id', '=', 'categories_items.rel_id')
                    ->where('content.is_deleted', '=', 0)
                    ->where('content.is_active', '=', 1);
            })
            ->select([
                'categories_items.parent_id',
                'categories_items.rel_type',
                DB::raw('count( DISTINCT `' . $realTableName . '`.`id` ) as count'),
            ])
            ->where('rel_type', morph_name(\Modules\Content\Models\Content::class))
            ->groupBy('categories_items.parent_id');

        $query->whereIn('categories_items.rel_id',
            \Modules\Product\Models\Product::select(['content.id'])
                ->filter(['inStock' => 1])
                ->select(['content.id'])
        );

        $result = [];
        $data = $query->get();
        if ($data) {
            foreach ($data as $value) {
                $result[$value->parent_id] = $value->count;
            }
        }
        return $result;
    }

    /**
     * Get count of in-stock products for a specific category.
     *
     * @param int $categoryId
     * @return int
     */
    public static function getProductsInStockCount($categoryId)
    {
        $all = static::getItemsInStockCountAll();

        if (isset($all[$categoryId])) {
            return $all[$categoryId];
        }

        return 0;
    }

    /**
     * Check if a category (or its children) has products in stock.
     *
     * @param int $categoryId
     * @return bool
     */
    public static function hasProductsInStock($categoryId)
    {
        $count = static::getProductsInStockCount($categoryId);
        if ($count > 0) {
            return true;
        }

        $children = static::getChildsTree($categoryId);
        if (static::checkProductsInStockRecursive($children)) {
            return true;
        }

        return false;
    }

    /**
     * Recursively check child categories for in-stock products.
     *
     * @param array|false $categories
     * @return bool
     */
    private static function checkProductsInStockRecursive($categories)
    {
        if (!empty($categories)) {
            foreach ($categories as $category) {
                $count = static::getProductsInStockCount($category['id']);
                if ($count > 0) {
                    return true;
                }
                if (isset($category['childs']) && !empty($category['childs'])) {
                    return static::checkProductsInStockRecursive($category['childs']);
                }
            }
        }
        return false;
    }

    /**
     * Get count of all category items (active, non-deleted content) grouped by category id.
     *
     * @return array
     */
    public static function getItemsCountAll()
    {
        $realTableName = app()->database_manager->real_table_name('content');
        $query = CategoryItem::query()
            ->leftJoin('content', function ($join) {
                $join->on('content.id', '=', 'categories_items.rel_id')
                    ->where('content.is_deleted', '=', 0)
                    ->where('content.is_active', '=', 1);
            })
            ->select([
                'categories_items.parent_id',
                'categories_items.rel_type',
                DB::raw('count( DISTINCT `' . $realTableName . '`.`id` ) as count'),
            ])
            ->where('rel_type', morph_name(\Modules\Content\Models\Content::class))
            ->groupBy('categories_items.parent_id');

        $result = [];
        $data = $query->get();
        if ($data) {
            foreach ($data as $value) {
                $result[$value->parent_id] = $value->count;
            }
        }
        return $result;
    }

    /**
     * Get count of items for a specific category.
     *
     * @param int $categoryId
     * @return int
     */
    public static function getItemsCount($categoryId)
    {
        $all = static::getItemsCountAll();

        if (isset($all[$categoryId])) {
            return $all[$categoryId];
        }

        return 0;
    }

    /**
     * Get category items by category id, rel type, and rel id.
     *
     * @param int|null $categoryId
     * @param string|false $relType
     * @param int|false $relId
     * @return array|false
     */
    public static function getItems($categoryId, $relType = false, $relId = false)
    {
        if ($relType == 'content' || $relType == 'category') {
            $relType = app()->database_manager->morphClassFromTable($relType);
        }

        if (!$relType) {
            $relType = morph_name(\Modules\Content\Models\Content::class);
        }

        $model = (new CategoryItem())->newQuery();
        if ($categoryId) {
            $model->where('parent_id', $categoryId);
        }
        if ($relType) {
            $model->where('rel_type', $relType);
        }
        if ($relId) {
            $model->where('rel_id', $relId);
        }
        $data = $model->get();

        if ($data and $data->count() > 0) {
            return $data->toArray();
        } else {
            return false;
        }
    }

    /**
     * Get media for a category by its id.
     *
     * @param mixed $id
     * @return array
     */
    public static function getMediaById($id)
    {
        $item = static::find($id);
        if ($item) {
            $get = $item->media;
            if ($get) {
                return $get->toArray();
            }
        }
        return [];
    }

    /**
     * Scope to filter categories by URL.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $url
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByUrl($query, $url)
    {
        return $query->where('url', $url);
    }

    /**
     * Get categories by URL as plain arrays.
     *
     * @param string $url
     * @return array
     */
    public static function getByUrl($url)
    {
        $categories = DB::table('categories')->where('url', $url)->get();

        return collect($categories)->map(function ($item) {
            return (array) $item;
        })->toArray();
    }

    //todo move to repository
    public static function hasActiveProductInSubcategories($category)
    {

        if (empty($category) || count($category->items) == 0) {
            return false;
        }

        foreach ($category->items as $item) {
            $product = \Modules\Product\Models\Product::find($item->rel_id);

            if ($product->in_stock) {
                return true;
            }
        }

        if (count($category->children) > 0) {
            foreach ($category->children as $childCat) {
                if (self::hasActiveProductInSubcategories($childCat)) {
                    return true;
                }
            }
        }

        return false;
    }
}
