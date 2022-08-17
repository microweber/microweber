<?php

namespace MicroweberPackages\Menu\Repositories;

use Illuminate\Support\Facades\DB;
use MicroweberPackages\Menu\Menu;
use MicroweberPackages\Repository\MicroweberQuery;
use MicroweberPackages\Repository\Repositories\AbstractRepository;

class MenuRepository extends AbstractRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    public $model = Menu::class;

    public static $_getAllMenus = [];
    public function clearCache()
    {
        self::$_getAllMenus = [];
        parent::clearCache();
    }
    public function getAllMenus()
    {
        if (!empty(self::$_getAllMenus)) {
            return self::$_getAllMenus;
        }

        $menus = $this->cacheCallback(__FUNCTION__, func_get_args(), function () {

            $getMenu = Menu::query()->orderBy('position', 'asc')->get();

            $allMenus = collect($getMenu)->map(function ($option) {
                return $option->toArray();
            })->toArray();

            return $allMenus;
        });

        self::$_getAllMenus = $menus;
        return $menus;
    }

    public function getMenusByParentIdAndItemType($parentId, $itemType)
    {

        $allMenus = $this->getAllMenus();

        foreach ($allMenus as $menu) {
            if ($menu['parent_id'] == $parentId && $menu['item_type'] == $itemType) {
                return $menu;
            }
        }

        return [];

      /*  return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($parentId,$itemType) {
            return $this->getModel()->where('parent_id', $parentId)->where('item_type', $itemType)->orderBy('position', 'ASC')->get()->toArray();
        });*/
    }

    public function getMenusByParentId($parentId)
    {
        $allMenus = $this->getAllMenus();

        $menus = [];
        foreach ($allMenus as $menu) {
            if ($menu['parent_id'] == $parentId) {
                $menus[] = $menu;
            }
        }

       if (is_array($menus) && !empty($menus)) {

            $hookParams = [];
            $hookParams['data'] = $menus;
            $hookParams['hook_overwrite_type'] = 'multiple';

            $overwrite = app()->event_manager->response(get_class($this) .'\\'. __FUNCTION__, $hookParams);

            if (isset($overwrite['data'])) {
                $menus = $overwrite['data'];
            }
        }

        return $menus;
    }

    public function getMenus($params)
    {
       // dump($params);

        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($params) {

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
                $params['no_cache'] = 1; // If remove this we mess up menu auto creating
                //dd($params);
            }

            $menus = MicroweberQuery::execute(Menu::query(), $params);

            if (!empty($menus)) {
                return $menus;
            } else {
                if (!defined('MW_MENU_IS_ALREADY_MADE_ONCE')) {
                    if (isset($params['make_on_not_found']) and ($params['make_on_not_found']) == true and isset($params['title'])) {
                        $check  = app()->database_manager->get('no_cache=1&title=' . $params['title']);
                        if(!$check){
                            $new_menu = app()->menu_manager->menu_create('title=' . $params['title']);
                            $params['id'] = $new_menu;
                            $menus = app()->database_manager->get($params);
                        }
                    }
                    define('MW_MENU_IS_ALREADY_MADE_ONCE', true);
                }
            }
            if (!empty($menus)) {
                return $menus;
            }

        });
    }


}
