<?php
namespace MicroweberPackages\Menu\Models;

use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Content\Models\Content;
use MicroweberPackages\Database\Casts\ReplaceSiteUrlCast;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;
use MicroweberPackages\Multilanguage\Models\Traits\HasMultilanguageTrait;

class Menu extends Model
{
    use CacheableQueryBuilderTrait;
    use HasMultilanguageTrait;

    protected $casts = [
         'url' => ReplaceSiteUrlCast::class,
         'default_image' => ReplaceSiteUrlCast::class,
         'rollover_image' => ReplaceSiteUrlCast::class,
    ];

    public $fillable = [
        "id",
        "title",
        "item_type",
        "parent_id",
        "content_id",
        "categories_id",
        "position",
        "is_active",
        "auto_populate",
        "description",
        "url",
        "url_target",
        "size",
        "default_image",
        "rollover_image"
    ];

    public $translatable = ['title','url'];

    public $cacheTagsToClear = ['menus','repositories','content'];


    public function getDisplayTitleAttribute()
    {
        $title = $this->title;

        if ($this->content_id) {
            $content = Content::where('id', $this->content_id)->first();
            if ($content) {
                $title = $content->title;
            }
        }
        if ($this->categories_id) {
            $category = Category::where('id', $this->categories_id)->first();
            if ($category) {
                $title = $category->title;
            }
        }

        return $title;
    }
}
