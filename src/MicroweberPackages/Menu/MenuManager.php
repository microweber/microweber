<?php

namespace MicroweberPackages\Menu;

use Content;
use Menu;
use MicroweberPackages\App\Http\RequestRoute;
use MicroweberPackages\Core\Events\AbstractResourceWasUpdated;
use MicroweberPackages\Menu\Events\MenuWasUpdated;

/**
 * Content class is used to get and save content in the database.
 *
 * @category Content
 * @desc  These functions will allow you to get and save content in the database.
 */
class MenuManager
{
    public $app = null;

    /**
     *  Boolean that indicates the usage of cache while making queries.
     *
     * @var
     */
    public $no_cache = false;

    public $tables = array();

    public function __construct($app = null)
    {
        if (!is_object($this->app)) {
            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = mw();
            }
        }
        $this->set_table_names();
    }

    /**
     * Sets the database table names to use by the class.
     *
     * @param array|bool $tables
     */
    public function set_table_names($tables = false)
    {
        if (!isset($tables['menus'])) {
            $tables['menus'] = 'menus';
        }
        $this->tables['menus'] = $tables['menus'];
    }

    public function menu_create($data_to_save)
    {
        $params2 = array();
        if ($data_to_save == false) {
            $data_to_save = array();
        }
        if (is_string($data_to_save)) {
            $params = parse_str($data_to_save, $params2);
            $data_to_save = $params2;
        }

        if (isset($data_to_save['menu_id'])) {
            $data_to_save['id'] = intval($data_to_save['menu_id']);
        }
        $table = $this->tables['menus'];
        $data_to_save['table'] = $table;
        $data_to_save['item_type'] = 'menu';
        if (!isset($data_to_save['id']) or $data_to_save['id'] == 0) {
            $data_to_save['is_active'] = 1;
        }

        if (isset($data_to_save['title']) and !isset($data_to_save['menu_name'])) {
            $data_to_save['menu_name'] = $data_to_save['title'];
        }

        $save = $this->app->database_manager->save($table, $data_to_save);
        $this->app->cache_manager->delete('menus');

        return $save;
    }

    public function menu_item_save($data_to_save)
    {
        if (isset($data_to_save['menu_id'])) {
            $data_to_save['parent_id'] = intval($data_to_save['menu_id']);
            unset($data_to_save['menu_id']);
        }

        if (!isset($data_to_save['id']) and isset($data_to_save['link_id'])) {
            $data_to_save['id'] = intval($data_to_save['link_id']);
        }

        if (isset($data_to_save['id'])) {
            $data_to_save['id'] = intval($data_to_save['id']);
        }

        if (!isset($data_to_save['id']) or intval($data_to_save['id']) == 0) {
            $data_to_save['position'] = 99999;
        }

        $url_from_content = false;
        if (isset($data_to_save['content_id']) and intval($data_to_save['content_id']) != 0) {
            $url_from_content = 1;
        }
        if (isset($data_to_save['categories_id']) and intval($data_to_save['categories_id']) != 0) {
            $url_from_content = 1;
        }
        if (isset($data_to_save['url_target'])) {
            $data_to_save['url_target'] = trim($data_to_save['url_target']);
        }
        if ($url_from_content != false) {
            if (!isset($data_to_save['title'])) {
                $data_to_save['title'] = '';
            }
        }
        if (isset($data_to_save['categories'])) {
            unset($data_to_save['categories']);
        }
        if ($url_from_content == true and isset($data_to_save['url'])) {
            $data_to_save['url'] = '';
        }
        if (isset($data_to_save['parent_id'])) {
            $data_to_save['parent_id'] = intval($data_to_save['parent_id']);
        }
        $data_to_save['item_type'] = 'menu_item';

        $saveMenu = null;
        if (isset($data_to_save['id']) && $data_to_save['id'] > 0) {
            $saveMenu = \MicroweberPackages\Menu\Menu::where('id', $data_to_save['id'])->first();
        }

        if ($saveMenu == null) {
            $saveMenu = new \MicroweberPackages\Menu\Menu();
        }
        foreach ($data_to_save as $key => $value){
            $saveMenu->$key = $value;
        }

        $saveMenu->save();
        $this->app->cache_manager->delete('content');
        $this->app->cache_manager->delete('content_fields');
        $this->app->cache_manager->delete('content_fields');
        $this->app->cache_manager->delete('repositories');
        $this->app->content_repository->clearCache();
        $this->app->category_repository->clearCache();
        $this->app->menu_repository->clearCache();
        event(new MenuWasUpdated($saveMenu, $data_to_save));

        return $saveMenu->id;
    }

    public function get_menu($params = false)
    {
        $params2 = array();
        if ($params == false) {
            $params = array();
        }
        if (is_string($params)) {
            $params = parse_str($params, $params2);
            $params = $params2;
        }
        $params['one'] = true;
        $params['limit'] = 1;
        $menu = $this->get_menus($params);

        return $menu;
    }

    public function get_menus($params = false)
    {
        return app()->menu_repository->getMenus($params);
    }

    public function menu_tree($menu_id, $maxdepth = false, $show_images = false)
    {
        static $passed_ids;
        static $passed_actives;
        static $main_menu_id;

        if (!is_array($passed_actives)) {
            $passed_actives = array();
        }
        if (!is_array($passed_ids)) {
            $passed_ids = array();
        }
        $menu_params = '';
        if (is_string($menu_id)) {
            $menu_params = parse_params($menu_id);
            if (is_array($menu_params)) {
                extract($menu_params);
            }
        } elseif (is_array($menu_id)) {
            $menu_params = $menu_id;
            extract($menu_id);
        }

        if (is_array($menu_id)) {
            $menu_params = $menu_id;
            extract($menu_id);
        }

        $cache_group = 'menus/global';
//        $function_cache_id = false;
//        $args = func_get_args();
//        foreach ($args as $k => $v) {
//            $function_cache_id = $function_cache_id . serialize($k) . serialize($v);
//        }
//
//        $function_cache_id = __FUNCTION__ . crc32($function_cache_id . site_url()).current_lang();
//        if (defined('PAGE_ID')) {
//            $function_cache_id = $function_cache_id . PAGE_ID;
//        }
//        if (defined('CATEGORY_ID')) {
//            $function_cache_id = $function_cache_id . CATEGORY_ID;
//        }

        if (!isset($depth) or $depth == false) {
            $depth = 0;
        }
        $orig_depth = $depth;
        $params_o = $menu_params;

//        if ($orig_depth == 0) {
//            $cache_content = $this->app->cache_manager->get($function_cache_id, $cache_group);
//            if (!isset($no_cache) and ($cache_content) != false) {
//                //  return $cache_content;
//            }
//        }\



        $data_to_return = [];

        $params = array();
        $params['item_parent'] = $menu_id;
        $menu_id = intval($menu_id);
        $params_order = array();
        $params_order['position'] = 'ASC';

        $menus = $this->tables['menus'];

      //  $menu_params = array();
    //    $menu_params['parent_id'] = $menu_id;
       // $menu_params['table'] = $menus;
       // $menu_params['order_by'] = 'position ASC';

        $q = app()->menu_repository->getMenusByParentId($menu_id);

        //   dd($menu_params,$q);
        $has_items = false;

        $active_class = '';
        $a_class = '';
        $auto_populate = false;

        if (isset($params_o['auto_populate']) != false) {
            $auto_populate = $params_o['auto_populate'];
        }

        /*     if($auto_populate != false){
                 $auto_populate = trim($auto_populate);
                 if($auto_populate == 1){
                     if (isset($params_o['parent_id']) and isset($params_o['auto_populate_item']) and !empty($params_o['auto_populate_item'])) {
                         $menu_item = $params_o['auto_populate_item'];
                         if(isset($menu_item['content_id']) and intval($menu_item['content_id']) != 0){
                             $pt = $params_o;
                             $pt['parent'] = intval($menu_item['content_id']);
                             $pt['include_all_content'] = intval($menu_item['content_id']);
                          //   dd($pt);
                             return $this->app->content_manager->pages_tree($pt);

                         }

     //                    if(!is_array($q)){
     //                        $q = array();
     //                    }
     //                    if(isset($menu_item['content_id']) and intval($menu_item['content_id']) != 0){
     //                        $more_menu_items = array();
     //                        $sub_menu_items_params = array();
     //                        $sub_menu_items_params['parent'] = $menu_item['content_id'];
     //                        $sub_menu_items_params['no_limit'] = true;
     //                        $content_items = $this->app->content_manager->get($sub_menu_items_params);
     //                        if(!empty($content_items)){
     //                            foreach($content_items as $content_item){
     //                                $a = array();
     //                                $a['title'] = $content_item['title'];
     //                             //   $a['item_type'] = 'menu_item';
     //                                $a['content_id'] = $content_item['id'];
     //                                $a['id'] = $params_o['parent_id'].$content_item['id'];
     //                               $a['cccid'] = $menu_item['id'];
     //                              //   $a['parent_id'] =$content_item['id'];
     //                                 $a['parent_id'] =$params_o['parent_id'];
     //                                 $a['parent_id'] = $params_o['parent_id'].$content_item['id'];
     //                                // $a['parent_id'] =$params_o['parent_id'];
     //                                $a['url'] = $this->app->content_manager->link($content_item['id']);
     //                                $more_menu_items[] =$a;
     //                             //   $q[] =$a;
     //                            }
     //                        }
     //                        if(!empty($more_menu_items)){
     //
     //                          $q = array_merge($q,$more_menu_items);
     //                         //  dd($q);
     //                        }
     //                       //dd($more_menu_items);
     //                        //dd($menu_item);
     //
     //                    }
                     }

                 }

             }

     */

        if (empty($q)) {
            return false;
        }

        if (!isset($ul_class)) {
            $ul_class = 'menu';
        }
        if (!isset($ul_id)) {
            $ul_id = '';
        }

        if (!isset($li_class)) {
            $li_class = 'menu_element';
        }


        if (isset($ul_class_deep)) {
            if ($depth > 0) {
                $ul_class = $ul_class_deep;
            }
        }

        if (isset($li_class_deep)) {
            if ($depth > 0) {
                $li_class = $li_class_deep;
            }
        }

        if (isset($ul_tag) == false) {
            $ul_tag = 'ul';
        }

        if (isset($li_tag) == false) {
            $li_tag = 'li';
        }

        if (isset($li_submenu_class) == false) {
            $li_submenu_class = ' have-submenu ';
        }

        if (isset($params_o['li_submenu_a_class']) != false) {
            $li_submenu_a_class = $params_o['li_submenu_a_class'];

        }


        if (isset($li_submenu_a_class) == false) {
            $li_submenu_a_class = ' have-submenu-link ';
        }


        if (isset($params['max_depth']) != false) {
            $params['maxdepth'] = $params['max_depth'];
        }

        if (isset($params['show_images']) != false) {
            $show_images = $params['show_images'];
        }

        $return_data = false;
        if (isset($params_o['return_data']) != false) {
            $return_data = $params_o['return_data'];
        }

        if (isset($params['maxdepth']) != false) {
            $maxdepth = $params['maxdepth'];
        }
        if (isset($params['depth']) != false) {
            $maxdepth = $params['depth'];
        }
        if (isset($params_o['a_class']) != false) {
            $a_class = $params_o['a_class'];
        }
        if (isset($params_o['depth']) != false) {
            $maxdepth = $params_o['depth'];
        }
        if (isset($params_o['maxdepth']) != false) {
            $maxdepth = $params_o['maxdepth'];
        }
        $li_submenu_a_link = false;
        if (isset($params_o['li_submenu_a_link']) != false) {
            $li_submenu_a_link = $params_o['li_submenu_a_link'];

        }
        $cur_content_id_data = [];
        if(content_id()){
            $cur_content_id_data = get_content_by_id(content_id());

        }




//        if (isset($params_o['li_submenu_a_class']) != false) {
//            $li_submenu_a_class = $params_o['li_submenu_a_class'];
//        }

        if (!isset($link) or $link == false) {
            $link = '<a itemprop="url" data-item-id="{id}" class="menu_element_link {active_class} {exteded_classes} {nest_level} {a_class} {a_submenu_class}"  {target_attribute} href="{url}">{title}</a>';
        }

        $id_attr_str = '';
        if (isset($ul_id)) {
            if ($depth == 0) {
                $id_attr_str = ' id="' . $ul_id . '" ';
            }
        }


        $to_print = '<' . $ul_tag . ' role="menu" ' . $id_attr_str . ' class="{ul_class}' . ' menu_' . $menu_id . ' {exteded_classes}" >';

        $cur_depth = 0;
        $res_count = 0;



        foreach ($q as $item) {

         /*   $override = $this->app->event_manager->trigger('menu.after.get_item', $item);
            if (is_array($override) && isset($override[0])) {
                $item = $override[0];
            }*/

            $title = '';
            $url = '';
            $is_active = true;
            $url = $item['url']  = trim(  $item['url'] );

            if (intval($item['content_id']) > 0 ) {
                 $cont = $this->app->content_manager->get_by_id($item['content_id']);


                if (is_array($cont) and isset($cont['is_deleted']) and $cont['is_deleted'] == 1) {

                     $is_active = false;
                     $cont = false;
                     // skip the deleted item
                     continue;
                } else if (is_array($cont) and isset($cont['is_active']) and $cont['is_active'] == 0) {
                    $is_active = false;
                    $cont = false;
                }
                elseif
                 (!$cont){
                    continue;
                }
                $full_item = $item;
              //
                if (is_array($cont) and !empty($cont)) {

                    $title = $cont['title'];
                       $url = $this->app->content_manager->link($cont['id']);

                    if ($cont['is_active'] != 1) {
                        $is_active = false;
                        $cont = false;
                    }
                }
            } elseif (intval($item['categories_id']) > 0) {
                $cont = $this->app->category_manager->get_by_id($item['categories_id']);
                if (is_array($cont)) {
                    $title = $cont['title'];
                    $url = $this->app->category_manager->link($cont['id']);
                } else {
                    $this->app->database_manager->delete_by_id($menus, $item['id']);
                    $title = false;
                    $item['title'] = false;
                }
            } else {
                $title = $item['title'];
                $url = $item['url'];
            }

            if (trim($item['url'] != '')) {
               $url = $item['url'];
            }


            if ($item['title'] == '') {
                $item['title'] = $title;
            } else {
                $title = $item['title'];
            }

            $active_class = '';
            $site_url = $this->app->url_manager->site();
            $cur_url = $this->app->url_manager->current(1);

            if (trim($item['url'] != '')) {

               $item['url'] = $this->app->format->replace_once('{SITE_URL}', $site_url, $item['url']);
            }


            if (trim($item['url'] != '') and intval($item['content_id']) == 0 and intval($item['categories_id']) == 0) {

                if ($item['url'] == $cur_url) {
                    $active_class = 'active';
                } else {
                    $active_class = '';
                }
            } elseif (trim($item['url'] == '') and $cur_content_id_data and isset($cur_content_id_data['parent']) and $cur_content_id_data['parent'] and $item['content_id'] == $cur_content_id_data['parent']) {
                $active_class = 'active';
                 // $active_class = 'active-parent';
            } elseif (trim($item['url'] == '') and content_id() and $item['content_id'] == content_id()) {
                $active_class = 'active';
             } elseif (trim($item['url'] == '') and page_id() and $item['content_id'] == page_id()) {
                $active_class = 'active';
            } elseif (trim($item['url'] == '') and post_id() and $item['content_id'] == post_id()) {
                $active_class = 'active';
            } elseif (trim($item['url'] == '') and category_id() and intval($item['categories_id']) != 0 and $item['categories_id'] == category_id()) {
                $active_class = 'active';
            } elseif (isset($cont['parent']) and page_id()  and $cont['parent'] == page_id() ) {
                // $active_class = 'active';
            } elseif (trim($item['url'] == '') and isset($cont['parent']) and defined('MAIN_PAGE_ID') and MAIN_PAGE_ID != 0 and $item['content_id'] == MAIN_PAGE_ID) {
                $active_class = 'active';
                $active_class = 'active-parent';
            } elseif (trim($item['url'] != '') and $item['url'] == $cur_url) {
                $active_class = 'active';
                $active_class = 'active-parent';
            } elseif (trim($item['url'] == '') and $item['content_id'] != 0 and page_id() ) {
                 $cont_link = $this->app->content_manager->link(page_id() );
                 if ($item['content_id'] == page_id()  and $cont_link == $item['url']) {
                    $active_class = 'active';
                } elseif ($cont_link == $item['url']) {
                    $active_class = 'active';
                }

            } else {
                $active_class = '';
            }

            if ($is_active == false) {
                $title = '';
            }
            if (isset($item['id'])) {
                if ($active_class == 'active') {
                    $passed_actives[] = $item['id'];
                } elseif ($active_class == '') {
                    if (isset($cont['content_id'])) {
                        if (in_array($item['content_id'], $passed_actives)) {
                            $active_class = 'active';
                        }
                    }
                }
            }
            if (isset($item['title']) and ($item['title']) != false) {
                $title = $item['title'] = strip_tags(html_entity_decode($item['title']));
            }

            if ($title != '') {
                $has_items = true;
                $has_childs = false;
                $has_childs_class = false;

/*
                $sub_menu_params = array();
                $sub_menu_params['parent_id'] = $item['id'];
                $sub_menu_params['table'] = $menus;
                $sub_menu_params['item_type'] = 'menu_item';
               // $sub_menu_params['count'] = true;
                $sub_menu_q = $this->app->database_manager->get($sub_menu_params);
                */

                $sub_menu_q = app()->menu_repository->getMenusByParentIdAndItemType($item['id'], 'menu_item');

                if ($sub_menu_q) {


                    foreach ($sub_menu_q as $ik=> $sub_menu_q_item_inner){
                        $check_if_content_exists = true;
                         if(isset($sub_menu_q_item_inner['content_id']) and $sub_menu_q_item_inner['content_id']){
                            $check_if_content_exists_by_id = $this->app->content_manager->get_by_id($sub_menu_q_item_inner['content_id']);
                             if(!$check_if_content_exists_by_id){
                                $check_if_content_exists = false;
                            } else{
                                 if(isset($check_if_content_exists_by_id['content_id']) and intval($check_if_content_exists_by_id['is_deleted']) == 1) {
                                     $check_if_content_exists = false;
                                 }
                             }
                        }
                        if(!$check_if_content_exists){
                           unset($sub_menu_q[$ik]) ;
                        }
                    }


                    //  $has_childs_class = 'have-submenu';
                    if($sub_menu_q){
                        $has_childs = true;
                     $has_childs_class = $li_submenu_class;
                    }
                }

                $item['url'] = $url;


                $ext_classes = '';
                $url_target = '';
                $url_target_attr_string = '';


                if (isset($item['url_target']) and trim($item['url_target']) != '') {
                    $url_target  =$item['url_target'];
                    $url_target_attr_string = ' target="'.$url_target.'" ';

                }


                if (isset($item['parent']) and intval($item['parent']) > 0) {
                    $ext_classes .= ' have-parent';
                }

                if (isset($item['subtype_value']) and intval($item['subtype_value']) != 0) {
                    $ext_classes .= ' have-category';
                }

                $class_for_li_submenu_a = '';
                if ($has_childs) {
                    $ext_classes .= ' ' . $has_childs_class;
                    if (isset($li_submenu_a_class) and $li_submenu_a_class) {
                        $class_for_li_submenu_a = $li_submenu_a_class;
                    }


                }

                $override = $this->app->event_manager->trigger('menu.after.get_item', $item);
                if (is_array($override) && isset($override[0])) {
                    $item = $override[0];
                }

                $to_print .= '<' . $li_tag . ' role="menuitem" class="{li_class}' . ' ' . $active_class . '  ' . $has_childs_class . ' {nest_level}" data-item-id="' . $item['id'] . '" >';



                if ($show_images == true && $depth == 0 && isset($item['default_image']) and $item['default_image']) {
                    $style = ($item['size'] == 'auto' ? '' : ' style="width:' . $item['size'] . 'px"');
                    $image_html = '<div class="mw-rollover_images">';
                    $image_html .= '<a href="' . $item['url'] . '"><img class="mw-rollover-default_image" src="' . $item['default_image'] . '" alt="' . $item['title'] . '"' . $style . '/></a>';
                    if (isset($item['rollover_image'])) {
                        $image_html .= '<div class="mw-rollover-overlay"><a href="' . $item['url'] . '"><img src="' . $item['rollover_image'] . '" alt=""' . $style . '/></a></div>';
                    }
                    $image_html .= '</div>';
                    if (isset($item['rollover_image'])) {
                        if (!strstr($li_class, 'mw-rollover')) $li_class .= " mw-rollover";
                    } else {
                        $li_class = str_replace(' mw-rollover', '', $li_class);
                    }
                    $to_print .= $image_html;
                }

                $ext_classes = trim($ext_classes);
                if (is_callable($link)) {
                    $menu_link = call_user_func_array($link, array($item));
                } else {
                    $menu_link = $link;
                }


                if ($has_childs and isset($li_submenu_a_link) and $li_submenu_a_link   /*and $depth == 0*/) {
                    $menu_link = $li_submenu_a_link;
                }

                foreach ($item as $key => $value) {
                    $menu_link = str_replace('{' . $key . '}', $value, $menu_link);
                }
                $menu_link = str_replace('{active_class}', $active_class, $menu_link);
                $menu_link = str_replace('{a_class}', $a_class, $menu_link);
                $menu_link = str_replace('{a_submenu_class}', $class_for_li_submenu_a, $menu_link);
                $menu_link = str_replace('{target_attribute}', $url_target_attr_string, $menu_link);
                $menu_link = str_replace('{li_submenu_a_class}', $class_for_li_submenu_a, $menu_link);
                $to_print .= $menu_link;

                $ext_classes = '';
                //  if ($orig_depth > 0) {

                if ($main_menu_id == false) {
                    $main_menu_id = $menu_id;
                    $ext_classes .= ' menu-root';
                } else {
                    if ($res_count == 0) {

                        // if($main_menu_id == false){
                        $ext_classes .= ' first-child';
                        $ext_classes .= ' child-' . $res_count . '';
                        // }
                    } elseif (!isset($q[$res_count + 1])) {
                        $ext_classes .= ' last-child';
                        $ext_classes .= ' child-' . $res_count . '';
                    } else {
                        $ext_classes .= ' child-' . $res_count . '';
                    }
                }
                $ext_classes .= ' menu-item-id-' . $item['id'] . '';

                if (isset($item['parent_id'])) {
                    $ext_classes .= ' menu-item-parent-' . $item['parent_id'] . '';
                }


                // }
                if (in_array($item['parent_id'], $passed_ids) == false) {
                    if ($maxdepth == false) {
                        if (isset($params) and is_array($params)) {

//                            if (isset($item['auto_populate']) and $item['auto_populate'] !=false) {
//                                $menu_item = $item;
////dd($menu_item);
//                                if(isset($menu_item['content_id']) and intval($menu_item['content_id']) != 0){
//                                    $pt = $params_o;
//                                    $pt['parent'] = intval($menu_item['content_id']);
//                                    $pt['include_all_content'] = intval($menu_item['content_id']);
//
//                                    $to_print .= $this->app->content_manager->pages_tree($pt);
//
//                                    //$to_print .= strval($test1);
//
//                                }
//
//
//
//                            } else {


                            $menu_params['menu_id'] = $item['id'];
                            $menu_params['link'] = $link;
                            if (isset($menu_params['item_parent'])) {
                                unset($menu_params['item_parent']);
                            }
                            if (isset($ul_class)) {
                                $menu_params['ul_class'] = $ul_class;
                            }
                            if (isset($ul_id)) {
                                $menu_params['ul_id'] = $ul_id;
                            }
                            if (isset($li_class)) {
                                $menu_params['li_class'] = $li_class;
                            }

                            if (isset($maxdepth)) {
                                $menu_params['maxdepth'] = $maxdepth;
                            }


                            if (isset($li_submenu_a_link) and $li_submenu_a_link) {
                                $menu_params['li_submenu_a_link'] = $li_submenu_a_link;

                            }
                            //  dd($menu_params);
                            if (isset($li_tag)) {
                                $menu_params['li_tag'] = $li_tag;
                            }
                            if (isset($ul_tag)) {
                                $menu_params['ul_tag'] = $ul_tag;
                            }
                            if (isset($ul_class_deep)) {
                                $menu_params['ul_class_deep'] = $ul_class_deep;
                            }
                            if (isset($li_class_empty)) {
                                $menu_params['li_class_empty'] = $li_class_empty;
                            }

                            if (isset($li_class_deep)) {
                                $menu_params['li_class_deep'] = $li_class_deep;
                            }
 if (isset($return_data) and $return_data) {
                                $menu_params['return_data'] = $return_data;
                            }

                            if (isset($li_submenu_a_class)) {

                                $menu_params['li_submenu_a_class'] = $li_submenu_a_class;


                            }

                            if (isset($depth)) {
                                $menu_params['depth'] = $depth + 1;
                            }



                            $menu_items_render = $this->menu_tree($menu_params);

                            //   }
                        } else {

                            $menu_items_render = $this->menu_tree($item['id']);
                        }
                    } else {

                        if (($maxdepth != false) and intval($maxdepth) > 1 and ($cur_depth <= $maxdepth)) {
                            if (isset($params) and is_array($params)) {
                                $menu_items_render = $this->menu_tree($menu_params);
                            } else {

                                $menu_items_render = $this->menu_tree($item['id']);
                            }
                        }
                    }
                }

                if(isset($menu_items_render) and $return_data){
                    $item['children'] = $menu_items_render;
                }

                if (isset($li_class_empty) and isset($menu_items_render) and trim($menu_items_render) == '') {
                    if ($depth > 0) {
                        $li_class = $li_class_empty;
                    }
                }

                $to_print = str_replace('{a_class}', $a_class, $to_print);
                $to_print = str_replace('{ul_id}', $ul_id, $to_print);
                $to_print = str_replace('{ul_class}', $ul_class, $to_print);
                $to_print = str_replace('{li_class}', $li_class, $to_print);
                $to_print = str_replace('{exteded_classes}', $ext_classes, $to_print);
                $to_print = str_replace('{nest_level}', 'depth-' . $depth, $to_print);


/*
                if(isset($item['auto_populate']) and $item['auto_populate'] and $item['auto_populate'] == 'all') {

                    if(isset($item['content_id']) and intval($item['content_id']) != 0){
                        $pt = array();
                        $pt['parent'] = intval($item['content_id']);
                        $pt['link'] = $link;
                        $pt['a_class'] = $a_class;
                        $pt['ul_class'] = $ul_class;
                        $pt['li_tag'] = $li_tag;
                        $pt['ul_tag'] = $ul_tag;
                        if($li_submenu_a_link){
                        $pt['li_submenu_a_link'] = $li_submenu_a_link;
                        }
                        $pt['return_data'] = 1;

                       // dd($this->app->content_manager->pages_tree($pt));

                     //    $pt['include_all_content'] = intval($item['content_id']);

                     //   $to_print = $to_print .  $this->app->content_manager->pages_tree($pt);
                    //    dd($this->app->content_manager->pages_tree($pt));

                    }
                }
*/

                if (isset($menu_items_render) and is_string($menu_items_render) and  strval($menu_items_render) != '') {
                    $to_print .= strval($menu_items_render);
                    ++$res_count;
                }

                $to_print .= '</' . $li_tag . '>';
            }

            if($return_data){
                $data_to_return[] = $item;
            }

            ++$cur_depth;
        }

        $to_print .= '</' . $ul_tag . '>';
        if ($orig_depth == 0) {
            // $this->app->cache_manager->save($to_print, $function_cache_id, $cache_group);
        }

        if ($has_items) {
            if($return_data){
                return $data_to_return;
            }

            return $to_print;
        } else {
            return false;
        }
    }

    public function menu_delete($id = false)
    {
        $params = parse_params($id);

        if (!isset($params['id'])) {
            mw_error('Error: id param is required.');
        }

        $id = $params['id'];

        $id = $this->app->database_manager->escape_string($id);
        $id = htmlspecialchars_decode($id);
        $table = $this->tables['menus'];

        $this->app->database_manager->delete_by_id($table, trim($id), $field_name = 'id');

        $this->app->cache_manager->delete('menus');

        return true;
    }

    public function menu_item_get($id)
    {
        $id = intval($id);
        $table = $this->tables['menus'];

        return db_get("one=1&limit=1&table=$table&id=$id");
    }

    public function menu_item_delete($id = false)
    {
        if (is_array($id)) {
            extract($id);
        }
        if (!isset($id) or $id == false or intval($id) == 0) {
            return false;
        }
        $table = $this->tables['menus'];
        $this->app->database_manager->delete_by_id($table, intval($id), $field_name = 'id');
        $this->app->cache_manager->delete('menus');

        return true;
    }

    public function menu_items_reorder($data)
    {
        $return_res = false;
        if (isset($data['ids_parents'])) {
            $value = $data['ids_parents'];
            if (is_array($value)) {
                foreach ($value as $value2 => $k) {
                    $k = intval($k);
                    $value2 = intval($value2);

                    \DB::table($this->tables['menus'])->whereId($value2)->where('id', '!=', $k)->whereItemType('menu_item')->update(['parent_id' => $k]);

                    $this->app->cache_manager->delete('menus/' . $k);
                    $this->app->cache_manager->delete('menus/' . $value2);
                }
            }
        }

        if (isset($data['ids'])) {
            $value = $data['ids'];
            if (is_array($value)) {
                $indx = array();
                $i = 0;
                foreach ($value as $value2) {
                    $indx[$i] = $value2;
                    $this->app->cache_manager->delete('menus/' . $value2);

                    ++$i;
                }
                $this->app->database_manager->update_position_field('menus', $indx);
                $return_res = $indx;
            }
        }

        clearcache();

        return $return_res;
    }

    public function is_in_menu($menu_id = false, $content_id = false)
    {
        if ($menu_id == false or $content_id == false) {
            return false;
        }

        $menu_id = intval($menu_id);
        $content_id = intval($content_id);
        $check = $this->get_menu_items("limit=1&count=1&parent_id={$menu_id}&content_id=$content_id");
        $check = intval($check);
        if ($check > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_menu_items($params = false)
    {
        $table = $this->tables['menus'];
        if (is_string($params)) {
            $params = parse_params($params);
        }
        $params['table'] = $table;
        $params['item_type'] = 'menu_item';

        return $this->app->database_manager->get($params);
    }
}
