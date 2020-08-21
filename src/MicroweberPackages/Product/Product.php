<?php
namespace MicroweberPackages\Product;

use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Content\Scopes\ProductScope;
use MicroweberPackages\Menu\Traits\HasMenuItem;

class Product extends Model
{
    use HasMenuItem;

    protected $table = 'content';
    protected $primaryKey = 'id';

    protected $fillable = [
        'title',
        'url',
        'parent',
        'description',
        'position',
        'content',
        'content_body',
        'is_active',
        'is_home',
        'is_shop',
        'is_deleted',
        'status',
        'add_content_to_menu'
    ];

    public $translatable = ['title','description','content','content_body'];

    public function setPrice($price) {
        $this->price = $price;
    }

    public function setSpecialPrice($price) {
        $this->special_price = $price;
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new ProductScope());
    }
}
