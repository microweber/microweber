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

    public function delete($data) {
        $to_trash = false;
        $to_untrash = false;


        if (!is_array($data)){
            $del_data = array();
            $del_data['id'] = intval($data);
            $data = $del_data;
            $to_trash = false;
        }

        if (isset($data['forever']) or isset($data['delete_forever'])){
            $to_trash = false;
        }

        if (isset($data['undelete'])){
            $to_trash = true;
            $to_untrash = true;
        }

        $del_ids = array();
        if (isset($data['id'])){
            $c_id = intval($data['id']);
            $del_ids[] = $c_id;
            if ($to_trash==false){
                $this->app->database_manager->delete_by_id('content', $c_id);
            }
        }
        $this->app->event_manager->trigger('content.before.delete', $data);

        if (isset($data['ids']) and is_array($data['ids'])){
            foreach ($data['ids'] as $value) {
                $c_id = intval($value);
                $del_ids[] = $c_id;
                if ($to_trash==false){
                    $this->app->database_manager->delete_by_id('content', $c_id);
                }
            }

        }

        if (!empty($del_ids)){
            DB::transaction(function () use ($del_ids, $to_untrash, $to_trash) {
                foreach ($del_ids as $value) {
                    $c_id = intval($value);
                    if ($to_untrash==true){
                        DB::table($this->tables['content'])->whereId($c_id)->whereIsDeleted(1)->update(['is_deleted' => 1]);
                        DB::table($this->tables['content'])->whereParent($c_id)->whereIsDeleted(1)->update(['is_deleted' => 1]);

                        if (isset($this->tables['categories'])){
                            DB::table($this->tables['categories'])->whereRelId($c_id)->whereRelType('content')->whereIsDeleted(1)->update(['is_deleted' => 0]);
                        }

                    } else if ($to_trash==false){
                        DB::table($this->tables['content'])->whereParent($c_id)->update(['parent' => 0]);

                        $this->app->database_manager->delete_by_id('menus', $c_id, 'content_id');

                        if (isset($this->tables['media'])){
                            $delete_in_table = $this->tables['media'];
                            $delete_in_table = $this->app->database_manager->real_table_name($delete_in_table);
                            $q = "DELETE FROM $delete_in_table WHERE rel_id=$c_id  AND  rel_type='content'  ";
                            $this->app->database_manager->q($q);
                        }

                        if (isset($this->tables['categories'])){
                            $delete_in_table = $this->tables['categories'];
                            $delete_in_table = $this->app->database_manager->real_table_name($delete_in_table);
                            $q = "DELETE FROM $delete_in_table WHERE rel_id=$c_id  AND  rel_type='content'  ";
                            $this->app->database_manager->q($q);
                        }


                        if (isset($this->tables['categories_items'])){
                            $delete_in_table = $this->tables['categories_items'];
                            $delete_in_table = $this->app->database_manager->real_table_name($delete_in_table);
                            $q = "DELETE FROM $delete_in_table WHERE rel_id=$c_id  AND  rel_type='content'  ";
                            $this->app->database_manager->q($q);
                        }
                        if (isset($this->tables['custom_fields'])){
                            $delete_in_table = $this->tables['custom_fields'];
                            $delete_in_table = $this->app->database_manager->real_table_name($delete_in_table);
                            $q = "DELETE FROM $delete_in_table WHERE rel_id=$c_id  AND  rel_type='content'  ";
                            $this->app->database_manager->q($q);
                        }

                        if (isset($this->tables['content_data'])){
                            $delete_in_table = $this->tables['content_data'];
                            $delete_in_table = $this->app->database_manager->real_table_name($delete_in_table);
                            $q = "DELETE FROM $delete_in_table WHERE content_id=$c_id    ";
                            $this->app->database_manager->q($q);
                        }


                    } else {
                        DB::table($this->tables['content'])->whereId($c_id)->update(['is_deleted' => 1]);
                        DB::table($this->tables['content'])->whereParent($c_id)->update(['is_deleted' => 1]);

                        if (isset($this->tables['categories'])){
                            DB::table($this->tables['categories'])->whereRelId($c_id)->whereRelType('content')->update(['is_deleted' => 1]);
                        }
                    }
                    $this->app->cache_manager->delete('content/' . $c_id);
                }
            });
        }
        $this->app->cache_manager->delete('menus');
        $this->app->cache_manager->delete('content');
        $this->app->cache_manager->delete('categories/global');
        $this->app->cache_manager->delete('content/global');

        return ($del_ids);
    }

    public function reset_edit_field($data) {

        if (isset($data['id'])){
            $cont = get_content_by_id($data['id']);
            if (isset($cont['id']) and $cont['id']!=0){
                $id = intval($cont['id']);
                $cont['content'] = false;
                $cont['content_body'] = false;
                $save = $this->save($cont);

                $table_fields = $this->app->database_manager->real_table_name($this->tables['content_fields']);
                $del = "DELETE FROM {$table_fields} WHERE rel_type='content' AND rel_id='{$id}' ";
                $this->app->database_manager->query($del);
                $this->app->cache_manager->delete('content');
                $this->app->cache_manager->delete('content_fields');

                return $save;
            }

        }

    }

    public function bulk_assign($data) {

        if (is_string($data)){
            $data = parse_params($data);
        }

        if (isset($data['content_ids'])){
            $content_ids = $data['content_ids'];

            if (is_array($content_ids)){
                foreach ($content_ids as $content_id) {
                    $to_save = array();
                    $to_save['id'] = $content_id;
                    $to_save['skip_timestamps'] = true;
                    if (isset($data['parent_id'])){
                        $to_save['parent'] = $data['parent_id'];
                    }
                    if (isset($data['categories'])){
                        $to_save['categories'] = $data['categories'];
                    }
                    $this->save_content($to_save);
                }
            }
        }

        return array('success' => 'Content is moved');


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

    public function copy($data) {
        $new_cont_id = false;

        if (defined('MW_API_CALL')){
            $to_trash = true;
            $adm = $this->app->user_manager->is_admin();
            if ($adm==false){
                return array('error' => 'You must be admin to copy content!');
            }
        }
        if (isset($data['id'])){
            $this->app->event_manager->trigger('content.before.copy', $data);
            $cont = get_content_by_id($data['id']);
            if ($cont!=false and isset($cont['id'])){
                $new_cont = $cont;
                if (isset($new_cont['title'])){
                    $new_cont['title'] = $new_cont['title'] . ' copy';
                }

                $new_cont['id'] = 0;
                $content_cats = array();

                $cats = content_categories($cont['id']);
                if (!empty($cats)){
                    foreach ($cats as $cat) {
                        if (isset($cat['id'])){
                            $content_cats[] = $cat['id'];
                        }
                    }
                }
                if (!empty($content_cats)){
                    $new_cont['categories'] = $content_cats;
                }
                $new_cont_id = $this->save($new_cont);


                $cust_fields = get_custom_fields('content', $data['id'], true);
                if (!empty($cust_fields)){
                    foreach ($cust_fields as $cust_field) {
                        $new = $cust_field;
                        $new['id'] = 0;
                        $new['rel_id'] = $new_cont_id;
                        $new_item = save_custom_field($new);
                    }
                }
                $images = get_pictures($data['id']);
                if (!empty($images)){
                    foreach ($images as $image) {
                        $new = $image;
                        $new['id'] = 0;
                        $new['rel_id'] = $new_cont_id;
                        $new['rel_type'] = 'content';
                        $new_item = save_media($new);

                    }
                }


            }
        }

        return $new_cont_id;

    }

    public function save_from_live_edit($post_data) {


        $is_module = false;
        $is_admin = $this->app->user_manager->is_admin();
        if ($post_data){
            if (isset($post_data['json_obj'])){
                $obj = json_decode($post_data['json_obj'], true);
                $post_data = $obj;
            }
            if (isset($post_data['mw_preview_only'])){
                $is_no_save = true;
                unset($post_data['mw_preview_only']);
            }
            $is_no_save = false;
            $is_draft = false;
            if (isset($post_data['is_draft'])){
                unset($post_data['is_draft']);
                $is_draft = 1;
            }
            $the_field_data_all = $post_data;
        } else {

            return array('error' => 'no POST?');

        }

        $ustr2 = $this->app->url_manager->string(1, 1);

        if (isset($ustr2) and trim($ustr2)=='favicon.ico'){
            return false;
        }
        $ref_page = $ref_page_url = false;
        if (isset($_SERVER['HTTP_REFERER'])){
            $ref_page = $ref_page_url = $_SERVER['HTTP_REFERER'];

        }

        if (isset($post_data['id']) and intval($post_data['id']) > 0){
            $page_id = intval($post_data['id']);
        } elseif ($ref_page!='') {
            //removing hash from url
            if (strpos($ref_page_url, '#')){
                $ref_page = $ref_page_url = substr($ref_page_url, 0, strpos($ref_page_url, '#'));
            }
            $ref_page2 = $ref_page = $this->get_by_url($ref_page_url);
            if ($ref_page2==false){

                $ustr = $this->app->url_manager->string(1);

                if ($this->app->modules->is_installed($ustr)){
                    $ref_page = false;
                }

            } else {
                $ref_page = $ref_page2;
            }
            if (isset($ustr) and trim($ustr)=='favicon.ico'){
                return false;
            } elseif ($ustr2=='' or $ustr2=='/') {
                $ref_page = $this->homepage();
                if ($ref_page_url){
                    $page_url_ref = $this->app->url_manager->param('content_id', $ref_page_url);
                    if ($page_url_ref!==false){
                        if ($page_url_ref==0){
                            return false;
                        }
                    }
                }


            }


            if ($ref_page==false){
                $guess_page_data = new \Microweber\Controllers\DefaultController();
                // $guess_page_data =  new  $this->app->controller($this->app);
                $ref_page_url = strtok($ref_page_url, '?');

                $guess_page_data->page_url = $ref_page_url;
                $guess_page_data->return_data = true;
                $guess_page_data->create_new_page = true;
                $pd = $guess_page_data->index();
                $ustr = $this->app->url_manager->string(1);
                $is_module = false;
                $pd['url'] = $ustr;

                if (isset($pd['active_site_template']) and $pd['active_site_template']==template_name()){
                    $pd['active_site_template'] = '';
                }


                if ($this->app->modules->is_installed($ustr)){
                    $is_module = true;
                    $save_page['layout_file'] = 'clean.php';
                    $save_page['subtype'] = 'module';
                    $hp_id = $this->app->content_manager->homepage();
                    if (isset($hp_id['id'])){
                        $page_id = $hp_id['id'];
                    } else {
                        $page_id = 1;
                    }
                    $is_module = 1;
                    $save_page = false;
                }

                if ($is_admin==true and is_array($pd) and $is_module==false){
                    $save_page = $pd;
                    if (!isset($_GET['mw_quick_edit'])){
                        if (isset($ref_page_url) and $ref_page_url!=false){
                            $save_page['url'] = $ref_page_url;
                        } else {
                            $save_page['url'] = $this->app->url_manager->string(1);

                        }
                        $title = str_replace('%20', ' ', ($this->app->url_manager->string(1)));

                        if ($title=='editor_tools/wysiwyg' or $title=='api/module' or $title=='admin/view:content'){
                            return false;
                        }

                        $save_page['title'] = $title;
                        if ($save_page['url']=='' or $save_page['url']=='/' or $save_page['url']==$this->app->url_manager->site()){
                            $save_page['url'] = 'home';
                            $home_exists = $this->homepage();
                            if ($home_exists==false){
                                $save_page['is_home'] = 1;
                            }
                        }
                    }
                    if ($save_page['title']==''){
                        $save_page['title'] = 'Home';
                    }
                    if (!isset($save_page['is_active'])){
                        $save_page['is_active'] = 1;
                    }
                    if (isset($save_page['content_type']) and $save_page['content_type']=='page'){
                        if (!isset($save_page['subtype'])){
                            $save_page['subtype'] = 'static';
                            if (!isset($save_page['layout_file']) or $save_page['layout_file']==false){
                                $save_page['layout_file'] = 'inherit';
                            }
                        }
                    }
                    if ($save_page!=false){

                        $page_id = $this->app->content_manager->save_content_admin($save_page);
                    }
                }

            } else {
                $page_id = $ref_page['id'];
                $ref_page['custom_fields'] = $this->app->content_manager->custom_fields($page_id, false);
            }
        }

        $author_id = user_id();
        if ($is_admin==false and $page_id!=0 and $author_id!=0){
            $page_data_to_check_author = $this->get_by_id($page_id);
            if (!isset($page_data_to_check_author['created_by']) or ($page_data_to_check_author['created_by']!=$author_id)){
                return array('error' => 'You dont have permission to edit this content');
            }


        } else if ($is_admin==false){
            return array('error' => 'Not logged in as admin to use ' . __FUNCTION__);

        }


        $save_as_draft = false;
        if (isset($post_data['save_draft'])){
            $save_as_draft = true;
            unset($post_data['save_draft']);
        }


        $json_print = array();
        foreach ($the_field_data_all as $the_field_data) {
            $save_global = false;
            $save_layout = false;
            if (isset($page_id) and $page_id!=0 and !empty($the_field_data)){
                $save_global = false;

                $content_id = $page_id;


                $url = $this->app->url_manager->string(true);
                $some_mods = array();
                if (isset($the_field_data) and is_array($the_field_data) and isset($the_field_data['attributes'])){
                    if (($the_field_data['html'])!=''){
                        $field = false;
                        if (isset($the_field_data['attributes']['field'])){
                            $field = trim($the_field_data['attributes']['field']);
                        }

                        if (isset($the_field_data['attributes']['data-field'])){
                            $field = $the_field_data['attributes']['field'] = trim($the_field_data['attributes']['data-field']);
                        }


                        if (($field!=false)){
                            $page_element_id = $field;
                        }
                        if (!isset($the_field_data['attributes']['rel'])){
                            $the_field_data['attributes']['rel_type'] = 'content';
                        } else {
                            $the_field_data['attributes']['rel_type'] = $the_field_data['attributes']['rel'];
                        }

                        if (isset($the_field_data['attributes']['rel-id'])){
                            $content_id = $the_field_data['attributes']['rel-id'];
                        } elseif (isset($the_field_data['attributes']['rel_id'])) {
                            $content_id = $the_field_data['attributes']['rel_id'];
                        } elseif (isset($the_field_data['attributes']['data-rel-id'])) {
                            $content_id = $the_field_data['attributes']['data-rel-id'];
                        } elseif (isset($the_field_data['attributes']['data-rel_id'])) {
                            $content_id = $the_field_data['attributes']['data-rel_id'];
                        }


                        $save_global = false;
                        if (isset($the_field_data['attributes']['rel_type']) and (trim($the_field_data['attributes']['rel_type'])=='global' or trim($the_field_data['attributes']['rel_type']))=='module'){
                            $save_global = true;
                        } else {
                            $save_global = false;
                        }
                        if (isset($the_field_data['attributes']['rel_type']) and trim($the_field_data['attributes']['rel_type'])=='layout'){
                            $save_global = false;
                            $save_layout = true;
                        } else {
                            $save_layout = false;
                        }
                        if (isset($the_field_data['attributes']['rel'])){
                            $the_field_data['attributes']['rel_type'] = $the_field_data['attributes']['rel'];
                        }

                        if (!isset($the_field_data['attributes']['data-id'])){
                            $the_field_data['attributes']['data-id'] = $content_id;
                        }

                        $save_global = 1;

                        if (isset($the_field_data['attributes']['rel_type']) and isset($the_field_data['attributes']['data-id'])){


                            $rel_ch = trim($the_field_data['attributes']['rel_type']);
                            switch ($rel_ch) {
                                case 'content':
                                    $save_global = false;
                                    $save_layout = false;
                                    $content_id_for_con_field = $content_id = $the_field_data['attributes']['data-id'];
                                    break;
                                case 'page':
                                case 'post':
                                    $save_global = false;
                                    $save_layout = false;
                                    $content_id_for_con_field = $content_id = $page_id;
                                    break;
                                default:
                                    break;
                            }
                        }

                        $inh = false;

                        if (isset($the_field_data['attributes']['rel_type']) and ($the_field_data['attributes']['rel_type'])=='inherit'){
                            $save_global = false;
                            $save_layout = false;
                            $content_id = $page_id;
                            $inh = $this->get_inherited_parent($page_id);
                            if ($inh!=false){
                                $content_id_for_con_field = $content_id = $inh;
                            }
                        } else if (isset($the_field_data['attributes']['rel_type']) and ($the_field_data['attributes']['rel_type'])=='page'){
                            $save_global = false;
                            $save_layout = false;
                            $content_id = $page_id;
                            $check_if_page = $this->get_by_id($content_id);
                            if (is_array($check_if_page)
                                and isset($check_if_page['content_type'])
                                and isset($check_if_page['parent'])
                                and $check_if_page['content_type']!=''
                                and intval($check_if_page['parent'])!=0
                                and $check_if_page['content_type']!='page'
                            ){
                                $inh = $check_if_page['parent'];
                                if ($inh!=false){
                                    $content_id_for_con_field = $content_id = $inh;

                                }

                            }


                        }

                        $save_layout = false;
                        if (isset($post_data['id'])){
                            $content_id_for_con_field = $post_data['id'];
                        } elseif ($inh==false and !isset($content_id_for_con_field)) {
                            if (is_array($ref_page) and isset($ref_page['parent']) and isset($ref_page['content_type']) and $ref_page['content_type']=='post'){
                                $content_id_for_con_field = intval($ref_page['parent']);
                            } else {
                                $content_id_for_con_field = intval($ref_page['id']);
                            }
                        }
                        $html_to_save = $the_field_data['html'];
                        $html_to_save = $content = $this->app->parser->make_tags($html_to_save);

                        if ($save_global==false and $save_layout==false){
                            if ($content_id){
                                $for_histroy = $ref_page;
                                $old = false;
                                $field123 = str_ireplace('custom_field_', '', $field);
                                if (stristr($field, 'custom_field_')){
                                    $old = $for_histroy['custom_fields'][ $field123 ];
                                } else {

                                    if (isset($for_histroy['custom_fields'][ $field123 ])){
                                        $old = $for_histroy['custom_fields'][ $field123 ];
                                    } elseif (isset($for_histroy[ $field ])) {
                                        $old = $for_histroy[ $field ];
                                    }
                                }
                                $history_to_save = array();
                                $history_to_save['table'] = 'content';
                                $history_to_save['id'] = $content_id;
                                $history_to_save['value'] = $old;
                                $history_to_save['field'] = $field;

                                $cont_field = array();
                                $cont_field['rel_type'] = 'content';
                                $cont_field['rel_id'] = $content_id_for_con_field;
                                $cont_field['value'] = $html_to_save;
                                $cont_field['field'] = $field;


                                if ($is_draft!=false){
                                    $cont_id = $content_id_for_con_field;
                                    $cont_field['is_draft'] = 1;
                                    $cont_field['rel_type'] = $rel_ch;
                                    $cont_field['url'] = $url;
                                    $to_save_draft = true;
                                    if (isset($cont_field['value'])){
                                        $draftmd5 = md5($cont_field['value']);
                                        $draftmd5_last = $this->app->user_manager->session_get('content_draft_save_md5');
                                        if ($draftmd5_last==$draftmd5){
                                            $to_save_draft = false;
                                        } else {
                                            $this->app->user_manager->session_set('content_draft_save_md5', $draftmd5);
                                        }
                                    }
                                    if ($to_save_draft){
                                        $cont_field1 = $this->app->content_manager->save_content_field($cont_field);
                                    }
                                } else {
                                    if ($field!='content'){
                                        $cont_field1 = $this->app->content_manager->save_content_field($cont_field);
                                    }
                                }
                                $this->app->event_manager->trigger('mw.content.save_edit', $cont_field);

                                $to_save = array();
                                $to_save['id'] = $content_id;

                                $is_native_fld = $this->app->database_manager->get_fields('content');
                                if (in_array($field, $is_native_fld)){
                                    $to_save[ $field ] = ($html_to_save);
                                }


                                if ($is_no_save!=true and $is_draft==false){
                                    $json_print[] = $to_save;
                                    $saved = $this->app->content_manager->save_content_admin($to_save);
                                }
                            } else if (isset($category_id)){
                                print(__FILE__ . __LINE__ . ' category is not implemented ... not ready yet');
                            }
                        } else {
                            $cont_field = array();
                            $cont_field['rel_type'] = $the_field_data['attributes']['rel_type'];
                            $cont_field['rel_id'] = 0;
                            if (isset($the_field_data['attributes']['rel-id'])){
                                $cont_field['rel_id'] = $the_field_data['attributes']['rel-id'];
                            } elseif (isset($the_field_data['attributes']['rel_id'])) {
                                $cont_field['rel_id'] = $the_field_data['attributes']['rel_id'];
                            } elseif (isset($the_field_data['attributes']['data-rel-id'])) {
                                $cont_field['rel_id'] = $the_field_data['attributes']['data-rel-id'];
                            } elseif ($cont_field['rel_type']!='global' and isset($the_field_data['attributes']['content-id'])) {
                                $cont_field['rel_id'] = $the_field_data['attributes']['content-id'];
                            } elseif ($cont_field['rel_type']!='global' and isset($the_field_data['attributes']['data-id'])) {
                                $cont_field['rel_id'] = $the_field_data['attributes']['data-id'];
                            } elseif (isset($the_field_data['attributes']['data-rel_id'])) {
                                $cont_field['rel_id'] = $the_field_data['attributes']['data-rel_id'];
                            }
                            $cont_field['value'] = $this->app->parser->make_tags($html_to_save);
                            if ((!isset($the_field_data['attributes']['field']) or $the_field_data['attributes']['field']=='') and isset($the_field_data['attributes']['data-field'])){
                                $the_field_data['attributes']['field'] = $the_field_data['attributes']['data-field'];
                            }
                            $cont_field['field'] = $the_field_data['attributes']['field'];
                            if ($is_draft!=false){
                                $cont_field['is_draft'] = 1;
                                $cont_field['url'] = $this->app->url_manager->string(true);
                                $cont_field_new = $this->app->content_manager->save_content_field($cont_field);
                            } else {
                                $cont_field_new = $this->app->content_manager->save_content_field($cont_field);
                            }

                            if ($save_global==true and $save_layout==false){
                                $json_print[] = $cont_field;
                                $history_to_save = array();
                                $history_to_save['table'] = 'global';
                                $history_to_save['value'] = $cont_field['value'];
                                $history_to_save['field'] = $field;
                                $history_to_save['page_element_id'] = $page_element_id;
                            }

                        }
                    }
                }
            }
        }
        if (isset($opts_saved)){
            $this->app->cache_manager->delete('options');
        }

        return $json_print;
    }

    public function get_edit_field_draft($data) {

        $page = false;
        if (isset($_SERVER["HTTP_REFERER"])){
            $url = $_SERVER["HTTP_REFERER"];
            $url = explode('?', $url);
            $url = $url[0];

            if (trim($url)=='' or trim($url)==$this->app->url_manager->site()){
                //$page = $this->get_by_url($url);
                $page = $this->homepage();
                // var_dump($page);
            } else {

                $page = $this->get_by_url($url);
            }
        } else {
            $url = $this->app->url_manager->string();
        }

        $this->app->content_manager->define_constants($page);


        $table_drafts = $this->tables['content_fields_drafts'];


        $data = parse_params($data);

        if (isset($data['id']) and $data['id']=='latest_content_edit'){

            if (isset($page['id'])){
                $page_data = $this->get_by_id($page['id']);

                $results = array();
                if (isset($page_data['title'])){
                    $arr = array('rel_type' => 'content',
                                 'field'    => 'title',
                                 'value'    => $page_data['title']);
                    $results[] = $arr;
                    if (isset($page_data['content_type'])){
                        $arr = array('rel_type' => $page_data['content_type'],
                                     'field'    => 'title',
                                     'value'    => $page_data['title']);
                        $results[] = $arr;
                    }
                    if (isset($page_data['subtype'])){
                        $arr = array('rel_type' => $page_data['subtype'],
                                     'field'    => 'title',
                                     'value'    => $page_data['title']);
                        $results[] = $arr;
                    }
                }
                if (isset($page_data['content']) and $page_data['content']!=''){
                    $arr = array('rel_type' => 'content',
                                 'field'    => 'content',
                                 'value'    => $page_data['content']);
                    $results[] = $arr;
                    if (isset($page_data['content_type'])){
                        $arr = array('rel_type' => $page_data['content_type'],
                                     'field'    => 'content',
                                     'value'    => $page_data['content']);
                        $results[] = $arr;
                    }
                    if (isset($page_data['subtype'])){
                        $arr = array('rel_type' => $page_data['subtype'],
                                     'field'    => 'content',
                                     'value'    => $page_data['content']);
                        $results[] = $arr;
                    }
                }
                //$results[]

            }


        } else {
            $data['is_draft'] = 1;
            $data['full'] = 1;
            $data['all'] = 1;
            $results = $this->get_edit_field($data);
        }


        $ret = array();


        if ($results==false){
            return;
        }

        $i = 0;
        foreach ($results as $item) {


            if (isset($item['value'])){
                $field_content = htmlspecialchars_decode($item['value']);
                $field_content = $this->_decode_entities($field_content);
                $item['value'] = $this->app->parser->process($field_content, $options = false);

            }

            $ret[ $i ] = $item;
            $i ++;

        }


        return $ret;


    }


    public function  save_content_field($data, $delete_the_cache = true) {

        $adm = $this->app->user_manager->is_admin();
        $table = $this->tables['content_fields'];
        $table_drafts = $this->tables['content_fields_drafts'];


        if ($adm==false){
            return false;
        }

        if (!is_array($data)){
            $data = array();
        }

        if (isset($data['is_draft'])){
            $table = $table_drafts;


        }

        $data = $this->app->format->strip_unsafe($data);

        if (isset($data['is_draft']) and isset($data['url'])){

            $draft_url = $this->app->database_manager->escape_string($data['url']);
            $last_saved_date = date("Y-m-d H:i:s", strtotime("-1 week"));
            $history_files_params = array();
            $history_files_params['order_by'] = 'id desc';
            $history_files_params['fields'] = 'id';
            $history_files_params['field'] = $data['field'];
            $history_files_params['rel_type'] = $data['rel_type'];
            $history_files_params['rel_id'] = $data['rel_id'];
            $history_files_params['is_draft'] = 1;
            $history_files_params['limit'] = 200;
            $history_files_params['no_cache'] = true;

            $history_files_params['url'] = $draft_url;
            $history_files_params['current_page'] = 2;
            $history_files_params['created_at'] = '[lt]' . $last_saved_date;
            $history_files = $this->get_edit_field($history_files_params);

            if (is_array($history_files)){
                $history_files_ids = $this->app->format->array_values($history_files);
            }

            if (isset($history_files_ids) and is_array($history_files_ids) and !empty($history_files_ids)){
                ContentFields::whereIn('id', $history_files_ids)->delete();
            }
        }
        if (!isset($data['rel_type']) or !isset($data['rel_id'])){
            mw_error('Error: ' . __FUNCTION__ . ' rel and rel_id is required');
        }

        if (isset($data['field']) and !isset($data['is_draft'])){
            $fld = $this->app->database_manager->escape_string($data['field']);
            $fld_rel = $this->app->database_manager->escape_string($data['rel_type']);
            $del_params = array();
            $del_params['rel_type'] = $fld_rel;
            $del_params['field'] = $fld;
            $del_params['table'] = $table;

            if (isset($data['rel_id'])){
                $i = ($data['rel_id']);
                $del_params['rel_id'] = $i;
            } else {
                $del_params['rel_id'] = 0;
            }
            $del = $this->app->database_manager->get($del_params);
            if (!empty($del)){
                foreach ($del as $item) {
                    $this->app->database_manager->delete_by_id($table, $item['id']);
                }
            }
            $cache_group = guess_cache_group('content_fields/' . $data['rel_type'] . '/' . $data['rel_id']);
            $this->app->cache_manager->delete($cache_group);
        }
        if (isset($fld)){
            $this->app->cache_manager->delete('content_fields/' . $fld);
            $this->app->cache_manager->delete('content_fields/global/' . $fld);
        }
        $this->app->cache_manager->delete('content_fields/global');
        if (isset($data['rel_type']) and isset($data['rel_id'])){
            $cache_group = guess_cache_group('content_fields/' . $data['rel_type'] . '/' . $data['rel_id']);
            $this->app->cache_manager->delete($cache_group);
            $this->app->cache_manager->delete('content/' . $data['rel_id']);

        }
        if (isset($data['rel_type'])){
            $this->app->cache_manager->delete('content_fields/' . $data['rel_type']);
        }
        if (isset($data['rel_type']) and isset($data['rel_id'])){
            $this->app->cache_manager->delete('content_fields/' . $data['rel_type'] . '/' . $data['rel_id']);
            $this->app->cache_manager->delete('content_fields/global/' . $data['rel_type'] . '/' . $data['rel_id']);
        }
        if (isset($data['field'])){
            $this->app->cache_manager->delete('content_fields/' . $data['field']);
        }
        $this->app->cache_manager->delete('content_fields/global');
        $data['table'] = $table;
        $data['allow_html'] = 1;


        $save = $this->app->database_manager->save($data);


        $this->app->cache_manager->delete('content_fields');

        return $save;
    }

    private function _decode_entities($text) {
        $text = html_entity_decode($text, ENT_QUOTES, "ISO-8859-1"); #NOTE: UTF-8 does not work!
        $text = preg_replace('/&#(\d+);/m', "chr(\\1)", $text); #decimal notation
        $text = preg_replace('/&#x([a-f0-9]+);/mi', "chr(0x\\1)", $text); #hex notation
        return $text;
    }

    public function download_remote_images_from_text($text) {
        $site_url = $this->app->url_manager->site();
        $images = $this->app->parser->query($text, 'img');
        $to_download = array();
        $to_replace = array();
        $possible_sources = array();


        if (!empty($images)){
            foreach ($images as $image) {
                $srcs = array();
                preg_match('/src="([^"]*)"/i', $image, $srcs);
                if (!empty($srcs) and isset($srcs[1]) and $srcs[1]!=false){
                    $possible_sources[] = $srcs[1];
                }
            }
        }

        if (!empty($possible_sources)){
            foreach ($possible_sources as $image_src) {
                if (!stristr($image_src, $site_url)){

                    $to_replace[] = $image_src;
                    $image_src = strtok($image_src, '?');
                    $ext = get_file_extension($image_src);
                    switch (strtolower($ext)) {
                        case 'jpg':
                        case 'jpeg':
                        case 'png':
                        case 'gif':
                        case 'svg':
                            $to_download[] = $image_src;
                            break;
                        default:
                            break;
                    }

                }
            }
        }

        if (!empty($to_download)){
            $to_download = array_unique($to_download);

            if (!empty($to_download)){
                foreach ($to_download as $src) {
                    $dl_dir = media_base_path() . 'downloaded' . DS;
                    if (!is_dir($dl_dir)){
                        mkdir_recursive($dl_dir);
                    }
                    $dl_file = $dl_dir . md5($src) . basename($src);
                    if (!is_file($dl_file)){
                        $is_dl = $this->app->url_manager->download($src, false, $dl_file);
                    }
                    if (is_file($dl_file)){
                        $url_local = dir2url($dl_file);
                        $text = str_ireplace($src, $url_local, $text);
                    }
                }
            }
        }

        return $text;
    }

}