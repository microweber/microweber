<?php
namespace MicroweberPackages\Category\Models;

use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;

class CategoryItem extends Model
{
    use CacheableQueryBuilderTrait;

    public $table = 'categories_items';
    public $timestamps = false;


    /**
     * @deprecated
     */
    public function parent()
    {
        return $this->category();
    }

    public function category()
    {
        return $this->hasOne(Category::class,  'id', 'parent_id');
    }

    public function contentItems()
    {
        return $this->hasMany(\Modules\Content\Models\Content::class, 'id', 'rel_id')->where('rel_type', morph_name(\Modules\Content\Models\Content::class));
    }
}
