<?php
namespace mw\content;


class StaticPages {


    /**
     *  Generates static pages navigation from directory of files
     * @category Content
     * @package Content
     * @subpackage Experimental
     * @internal not_tested
     * @uses get_content_by_url()
     * @param $params = array();
     * @param $params['dir_name'] = your dir; //path to the directory root
     * @return string <ul> with <li>
     */
    static function tree($params = false)
    {
        $params = parse_params($params);
        @extract($params);

        if (!isset($dir_name)) {
            return 'Error: You must set $dir_name for the function ' . __FUNCTION__;
        }
        if (!empty($params)) {
            ksort($params);
        }

        $function_cache_id = __FUNCTION__ . crc32(serialize($params));
        $cache_content = false;
        //$cache_content = cache_get_content($function_cache_id, 'content/static');

        if (($cache_content) != false) {

            $tree = $cache_content;
        } else {

            //cache_save($tree, $function_cache_id, $cache_group = 'content/static');
        }
        if (!isset($url)) {
            $url = curent_url(true, true);
        }
        $params['url'] = $url;
        $params['url_param'] = 'page';


        directory_tree($dir_name, $params);


    }



    /**
     *  Get a static page from a file
     * @category Content
     * @package Content
     * @subpackage Experimental
     * @internal not_tested
     * @uses get_content_by_url()
     */
    static function get($params = false)
    {

        $params = parse_params($params);
        @extract($params);
        if (!isset($dir_name)) {
            return 'Error: You must set $dir_name for the function ' . __FUNCTION__;
        }


        $load_file = false;
        $url = curent_url(true, true);
        $page_url = url_param('page');
        if ($page_url != false and $page_url != '') {
            $page_url = urldecode($page_url);
            $page_url = str_replace("--", "/", $page_url);
            if ($page_url != false and $page_url != '') {
                $path = str_replace('..', '', $dir_name);
                $file = str_replace('..', '', $page_url);

                if ($path != false and trim($path != '') and $file != false) {
                    $try_file = $path . $file;
                    if (is_file($try_file)) {
                        $try_file = normalize_path($try_file, false);
                        $load_file = ($try_file);
                    }


                } else if ($path != false and trim($path != '') and $file == false) {
                    $try_file = $path . DS . 'index.php';
                    if (is_file($try_file)) {
                        $try_file = normalize_path($try_file, false);
                        $load_file = ($try_file);
                    }
                }
            }


            if ($load_file != false) {

                $static_page = new MwView($load_file);
                $config = array();
                $config['dir_name'] = $dir_name;
                $config['filename'] = $load_file;
                $static_page->config = $config;
                $static_page->params = $params;

                $static_page = $static_page->__toString();


                return $static_page;


            }


        }

    }


}