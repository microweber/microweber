<?php
namespace MicroweberPackages\Category\Models;

use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Content\Models\Content;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;

class CategoryItem extends Model
{

    use CacheableQueryBuilderTrait;

    public $table = 'categories_items';
    public $timestamps = false;


    public function parent()
    {
        return $this->hasOne(Category::class,  'id', 'parent_id');
    }

    public function contentItems()
    {
        return $this->hasMany(Content::class, 'id', 'rel_id')->where('rel_type', 'content');
    }
}
