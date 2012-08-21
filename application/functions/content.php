<?php

function define_constants($content = false) {
    $page_data = false;
    if (is_array($content)) {
        if (isset($content['id'])) {

            $page = $content;
        }
    } else {

    }

    if (isset($page)) {
        if ($page['content_type'] == "post") {
            $content = $page;

            $page = get_content_by_id($page['content_parent']);
            if (defined('POST_ID') == false) {
                define('POST_ID', $content['id']);
            }
        } else {
            $content = $page;
            if (defined('POST_ID') == false) {
                define('POST_ID', false);
            }
        }

        if (defined('ACTIVE_PAGE_ID') == false) {

            define('ACTIVE_PAGE_ID', $page['id']);
        }

        if (defined('CATEGORY_ID') == false) {
            define('CATEGORY_ID', false);
        }

        if (defined('CONTENT_ID') == false) {
            define('CONTENT_ID', $content['id']);
        }

        if (defined('PAGE_ID') == false) {
            define('PAGE_ID', $page['id']);
        }

        if (defined('MAIN_PAGE_ID') == false) {
            define('MAIN_PAGE_ID', $page['content_parent']);
        }
    }







    if (defined('ACTIVE_PAGE_ID') == false) {

        define('ACTIVE_PAGE_ID', false);
    }

    if (defined('CATEGORY_ID') == false) {
        define('CATEGORY_ID', false);
    }

    if (defined('CONTENT_ID') == false) {
        define('CONTENT_ID', false);
    }

    if (defined('POST_ID') == false) {
        define('POST_ID', false);
    }
    if (defined('PAGE_ID') == false) {
        define('PAGE_ID', false);
    }

    if (defined('MAIN_PAGE_ID') == false) {
        define('MAIN_PAGE_ID', false);
    }





























    if (isset($page) and ($page['active_site_template']) != '' and strtolower($page['active_site_template']) != 'default') {
        $the_active_site_template = $page['active_site_template'];
    } else {
        $the_active_site_template = get_option('curent_template');
    }
    $the_active_site_template_dir = normalize_path(TEMPLATEFILES . $the_active_site_template . '/');

    if (defined('ACTIVE_TEMPLATE_DIR') == false) {

        define('ACTIVE_TEMPLATE_DIR', $the_active_site_template_dir);
    }

    if (defined('TEMPLATE_DIR') == false) {

        define('TEMPLATE_DIR', $the_active_site_template_dir);
    }

    $the_template_url = site_url('userfiles/' . TEMPLATEFILES_DIRNAME . '/' . $the_active_site_template);
    // d($the_template_url);
    $the_template_url = $the_template_url . '/';
    if (defined('TEMPLATE_URL') == false) {
        define("TEMPLATE_URL", $the_template_url);
    }


    if (defined('LAYOUTS_DIR') == false) {

        $layouts_dir = TEMPLATE_DIR . 'layouts/';

        define("LAYOUTS_DIR", $layouts_dir);
    } else {

        $layouts_dir = LAYOUTS_DIR;
    }

    if (defined('LAYOUTS_URL') == false) {

        $layouts_url = reduce_double_slashes(dirToURL($layouts_dir) . '/');

        define("LAYOUTS_URL", $layouts_url);
    } else {

        $layouts_url = LAYOUTS_URL;
    }

    // d ( $the_active_site_template_dir );

    return true;
}

function get_layout_for_page($page = array()) {
    $render_file = false;

    if (trim($page['content_layout_name']) != '') {
        $template_view = ACTIVE_TEMPLATE_DIR . 'layouts' . DS . $page['content_layout_name'] . DS . 'index.php';
        // d($template_view);
        if (is_file($template_view) == true) {
            $render_file = $template_view;
        }
    }

    if ($render_file == false and strtolower($page['active_site_template']) == 'default') {
        $template_view = ACTIVE_TEMPLATE_DIR . 'index.php';
        if (is_file($template_view) == true) {
            $render_file = $template_view;
        }
    }

    if ($render_file == false and strtolower($page['active_site_template']) == 'default') {
        $template_view = ACTIVE_TEMPLATE_DIR . 'index.php';
        if (is_file($template_view) == true) {
            $render_file = $template_view;
        }
    }

    if ($render_file == false and ($page['content_layout_name']) == false and ($page['content_layout_style']) == false) {
        $template_view = ACTIVE_TEMPLATE_DIR . 'index.php';
        if (is_file($template_view) == true) {
            $render_file = $template_view;
        }
    }

    if ($render_file == false and ($page['content_layout_file']) != false) {
        $template_view = ACTIVE_TEMPLATE_DIR . 'layouts' . DS . $page['content_layout_file'];
        // d($template_view);
        if (is_file($template_view) == true) {
            $render_file = $template_view;
        }
    }




    return $render_file;
}

function get_homepage() {
    $table = c('db_tables');
    // ->'table_content';
    $table = $table['table_content'];

    $sql = "SELECT * from $table where is_home='y'  order by updated_on desc limit 0,1 ";

    $q = db_query($sql, __FUNCTION__ . crc32($sql), 'content/global');
    // var_dump($q);
    $result = $q;

    $content = $result[0];

    return $content;
}

function get_content_by_url($url = '', $no_recursive = false) {
    return get_page_by_url($url, $no_recursive);
}

function get_page_by_url($url = '', $no_recursive = false) {
    if (strval($url) == '') {

        $url = url_string();
    }

    $table = c('db_tables');
    // ->'table_content';
    $table = $table['table_content'];

    $url = strtolower($url);
    $url = string_clean($url);
    $url = addslashes($url);
    $sql = "SELECT id,content_url from $table where content_url='{$url}' or content_title LIKE '{$url}'   order by updated_on desc limit 0,1 ";

    $q = db_query($sql, __FUNCTION__ . crc32($sql), 'content/global');

    $result = $q;

    $content = $result[0];

    if (!empty($content)) {

        $get_by_id = get_content_by_id($content['id']);

        return $get_by_id;
    }

    if ($no_recursive == false) {

        if (empty($content) == true) {

            // /var_dump ( $url );

            $segs = explode('/', $url);

            $segs_qty = count($segs);

            for ($counter = 0; $counter <= $segs_qty; $counter += 1) {

                $test = array_slice($segs, 0, $segs_qty - $counter);

                $test = array_reverse($test);

                if (isset($test[0])) {
                    $url = get_page_by_url($test[0], true);
                }
                if (!empty($url)) {

                    return $url;
                }
            }
        }
    } else {
        $content['id'] = ((int) $content['id']);
        $get_by_id = get_content_by_id($content['id']);

        return $get_by_id;
    }
}

/**
 * Function to get single content item by id from the content_table
 *
 * @param
 *        	int
 * @return array
 * @author Peter Ivanov
 *
 */
function get_content_by_id($id) {
    $table = c('db_tables');
    // ->'table_content';
    $table = $table['table_content'];

    $id = intval($id);
    $q = "SELECT * from $table where id='$id'  limit 0,1 ";

    $q = db_query($q, __FUNCTION__ . crc32($q), 'content/' . $id);
    $content = $q[0];

    return $content;
}

function get_page($id = false) {
    if ($id == false) {
        return false;
    }

    // $CI = get_instance ();
    if (intval($id) != 0) {
        $page = get_content_by_id($id);

        if (empty($page)) {
            $page = get_content_by_url($id);
        }
    } else {
        if (empty($page)) {
            $page = array();
            $page['content_layout_name'] = trim($id);

            $page = get_pages($page);
            $page = $page[0];
        }
    }

    return $page;

    // $link = get_instance()->content_model->getContentURLByIdAndCache (
    // $link['id'] );
}

api_expose('get_content_admin');

function get_content_admin($params) {
    if (is_admin() == false) {
        return false;
    }

    return get_content($params);
}

/**
 * Function to get single content item by id from the content_table
 *
 * @param
 *        	array
 * @return array
 * @author Peter Ivanov
 *
 */
function get_content($params) {
    $params2 = array();

    if (is_string($params)) {
        $params = parse_str($params, $params2);
        $params = $params2;
    }

    $function_cache_id = false;

    $args = func_get_args();

    foreach ($args as $k => $v) {

        $function_cache_id = $function_cache_id . serialize($k) . serialize($v);
    }

    $function_cache_id = __FUNCTION__ . crc32($function_cache_id);

    $cache_content = cache_get_content($function_cache_id, $cache_group = 'content');
    if (($cache_content) == '--false--') {
        return false;
    }
    // $cache_content = false;
    if (($cache_content) != false) {

        return $cache_content;
    } else {

        $table = c('db_tables');
        $table = $table['table_content'];
        // $params['orderby'];
        if (isset($params['orderby'])) {
            $orderby = $params['orderby'];
        }
        if (isset($orderby) == false) {
            $orderby = array();
            $orderby[0] = 'created_on';

            $orderby[1] = 'DESC';
        }

        if (isset($params['limit'])) {
            $limit = $params['limit'];
        } else {
            $limit = array();
            $limit[0] = '0';

            $limit[1] = '30';
        }
        $params['limit'] = $limit;



        $get = db_get($table, $params, $cache_group = 'content');

        if (!empty($get)) {
            $data2 = array();
            foreach ($get as $item) {
                if (isset($item['content_url'])) {
                    //$item['url'] = page_link($item['id']);
                    $item['url'] = site_url($item['content_url']);
                }
                $data2[] = $item;
            }
            $get = $data2;
            cache_store_data($get, $function_cache_id, $cache_group = 'content');

            return $get;
        } else {
            cache_store_data('--false--', $function_cache_id, $cache_group = 'content');

            return FALSE;
        }
    }
}

function post_link($id = false) {
    if (is_string($id)) {
        // $link = page_link_to_layout ( $id );
    }
    if ($id == false) {
        if (defined('PAGE_ID') == true) {
            $id = PAGE_ID;
        }
    }

    $link = get_content_by_id($id);
    if (strval($link) == '') {
        $link = get_page_by_url($id);
    }
    $link = site_url($link['content_url']);
    return $link;
}

function page_link($id = false) {
    if (is_string($id)) {
        // $link = page_link_to_layout ( $id );
    }
    if ($id == false) {
        if (defined('PAGE_ID') == true) {
            $id = PAGE_ID;
        }
    }

    $link = get_content_by_id($id);
    if (strval($link) == '') {
        $link = get_page_by_url($id);
    }
    $link = site_url($link['content_url']);
    return $link;
}

/**
 * get_posts
 *
 * get_posts is used to get content by parameters
 *
 * @category posts
 *
 *
 */
function get_posts($params = false) {
    $params2 = array();

    if (is_string($params)) {
        $params = parse_str($params, $params2);
        $params = $params2;
    }
    return get_content($params);
}

function paging_links($base_url = false, $pages_count, $paging_param = 'curent_page', $keyword_param = 'keyword') {

    // getCurentURL()
    if ($base_url == false) {
        if (PAGE_ID != false and CATEGORY_ID == false) {
            $base_url = page_link(PAGE_ID);

            // p($base_url);
        } elseif (PAGE_ID != false and CATEGORY_ID != false) {
            $base_url = category_link(CATEGORY_ID);
        } else {
            $base_url = url_string();
        }
    }

    // print $base_url;

    $page_links = array();

    $the_url = parse_url($base_url, PHP_URL_QUERY);

    $the_url = $base_url;

    $the_url = explode('/', $the_url);

    // var_dump ( $the_url );

    for ($x = 1; $x <= $pages_count; $x++) {

        $new_url = array();

        $new = array();

        foreach ($the_url as $itm) {

            $itm = explode(':', $itm);

            if ($itm[0] == $paging_param) {

                $itm[1] = $x;
            }

            $new[] = implode(':', $itm);
        }

        $new_url = implode('/', $new);

        // var_dump ( $new_url);

        $page_links[$x] = $new_url;
    }

    for ($x = 1; $x <= count($page_links); $x++) {

        if (stristr($page_links[$x], $paging_param . ':') == false) {

            $page_links[$x] = reduce_double_slashes($page_links[$x] . '/' . $paging_param . ':' . $x);
        }
    }

    return $page_links;
}

/**
 * paging
 *
 * paging
 *
 * @access public
 * @category posts
 * @author Microweber
 * @link
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 * @param $display =
 *        	'default' //sets the default paging display with <ul> and </li>
 *        	tags. If $display = false, the function will return the paging
 *        	array which is the same as $posts_pages_links in every template
 *
 *
 */
function paging($display = 'default', $data = false) {
    print "paging()";
    return true;

    // $CI = get_instance ();
    if ($display) {
        $list_file = $display;
        $display = strtolower($display);
    }
    if ($data == false) {
        $posts_pages_links = get_instance()->template['posts_pages_links'];
    } else {
        $posts_pages_links = $data;
    }

    if ($posts_pages_curent_page == false) {

        $posts_pages_curent_page = url_param('curent_page');
    }

    if ($posts_pages_curent_page == false) {

        $posts_pages_curent_page = 1;
    }

    //
    switch ($display) {
        case 'default' :
        case 'ul' :
        case 'uls' :
            get_instance()->template['posts_pages_links'] = $posts_pages_links;
            get_instance()->template['posts_pages_curent_page'] = $posts_pages_curent_page;
            get_instance()->load->vars(get_instance()->template);

            // p(RESOURCES_DIR . 'blocks/nav/default.php');

            $content_filename = get_instance()->load->file(RESOURCES_DIR . 'blocks/nav/nav_default.php', true);
            print($content_filename);
            break;

        case 'divs' :
            get_instance()->template['posts_pages_links'] = $posts_pages_links;
            get_instance()->template['posts_pages_curent_page'] = $posts_pages_curent_page;
            get_instance()->load->vars(get_instance()->template);

            $content_filename = get_instance()->load->file(RESOURCES_DIR . 'blocks/nav/nav_divs.php', true);

            print($content_filename);
            break;

        case false :
        default :
            return $posts_pages_links;
            break;
    }
}

/**
 * cf_val
 *
 * Returns custom field value
 *
 * @return string or array
 * @author Peter Ivanov
 */
function custom_field_value($content_id, $field_name, $use_vals_array = true) {
    $fields = get_custom_fields_for_content($content_id);
    if (empty($fields)) {
        return false;
    }
    // p($fields);
    foreach ($fields as $field) {
        if ((strtolower($field_name)) == strtolower($field['custom_field_name'])) {

            if (!empty($field['custom_field_values']) and $use_vals_array == true) {
                return $field['custom_field_values'];
            }

            if ($field['custom_field_value'] != 'Array' and $field['custom_field_value'] != '') {
                return $field['custom_field_value'];
            } else {

                if ($field['custom_field_values']) {
                    return $field['custom_field_values'];
                }
            }

            // p ( $field );
        }
    }
}

function get_custom_fields_for_content($content_id, $full = true) {
    $more = false;
    $more = get_custom_fields('table_content', $content_id, $full);
    return $more;
}

function get_custom_fields($table, $id = 0, $return_full = false, $field_for = false, $debug = false) {

    // $id = intval ( $id );
    if ($id == 0) {

        // return false;
    }
    $table_assoc_name = false;
    if ($table != false) {
        $table_assoc_name = db_get_table_name($table);
    } else {

        $table_assoc_name = "MW_ANY_TABLE";
    }


    if ((int) $table_assoc_name == 0) {
        $table_assoc_name = guess_table_name($table);
    }




    $table_custom_field = c('db_tables');
    // ->'table_custom_fields';
    $table_custom_field = $table_custom_field['table_custom_fields'];

    $the_data_with_custom_field__stuff = array();

    if (strval($table_assoc_name) != '') {

        if ($field_for != false) {
            $field_for = trim($field_for);
            $field_for_q = " and  (field_for='{$field_for} OR custom_field_name='{$field_for}')'";
        } else {
            $field_for_q = " ";
        }

        if ($table_assoc_name == 'MW_ANY_TABLE') {

            $qt = '';
        } else {
            $qt = "to_table = '{$table_assoc_name}' and";
        }

        if ($return_full == true) {

            $select_what = '*';
        } else {
            $select_what = '*';
        }

        $q = " SELECT
		{$select_what} from  $table_custom_field where
		{$qt}
		to_table_id='{$id}'
		$field_for_q
		order by field_order asc  ";

        if ($debug != false) {
            d($q);
        }
        // d($q);
        // $crc = crc32 ( $q );

        $crc = abs(crc32($q));
        if ($crc & 0x80000000) {
            $crc^=0xffffffff;
            $crc += 1;
        }

        $cache_id = __FUNCTION__ . '_' . $crc;

        $q = db_query($q, $cache_id, 'custom_fields/' . $table);

        if (!empty($q)) {

            if ($return_full == true) {
                $to_ret = array();
                foreach ($q as $it) {

                    // $it ['value'] = $it ['custom_field_value'];
                    $it['value'] = $it['custom_field_value'];
                    $it['values'] = $it['custom_field_value'];

                    $it['cssClass'] = $it['custom_field_type'];
                    $it['type'] = $it['custom_field_type'];

                    $it['baseline'] = "undefined";

                    $it['title'] = $it['custom_field_name'];
                    $it['required'] = $it['custom_field_required'];

                    $to_ret[] = $it;
                }
                return $to_ret;
            }

            $append_this = array();

            foreach ($q as $q2) {

                $i = 0;

                $the_name = false;

                $the_val = false;

                foreach ($q2 as $cfk => $cfv) {

                    if ($cfk == 'custom_field_name') {

                        $the_name = $cfv;
                    }

                    if ($cfk == 'custom_field_value') {

                        $the_val = $cfv;
                    }

                    $i++;
                }

                if ($the_name != false and $the_val != false) {
                    if ($return_full == false) {

                        $the_data_with_custom_field__stuff[$the_name] = $the_val;
                    } else {
                        $cf_cfg = array();
                        $cf_cfg['name'] = $the_name;
                        $cf_cfg = $this->getCustomFieldsConfig($cf_cfg);
                        if (!empty($cf_cfg)) {
                            $cf_cfg = $cf_cfg[0];
                            $q2['config'] = $cf_cfg;
                        }

                        $the_data_with_custom_field__stuff[$the_name] = $q2;
                    }
                }
            }
        }
    }

    $result = $the_data_with_custom_field__stuff;
    $result = (array_change_key_case($result, CASE_LOWER));
    return $result;
}

function save_edit($post_data) {
    // p($_SERVER);
    $id = is_admin();
    // $id = 1;
    if ($id == false) {
        exit('Error: not logged in as admin.');
    }

    // p($_REQUEST);
    if ($post_data) {
        if (isset($post_data['json_obj'])) {
            $obj = json_decode($post_data['json_obj'], true);
            $post_data = $obj;
        }
        // p($post_data);
        if (isset($post_data['mw_preview_only'])) {
            $is_no_save = true;
            unset($the_field_data_all['mw_preview_only']);
        }
        $is_no_save = false;
        $the_field_data_all = $post_data;
    } else {
        exit('Error: no POST?');
    }
    $ref_page = $_SERVER['HTTP_REFERER'];
    if ($ref_page != '') {
        $ref_page = $the_ref_page = get_content_by_url($ref_page);
        $page_id = $ref_page['id'];
        $ref_page['custom_fields'] = get_custom_fields_for_content($page_id, false);
    }

    $json_print = array();
    foreach ($the_field_data_all as $the_field_data) {
        $save_global = false;
        $save_layout = false;
        if (!empty($the_field_data)) {
            $save_global = false;
            if ($the_field_data['attributes']) {
                // $the_field_data ['attributes'] = json_decode($the_field_data
                // ['attributes']);
                // var_dump($the_field_data ['attributes']);
            }
            $content_id = $page_id;

            /*
             * if (intval ( $the_field_data ['attributes'] ['page'] ) != 0) {
             * $page_id = intval ( $the_field_data ['attributes'] ['page'] );
             * $the_ref_page = get_page ( $page_id ); } if (intval (
             * $the_field_data ['attributes'] ['post'] ) != 0) { $post_id =
             * intval ( $the_field_data ['attributes'] ['post'] ); $content_id =
             * $post_id; $the_ref_post = get_content_by_id ( $post_id ); } if
             * (intval ( $the_field_data ['attributes'] ['category'] ) != 0) {
             * $category_id = intval ( $the_field_data ['attributes']
             * ['category'] ); } $page_element_id = false; if (strval (
             * $the_field_data ['attributes'] ['id'] ) != '') { $page_element_id
             * = ($the_field_data ['attributes'] ['id']); } if (($the_field_data
             * ['attributes'] ['global']) != false) { $save_global = true; } if
             * (($the_field_data ['attributes'] ['rel']) == 'global') {
             * $save_global = true; $save_layout = false; } if (trim (
             * $the_field_data ['attributes'] ['rel'] ) == 'layout') {
             * $save_global = false; $save_layout = true; // p($the_field_data
             * ['attributes'] ['rel']); } if (($the_field_data ['attributes']
             * ['rel']) == 'post') { if ($ref_page != '') { $save_global =
             * false; $ref_post = $the_ref_post = get_ref_post (); // p (
             * $ref_post ); $post_id = $ref_post ['id']; $page_id = $ref_page
             * ['id']; $content_id = $post_id; } } if (($the_field_data
             * ['attributes'] ['rel']) == 'page') { p ( $_SERVER ); if
             * ($ref_page != '') { $save_global = false; $ref_page =
             * $the_ref_page = get_ref_page (); $page_id = $ref_page ['id'];
             * $content_id = $page_id; } } if (($the_field_data ['attributes']
             * ['rel']) == 'PAGE_ID') { // p ( $_SERVER ); if ($ref_page != '')
             * { $save_global = false; $ref_page = $the_ref_page = get_ref_page
             * (); $page_id = $ref_page ['id']; $content_id = $page_id; } } if
             * (($the_field_data ['attributes'] ['rel']) == 'POST_ID') { // p (
             * $_SERVER ); if ($ref_page != '') { $save_global = false;
             * $ref_page = $the_ref_page = get_ref_page (); $page_id = $ref_page
             * ['id']; $content_id = $page_id; } }
             */
            $some_mods = array();
            if (($the_field_data['attributes'])) {
                if (($the_field_data['html']) != '') {
                    $field = trim($the_field_data['attributes']['field']);

                    if (isset($the_field_data['attributes']['field'])) {
                        $page_element_id = $the_field_data['attributes']['field'];
                    } else {
                        $page_element_id = $field;
                    }

                    $save_global = false;
                    if (isset($the_field_data['attributes']['rel']) and trim($the_field_data['attributes']['rel']) == 'global') {
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

                    if (isset($the_field_data['attributes']['rel'])) {
                        d($the_field_data);
                        error('rel must be finished', __FILE__, __LINE__);
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
                            $history_to_save['table'] = 'table_content';
                            $history_to_save['id'] = $content_id;
                            $history_to_save['value'] = $old;
                            $history_to_save['field'] = $field;
                            // p ( $history_to_save );
                            if ($is_no_save != true) {
                                save_history($history_to_save);
                            }

                            // p($html_to_save,1);
                            $to_save = array();
                            $to_save['id'] = $content_id;
                            // $to_save['quick_save'] = true;
                            // $to_save['r'] = $some_mods;
                            $to_save['page_element_id'] = $page_element_id;
                            // $to_save['page_element_content'] =
                            // $this->template_model->parseMicrwoberTags($html_to_save,
                            // $options = false);
                            $to_save['custom_fields'][$field] = ($html_to_save);
                            // print "<h2>For content $content_id</h2>";
                            // p ( $post_data );
                            // p ( $html_to_save, 1 );

                            if ($is_no_save != true) {
                                $json_print[] = $to_save;
                                // if($to_save['content_body'])
                                $saved = save_content($to_save);
                                // p($to_save);
                                // p($content_id);
                                // p($page_id);
                                // p ( $html_to_save ,1);
                            }
                            // print ($html_to_save) ;
                            // $html_to_save =
                            // $this->template_model->parseMicrwoberTags (
                            // $html_to_save, $options = false );
                        } else if (isset($category_id)) {
                            print(__FILE__ . __LINE__ . ' category is not implemented not ready yet');
                        }
                    } else {
                        if ($save_global == true and $save_layout == false) {
                            $field_content = $this->core_model->optionsGetByKey($the_field_data['attributes']['field'], $return_full = true, $orderby = false);
                            $html_to_save = $this->template_model->parseToTags($html_to_save);
                            // p($html_to_save,1);
                            $to_save = $field_content;
                            $to_save['option_key'] = $the_field_data['attributes']['field'];
                            $to_save['option_value'] = $html_to_save;
                            $to_save['option_key2'] = 'editable_region';
                            $to_save['page_element_id'] = $page_element_id;
                            // $to_save['page_element_content'] =
                            // $this->template_model->parseMicrwoberTags($html_to_save,
                            // $options = false);
                            // print "<h2>Global</h2>";
                            // p ( $to_save );
                            if ($is_no_save != true) {
                                $to_save = $this->core_model->optionsSave($to_save);
                            }
                            $json_print[] = $to_save;
                            $history_to_save = array();
                            $history_to_save['table'] = 'global';
                            // $history_to_save ['id'] = 'global';
                            $history_to_save['value'] = $field_content['option_value'];
                            $history_to_save['field'] = $field;
                            if ($is_no_save != true) {
                                $this->core_model->saveHistory($history_to_save);
                            }
                            // $html_to_save =
                            // $this->template_model->parseMicrwoberTags (
                            // $html_to_save, $options = false );
                            // $json_print[] = array ($the_field_data
                            // ['attributes'] ['id'] => $html_to_save );
                        }
                        if ($save_global == false and $save_layout == true) {
                            // $field_content =
                            // $this->core_model->optionsGetByKey (
                            // $the_field_data ['attributes'] ['field'],
                            // $return_full = true, $orderby = false );
                            $d = TEMPLATE_DIR . 'layouts' . DIRECTORY_SEPARATOR . 'editabe' . DIRECTORY_SEPARATOR;
                            $f = $d . $ref_page['id'] . '.php';
                            if (!is_dir($d)) {
                                mkdir_recursive($d);
                            }
                            // var_dump ( $f );
                            // $html_to_save =
                            // $this->template_model->parseToTags($html_to_save);
                            // p($html_to_save);
                            file_put_contents($f, $html_to_save);
                            // p($html_to_save,1);
                        }
                        // print ($html_to_save) ;
                        // print ($to_save) ;
                        // p ( $field_content );
                        // optionsSave($data)
                    }
                }
            } else {
                // print ('Error: plase specify a "field" attribute') ;
                // p($the_field_data);
            }
        }
    }
    header('Cache-Control: no-cache, must-revalidate');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Content-type: application/json');

    $json_print = json_encode($json_print);
    // if ($is_no_save == true) {
    // / $for_history = serialize ( $json_print );
    // $for_history = base64_encode ( $for_history );
    $history_to_save = array();
    $history_to_save['table'] = 'edit';
    $history_to_save['id'] = (parse_url(strtolower($_SERVER['HTTP_REFERER']), PHP_URL_PATH));
    $history_to_save['value'] = $json_print;
    $history_to_save['field'] = 'html_content';
    save_history($history_to_save);
    // }
    print $json_print;
    cache_clean_group('global/blocks');
    exit();
}

/**
 * Function to save content into the content_table
 *
 * @param
 *        	array
 *
 * @param
 *        	boolean
 *
 * @return string | the id saved
 *
 * @author Peter Ivanov
 *
 * @version 1.0
 *
 * @since Version 1.0
 *
 */
api_expose('save_content');

function save_content($data, $delete_the_cache = true) {
    $id = is_admin();
    if ($id == false) {
        error('Error: not logged in as admin.');
    }


    $cms_db_tables = c('db_tables');

    $table = $cms_db_tables['table_content'];

    if (empty($data)) {

        return false;
    }
    $data_to_save = $data;

    $more_categories_to_delete = array();
    if (intval($data['id']) != 0) {

        $q = "SELECT * from $table where id='{$data_to_save['id']}' ";

        $q = db_query($q);

        $thecontent_title = $q[0]['content_title'];

        $q = $q[0]['content_url'];

        $thecontent_url = $q;

        $more_categories_to_delete = get_categories_for_content($data['id'], 'categories');
    } else {

        $thecontent_url = $data['content_url'];

        $thecontent_title = $data['content_title'];
    }

    if (isset($thecontent_url) != false) {

        if (isset($data['content_url']) and $data['content_url'] == $thecontent_url) {

            // print 'asd2';

            $data['content_url'] = $thecontent_url;
        } else {

        }

        if (!isset($data['content_url']) or strval($data['content_url']) == '') {

            // print 'asd1';

            $data['content_url'] = $thecontent_url;
        }

        if ((strval($data['content_url']) == '') and (strval($thecontent_url) == '')) {

            // print 'asd';

            $data['content_url'] = url_title($thecontent_title);
        }
    } else {
        if ($thecontent_title != false) {
            $data['content_url'] = url_title($thecontent_title);
        }
    }
    if (isset($item['content_title'])) {
        $item['content_title'] = htmlspecialchars_decode($item['content_title'], ENT_QUOTES);

        $item['content_title'] = strip_tags($item['content_title']);
    }
    if ($data['content_url'] != false) {
        // if (intval ( $data ['id'] ) == 0) {
        $data_to_save['content_url'] = $data['content_url'];

        // }
    }

    if ($data['content_url'] != false) {
        $data['content_url'] = url_title($data['content_url']);

        if (strval($data['content_url']) == '') {

            $data['content_url'] = url_title($data['content_title']);
        }

        $date123 = date("YmdHis");

        $q = "select id, content_url from $table where content_url LIKE '{$data ['content_url']}'";

        $q = db_query($q);

        if (!empty($q)) {

            $q = $q[0];

            if ($data['id'] != $q['id']) {

                $data['content_url'] = $data['content_url'] . '-' . $date123;
                $data_to_save['content_url'] = $data['content_url'];
            }
        }

        if (isset($data_to_save['content_url']) and strval($data_to_save['content_url']) == '' and ($data_to_save['quick_save'] == false)) {

            $data_to_save['content_url'] = $data_to_save['content_url'] . '-' . $date123;
        }

        if (isset($data_to_save['content_title']) and strval($data_to_save['content_title']) == '' and ($data_to_save['quick_save'] == false)) {

            $data_to_save['content_title'] = 'post-' . $date123;
        }
        if (isset($data_to_save['content_url']) and strval($data_to_save['content_url']) == '' and ($data_to_save['quick_save'] == false)) {
            $data_to_save['content_url'] = strtolower(reduce_double_slashes($data['content_url']));
        }

        // $data_to_save ['content_url_md5'] = md5 ( $data_to_save
        // ['content_url'] );
    }

    $data_to_save_options = array();

    if (isset($data_to_save['is_home']) and $data_to_save['is_home'] == 'y') {
        $sql = "UPDATE $table set is_home='n'   ";
        $q = db_query($sql);
    }

    if (isset($data_to_save['content_subtype_value_new']) and strval($data_to_save['content_subtype_value_new']) != '') {
        $adm = is_admin();

        if ($data_to_save['content_subtype_value_new'] != '') {

            if ($adm == true) {

                $new_category = array();
                $new_category["taxonomy_type"] = "category";
                $new_category["taxonomy_value"] = $data_to_save['content_subtype_value_new'];
                $new_category["parent_id"] = "0";

                $new_category = $this->taxonomy_model->taxonomySave($new_category);

                $data_to_save['content_subtype_value'] = $new_category;
                $data_to_save['content_subtype'] = 'dynamic';
            }
        }
        if ($data_to_save['content_subtype_value_auto_create'] == '') {
            $data_to_save['content_subtype_value_auto_create'] = $data_to_save['auto_create_categories'];
        }

        if (!empty($data_to_save['taxonomy_categories_str'])) {
            $data_to_save['content_subtype_value_auto_create'] = $data_to_save['taxonomy_categories_str'];
        }

        if (!empty($data_to_save['taxonomy_categories_str']) or $data_to_save['content_subtype_value_auto_create'] != '') {

            if ($adm == true) {
                if (!is_array($original_data['content_subtype_value_auto_create'])) {

                    $scats = explode(',', $data_to_save['content_subtype_value_auto_create']);
                } else {

                    $scats = explode(',', $data_to_save['content_subtype_value_auto_create']);
                }
                if (!empty($scats)) {
                    foreach ($scats as $sc) {
                        $new_scategory = array();
                        $new_scategory["taxonomy_type"] = "category";
                        $new_scategory["taxonomy_value"] = $sc;
                        $new_scategory["parent_id"] = intval($new_category);

                        $new_scategory = $this->taxonomy_model->taxonomySave($new_scategory);
                    }
                }
            }
        }
    }

    $save = save_data($table, $data_to_save);
    $id = $save;
    return $save;
    // if ($data_to_save ['content_type'] == 'page') {

    if (!empty($data_to_save['menus'])) {

        // housekeep

        $this->removeContentFromUnusedMenus($save, $data_to_save['menus']);

        foreach ($data_to_save ['menus'] as $menu_item) {

            $to_save = array();

            $to_save['item_type'] = 'menu_item';

            $to_save['item_parent'] = $menu_item;

            $to_save['content_id'] = intval($save);

            $to_save['item_title'] = $data_to_save['content_title'];

            $this->saveMenu($to_save);

            $this->core_model->cleanCacheGroup('menus');
        }
    }

    // }
    // $this->core_model->cacheDeleteAll ();

    if ($data_to_save['preserve_cache'] == false) {
        if (intval($data_to_save['content_parent']) != 0) {
            cache_clean_group('content' . DIRECTORY_SEPARATOR . intval($data_to_save['content_parent']));
        }
        cache_clean_group('content' . DIRECTORY_SEPARATOR . $id);
        // cache_clean_group ( 'content' . DIRECTORY_SEPARATOR . '0' );
        cache_clean_group('content' . DIRECTORY_SEPARATOR . 'global');

        if (!empty($data_to_save['taxonomy_categories'])) {
            foreach ($data_to_save ['taxonomy_categories'] as $cat) {

                cache_clean_group('taxonomy' . DIRECTORY_SEPARATOR . intval($cat));
            }
            // cache_clean_group ( 'taxonomy' . DIRECTORY_SEPARATOR . '0' );
            cache_clean_group('taxonomy' . DIRECTORY_SEPARATOR . 'global');
            cache_clean_group('taxonomy' . DIRECTORY_SEPARATOR . 'items');
        }

        if (!empty($more_categories_to_delete)) {
            foreach ($more_categories_to_delete as $cat) {
                cache_clean_group('taxonomy' . DIRECTORY_SEPARATOR . intval($cat));
            }
        }
    }
    return $save;
}

function pages_tree($content_parent = 0, $link = false, $actve_ids = false, $active_code = false, $remove_ids = false, $removed_ids_code = false, $ul_class_name = false, $include_first = false) {

    $params2 = array();
    $params = false;
    $output = '';
    if (is_integer($content_parent)) {

    } else {
        $params = $content_parent;
        if (is_string($params)) {
            $params = parse_str($params, $params2);
            $params = $params2;
            extract($params);
        }
        if (is_array($params)) {
            $content_parent = 0;
            extract($params);
        }
    }
    $cms_db_tables = c('db_tables');

    $table = $cms_db_tables['table_content'];

    if ($content_parent == false) {

        $content_parent = (0);
    }
    if ($include_first == true) {
        $sql = "SELECT * from $table where  id=$content_parent    and content_type='page'  order by updated_on desc limit 0,1";
    } else {

        $sql = "SELECT * from $table where  content_parent=$content_parent    and content_type='page'  order by updated_on desc limit 0,1";
    }

    $sql = "SELECT * from $table where  content_parent=$content_parent    and content_type='page'  order by updated_on desc limit 0,10000";

    $q = db_query($sql);

    $result = $q;

    if (!empty($result)) {

        if ($ul_class_name == false) {
            print "<ul>";
        } else {
            print "<ul class='{$ul_class_name}'>";
        }

        foreach ($result as $item) {

            $output = $output . $item['content_title'];

            $content_type_li_class = false;

            if ($item['is_home'] != 'y') {

                switch ($item ['content_subtype']) {

                    case 'dynamic' :
                        $content_type_li_class = 'is_category';

                        break;

                    case 'module' :
                        $content_type_li_class = 'is_module';

                        break;

                    default :
                        $content_type_li_class = 'is_page';

                        break;
                }
            } else {

                $content_type_li_class = 'is_home';
            }

            $to_pr_2 = "<li class='$content_type_li_class {active_class}' data-page-id='{$item['id']}' data-parent-page-id='{$item['content_parent']}'  id='page_list_holder_{$item['id']}' >";

            if ($link != false) {

                $to_print = str_ireplace('{id}', $item['id'], $link);

                $to_print = str_ireplace('{content_title}', $item['content_title'], $to_print);

                $to_print = str_ireplace('{link}', page_link($item['id']), $to_print);

                if (strstr($to_print, '{tn}')) {
                    $to_print = str_ireplace('{tn}', thumbnail($item['id'], 'original'), $to_print);
                }
                foreach ($item as $item_k => $item_v) {
                    $to_print = str_ireplace('{' . $item_k . '}', $item_v, $to_print);
                }

                if (is_array($actve_ids) == true) {

                    $is_there_active_ids = false;

                    foreach ($actve_ids as $active_id) {

                        if (strval($item['id']) == strval($active_id)) {

                            $is_there_active_ids = true;

                            $to_print = str_ireplace('{active_code}', $active_code, $to_print);
                            $to_print = str_ireplace('{active_class}', 'active', $to_print);
                            $to_pr_2 = str_ireplace('{active_class}', 'active', $to_pr_2);
                        }
                    }

                    if ($is_there_active_ids == false) {

                        $to_print = str_ireplace('{active_code}', '', $to_print);
                        $to_print = str_ireplace('{active_class}', '', $to_print);
                        $to_pr_2 = str_ireplace('{active_class}', '', $to_pr_2);
                    }
                } else {

                    $to_print = str_ireplace('{active_code}', '', $to_print);
                    $to_pr_2 = str_ireplace('{active_class}', '', $to_pr_2);
                }

                if (is_array($remove_ids) == true) {

                    if (in_array($item['id'], $remove_ids)) {

                        if ($removed_ids_code == false) {

                            $to_print = false;
                        } else {

                            $to_print = str_ireplace('{removed_ids_code}', $removed_ids_code, $to_print);
                        }
                    } else {

                        $to_print = str_ireplace('{removed_ids_code}', '', $to_print);
                    }
                }
                print $to_pr_2;
                $to_pr_2 = false;
                print $to_print;
            } else {
                print $to_pr_2;
                $to_pr_2 = false;
                print $item['content_title'];
            }
            if (is_array($params)) {
                $params['content_parent'] = $item['id'];
                $children = pages_tree($params);
            } else {
                $children = pages_tree(intval($item['id']), $link, $actve_ids, $active_code, $remove_ids, $removed_ids_code, $ul_class_name);
            }

            print "</li>";
        }

        print "</ul>";
    } else {

    }
}

