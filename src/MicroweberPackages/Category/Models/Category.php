<?php
namespace MicroweberPackages\Category\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

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

//    public static function getLinks()
//    {
//        $allCategories =  self::all();
//
//        return $allCategories;
//    }
}
