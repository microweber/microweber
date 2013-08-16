<?php
namespace Microweber;


class ContentUtils extends \Microweber\Content
{


    public function save_content($data, $delete_the_cache = true)
    {


        $adm = $this->app->user->is_admin();
        $table = MW_DB_TABLE_CONTENT;
        $checks = mw_var('FORCE_SAVE_CONTENT');

        if ($checks != $table) {
            if ($adm == false) {

                $stop = true;
                if (!isset($data['captcha'])) {
                    return array('error' => 'Please enter a captcha answer!');
                } else {
                    $cap = $this->app->user->session_get('captcha');
                    if ($cap == false) {
                        return array('error' => 'You must load a captcha first!');
                    }
                    if ($data['captcha'] != $cap) {
                        return array('error' => 'Invalid captcha answer!');
                    }
                }
                if (isset($data['categories'])) {
                    $data['category'] = $data['categories'];
                }
									


                if (isset($data['category'])) {
                    $cats_check = array();
                    if(is_array($data['category'])){
                        foreach($data['category'] as $cat){
                            $cats_check[] = intval($cat);
                        }
                    } else {
                        $cats_check[] = intval($data['category']);
                    }

                    $check_if_user_can_publish = $this->app->category->get('ids='.implode(',',$cats_check));
                    if(!empty($check_if_user_can_publish)){

                        $user_cats = array();
                        foreach($check_if_user_can_publish as $item){
                            if(isset($item["users_can_create_content"]) and $item["users_can_create_content"] == 'y'){
                                $user_cats[] = $item["id"];
								$cont_cat = $this->app->content->get('limit=1&content_type=page&subtype_value=' . $item["id"]);
								

                            }

                        }


                        if(!empty($user_cats)){
                            $stop = false;
                            $data['categories'] = $user_cats;
							
                        }
                    }
                   // d($check_if_user_can_publish);
                }

                if( $stop == true){


                return array('error' => 'You are not logged in as admin to save content!');
                }
            }
        }

        $cats_modified = false;


        if (empty($data) or !isset($data['id'])) {

            return false;
        }

        if (isset($data['content_url']) and !isset($data['url'])) {
            $data['url'] = $data['content_url'];
        }
        $data_to_save = $data;

        $more_categories_to_delete = array();
        if (!isset($data['url']) and intval($data['id']) != 0) {

            $q = "SELECT * FROM $table WHERE id='{$data_to_save['id']}' ";

            $q = $this->app->db->query($q);

            $thetitle = $q[0]['title'];

            $q = $q[0]['url'];

            $theurl = $q;

            $more_categories_to_delete = $this->app->category->get_for_content($data['id'], 'categories');
        } else {
            if (isset($data['url'])) {
                $theurl = $data['url'];
            } else {
                $theurl = $data['title'];
            }
            $thetitle = $data['title'];
        }

        if (isset($data['title'])) {
            $data['title'] = htmlspecialchars_decode($data['title'], ENT_QUOTES);

            $data['title'] = strip_tags($data['title']);
        }

        if (isset($data['url']) == false or $data['url'] == '') {
            if (isset($data['title']) != false and intval($data ['id']) == 0) {
                $data['url'] = $this->app->url->slug($data['title']);


            }
        }


        if (isset($data['url']) != false) {
            // if (intval ( $data ['id'] ) == 0) {
            $data_to_save['url'] = $data['url'];

            // }
        }

        if (isset($data['category']) or isset($data['categories'])) {
            $cats_modified = true;
        }
        $table_cats = MW_TABLE_PREFIX . 'categories';

        if (isset($data_to_save['title']) and (!isset($data['url']) or trim($data['url']) == '')) {
            $data['url'] = $this->app->url->slug($data_to_save['title']);
        }

        if (isset($data['url']) and $data['url'] != false) {

            if (trim($data['url']) == '') {

                $data['url'] = $this->app->url->slug($data['title']);
            }

            $date123 = date("YmdHis");

            $q = "SELECT id, url FROM $table WHERE url LIKE '{$data['url']}'";

            $q = $this->app->db->query($q);

            if (!empty($q)) {

                $q = $q[0];

                if ($data['id'] != $q['id']) {

                    $data['url'] = $data['url'] . '-' . $date123;
                    $data_to_save['url'] = $data['url'];

                }
            }

            if (isset($data_to_save['url']) and strval($data_to_save['url']) == '' and (isset($data_to_save['quick_save']) == false)) {

                $data_to_save['url'] = $data_to_save['url'] . '-' . $date123;
            }

            if (isset($data_to_save['title']) and strval($data_to_save['title']) == '' and (isset($data_to_save['quick_save']) == false)) {

                $data_to_save['title'] = 'post-' . $date123;
            }
            if (isset($data_to_save['url']) and strval($data_to_save['url']) == '' and (isset($data_to_save['quick_save']) == false)) {
                $data_to_save['url'] = strtolower(reduce_double_slashes($data['url']));
            }

        }


        if (isset($data_to_save['url']) and is_string($data_to_save['url'])) {
            $data_to_save['url'] = str_replace(site_url(), '', $data_to_save['url']);
        }


        $data_to_save_options = array();

        if (isset($data_to_save['is_home']) and $data_to_save['is_home'] == 'y') {
            $sql = "UPDATE $table SET is_home='n'   ";
            $q = $this->app->db->query($sql);
        }

        if (isset($data_to_save['subtype']) and strval($data_to_save['subtype']) == 'dynamic') {
            $check_ex = false;
            if (isset($data_to_save['subtype_value']) and trim($data_to_save['subtype_value']) != '' and intval(($data_to_save['subtype_value'])) > 0) {

                $check_ex = $this->app->category->get_by_id(intval($data_to_save['subtype_value']));
            }
            if ($check_ex == false) {
                if (isset($data_to_save['id']) and intval(trim($data_to_save['id'])) > 0) {
                    $test2 = $this->app->category->get('data_type=category&rel=content&rel_id=' . intval(($data_to_save['id'])));

                    if (isset($test2[0])) {
                        $check_ex = $test2[0];
                        $data_to_save['subtype_value'] = $test2[0]['id'];
                    }


                }

                if ($check_ex == false) {

                }

                unset($data_to_save['subtype_value']);
            }


            if (isset($check_ex) and $check_ex == false) {

                if (!isset($data_to_save['subtype_value_new'])) {
                    if (isset($data_to_save['title'])) {
                        //$cats_modified = true;
                        //$data_to_save['subtype_value_new'] = $data_to_save['title'];
                    }
                }
            }
        }


        $par_page = false;
        if (isset($data_to_save['content_type']) and strval($data_to_save['content_type']) == 'post') {
            if (isset($data_to_save['parent']) and intval($data_to_save['parent']) > 0) {
                $par_page = $this->get_by_id($data_to_save['parent']);
            }


            if (is_array($par_page)) {


                if ($par_page['subtype'] == 'static') {
                    $par_page_new = array();
                    $par_page_new['id'] = $par_page['id'];
                    $par_page_new['subtype'] = 'dynamic';

                    $par_page_new = $this->app->db->save($table, $par_page_new);
                    $cats_modified = true;
                }
                if (!isset($data_to_save['categories'])) {
                    $data_to_save['categories'] = '';
                }
                if (is_string($data_to_save['categories']) and isset($par_page['subtype_value']) and $par_page['subtype_value'] != '') {
                    $data_to_save['categories'] = $data_to_save['categories'] . ', ' . $par_page['subtype_value'];
                }
            }
            $c1 = false;
			  if (isset($data_to_save['category']) and !isset($data_to_save['categories'])) {
           $data_to_save['categories'] = $data_to_save['category'];
        		}
            if (isset($data_to_save['categories']) and $par_page == false) {
                if (is_string($data_to_save['categories'])) {
                    $c1 = explode(',', $data_to_save['categories']);
                    if (is_array($c1)) {
                        foreach ($c1 as $item) {
                            $item = intval($item);
                            if ($item > 0) {
                                $cont_cat = $this->app->content->get('limit=1&content_type=page&subtype_value=' . $item);
                                //	d($cont_cat);
                                if (isset($cont_cat[0]) and is_array($cont_cat[0])) {
                                    $cont_cat = $cont_cat[0];
                                    if (isset($cont_cat["subtype_value"]) and intval($cont_cat["subtype_value"]) > 0) {


                                        $data_to_save['parent'] = $cont_cat["id"];
                                        break;
                                    }
                                }
                                //
                            }
                        }
                    }


                }
            }
        }

        if (isset($data_to_save['content'])) {
            if (trim($data_to_save['content']) == '' or $data_to_save['content'] == false) {
                unset($data_to_save['content']);
                //
            } else {
                $data_to_save['content'] = mw('parser')->make_tags($data_to_save['content']);
            }
        }


        if (isset($data_to_save['id']) and intval($data_to_save['id']) == 0) {
            if (!isset($data_to_save['position']) or intval($data_to_save['position']) == 0) {

                $get_max_pos = "SELECT max(position) AS maxpos FROM $table  ";
                $get_max_pos = $this->app->db->query($get_max_pos);
                if (is_array($get_max_pos) and isset($get_max_pos[0]['maxpos']))


                    if (isset($data_to_save['content_type']) and strval($data_to_save['content_type']) == 'page') {
                        $data_to_save['position'] = intval($get_max_pos[0]['maxpos']) - 1;

                    } else {
                        $data_to_save['position'] = intval($get_max_pos[0]['maxpos']) + 1;

                    }

            }

        }


        $cats_modified = true;
        $data_to_save['updated_on'] = date("Y-m-d H:i:s");

        if (!isset($data_to_save['id']) or intval($data_to_save['id']) == 0) {
            if (!isset($data_to_save['parent'])) {
                $data_to_save['parent'] = 0;
            }




            if($data_to_save['parent'] == 0){
                if (isset($data_to_save['categories'])){
                    $first = false;
                    if(is_array($data_to_save['categories'])){
                        $temp = $data_to_save['categories'];
                        $first = array_shift($temp);
                    } else {
                        $first = intval($data_to_save['categories']);
                    }

                    if($first != false){
                        $first_par_for_cat = $this->app->category->get_page($first);
                        if(!empty($first_par_for_cat) and isset($first_par_for_cat['id'])){
                            $data_to_save['parent']  = $first_par_for_cat['id'];
                            if (!isset($data_to_save['content_type'])) {
                                $data_to_save['content_type'] = 'post';
                            }

                            if (!isset($data_to_save['subtype'])) {
                                $data_to_save['subtype'] = 'post';
                            }

                        }




                    }




                }
            }

        }



        if (isset($data_to_save['url']) and $data_to_save['url'] == $this->app->url->site()) {
            unset($data_to_save['url']);
        }


        if (isset($data_to_save['debug'])) {

        }

        $data_to_save['allow_html'] = true;

         $save = $this->app->db->save($table, $data_to_save);

        // $this->app->cache->delete('content/global');
        //$this->app->cache->delete('content/'.$save);
        if (isset($data_to_save['subtype']) and strval($data_to_save['subtype']) == 'dynamic') {
            $new_category = $this->app->category->get_for_content($save);

            if ($new_category == false) {
                //$new_category_id = intval($new_category);
                $new_category = array();
                $new_category["data_type"] = "category";
                $new_category["rel"] = 'content';
                $new_category["rel_id"] = $save;
                $new_category["table"] = $table_cats;
                $new_category["id"] = 0;
                $new_category["title"] = $data_to_save['title'];
                $new_category["parent_id"] = "0";
                $cats_modified = true;
                //	 d($new_category);
                $new_category = $this->app->categories->save($new_category);


            }
        }
        $custom_field_table = MW_TABLE_PREFIX . 'custom_fields';

        $sid = session_id();

        $id = $save;

        $clean = " UPDATE $custom_field_table SET
	rel =\"content\"
	, rel_id =\"{$id}\"
	WHERE
	session_id =\"{$sid}\"
	AND (rel_id=0 OR rel_id IS NULL) AND rel =\"content\"

	";


        $this->app->db->q($clean);
        $this->app->cache->delete('custom_fields');

        $media_table = MW_TABLE_PREFIX . 'media';

        $clean = " UPDATE $media_table SET

	rel_id =\"{$id}\"
	WHERE
	session_id =\"{$sid}\"
	AND rel =\"content\" AND (rel_id=0 OR rel_id IS NULL)

	";


        $this->app->cache->delete('media/global');

        $this->app->db->q($clean);

        if (isset($data_to_save['parent']) and intval($data_to_save['parent']) != 0) {
            $this->app->cache->delete('content' . DIRECTORY_SEPARATOR . intval($data_to_save['parent']));
        }
        if (isset($data_to_save['id']) and intval($data_to_save['id']) != 0) {
            $this->app->cache->delete('content' . DIRECTORY_SEPARATOR . intval($data_to_save['id']));
        }
        $this->app->cache->delete('content' . DIRECTORY_SEPARATOR . 'global');
        $this->app->cache->delete('content' . DIRECTORY_SEPARATOR . '0');
        $this->app->cache->delete('content_fields/global');

        if ($cats_modified != false) {

            $this->app->cache->delete('categories/global');
            $this->app->cache->delete('categories_items/global');
            if (isset($c1) and is_array($c1)) {
                foreach ($c1 as $item) {
                    $item = intval($item);
                    if ($item > 0) {
                        $this->app->cache->delete('categories/' . $item);
                    }
                }
            }
        }

        event_trigger('mw_save_content');
        //session_write_close();
        return $save;

    }


    public function save_edit($post_data)
    {
        $id = $this->app->user->is_admin();
        if ($id == false) {
            exit('Error: not logged in as admin.' . __FILE__ . __LINE__);
        }
        if ($post_data) {
            if (isset($post_data['json_obj'])) {
                $obj = json_decode($post_data['json_obj'], true);
                $post_data = $obj;
            }
            // p($post_data);
            if (isset($post_data['mw_preview_only'])) {
                $is_no_save = true;
                unset($post_data['mw_preview_only']);
            }
            $is_no_save = false;
            $is_draft = false;
            if (isset($post_data['is_draft'])) {
                unset($post_data['is_draft']);
                $is_draft = 1;
            }
            $the_field_data_all = $post_data;
        } else {
            exit('Error: no POST?');
        }

        $ustr2 = $this->app->url->string(1, 1);

        if (isset($ustr2) and trim($ustr2) == 'favicon.ico') {
            return false;
        }
        $ref_page = $ref_page_url = $_SERVER['HTTP_REFERER'];
        if ($ref_page != '') {
            // $ref_page = $the_ref_page = $this->get_by_url($ref_page_url);
            $ref_page2 = $ref_page = $this->get_by_url($ref_page_url, true);


            if ($ref_page2 == false) {

                $ustr = $this->app->url->string(1);

                if ($this->app->module->is_installed($ustr)) {
                    $ref_page = false;
                }

            } else {
                $ref_page = $ref_page2;
            }
            if (isset($ustr) and trim($ustr) == 'favicon.ico') {
                return false;
            } elseif ($ustr2 == '' or $ustr2 == '/') {

                $ref_page = get_homepage();

            }


            if ($ref_page == false) {


                $guess_page_data = new \Microweber\Controller();
                // $guess_page_data =  new  $this->app->controller($this->app);
                $guess_page_data->page_url = $ref_page_url;
                $guess_page_data->return_data = true;
                $guess_page_data->create_new_page = true;
                $pd = $guess_page_data->index();

                if (is_array($pd) and (isset($pd["active_site_template"]) or isset($pd["layout_file"]))) {
                    $save_page = $pd;
                    $save_page['url'] = $this->app->url->string(1);
                    $save_page['title'] = $this->app->url->slug($this->app->url->string(1));
                    $page_id = $this->save_content($save_page);
                }
                //

                // d($ref_page_url);
            } else {
                $page_id = $ref_page['id'];
                $ref_page['custom_fields'] = $this->custom_fields($page_id, false);
            }
        }
        $save_as_draft = false;
        if (isset($post_data['save_draft'])) {
            $save_as_draft = true;
            unset($post_data['save_draft']);
        }


        $json_print = array();
        foreach ($the_field_data_all as $the_field_data) {
            $save_global = false;
            $save_layout = false;
            if (isset($page_id) and $page_id != 0 and !empty($the_field_data)) {
                $save_global = false;

                $content_id = $page_id;


                $url = $this->app->url->string(true);
                $some_mods = array();
                if (isset($the_field_data) and is_array($the_field_data) and isset($the_field_data['attributes'])) {
                    if (($the_field_data['html']) != '') {
                        $field = false;
                        if (isset($the_field_data['attributes']['field'])) {
                            $field = trim($the_field_data['attributes']['field']);
                            //$the_field_data['attributes']['rel'] = $field;


                        }

                        if (isset($the_field_data['attributes']['data-field'])) {
                            $field = $the_field_data['attributes']['field'] = trim($the_field_data['attributes']['data-field']);
                        }

                        if ($field == false) {
                            if (isset($the_field_data['attributes']['id'])) {
                                //	$the_field_data['attributes']['field'] = $field = $the_field_data['attributes']['id'];
                            }
                        }

                        if (($field != false)) {
                            $page_element_id = $field;
                        }
                        if (!isset($the_field_data['attributes']['rel'])) {
                            $the_field_data['attributes']['rel'] = 'content';
                        }
                        $save_global = false;
                        if (isset($the_field_data['attributes']['rel']) and (trim($the_field_data['attributes']['rel']) == 'global' or trim($the_field_data['attributes']['rel'])) == 'module') {
                            $save_global = true;
                            // p($the_field_data ['attributes'] ['rel']);
                        } else {
                            $save_global = false;
                        }
                        if (isset($the_field_data['attributes']['rel']) and trim($the_field_data['attributes']['rel']) == 'layout') {
                            $save_global = false;
                            $save_layout = true;
                        } else {
                            $save_layout = false;
                        }


                        if (!isset($the_field_data['attributes']['data-id'])) {
                            $the_field_data['attributes']['data-id'] = $content_id;
                        }

                        $save_global = 1;

                        if (isset($the_field_data['attributes']['rel']) and isset($the_field_data['attributes']['data-id'])) {


                            $rel_ch = trim($the_field_data['attributes']['rel']);
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
                        if (isset($the_field_data['attributes']['rel']) and ($the_field_data['attributes']['rel']) == 'inherit') {


                            $save_global = false;
                            $save_layout = false;
                            $content_id = $page_id;

                            $inh = $this->get_inherited_parent($page_id);
                            if ($inh != false) {
                                $content_id_for_con_field = $content_id = $inh;

                            }

                        }


                        $save_layout = false;
                        if ($inh == false and !isset($content_id_for_con_field)) {

                            if (is_array($ref_page) and isset($ref_page['parent']) and  isset($ref_page['content_type'])  and $ref_page['content_type'] == 'post') {
                                $content_id_for_con_field = intval($ref_page['parent']);
                                // d($content_id);
                            } else {
                                $content_id_for_con_field = intval($ref_page['id']);

                            }
                        }
                        $html_to_save = $the_field_data['html'];
                        $html_to_save = $content = mw('parser')->make_tags($html_to_save);
                        if ($save_global == false and $save_layout == false) {
                            if ($content_id) {

                                $for_histroy = $ref_page;
                                $old = false;
                                $field123 = str_ireplace('custom_field_', '', $field);

                                if (stristr($field, 'custom_field_')) {

                                    $old = $for_histroy['custom_fields'][$field123];
                                } else {

                                    if (isset($for_histroy['custom_fields'][$field123])) {
                                        $old = $for_histroy['custom_fields'][$field123];
                                    } elseif (isset($for_histroy[$field])) {
                                        $old = $for_histroy[$field];
                                    }
                                }
                                $history_to_save = array();
                                $history_to_save['table'] = 'content';
                                $history_to_save['id'] = $content_id;
                                $history_to_save['value'] = $old;
                                $history_to_save['field'] = $field;
                                // p ( $history_to_save );
                                if ($is_no_save != true) {
                                    //	save_history($history_to_save);
                                }
                                $cont_field = array();
                                $cont_field['rel'] = 'content';
                                $cont_field['rel_id'] = $content_id_for_con_field;
                                $cont_field['value'] = $html_to_save;
                                $cont_field['field'] = $field;


                                if ($is_draft != false) {
                                    $cont_field['is_draft'] = 1;
                                    $cont_field['rel'] = $rel_ch;
                                    $cont_field['url'] = $url;

                                    $cont_field1 = save_content_field($cont_field);

                                } else {
                                    if ($field != 'content') {

                                        $cont_field1 = save_content_field($cont_field);
                                    }
                                }


                                $to_save = array();
                                $to_save['id'] = $content_id;

                                //   $to_save['debug'] = $content_id;

                                //$to_save['page_element_id'] = $page_element_id;

                                $is_native_fld = $this->app->db->get_fields('content');
                                if (in_array($field, $is_native_fld)) {
                                    $to_save[$field] = ($html_to_save);
                                } else {

                                    //$to_save['custom_fields'][$field] = ($html_to_save);
                                }

                                if ($is_no_save != true and $is_draft == false) {
                                    $json_print[] = $to_save;

                                    $saved = $this->save_content($to_save);


                                }


                            } else if (isset($category_id)) {
                                print(__FILE__ . __LINE__ . ' category is not implemented ... not ready yet');
                            }
                        } else {

                            $cont_field = array();

                            $cont_field['rel'] = $the_field_data['attributes']['rel'];
                            $cont_field['rel_id'] = 0;
                            if ($cont_field['rel'] != 'global' and isset($the_field_data['attributes']['content-id'])) {
                                $cont_field['rel_id'] = $the_field_data['attributes']['content-id'];
                            } else if ($cont_field['rel'] != 'global' and isset($the_field_data['attributes']['data-id'])) {
                                $cont_field['rel_id'] = $the_field_data['attributes']['data-id'];
                            }


                            $cont_field['value'] = mw('parser')->make_tags($html_to_save);
                            ;
                            if ((!isset($the_field_data['attributes']['field']) or $the_field_data['attributes']['field'] == '')and isset($the_field_data['attributes']['data-field'])) {
                                $the_field_data['attributes']['field'] = $the_field_data['attributes']['data-field'];
                            }
                            $cont_field['field'] = $the_field_data['attributes']['field'];


                            // d($cont_field);


                            //if($field != 'content'){
                            //
                            //
                            //
                            //

                            if ($is_draft != false) {
                                $cont_field['is_draft'] = 1;
                                $cont_field['url'] = $this->app->url->string(true);
                                //$cont_field['rel'] = $rel_ch;
                                $cont_field_new = save_content_field($cont_field);
                            } else {
                                $cont_field_new = save_content_field($cont_field);

                            }


                            //}


                            if ($save_global == true and $save_layout == false) {


                                $json_print[] = $cont_field;
                                $history_to_save = array();
                                $history_to_save['table'] = 'global';
                                // $history_to_save ['id'] = 'global';
                                $history_to_save['value'] = $cont_field['value'];
                                $history_to_save['field'] = $field;
                                $history_to_save['page_element_id'] = $page_element_id;

                                if ($is_no_save != true) {
                                    //	save_history($history_to_save);
                                }
                            }
                            if ($save_global == false and $save_layout == true) {

                                $d = TEMPLATE_DIR . 'layouts' . DIRECTORY_SEPARATOR . 'editable' . DIRECTORY_SEPARATOR;
                                $f = $d . $ref_page['id'] . '.php';
                                if (!is_dir($d)) {
                                    mkdir_recursive($d);
                                }

                                file_put_contents($f, $html_to_save);
                            }
                        }
                    }
                } else {

                }
            }
        }
        if (isset($opts_saved)) {
            $this->app->cache->delete('options');
        }
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-type: application/json');

        $json_print = json_encode($json_print);

        $history_to_save = array();
        $history_to_save['table'] = 'edit';
        $history_to_save['id'] = (parse_url(strtolower($_SERVER['HTTP_REFERER']), PHP_URL_PATH));
        $history_to_save['value'] = $json_print;
        $history_to_save['field'] = 'html_content';
        //save_history($history_to_save);
        // }
        print $json_print;
        //$this->app->cache->delete('global/blocks');
        exit();
    }

    public function save_content_field($data, $delete_the_cache = true)
    {

        $adm = $this->app->user->is_admin();
        $table = MW_DB_TABLE_CONTENT_FIELDS;
        $table_drafts = MW_DB_TABLE_CONTENT_FIELDS_DRAFTS;

        //$checks = mw_var('FORCE_SAVE_CONTENT');


        if ($adm == false) {
            mw_error('Error: not logged in as admin.' . __FILE__ . __LINE__);
        }

        if (!is_array($data)) {
            $data = array();
        }

        if (isset($data['is_draft'])) {
            $table = $table_drafts;


        }
        if (isset($data['is_draft']) and isset($data['url'])) {
            $fld_remove = $this->app->db->escape_string($data['url']);

            $history_files = $this->edit_field('order_by=id desc&fields=id&is_draft=1&all=1&limit=50&curent_page=3&url=' . $fld_remove);
            if (is_array($history_files)) {
                $history_files_ids = $this->app->format->array_values($history_files);
            }
            if (isset($history_files_ids) and is_array($history_files_ids)) {
                $history_files_ids_impopl = implode(',', $history_files_ids);
                $del_q = "DELETE FROM {$table} WHERE id IN ($history_files_ids_impopl) ";
                $this->app->db->q($del_q);
            }
            //d($history_files_ids);


//d($del_q );
            //	$this->app->db->q($del_q);
        }


        if (!isset($data['rel']) or !isset($data['rel_id'])) {
            mw_error('Error: ' . __FUNCTION__ . ' rel and rel_id is required');
        }
        //if($data['rel'] == 'global'){
        if (isset($data['field']) and !isset($data['is_draft'])) {
            $fld = $this->app->db->escape_string($data['field']);
            $fld_rel = $this->app->db->escape_string($data['rel']);
            $del_q = "DELETE FROM {$table} WHERE rel='$fld_rel' AND  field='$fld' ";
            if (isset($data['rel_id'])) {
                $i = $this->app->db->escape_string($data['rel_id']);
                $del_q .= " and  rel_id='$i' ";

            } else {
                $data['rel_id'] = 0;
            }
            $cache_group = guess_cache_group('content_fields/' . $data['rel'] . '/' . $data['rel_id']);
            $this->app->db->q($del_q);
            //$this->app->cache->delete($cache_group);

            //$this->app->cache->delete('content_fields/global');

        }
        if (isset($data['rel']) or isset($data['rel_id'])) {
            $cache_group = guess_cache_group('content_fields/' . $data['rel'] . '/' . $data['rel_id']);
            $this->app->cache->delete($cache_group);
            $this->app->cache->delete('content_fields/global');
        }

        //}
        $data['allow_html'] = true;

        $save = $this->app->db->save($table, $data);


        return $save;


    }

    public function delete($data)
    {

        $adm = $this->app->user->is_admin();
        if ($adm == false) {
            mw_error('Error: not logged in as admin.' . __FILE__ . __LINE__);
        }

        $to_trash = true;
        $to_untrash = false;
        if (isset($data['forever']) or isset($data['delete_forever'])) {

            $to_trash = false;
        }
        if (isset($data['undelete'])) {

            $to_trash = true;
            $to_untrash = true;

        }

        $del_ids = array();
        if (isset($data['id'])) {
            $c_id = intval($data['id']);
            $del_ids[] = $c_id;
            if ($to_trash == false) {
                $this->app->db->delete_by_id('content', $c_id);
            }
        }

        if (isset($data['ids']) and is_array($data['ids'])) {
            foreach ($data['ids'] as $value) {
                $c_id = intval($value);
                $del_ids[] = $c_id;
                if ($to_trash == false) {
                    $this->app->db->delete_by_id('content', $c_id);
                }
            }

        }


        if (!empty($del_ids)) {
            $table = MW_DB_TABLE_CONTENT;

            foreach ($del_ids as $value) {
                $c_id = intval($value);
                //$q = "update $table set parent=0 where parent=$c_id ";

                if ($to_untrash == true) {
                    $q = "UPDATE $table SET is_deleted='n' WHERE id=$c_id AND  is_deleted='y' ";
                    $q = $this->app->db->query($q);
                    $q = "UPDATE $table SET is_deleted='n' WHERE parent=$c_id   AND  is_deleted='y' ";
                    $q = $this->app->db->query($q);
                    if (defined("MW_DB_TABLE_TAXONOMY")) {
                        $table1 = MW_DB_TABLE_TAXONOMY;
                        $q = "UPDATE $table1 SET is_deleted='n' WHERE rel_id=$c_id  AND  rel='content' AND  is_deleted='y' ";
                        $q = $this->app->db->query($q);
                    }

                } else if ($to_trash == false) {
                    $q = "UPDATE $table SET parent=0 WHERE parent=$c_id ";
                    $q = $this->app->db->query($q);

                    $this->app->db->delete_by_id('menus', $c_id, 'content_id');

                    if (defined("MW_DB_TABLE_MEDIA")) {
                        $table1 = MW_DB_TABLE_MEDIA;
                        $q = "DELETE FROM $table1 WHERE rel_id=$c_id  AND  rel='content'  ";
                        $q = $this->app->db->query($q);
                    }

                    if (defined("MW_DB_TABLE_TAXONOMY")) {
                        $table1 = MW_DB_TABLE_TAXONOMY;
                        $q = "DELETE FROM $table1 WHERE rel_id=$c_id  AND  rel='content'  ";
                        $q = $this->app->db->query($q);
                    }


                    if (defined("MW_DB_TABLE_TAXONOMY_ITEMS")) {
                        $table1 = MW_DB_TABLE_TAXONOMY_ITEMS;
                        $q = "DELETE FROM $table1 WHERE rel_id=$c_id  AND  rel='content'  ";
                        $q = $this->app->db->query($q);
                    }


                    if (defined("MW_DB_TABLE_CUSTOM_FIELDS")) {
                        $table1 = MW_DB_TABLE_CUSTOM_FIELDS;
                        $q = "DELETE FROM $table1 WHERE rel_id=$c_id  AND  rel='content'  ";
                        $q = $this->app->db->query($q);
                    }


                } else {
                    $q = "UPDATE $table SET is_deleted='y' WHERE id=$c_id ";

                    $q = $this->app->db->query($q);
                    $q = "UPDATE $table SET is_deleted='y' WHERE parent=$c_id ";
                    $q = $this->app->db->query($q);
                    if (defined("MW_DB_TABLE_TAXONOMY")) {
                        $table1 = MW_DB_TABLE_TAXONOMY;
                        $q = "UPDATE $table1 SET is_deleted='y' WHERE rel_id=$c_id  AND  rel='content' AND  is_deleted='n' ";

                        $q = $this->app->db->query($q);
                    }


                }


                $this->app->cache->delete('content/' . $c_id);
            }

            $this->app->cache->delete('content');
            $this->app->cache->delete('categories/global');


        }
        return ($del_ids);
    }


    public function edit_field_draft($data)
    {
        only_admin_access();

        $page = false;
        if (isset($_SERVER["HTTP_REFERER"])) {
            $url = $_SERVER["HTTP_REFERER"];
            $url = explode('?', $url);
            $url = $url[0];

            if (trim($url) == '' or trim($url) == $this->app->url->site()) {
                //$page = $this->get_by_url($url);
                $page = get_homepage();
                // var_dump($page);
            } else {

                $page = $this->get_by_url($url);
            }
        } else {
            $url = $this->app->url->string();
        }

        $this->define_constants($page);


        $table_drafts = MW_DB_TABLE_CONTENT_FIELDS_DRAFTS;

        $data = parse_params($data);
        $data['is_draft'] = 1;
        $data['full'] = 1;
        $data['all'] = 1;
        $ret = array();
        $results = $this->edit_field($data);
        foreach ($results as $item) {

            $i = 0;

            if (isset($item['value'])) {
                $field_content = htmlspecialchars_decode($item['value']);
                 $field_content = $this->_decode_entities($field_content);
                $item['value'] = mw('parser')->process($field_content, $options = false);

            }

            $ret[$i] = $item;
            $i++;

        }


        return $ret;


    }


    public function reorder($params)
    {
        $id = $this->app->user->is_admin();
        if ($id == false) {
            exit('Error: not logged in as admin.' . __FILE__ . __LINE__);
        }
        $ids = $params['ids'];
        if (empty($ids)) {
            $ids = $_POST[0];
        }
        if (empty($ids)) {
            return false;
        }
        $ids = array_unique($ids);

        $ids_implode = implode(',', $ids);
        $ids_implode = $this->app->db->escape_string($ids_implode);


        $table = MW_TABLE_PREFIX . 'content';
        $maxpos = 0;
        $get_max_pos = "SELECT max(position) AS maxpos FROM $table  WHERE id IN ($ids_implode) ";
        $get_max_pos = $this->app->db->query($get_max_pos);
        if (is_array($get_max_pos) and isset($get_max_pos[0]['maxpos'])) {

            $maxpos = intval($get_max_pos[0]['maxpos']) + 1;

        }

        // $q = " SELECT id, created_on, position from $table where id IN ($ids_implode)  order by position desc  ";
        // $q = $this->app->db->query($q);
        // $max_date = $q[0]['created_on'];
        // $max_date_str = strtotime($max_date);
        $i = 1;
        foreach ($ids as $id) {
            $id = intval($id);
            $this->app->cache->delete('content/' . $id);
            //$max_date_str = $max_date_str - $i;
            //	$nw_date = date('Y-m-d H:i:s', $max_date_str);
            //$q = " UPDATE $table set created_on='$nw_date' where id = '$id'    ";
            $pox = $maxpos - $i;
            $q = " UPDATE $table SET position=$pox WHERE id=$id   ";
            //    var_dump($q);
            $q = $this->app->db->q($q);
            $i++;
        }
        //
        // var_dump($q);
        $this->app->cache->delete('content/global');
        $this->app->cache->delete('categories/global');
        return true;
    }


    /**
     * Set content to be unpublished
     *
     * Set is_active flag 'n'
     *
     * @param string|array|bool $params
     * @return string The url of the content
     * @package Content
     * @subpackage Advanced
     *
     * @uses $this->save_content()
     * @see content_set_unpublished()
     * @example
     * <code>
     * //set published the content with id 5
     * content_set_unpublished(5);
     *
     * //alternative way
     * content_set_unpublished(array('id' => 5));
     * </code>
     *
     */
    public function set_unpublished($params)
    {

        if (intval($params) > 0 and !isset($params['id'])) {
            if (!is_array($params)) {
                $id = $params;
                $params = array();
                $params['id'] = $id;
            }
        }


        if (!isset($params['id'])) {
            return array('error' => 'You must provide id parameter!');
        } else {
            if (intval($params['id'] != 0)) {
                $save = array();
                $save['id'] = intval($params['id']);
                $save['is_active'] = 'n';

                $save_data = $this->save_content($save);
                return ($save_data);
            }


        }

    }


    /**
     * Set content to be published
     *
     * Set is_active flag 'y'
     *
     * @param string|array|bool $params
     * @return string The url of the content
     * @package Content
     * @subpackage Advanced
     *
     * @uses $this->save_content()
     * @see content_set_unpublished()
     * @example
     * <code>
     * //set published the content with id 5
     * content_set_published(5);
     *
     * //alternative way
     * content_set_published(array('id' => 5));
     * </code>
     *
     */
    public function set_published($params)
    {

        if (intval($params) > 0 and !isset($params['id'])) {
            if (!is_array($params)) {
                $id = $params;
                $params = array();
                $params['id'] = $id;
            }
        }

        if (!isset($params['id'])) {
            return array('error' => 'You must provide id parameter!');
        } else {
            if (intval($params['id'] != 0)) {

                $save = array();
                $save['id'] = intval($params['id']);
                $save['is_active'] = 'y';

                $save_data = $this->save_content($save);
                return ($save_data);
            }

        }
    }


    function create_default_content($what)
    {

        if (defined("MW_NO_DEFAULT_CONTENT")) {
            return true;
        }


        switch ($what) {
            case 'shop' :
                $is_shop = $this->app->content->get('content_type=page&is_shop=y');
                //$is_shop = false;
                $new_shop = false;
                if ($is_shop == false) {
                    $add_page = array();
                    $add_page['id'] = 0;
                    $add_page['parent'] = 0;

                    $add_page['title'] = "Online shop";
                    $add_page['url'] = "shop";
                    $add_page['content_type'] = "page";
                    $add_page['subtype'] = 'dynamic';
                    $add_page['is_shop'] = 'y';
                    $add_page['active_site_template'] = 'default';
                    $find_layout = $this->app->layouts->scan();
                    if (is_array($find_layout)) {
                        foreach ($find_layout as $item) {
                            if (isset($item['layout_file']) and isset($item['is_shop'])) {
                                $add_page['layout_file'] = $item['layout_file'];
                                if (isset($item['name'])) {
                                    $add_page['title'] = $item['name'];
                                }
                            }
                        }
                    }
                    //  d($add_page);
                    $new_shop = $this->app->db->save('content', $add_page);
                    $this->app->cache->delete('content');
                    $this->app->cache->delete('categories');
                    $this->app->cache->delete('custom_fields');

                    //
                } else {

                    if (isset($is_shop[0])) {
                        $new_shop = $is_shop[0]['id'];
                    }
                }

                $posts = $this->app->content->get('content_type=post&parent=' . $new_shop);
                if ($posts == false and $new_shop != false) {
                    $add_page = array();
                    $add_page['id'] = 0;
                    $add_page['parent'] = $new_shop;
                    $add_page['title'] = "My product";
                    $add_page['url'] = "my-product";
                    $add_page['content_type'] = "post";
                    $add_page['subtype'] = "product";

                    //$new_shop = $this->save_content($add_page);
                    //$this->app->cache->delete('content');
                    //$this->app->cache->flush();
                }


                break;


            case 'blog' :
                $is_shop = $this->app->content->get('is_deleted=n&content_type=page&subtype=dynamic&is_shop=n&limit=1');
                //$is_shop = false;
                $new_shop = false;
                if ($is_shop == false) {
                    $add_page = array();
                    $add_page['id'] = 0;
                    $add_page['parent'] = 0;

                    $add_page['title'] = "Blog";
                    $add_page['url'] = "blog";
                    $add_page['content_type'] = "page";
                    $add_page['subtype'] = 'dynamic';
                    $add_page['is_shop'] = 'n';
                    $add_page['active_site_template'] = 'default';
                    $find_layout = $this->app->layouts->scan();
                    if (is_array($find_layout)) {
                        foreach ($find_layout as $item) {
                            if (!isset($item['is_shop']) and isset($item['layout_file']) and isset($item['content_type']) and trim(strtolower($item['content_type'])) == 'dynamic') {
                                $add_page['layout_file'] = $item['layout_file'];
                                if (isset($item['name'])) {
                                    $add_page['title'] = $item['name'];
                                }
                            }
                        }

                        foreach ($find_layout as $item) {
                            if (isset($item['name']) and stristr($item['name'], 'blog') and !isset($item['is_shop']) and isset($item['layout_file']) and isset($item['content_type']) and trim(strtolower($item['content_type'])) == 'dynamic') {
                                $add_page['layout_file'] = $item['layout_file'];
                                if (isset($item['name'])) {
                                    $add_page['title'] = $item['name'];
                                }
                            }
                        }


                    }
                    //  d($add_page);
                    $new_shop = $this->app->db->save('content', $add_page);
                    $this->app->cache->delete('content');
                    $this->app->cache->delete('categories');
                    $this->app->cache->delete('content_fields');


                    //
                } else {

                    if (isset($is_shop[0])) {
                        $new_shop = $is_shop[0]['id'];
                    }
                }


                break;

            case 'default' :
            case 'install' :
                $any = $this->app->content->get('count=1&content_type=page&limit=1');
                if (intval($any) == 0) {


                    $table = MW_TABLE_PREFIX . 'content';
                    mw_var('FORCE_SAVE_CONTENT', $table);
                    mw_var('FORCE_SAVE', $table);

                    $add_page = array();
                    $add_page['id'] = 0;
                    $add_page['parent'] = 0;
                    $add_page['title'] = "Home";
                    $add_page['url'] = "home";
                    $add_page['content_type'] = "page";
                    $add_page['subtype'] = 'static';
                    $add_page['is_shop'] = 'n';
                    //$add_page['debug'] = 1;
                    $add_page['is_home'] = 'y';
                    $add_page['active_site_template'] = 'default';
                    $new_shop = $this->save_content($add_page);
                }

                break;

            default :
                break;
        }
    }


    /**
     * @desc  Get the template layouts info under the layouts subdir on your active template
     * @param $options
     * $options ['type'] - 'layout' is the default type if you dont define any. You can define your own types as post/form, etc in the layout.txt file
     * @return array
     * @author    Microweber Dev Team
     * @since Version 1.0
     */
    public function site_templates($options = false)
    {

        $args = func_get_args();
        $function_cache_id = '';
        foreach ($args as $k => $v) {

            $function_cache_id = $function_cache_id . serialize($k) . serialize($v);
        }

        $cache_id = __FUNCTION__ . crc32($function_cache_id);

        $cache_group = 'templates';

        $cache_content = $this->app->cache->get($cache_id, $cache_group, 'files');

        if (($cache_content) != false) {

            return $cache_content;
        }

        $path = MW_TEMPLATES_DIR;
        $path_to_layouts = $path;
        $layout_path = $path;
        //	print $path;
        //exit;
        //$map = $this->directory_map ( $path, TRUE );
        $map = $this->directory_map($path, TRUE, TRUE);

        $to_return = array();

        foreach ($map as $dir) {

            //$filename = $path . $dir . DIRECTORY_SEPARATOR . 'layout.php';
            $filename = $path . DIRECTORY_SEPARATOR . $dir;
            $filename_location = false;
            $filename_dir = false;
            $filename = normalize_path($filename);
            $filename = rtrim($filename, '\\');
            //p ( $filename );
            if (is_dir($filename)) {
                //
                $fn1 = normalize_path($filename, true) . 'config.php';
                $fn2 = normalize_path($filename);

                //  p ( $fn1 );

                if (is_file($fn1)) {
                    $config = false;

                    include ($fn1);
                    if (!empty($config)) {
                        $c = $config;
                        $c['dir_name'] = $dir;

                        $screensshot_file = $fn2 . '/screenshot.png';
                        $screensshot_file = normalize_path($screensshot_file, false);
                        //p($screensshot_file);
                        if (is_file($screensshot_file)) {
                            $c['screenshot'] = $this->app->url->link_to_file($screensshot_file);
                        }

                        $to_return[] = $c;
                    }
                } else {
                    $filename_dir = false;
                }

                //	$path = $filename;
            }

            //p($filename);
        }
        $this->app->cache->save($to_return, $function_cache_id, $cache_group, 'files');

        return $to_return;
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

        $id = $this->app->user->is_admin();
        if ($id == false) {
            //error('Error: not logged in as admin.'.__FILE__.__LINE__);
        } else {

            if (isset($data_to_save['menu_id'])) {
                $data_to_save['id'] = intval($data_to_save['menu_id']);
            }
            $table = MODULE_DB_MENUS;

            $data_to_save['table'] = $table;
            $data_to_save['item_type'] = 'menu';

            $save = $this->app->db->save($table, $data_to_save);

            $this->app->cache->delete('menus/global');

            return $save;
        }

    }


    public function menu_delete($id = false)
    {
        $params = parse_params($id);


        $is_admin = $this->app->user->is_admin();
        if ($is_admin == false) {
            mw_error('Error: not logged in as admin.' . __FILE__ . __LINE__);
        }


        if (!isset($params['id'])) {
            mw_error('Error: id param is required.');
        }

        $id = $params['id'];

        $id = $this->app->db->escape_string($id);
        $id = htmlspecialchars_decode($id);
        $table = MODULE_DB_MENUS;

        $this->app->db->delete_by_id($table, trim($id), $field_name = 'id');

        $this->app->cache->delete('menus/global');

        return true;

    }

    public function menu_item_get($id)
    {

        $is_admin = $this->app->user->is_admin();
        if ($is_admin == false) {
            mw_error('Error: not logged in as admin.' . __FILE__ . __LINE__);
        }
        $id = intval($id);

        $table = MODULE_DB_MENUS;

        return get("one=1&limit=1&table=$table&id=$id");

    }


    public function  menu_item_save($data_to_save)
    {

        $id = $this->app->user->is_admin();
        if ($id == false) {
            mw_error('Error: not logged in as admin.' . __FILE__ . __LINE__);
        }

        if (isset($data_to_save['menu_id'])) {
            $data_to_save['id'] = intval($data_to_save['menu_id']);
            $this->app->cache->delete('menus/' . $data_to_save['id']);

        }

        if (!isset($data_to_save['id']) and isset($data_to_save['link_id'])) {
            $data_to_save['id'] = intval($data_to_save['link_id']);
        }

        if (isset($data_to_save['id'])) {
            $data_to_save['id'] = intval($data_to_save['id']);
            $this->app->cache->delete('menus/' . $data_to_save['id']);
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
        if (isset($data_to_save['content_id']) and intval($data_to_save['content_id']) == 0) {
            unset($data_to_save['content_id']);
        }

        if (isset($data_to_save['categories_id']) and intval($data_to_save['categories_id']) == 0) {
            unset($data_to_save['categories_id']);
            //$url_from_content = 1;
        }

        if ($url_from_content != false) {
            if (isset($data_to_save['title'])) {
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
            $this->app->cache->delete('menus/' . $data_to_save['parent_id']);
        }

        $table = MODULE_DB_MENUS;

        $data_to_save['table'] = $table;
        $data_to_save['item_type'] = 'menu_item';
        // d($data_to_save);
        $save = $this->app->db->save($table, $data_to_save);

        $this->app->cache->delete('menus/global');

        return $save;

    }

    public function menu_item_delete($id)
    {

        $is_admin = $this->app->user->is_admin();
        if ($is_admin == false) {
            mw_error('Error: not logged in as admin.' . __FILE__ . __LINE__);
        }

        $table = MODULE_DB_MENUS;

        $this->app->db->delete_by_id($table, intval($id), $field_name = 'id');

        $this->app->cache->delete('menus/global');

        return true;

    }


    public function menu_items_reorder($data)
    {

        $adm = $this->app->user->is_admin();
        if ($adm == false) {
            mw_error('Error: not logged in as admin.' . __FILE__ . __LINE__);
        }
        $table = MODULE_DB_MENUS;

        if (isset($data['ids_parents'])) {
            $value = $data['ids_parents'];
            if (is_array($value)) {

                foreach ($value as $value2 => $k) {
                    $k = intval($k);
                    $value2 = intval($value2);

                    $sql = "UPDATE $table SET
				parent_id=$k
				WHERE id=$value2 AND id!=$k
				AND item_type='menu_item'
				";
                    // d($sql);
                    $q = $this->app->db->q($sql);
                    $this->app->cache->delete('menus/' . $k);
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
                    $i++;
                }

                \mw('Microweber\DbUtils')->update_position_field($table, $indx);
                return true;
                // d($indx);
            }
        }

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


    public function add_content_to_menu($content_id, $menu_id = false)
    {
        $id = $this->app->user->is_admin();
        if ($id == false) {
            return;
            mw_error('Error: not logged in as admin.' . __FILE__ . __LINE__);
        }
        $content_id = intval($content_id);
        if ($content_id == 0) {
            return;
        }

        if ($menu_id != false) {
            $_REQUEST['add_content_to_menu'] = $menu_id;
        }


        $menus = MODULE_DB_MENUS;
        if (isset($_REQUEST['add_content_to_menu']) and is_array($_REQUEST['add_content_to_menu'])) {
            $add_to_menus = $_REQUEST['add_content_to_menu'];
            $add_to_menus_int = array();
            foreach ($add_to_menus as $value) {
                if ($value == 'remove_from_all') {
                    $sql = "DELETE FROM {$menus}
				WHERE
				item_type='menu_item'
				AND content_id={$content_id}
				";
                    //d($sql);
                    $this->app->cache->delete('menus');
                    $q = $this->app->db->q($sql);
                    return;
                }

                $value = intval($value);
                if ($value > 0) {
                    $add_to_menus_int[] = $value;
                }
            }

        }

        if (isset($add_to_menus_int) and is_array($add_to_menus_int)) {
            $add_to_menus_int_implode = implode(',', $add_to_menus_int);
            $sql = "DELETE FROM {$menus}
		WHERE parent_id NOT IN ($add_to_menus_int_implode)
		AND item_type='menu_item'
		AND content_id={$content_id}
		";

            $q = $this->app->db->q($sql);

            foreach ($add_to_menus_int as $value) {
                $check = $this->get_menu_items("limit=1&count=1&parent_id={$value}&content_id=$content_id");
                //d($check);
                if ($check == 0) {
                    $save = array();
                    $save['item_type'] = 'menu_item';
                    //	$save['debug'] = $menus;
                    $save['parent_id'] = $value;
                    $save['url'] = '';
                    $save['content_id'] = $content_id;
                    $this->app->db->save($menus, $save);
                }
            }
            $this->app->cache->delete('menus/global');
        }

    }


    public function _decode_entities($text)
    {
        $text = html_entity_decode($text, ENT_QUOTES, "ISO-8859-1"); #NOTE: UTF-8 does not work!
        $text = preg_replace('/&#(\d+);/me', "chr(\\1)", $text); #decimal notation
        $text = preg_replace('/&#x([a-f0-9]+);/mei', "chr(0x\\1)", $text); #hex notation
        return $text;
    }


// ------------------------------------------------------------------------

    /**
     * Create a Directory Map
     *
     *
     * Reads the specified directory and builds an array
     * representation of it.  Sub-folders contained with the
     * directory will be mapped as well.
     *
     * @author        ExpressionEngine Dev Team
     * @link        http://codeigniter.com/user_guide/helpers/directory_helper.html
     * @access    public
     * @param    string    path to source
     * @param    int        depth of directories to traverse (0 = fully recursive, 1 = current dir, etc)
     * @return    array
     */
    function directory_map($source_dir, $directory_depth = 0, $hidden = FALSE, $full_path = false)
    {
        if ($fp = @opendir($source_dir)) {
            $filedata = array();
            $new_depth = $directory_depth - 1;
            $source_dir = rtrim($source_dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

            while (FALSE !== ($file = readdir($fp))) {
                // Remove '.', '..', and hidden files [optional]
                if (!trim($file, '.') OR ($hidden == FALSE && $file[0] == '.')) {
                    continue;
                }

                if (($directory_depth < 1 OR $new_depth > 0) && @is_dir($source_dir . $file)) {
                    $filedata[$file] = $this->directory_map($source_dir . $file . DIRECTORY_SEPARATOR, $new_depth, $hidden, $full_path);
                } else {
                    if ($full_path == false) {
                        $filedata[] = $file;
                    } else {
                        $filedata[] = $source_dir . $file;
                    }

                }
            }

            closedir($fp);
            return $filedata;
        }

        return FALSE;
    }


    /**
     * Saves your custom language translation
     * @internal its used via ajax in the admin panel under Settings->Language
     * @package Language
     */
    function lang_file_save($data)
    {

        if (isset($_POST) and !empty($_POST)) {
            $data = $_POST;
        }
        if (is_admin() == true) {
            if (isset($data['unicode_temp_remove'])) {
                unset($data['unicode_temp_remove']);
            }


            $lang = current_lang();

            $cust_dir = $lang_file = MW_APP_PATH . 'functions' . DIRECTORY_SEPARATOR . 'language' . DIRECTORY_SEPARATOR . 'custom' . DIRECTORY_SEPARATOR;
            if (!is_dir($cust_dir)) {
                mkdir_recursive($cust_dir);
            }

            $language_content = $data;

            $lang_file = $cust_dir . $lang . '.php';

            if (is_array($language_content)) {
                $language_content = array_unique($language_content);

                $lang_file_str = '<?php ' . "\n";
                $lang_file_str .= ' $language=array();' . "\n";
                foreach ($language_content as $key => $value) {

                    $value = addslashes($value);
                    $lang_file_str .= '$language["' . $key . '"]' . "= '{$value}' ; \n";

                }
                $language_content_saved = 1;
                if (is_admin() == true) {
                    file_put_contents($lang_file, $lang_file_str);
                }
            }
            return array('success' => 'Language file [' . $lang . '] is updated');


        }


    }
}