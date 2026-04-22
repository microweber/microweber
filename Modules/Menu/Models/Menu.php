<?php
namespace Modules\Menu\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Database\Casts\ReplaceSiteUrlCast;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;
use MicroweberPackages\Multilanguage\Models\Traits\HasMultilanguageTrait;
use MicroweberPackages\Repository\MicroweberQuery;
use Modules\Category\Models\Category;
use Modules\Content\Models\Content;
use Modules\Menu\Database\Factories\MenuFactory;

class Menu extends Model
{
    use CacheableQueryBuilderTrait;
    use HasFactory;
    use HasMultilanguageTrait;

    protected static function newFactory()
    {
        return MenuFactory::new();
    }

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
     * Get the content associated with this menu item.
     */
    public function content()
    {
        return $this->belongsTo(Content::class, 'content_id');
    }

    /**
     * Get the category associated with this menu item.
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'categories_id');
    }

    /**
     * Get all menus ordered by position with eager-loaded relations.
     */
    public static function queryAllOrdered(): array
    {
        return static::query()
            ->with(['content:id,title', 'category:id,title'])
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

    /**
     * Query menus by params with auto-creation support.
     */
    public static function getMenus($params)
    {
        $params2 = array();
        if ($params == false) {
            $params = array();
        }
        if (is_string($params)) {
            $params = parse_str($params, $params2);
            $params = $params2;
        }

        $params['item_type'] = 'menu';
        if (is_live_edit()) {
            $params['no_cache'] = 1;
        }

        $menus = MicroweberQuery::execute(static::query(), $params);

        if (!empty($menus)) {
            return $menus;
        }

        if (!defined('MW_MENU_IS_ALREADY_MADE_ONCE')) {
            if (isset($params['make_on_not_found']) and ($params['make_on_not_found']) == true and isset($params['title'])) {
                $check = app()->database_manager->get('no_cache=1&title=' . $params['title']);
                if (!$check) {
                    $new_menu = app()->menu_manager->menu_create('title=' . $params['title']);
                    $params['id'] = $new_menu;
                    $menus = app()->database_manager->get($params);
                }
            }
            define('MW_MENU_IS_ALREADY_MADE_ONCE', true);
        }

        if (!empty($menus)) {
            return $menus;
        }

        return null;
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
