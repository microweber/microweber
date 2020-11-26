<?php
namespace MicroweberPackages\Category\Models;

use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Content\Content;

class CategoryItem extends Model
{
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
