<?php


namespace Microweber\Providers\Content;

use Microweber\Providers\Database\Crud;
use Content;
use ContentFields;
use Menu;
use DB;

class ContentManagerHelpers extends ContentManagerCrud {

    public function add_content_to_menu($content_id, $menu_id = false) {

        $id = $this->app->user_manager->is_admin();
        if (defined("MW_API_CALL") and $id==false){
            return;
        }


        $new_item = false;

        $id = $this->app->user_manager->is_admin();
        if (defined("MW_API_CALL") and $id==false){
            return;
        }

        if (isset($content_id['id'])){
            $content_id = $content_id['id'];
        }


        $content_id = intval($content_id);
        if ($content_id==0 or !isset($this->tables['menus'])){
            return;
        }
        if ($menu_id!=false){
            //  $_REQUEST['add_content_to_menu'] = array( $menu_id);


        }

        $menus = $this->tables['menus'];
        if (isset($_REQUEST['add_content_to_menu']) and is_array($_REQUEST['add_content_to_menu'])){
            $add_to_menus = $_REQUEST['add_content_to_menu'];
            $add_to_menus_int = array();
            foreach ($add_to_menus as $value) {
                if ($value=='remove_from_all'){
                    Menu::where('content_id', $content_id)->where('item_type', 'menu_item')->delete();
                    $this->app->cache_manager->delete('menus');
                }
                $value = intval($value);
                if ($value > 0){
                    $add_to_menus_int[] = $value;
                }
            }
        }


        $add_under_parent_page = false;
        $content_data = false;

        if (isset($_REQUEST['add_content_to_menu_auto_parent']) and ($_REQUEST['add_content_to_menu_auto_parent'])!=false){
            $add_under_parent_page = true;
            $content_data = $this->get_by_id($content_id);
            if ($content_data['is_active']!=1){
                return false;
            }

        }
        if (!isset($add_to_menus_int) or empty($add_to_menus_int)){
            if ($menu_id!=false){
                $add_to_menus_int[] = intval($menu_id);
            }
        }


        if (isset($add_to_menus_int) and is_array($add_to_menus_int)){

            Menu::where('content_id', $content_id)
                ->where('item_type', 'menu_item')
                ->whereNotIn('parent_id', $add_to_menus_int)
                ->delete();
            foreach ($add_to_menus_int as $value) {
                //  $check = $this->app->menu_manager->get_menu_items("parent_id={$value}&content_id=$content_id");

                $check = Menu::where('content_id', $content_id)
                    ->where('item_type', 'menu_item')
                    ->where('parent_id', $value)
                    ->count();


                if ($check==0){
                    $save = array();
                    $save['item_type'] = 'menu_item';
                    $save['is_active'] = 1;
                    $save['parent_id'] = $value;
                    $save['position'] = 999999;
                    if ($add_under_parent_page!=false and is_array($content_data) and isset($content_data['parent'])){
                        $parent_cont = $content_data['parent'];
                        $check_par = $this->app->menu_manager->get_menu_items("limit=1&one=1&content_id=$parent_cont");
                        if (is_array($check_par) and isset($check_par['id'])){
                            $save['parent_id'] = $check_par['id'];
                        }
                    }


                    $save['url'] = '';
                    $save['content_id'] = $content_id;

                    $new_item = $this->app->database_manager->save($menus, $save);

                    $this->app->cache_manager->delete('menus');

                    $this->app->cache_manager->delete('menus/' . $save['parent_id']);

                    $this->app->cache_manager->delete('menus/' . $value);

                    $this->app->cache_manager->delete('content/' . $content_id);
                }
            }

            $this->app->cache_manager->delete('menus/global');
            $this->app->cache_manager->delete('menus');

        }

        return $new_item;

    }

    function create_default_content($what) {

        if (defined("MW_NO_DEFAULT_CONTENT")){
            return true;
        }


        switch ($what) {
            case 'shop' :
                $is_shop = $this->get('content_type=page&is_shop=0');
                //$is_shop = false;
                $new_shop = false;
                if ($is_shop==false){
                    $add_page = array();
                    $add_page['id'] = 0;
                    $add_page['parent'] = 0;
                    $add_page['is_active'] = 1;

                    $add_page['title'] = "Online shop";
                    $add_page['url'] = "shop";
                    $add_page['content_type'] = "page";
                    $add_page['subtype'] = 'dynamic';
                    $add_page['is_shop'] = '1';
                    $add_page['active_site_template'] = 'default';
                    $find_layout = $this->app->layouts_manager->scan();
                    if (is_array($find_layout)){
                        foreach ($find_layout as $item) {
                            if (isset($item['layout_file']) and isset($item['is_shop'])){
                                $add_page['layout_file'] = $item['layout_file'];
                                if (isset($item['name'])){
                                    $add_page['title'] = $item['name'];
                                }
                            }
                        }
                    }
                    $new_shop = $this->app->database_manager->save('content', $add_page);
                    $this->app->cache_manager->delete('content');
                    $this->app->cache_manager->delete('categories');
                    $this->app->cache_manager->delete('custom_fields');

                    //
                } else {

                    if (isset($is_shop[0])){
                        $new_shop = $is_shop[0]['id'];
                    }
                }

                $posts = $this->get('content_type=post&parent=' . $new_shop);
                if ($posts==false and $new_shop!=false){
                    $add_page = array();
                    $add_page['id'] = 0;
                    $add_page['parent'] = $new_shop;
                    $add_page['title'] = "My product";
                    $add_page['url'] = "my-product";
                    $add_page['content_type'] = "post";
                    $add_page['subtype'] = "product";
                    $add_page['is_active'] = 1;
                    //$new_shop = $this->save_content($add_page);
                    //$this->app->cache_manager->delete('content');
                    //$this->app->cache_manager->clear();
                }


                break;


            case 'blog' :
                $is_shop = $this->get('is_deleted=0&content_type=page&subtype=dynamic&is_shop=1&limit=1');
                //$is_shop = false;
                $new_shop = false;
                if ($is_shop==false){
                    $add_page = array();
                    $add_page['id'] = 0;
                    $add_page['parent'] = 0;
                    $add_page['is_active'] = 1;
                    $add_page['title'] = "Blog";
                    $add_page['url'] = "blog";
                    $add_page['content_type'] = "page";
                    $add_page['subtype'] = 'dynamic';
                    $add_page['is_shop'] = 0;
                    $add_page['active_site_template'] = 'default';
                    $find_layout = $this->app->layouts_manager->scan();
                    if (is_array($find_layout)){
                        foreach ($find_layout as $item) {
                            if (!isset($item['is_shop']) and isset($item['layout_file']) and isset($item['content_type']) and trim(strtolower($item['content_type']))=='dynamic'){
                                $add_page['layout_file'] = $item['layout_file'];
                                if (isset($item['name'])){
                                    $add_page['title'] = $item['name'];
                                }
                            }
                        }

                        foreach ($find_layout as $item) {
                            if (isset($item['name']) and stristr($item['name'], 'blog') and !isset($item['is_shop']) and isset($item['layout_file']) and isset($item['content_type']) and trim(strtolower($item['content_type']))=='dynamic'){
                                $add_page['layout_file'] = $item['layout_file'];
                                if (isset($item['name'])){
                                    $add_page['title'] = $item['name'];
                                }
                            }
                        }


                    }

                    $new_shop = $this->app->database_manager->save('content', $add_page);
                    $this->app->cache_manager->delete('content');
                    $this->app->cache_manager->delete('categories');
                    $this->app->cache_manager->delete('content_fields');


                    //
                } else {

                    if (isset($is_shop[0])){
                        $new_shop = $is_shop[0]['id'];
                    }
                }


                break;

            case 'default' :
            case 'install' :
                $any = $this->get('count=1&content_type=page&limit=1');
                if (intval($any)==0){
                    $table = $this->tables['content'];
                    mw_var('FORCE_SAVE_CONTENT', $table);
                    mw_var('FORCE_SAVE', $table);

                    $add_page = array();
                    $add_page['id'] = 0;
                    $add_page['parent'] = 0;
                    $add_page['title'] = "Home";
                    $add_page['url'] = "home";
                    $add_page['content_type'] = "page";
                    $add_page['subtype'] = 'static';
                    $add_page['is_shop'] = 0;
                    //$add_page['debug'] = 1;
                    $add_page['is_active'] = 1;
                    $add_page['is_home'] = 1;
                    $add_page['active_site_template'] = 'default';
                    $new_shop = $this->save_content($add_page);
                }

                break;

            default :
                break;
        }
    }

}