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

    public function getMenus($params)
    {
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
            if(is_live_edit()){
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
