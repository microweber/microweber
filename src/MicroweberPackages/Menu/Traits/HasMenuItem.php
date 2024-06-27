<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 8/20/2020
 * Time: 1:16 PM
 */

namespace MicroweberPackages\Menu\Traits;

use Illuminate\Support\Facades\DB;
use MicroweberPackages\Menu\Models\Menu;
use MicroweberPackages\Menu\Models\MenuItem;

trait HasMenuItem
{
    public static $addContentToMenu = [];
    public static $setMenuIdsForContent = null;
    private $_setMenuIdsForContent = null;


    public function initializeHasMenuItem()
    {
        $this->fillable[] = 'add_content_to_menu';
        $this->fillable[] = '_setMenuIdsForContent';
        //  $this->hidden[] = 'contentMenuIdsSet';
        // $this->hidden[] = 'contentMenuIdsSet';
        $this->casts['add_content_to_menu'] = 'array';
        // $this->casts['contentMenuIdsSet'] = 'array';
        //$this->casts['menuIds'] = 'array';
    }


    public static function bootHasMenuItem()
    {
        static::updated(function ($model) {

        });
        static::saving(function ($model) {

            // append content to categories
            if (isset($model->add_content_to_menu)) {
                self::$addContentToMenu = $model->add_content_to_menu;
            }

            if (isset($model->_setMenuIdsForContent)) {
                self::$setMenuIdsForContent = $model->_setMenuIdsForContent;
            }
            unset($model->add_content_to_menu);
            unset($model->_setMenuIdsForContent);

        });


        static::saved(function ($model) {
            $meuIdsToKeep = [];
            if (!empty(self::$addContentToMenu) && is_array(self::$addContentToMenu)) {
                foreach (self::$addContentToMenu as $menuId) {
                    // check if content is already in menu
                    if (!app()->menu_manager->is_in_menu($menuId, $model->id)) {
                        app()->content_manager->helpers->add_content_to_menu($model->id, $menuId);
                    }
                    $meuIdsToKeep[] = $menuId;
                }
            }
            if (isset(self::$setMenuIdsForContent)) {
                if (empty(self::$setMenuIdsForContent)) {
                    $model->menuItems()->delete();
                } else {

                    foreach (self::$setMenuIdsForContent as $menuId) {
                        $meuIdsToKeep[] = $menuId;
                        if (!app()->menu_manager->is_in_menu($menuId, $model->id)) {
                            app()->content_manager->helpers->add_content_to_menu($model->id, $menuId);
                        }
                    }

                }
            }

            if (!empty($meuIdsToKeep)) {
                $model->menuItems()->whereNotIn('parent_id', $meuIdsToKeep)
                    ->where('item_type', 'menu_item')
                    ->where('content_id', $model->id)
                    ->delete();
            }

        });


        static::deleting(function ($model) {
            $model->menuItems()->delete();
        });
    }

    public function menuItems()
    {
        return $this->hasMany(MenuItem::class, 'content_id');
        //        return $this->belongsToMany(MenuItem::class, 'content', 'id','id', 'id', 'content_id');
    }

    public function setMenuIds($value)
    {

        if (!is_array($value)) {
            $this->_setMenuIdsForContent = [$value];

        } else {
            $this->_setMenuIdsForContent = $value;
        }
    }

    public function getMenuIdsAttribute()
    {

        $menus = $this->menuItems()->get();

//        $menus = DB::table('menus')
//            ->select('parent_id')
//            ->where('item_type', 'menu_item')
//            ->where('content_id', $this->id)
//            ->get();

        $menusItems = [];
        if ($menus) {
            foreach ($menus as $menu) {
                $menusItems[] = $menu->parent_id;
            }
        }

        return $menusItems;
    }




    //    public function addToMenu($contentId)
//    {
//        if (is_array($contentId)) {
//            self::$addContentToMenu = array_merge(self::$addContentToMenu, $contentId);
//        } else {
//            self::$addContentToMenu[] = $contentId;
//        }
//    }

}
