<?php
namespace MicroweberPackages\Product;

use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Content\Scopes\ProductScope;

class Product extends Model
{
    protected $table = 'content';
    protected $primaryKey = 'id';

    protected $fillable = [
        'title',
        'url',
        'parent',
        'description',
        'position',
        'is_active',
        'is_deleted',
        'status'
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

    public function price()
    {
        return $this->hasOne(ProductPrice::class, 'rel_id');
    }

    public function specialPrice()
    {
        return $this->hasOne(ProductSpecialPrice::class, 'rel_id');
    }
}
