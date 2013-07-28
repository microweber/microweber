<?php



class ContentUtils
{


    static function save_content($data, $delete_the_cache = true)
    {


        $adm = is_admin();
        $table = MW_DB_TABLE_CONTENT;
        $checks = mw_var('FORCE_SAVE_CONTENT');

        if ($checks != $table) {
            if ($adm == false) {
                return array('error' => 'You are not logged in as admin to save content!');
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

            $q = db_query($q);

            $thetitle = $q[0]['title'];

            $q = $q[0]['url'];

            $theurl = $q;

            $more_categories_to_delete = get_categories_for_content($data['id'], 'categories');
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
                $data['url'] = url_title($data['title']);


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
            $data['url'] = url_title($data_to_save['title']);
        }

        if (isset($data['url']) and $data['url'] != false) {

            if (trim($data['url']) == '') {

                $data['url'] = url_title($data['title']);
            }

            $date123 = date("YmdHis");

            $q = "SELECT id, url FROM $table WHERE url LIKE '{$data['url']}'";

            $q = db_query($q);

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
            $q = db_query($sql);
        }

        if (isset($data_to_save['subtype']) and strval($data_to_save['subtype']) == 'dynamic') {
            $check_ex = false;
            if (isset($data_to_save['subtype_value']) and trim($data_to_save['subtype_value']) != '' and intval(($data_to_save['subtype_value'])) > 0) {

                $check_ex = get_category_by_id(intval($data_to_save['subtype_value']));
            }
            if ($check_ex == false) {
                if (isset($data_to_save['id']) and intval(trim($data_to_save['id'])) > 0) {
                    $test2 = get_categories('data_type=category&rel=content&rel_id=' . intval(($data_to_save['id'])));

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

        /*
        if (isset($data_to_save['subtype_value_new']) and strval($data_to_save['subtype_value_new']) != '') {


            if ($data_to_save['subtype_value_new'] != '') {

                if ($adm == true) {

                    $new_category = array();
                    $new_category["data_type"] = "category";
                    $new_category["rel"] = "content";
                    $new_category["table" ] = $table_cats;
                    //$new_category["debug" ] = $table_cats;
                        if (isset($data_to_save['id']) and intval(($data_to_save['id'])) > 0) {
                        $new_category["rel_id"] = intval(($data_to_save['id']));
                    }
                    $new_category["title"] = $data_to_save['subtype_value_new'];
                    $new_category["parent_id"] = "0";
                    $cats_modified = true;
                    //@todo remove code here and around
                    //$new_category = save_category($new_category);

                    $data_to_save['subtype_value'] = $new_category;
                    $data_to_save['subtype'] = 'dynamic';
                }
            }

            if (isset($data_to_save['categories_categories_str']) and !empty($data_to_save['categories_categories_str'])) {
                $data_to_save['subtype_value_auto_create'] = $data_to_save['categories_categories_str'];

                if ($adm == true) {
                    if (!is_array($original_data['subtype_value_auto_create'])) {

                        $scats = explode(',', $data_to_save['subtype_value_auto_create']);
                    } else {

                        $scats = explode(',', $data_to_save['subtype_value_auto_create']);
                    }
                    if (!empty($scats)) {
                        foreach ($scats as $sc) {
                            $new_scategory = array();
                            $new_scategory["data_type"] = "category";
                            $new_scategory["title"] = $sc;
                            $new_scategory["rel"] = "content";
                    $new_scategory["table" ] = $table_cats;
                            $new_scategory["parent_id"] = intval($new_category);
                            $cats_modified = true;
                            //@todo remove code here and around
                        //	$new_scategory = save_category($new_scategory);
                        }
                    }
                }
            }
        }*/

        $par_page = false;
        if (isset($data_to_save['content_type']) and strval($data_to_save['content_type']) == 'post') {
            if (isset($data_to_save['parent']) and intval($data_to_save['parent']) > 0) {
                $par_page = get_content_by_id($data_to_save['parent']);
            }


            if (is_array($par_page)) {


                if ($par_page['subtype'] == 'static') {
                    $par_page_new = array();
                    $par_page_new['id'] = $par_page['id'];
                    $par_page_new['subtype'] = 'dynamic';

                    $par_page_new = save_data($table, $par_page_new);
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
            if (isset($data_to_save['categories']) and $par_page == false) {
                if (is_string($data_to_save['categories'])) {
                    $c1 = explode(',', $data_to_save['categories']);
                    if (isarr($c1)) {
                        foreach ($c1 as $item) {
                            $item = intval($item);
                            if ($item > 0) {
                                $cont_cat = get_content('limit=1&content_type=page&subtype=dynamic&subtype_value=' . $item);
                                //	d($cont_cat);
                                if (isset($cont_cat[0]) and isarr($cont_cat[0])) {
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
                $data_to_save['content'] = make_microweber_tags($data_to_save['content']);
            }
        }


        if (isset($data_to_save['id']) and intval($data_to_save['id']) == 0) {
            if (!isset($data_to_save['position']) or intval($data_to_save['position']) == 0) {

                $get_max_pos = "SELECT max(position) AS maxpos FROM $table  ";
                $get_max_pos = db_query($get_max_pos);
                if (isarr($get_max_pos) and isset($get_max_pos[0]['maxpos']))


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
        }


        if (isset($data_to_save['url']) and $data_to_save['url'] == site_url()) {
            unset($data_to_save['url']);
        }


        if (isset($data_to_save['debug'])) {

        }

        $save = save_data($table, $data_to_save);

        // cache_clean_group('content/global');
        //cache_clean_group('content/'.$save);
        if (isset($data_to_save['subtype']) and strval($data_to_save['subtype']) == 'dynamic') {
            $new_category = get_categories_for_content($save);

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
                $new_category = save_category($new_category);


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


        db_q($clean);
        cache_clean_group('custom_fields');

        $media_table = MW_TABLE_PREFIX . 'media';

        $clean = " UPDATE $media_table SET

	rel_id =\"{$id}\"
	WHERE
	session_id =\"{$sid}\"
	AND rel =\"content\" AND (rel_id=0 OR rel_id IS NULL)

	";


        cache_clean_group('media/global');

        db_q($clean);

        if (isset($data_to_save['parent']) and intval($data_to_save['parent']) != 0) {
            cache_clean_group('content' . DIRECTORY_SEPARATOR . intval($data_to_save['parent']));
        }
        if (isset($data_to_save['id']) and intval($data_to_save['id']) != 0) {
            cache_clean_group('content' . DIRECTORY_SEPARATOR . intval($data_to_save['id']));
        }
        cache_clean_group('content' . DIRECTORY_SEPARATOR . 'global');
        cache_clean_group('content' . DIRECTORY_SEPARATOR . '0');
        cache_clean_group('content_fields/global');

        if ($cats_modified != false) {

            cache_clean_group('categories/global');
            cache_clean_group('categories_items/global');
            if (isset($c1) and isarr($c1)) {
                foreach ($c1 as $item) {
                    $item = intval($item);
                    if ($item > 0) {
                        cache_clean_group('categories/' . $item);
                    }
                }
            }
        }

        exec_action('mw_save_content');
        //session_write_close();
        return $save;

    }


    static function save_edit($post_data)
    {
        $id = is_admin();
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

        $ustr2 = url_string(1, 1);

        if (isset($ustr2) and trim($ustr2) == 'favicon.ico') {
            return false;
        }
        $ref_page = $ref_page_url = $_SERVER['HTTP_REFERER'];
        if ($ref_page != '') {
            // $ref_page = $the_ref_page = get_content_by_url($ref_page_url);
            $ref_page2 = $ref_page = get_content_by_url($ref_page_url, true);


            if ($ref_page2 == false) {

                $ustr = url_string(1);

                if (is_module_installed($ustr)) {
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


                $guess_page_data = new \mw\Controller();
                $guess_page_data->page_url = $ref_page_url;
                $guess_page_data->return_data = true;
                $guess_page_data->create_new_page = true;
                $pd = $guess_page_data->index();

                if (isarr($pd) and (isset($pd["active_site_template"]) or isset($pd["layout_file"]))) {
                    $save_page = $pd;
                    $save_page['url'] = url_string(1);
                    $save_page['title'] = url_title(url_string(1));
                    $page_id = save_content($save_page);
                }
                //

                // d($ref_page_url);
            } else {
                $page_id = $ref_page['id'];
                $ref_page['custom_fields'] = get_custom_fields_for_content($page_id, false);
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


                $url = url_string(true);
                $some_mods = array();
                if (isset($the_field_data) and isarr($the_field_data) and isset($the_field_data['attributes'])) {
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

                            $inh = get_content_inherited_parent($page_id);
                            if ($inh != false) {
                                $content_id_for_con_field = $content_id = $inh;

                            }

                        }


                        $save_layout = false;
                        if ($inh == false and !isset($content_id_for_con_field)) {

                            if (isarr($ref_page) and isset($ref_page['parent']) and  isset($ref_page['content_type'])  and $ref_page['content_type'] == 'post') {
                                $content_id_for_con_field = intval($ref_page['parent']);
                                // d($content_id);
                            } else {
                                $content_id_for_con_field = intval($ref_page['id']);

                            }
                        }
                        $html_to_save = $the_field_data['html'];
                        $html_to_save = $content = make_microweber_tags($html_to_save);
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

                                // $to_save['debug'] = $content_id;

                                //$to_save['page_element_id'] = $page_element_id;

                                $is_native_fld = db_get_table_fields('content');
                                if (in_array($field, $is_native_fld)) {
                                    $to_save[$field] = ($html_to_save);
                                } else {

                                    //$to_save['custom_fields'][$field] = ($html_to_save);
                                }


                                if ($is_no_save != true and $is_draft == false) {
                                    $json_print[] = $to_save;
                                    //d($to_save);
                                    $saved = save_content($to_save);


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


                            $cont_field['value'] = make_microweber_tags($html_to_save);
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
                                $cont_field['url'] = url_string(true);
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
            cache_clean_group('options');
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
        //cache_clean_group('global/blocks');
        exit();
    }

    static function save_content_field($data, $delete_the_cache = true)
    {

        $adm = is_admin();
        $table = MW_DB_TABLE_CONTENT_FIELDS;
        $table_drafts = MW_DB_TABLE_CONTENT_FIELDS_DRAFTS;

        //$checks = mw_var('FORCE_SAVE_CONTENT');


        if ($adm == false) {
            error('Error: not logged in as admin.' . __FILE__ . __LINE__);
        }

        if (!is_array($data)) {
            $data = array();
        }

        if (isset($data['is_draft'])) {
            $table = $table_drafts;


        }
        if (isset($data['is_draft']) and isset($data['url'])) {
            $fld_remove = db_escape_string($data['url']);

            $history_files = get_content_field('order_by=id desc&fields=id&is_draft=1&all=1&limit=50&curent_page=3&url=' . $fld_remove);
            if (isarr($history_files)) {
                $history_files_ids = array_values_recursive($history_files);
            }
            if (isset($history_files_ids) and isarr($history_files_ids)) {
                $history_files_ids_impopl = implode(',', $history_files_ids);
                $del_q = "DELETE FROM {$table} WHERE id IN ($history_files_ids_impopl) ";
                db_q($del_q);
            }
            //d($history_files_ids);


//d($del_q );
            //	db_q($del_q);
        }


        if (!isset($data['rel']) or !isset($data['rel_id'])) {
            error('Error: ' . __FUNCTION__ . ' rel and rel_id is required');
        }
        //if($data['rel'] == 'global'){
        if (isset($data['field']) and !isset($data['is_draft'])) {
            $fld = db_escape_string($data['field']);
            $fld_rel = db_escape_string($data['rel']);
            $del_q = "DELETE FROM {$table} WHERE rel='$fld_rel' AND  field='$fld' ";
            if (isset($data['rel_id'])) {
                $i = db_escape_string($data['rel_id']);
                $del_q .= " and  rel_id='$i' ";

            } else {
                $data['rel_id'] = 0;
            }
            $cache_group = guess_cache_group('content_fields/' . $data['rel'] . '/' . $data['rel_id']);
            db_q($del_q);
            //cache_clean_group($cache_group);

            //cache_clean_group('content_fields/global');

        }
        if (isset($data['rel']) or isset($data['rel_id'])) {
            $cache_group = guess_cache_group('content_fields/' . $data['rel'] . '/' . $data['rel_id']);
            cache_clean_group($cache_group);
            cache_clean_group('content_fields/global');
        }

        //}

        $save = save_data($table, $data);


        return $save;


    }

    static function delete($data)
    {

        $adm = is_admin();
        if ($adm == false) {
            error('Error: not logged in as admin.' . __FILE__ . __LINE__);
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
                db_delete_by_id('content', $c_id);
            }
        }

        if (isset($data['ids']) and isarr($data['ids'])) {
            foreach ($data['ids'] as $value) {
                $c_id = intval($value);
                $del_ids[] = $c_id;
                if ($to_trash == false) {
                    db_delete_by_id('content', $c_id);
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
                    $q = db_query($q);
                    $q = "UPDATE $table SET is_deleted='n' WHERE parent=$c_id   AND  is_deleted='y' ";
                    $q = db_query($q);
                    if (defined("MW_DB_TABLE_TAXONOMY")) {
                        $table1 = MW_DB_TABLE_TAXONOMY;
                        $q = "UPDATE $table1 SET is_deleted='n' WHERE rel_id=$c_id  AND  rel='content' AND  is_deleted='y' ";
                        $q = db_query($q);
                    }

                } else if ($to_trash == false) {
                    $q = "UPDATE $table SET parent=0 WHERE parent=$c_id ";
                    $q = db_query($q);

                    db_delete_by_id('menus', $c_id, 'content_id');

                    if (defined("MW_DB_TABLE_MEDIA")) {
                        $table1 = MW_DB_TABLE_MEDIA;
                        $q = "DELETE FROM $table1 WHERE rel_id=$c_id  AND  rel='content'  ";
                        $q = db_query($q);
                    }

                    if (defined("MW_DB_TABLE_TAXONOMY")) {
                        $table1 = MW_DB_TABLE_TAXONOMY;
                        $q = "DELETE FROM $table1 WHERE rel_id=$c_id  AND  rel='content'  ";
                        $q = db_query($q);
                    }


                    if (defined("MW_DB_TABLE_TAXONOMY_ITEMS")) {
                        $table1 = MW_DB_TABLE_TAXONOMY_ITEMS;
                        $q = "DELETE FROM $table1 WHERE rel_id=$c_id  AND  rel='content'  ";
                        $q = db_query($q);
                    }


                    if (defined("MW_DB_TABLE_CUSTOM_FIELDS")) {
                        $table1 = MW_DB_TABLE_CUSTOM_FIELDS;
                        $q = "DELETE FROM $table1 WHERE rel_id=$c_id  AND  rel='content'  ";
                        $q = db_query($q);
                    }


                } else {
                    $q = "UPDATE $table SET is_deleted='y' WHERE id=$c_id ";

                    $q = db_query($q);
                    $q = "UPDATE $table SET is_deleted='y' WHERE parent=$c_id ";
                    $q = db_query($q);
                    if (defined("MW_DB_TABLE_TAXONOMY")) {
                        $table1 = MW_DB_TABLE_TAXONOMY;
                        $q = "UPDATE $table1 SET is_deleted='y' WHERE rel_id=$c_id  AND  rel='content' AND  is_deleted='n' ";

                        $q = db_query($q);
                    }


                }


                cache_clean_group('content/' . $c_id);
            }

            cache_clean_group('content');
            cache_clean_group('categories/global');


        }
        return ($del_ids);
    }




    static function get_parents($id = 0, $without_main_parrent = false, $data_type = 'category')
    {
        return \mw\Content::get_parents($id , $without_main_parrent, $data_type);

    }


    static function edit_field_draft($data)
    {
        only_admin_access();

        $page = false;
        if (isset($_SERVER["HTTP_REFERER"])) {
            $url = $_SERVER["HTTP_REFERER"];
            $url = explode('?', $url);
            $url = $url[0];

            if (trim($url) == '' or trim($url) == site_url()) {
                //$page = get_content_by_url($url);
                $page = get_homepage();
                // var_dump($page);
            } else {

                $page = get_content_by_url($url);
            }
        } else {
            $url = url_string();
        }

        define_constants($page);


        $table_drafts = MW_DB_TABLE_CONTENT_FIELDS_DRAFTS;

        $data = parse_params($data);
        $data['is_draft'] = 1;
        $data['full'] = 1;


        $ret = get_content_field($data);

        if (isset($ret['value'])) {
            $field_content = htmlspecialchars_decode($ret['value']);
            $field_content = decode_entities($field_content);
            $ret['value'] = parse_micrwober_tags($field_content, $options = false);

        }


        return $ret;


    }


    static function reorder($params)
    {
        $id = is_admin();
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
        $ids_implode = db_escape_string($ids_implode);


        $table = MW_TABLE_PREFIX . 'content';
        $maxpos = 0;
        $get_max_pos = "SELECT max(position) AS maxpos FROM $table  WHERE id IN ($ids_implode) ";
        $get_max_pos = db_query($get_max_pos);
        if (isarr($get_max_pos) and isset($get_max_pos[0]['maxpos'])) {

            $maxpos = intval($get_max_pos[0]['maxpos']) + 1;

        }

        // $q = " SELECT id, created_on, position from $table where id IN ($ids_implode)  order by position desc  ";
        // $q = db_query($q);
        // $max_date = $q[0]['created_on'];
        // $max_date_str = strtotime($max_date);
        $i = 1;
        foreach ($ids as $id) {
            $id = intval($id);
            cache_clean_group('content/' . $id);
            //$max_date_str = $max_date_str - $i;
            //	$nw_date = date('Y-m-d H:i:s', $max_date_str);
            //$q = " UPDATE $table set created_on='$nw_date' where id = '$id'    ";
            $pox = $maxpos - $i;
            $q = " UPDATE $table SET position=$pox WHERE id=$id   ";
            //    var_dump($q);
            $q = db_q($q);
            $i++;
        }
        //
        // var_dump($q);
        cache_clean_group('content/global');
        cache_clean_group('categories/global');
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
     * @uses save_content()
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
    static function set_unpublished($params)
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

                $save_data = save_content($save);
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
     * @uses save_content()
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
    static function set_published($params)
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

                $save_data = save_content($save);
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
                $is_shop = get_content('content_type=page&is_shop=y');
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
                    $find_layout = layouts_list();
                    if (isarr($find_layout)) {
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
                    $new_shop = save_data('content', $add_page);
                    cache_clean_group('content');
                    cache_clean_group('categories');
                    cache_clean_group('custom_fields');

                    //
                } else {

                    if (isset($is_shop[0])) {
                        $new_shop = $is_shop[0]['id'];
                    }
                }

                $posts = get_content('content_type=post&parent=' . $new_shop);
                if ($posts == false and $new_shop != false) {
                    $add_page = array();
                    $add_page['id'] = 0;
                    $add_page['parent'] = $new_shop;
                    $add_page['title'] = "My product";
                    $add_page['url'] = "my-product";
                    $add_page['content_type'] = "post";
                    $add_page['subtype'] = "product";

                    //$new_shop = save_content($add_page);
                    //cache_clean_group('content');
                    //clearcache();
                }


                break;


            case 'blog' :
                $is_shop = get_content('is_deleted=n&content_type=page&subtype=dynamic&is_shop=n&limit=1');
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
                    $find_layout = layouts_list();
                    if (isarr($find_layout)) {
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
                    $new_shop = save_data('content', $add_page);
                    cache_clean_group('content');
                    cache_clean_group('categories');
                    cache_clean_group('content_fields');


                    //
                } else {

                    if (isset($is_shop[0])) {
                        $new_shop = $is_shop[0]['id'];
                    }
                }


                break;

            case 'default' :
            case 'install' :
                $any = get_content('count=1&content_type=page&limit=1');
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
                    $new_shop = save_content($add_page);
                }

                break;

            default :
                break;
        }
    }


    static function menu_create($data_to_save)
    {
        $params2 = array();
        if ($data_to_save == false) {
            $data_to_save = array();
        }
        if (is_string($data_to_save)) {
            $params = parse_str($data_to_save, $params2);
            $data_to_save = $params2;
        }

        $id = is_admin();
        if ($id == false) {
            //error('Error: not logged in as admin.'.__FILE__.__LINE__);
        } else {

            if (isset($data_to_save['menu_id'])) {
                $data_to_save['id'] = intval($data_to_save['menu_id']);
            }
            $table = MODULE_DB_MENUS;

            $data_to_save['table'] = $table;
            $data_to_save['item_type'] = 'menu';

            $save = save_data($table, $data_to_save);

            cache_clean_group('menus/global');

            return $save;
        }

    }


    static function menu_delete($id = false)
    {
        $params = parse_params($id);


        $is_admin = is_admin();
        if ($is_admin == false) {
            error('Error: not logged in as admin.' . __FILE__ . __LINE__);
        }


        if (!isset($params['id'])) {
            error('Error: id param is required.');
        }

        $id = $params['id'];

        $id = db_escape_string($id);
        $id = htmlspecialchars_decode($id);
        $table = MODULE_DB_MENUS;

        db_delete_by_id($table, trim($id), $field_name = 'id');

        cache_clean_group('menus/global');

        return true;

    }

    static function menu_item_get($id)
    {

        $is_admin = is_admin();
        if ($is_admin == false) {
            error('Error: not logged in as admin.' . __FILE__ . __LINE__);
        }
        $id = intval($id);

        $table = MODULE_DB_MENUS;

        return get("one=1&limit=1&table=$table&id=$id");

    }


    static function  menu_item_save($data_to_save)
    {

        $id = is_admin();
        if ($id == false) {
            error('Error: not logged in as admin.' . __FILE__ . __LINE__);
        }

        if (isset($data_to_save['menu_id'])) {
            $data_to_save['id'] = intval($data_to_save['menu_id']);
            cache_clean_group('menus/' . $data_to_save['id']);

        }

        if (!isset($data_to_save['id']) and isset($data_to_save['link_id'])) {
            $data_to_save['id'] = intval($data_to_save['link_id']);
        }

        if (isset($data_to_save['id'])) {
            $data_to_save['id'] = intval($data_to_save['id']);
            cache_clean_group('menus/' . $data_to_save['id']);
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
            cache_clean_group('menus/' . $data_to_save['parent_id']);
        }

        $table = MODULE_DB_MENUS;

        $data_to_save['table'] = $table;
        $data_to_save['item_type'] = 'menu_item';
        // d($data_to_save);
        $save = save_data($table, $data_to_save);

        cache_clean_group('menus/global');

        return $save;

    }

    static function menu_item_delete($id)
    {

        $is_admin = is_admin();
        if ($is_admin == false) {
            error('Error: not logged in as admin.' . __FILE__ . __LINE__);
        }

        $table = MODULE_DB_MENUS;

        db_delete_by_id($table, intval($id), $field_name = 'id');

        cache_clean_group('menus/global');

        return true;

    }



    static function menu_items_reorder($data)
    {

        $adm = is_admin();
        if ($adm == false) {
            error('Error: not logged in as admin.' . __FILE__ . __LINE__);
        }
        $table = MODULE_DB_MENUS;

        if (isset($data['ids_parents'])) {
            $value = $data['ids_parents'];
            if (is_arr($value)) {

                foreach ($value as $value2 => $k) {
                    $k = intval($k);
                    $value2 = intval($value2);

                    $sql = "UPDATE $table SET
				parent_id=$k
				WHERE id=$value2 AND id!=$k
				AND item_type='menu_item'
				";
                    // d($sql);
                    $q = db_q($sql);
                    cache_clean_group('menus/' . $k);
                }

            }
        }

        if (isset($data['ids'])) {
            $value = $data['ids'];
            if (is_arr($value)) {
                $indx = array();
                $i = 0;
                foreach ($value as $value2) {
                    $indx[$i] = $value2;
                    $i++;
                }

                db_update_position($table, $indx);
                return true;
                // d($indx);
            }
        }

    }


    static function is_in_menu($menu_id = false, $content_id = false)
    {
        if ($menu_id == false or $content_id == false) {
            return false;
        }

        $menu_id = intval($menu_id);
        $content_id = intval($content_id);
        $check = get_menu_items("limit=1&count=1&parent_id={$menu_id}&content_id=$content_id");
        $check = intval($check);
        if ($check > 0) {
            return true;
        } else {
            return false;
        }
    }


    static function add_content_to_menu($content_id, $menu_id=false)
    {
        $id = is_admin();
        if ($id == false) {
            error('Error: not logged in as admin.' . __FILE__ . __LINE__);
        }
        $content_id = intval($content_id);
        if ($content_id == 0) {
            return;
        }

        if($menu_id != false){
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
                    cache_clean_group('menus');
                    $q = db_q($sql);
                    return;
                }

                $value = intval($value);
                if ($value > 0) {
                    $add_to_menus_int[] = $value;
                }
            }

        }

        if (isset($add_to_menus_int) and isarr($add_to_menus_int)) {
            $add_to_menus_int_implode = implode(',', $add_to_menus_int);
            $sql = "DELETE FROM {$menus}
		WHERE parent_id NOT IN ($add_to_menus_int_implode)
		AND item_type='menu_item'
		AND content_id={$content_id}
		";

            $q = db_q($sql);

            foreach ($add_to_menus_int as $value) {
                $check = get_menu_items("limit=1&count=1&parent_id={$value}&content_id=$content_id");
                //d($check);
                if ($check == 0) {
                    $save = array();
                    $save['item_type'] = 'menu_item';
                    //	$save['debug'] = $menus;
                    $save['parent_id'] = $value;
                    $save['url'] = '';
                    $save['content_id'] = $content_id;
                    save_data($menus, $save);
                }
            }
            cache_clean_group('menus/global');
        }

    }


}