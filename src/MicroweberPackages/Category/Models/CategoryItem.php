<?php
namespace MicroweberPackages\Category\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryItem extends Model
{

    public $table = 'categories_items';
    public $timestamps = false;


    public function parent()
    {
        return $this->hasOne(Category::class,  'id', 'parent_id');
    }
}
