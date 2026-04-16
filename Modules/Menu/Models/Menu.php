<?php
namespace Modules\Menu\Models;

use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Database\Casts\ReplaceSiteUrlCast;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;
use MicroweberPackages\Multilanguage\Models\Traits\HasMultilanguageTrait;
use Modules\Category\Models\Category;
use Modules\Content\Models\Content;

class Menu extends Model
{
    use CacheableQueryBuilderTrait;
    use HasMultilanguageTrait;

    protected $casts = [
         'url' => ReplaceSiteUrlCast::class,
         'default_image' => ReplaceSiteUrlCast::class,
         'rollover_image' => ReplaceSiteUrlCast::class,
         'mega_menu_settings' => 'array',
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
        "rollover_image",
        "enable_mega_menu",
        "menu_item_template",
        "mega_menu_settings",
    ];

    public $translatable = ['title','url'];

    public $cacheTagsToClear = ['menus','repositories','content','categories','menus_item'];


    /**
     * Get all menus ordered by position.
     */
    public static function queryAllOrdered(): array
    {
        return static::query()
            ->orderBy('position', 'asc')
            ->get()
            ->map(fn($menu) => $menu->toArray())
            ->toArray();
    }

    /**
     * Filter menus array by parent_id.
     */
    public static function filterByParentId(array $allMenus, $parentId): array
    {
        $menus = [];
        foreach ($allMenus as $menu) {
            if ($menu['parent_id'] == $parentId) {
                $menus[] = $menu;
            }
        }
        return $menus;
    }

    /**
     * Filter menus array by parent_id and item_type, returning the first match.
     */
    public static function filterByParentIdAndItemType(array $allMenus, $parentId, $itemType): array
    {
        foreach ($allMenus as $menu) {
            if ($menu['parent_id'] == $parentId && $menu['item_type'] == $itemType) {
                return $menu;
            }
        }
        return [];
    }

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
