<?php

namespace MicroweberPackages\Menu\Repositories;

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

    public function getAllMenus()
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () {
            return $this->getModel()->get()->toArray();
        });
    }

    public function getMenusByParentIdAndItemType($parentId, $itemType)
    {

        $allMenus = $this->getAllMenus();

        foreach ($allMenus as $menu) {
            if ($menu['parent_id'] == $parentId && $menu['item_type'] == $itemType) {
                return $menu;
            }
        }
        
        return false;

      /*  return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($parentId,$itemType) {
            return $this->getModel()->where('parent_id', $parentId)->where('item_type', $itemType)->orderBy('position', 'ASC')->get()->toArray();
        });*/
    }

    public function getMenusByParentId($parentId)
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($parentId) {
            return $this->getModel()->where('parent_id', $parentId)->orderBy('position', 'ASC')->get()->toArray();
        });
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
                        $check  = $this->app->database_manager->get('no_cache=1&title=' . $params['title']);
                        if(!$check){
                            $new_menu = $this->menu_create('title=' . $params['title']);
                            $params['id'] = $new_menu;
                            $menus = $this->app->database_manager->get($params);
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
