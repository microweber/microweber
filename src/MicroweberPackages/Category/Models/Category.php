<?php
namespace MicroweberPackages\Category\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Category\Models\ModelFilters\CategoryFilter;

class Category extends Model
{

    use Filterable;

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

    public $translatable = ['title','url','description','content'];

    public function link($id)
    {
        return category_link($id);
    }

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

//    public static function getLinks()
//    {
//        $allCategories =  self::all();
//
//        return $allCategories;
//    }
}
