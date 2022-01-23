<?php
namespace MicroweberPackages\Category\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Category\Models\ModelFilters\CategoryFilter;
use MicroweberPackages\ContentData\Traits\ContentDataTrait;
use MicroweberPackages\Core\Models\HasSearchableTrait;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;
use MicroweberPackages\Database\Traits\HasCreatedByFieldsTrait;
use MicroweberPackages\Database\Traits\HasSlugTrait;
use MicroweberPackages\Database\Traits\MaxPositionTrait;
use MicroweberPackages\Media\Traits\MediaTrait;
use MicroweberPackages\Multilanguage\Models\Traits\HasMultilanguageTrait;

class Category extends Model
{
    use HasMultilanguageTrait;
    use CacheableQueryBuilderTrait;
    use Filterable;
    use HasSearchableTrait;
    use ContentDataTrait;
    use HasCreatedByFieldsTrait;
    use MaxPositionTrait;
    use MediaTrait;
    use HasSlugTrait;

    protected $table = 'categories';

  //  public $timestamps = false;

    /**
     * The model's default values for attributes.
     * @var array
     */
    protected $attributes = [
        'data_type' => 'category',
        'rel_type' => 'content'
    ];

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
        "category_meta_title",
        "category_meta_description",
        "is_hidden",
        "category_meta_keywords"
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

    public $translatable = ['title','url','description','content'];

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

    public function getMorphClass()
    {
        return 'categories';
    }


    public static function hasActiveProductInSubcategories($category)
    {

        if(empty($category) || count($category->items) == 0) {
            return false;
        }

        foreach($category->items as $item) {
            $product = \MicroweberPackages\Product\Models\Product::find($item->rel_id);

            if($product->in_stock) {
                return true;
            }
        }

        if(count($category->children) > 0) {
            foreach($category->children as $childCat) {
                if(self::hasActiveProductInSubcategories($childCat)) {
                    return true;
                }
            }
        }

        return false;
    }
}
