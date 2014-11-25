<?php


namespace Files;


class Api
{

    static function rte_image_editor_search()
    {
        $active = url_param('view');
        $cls = '';
        if ($active == 'shop') {
            $cls = ' class="active" ';
        }
        print '<module type="files/admin" />';
    }


    /**
     * \Files\Api::get
     *
     *  Get an array that represents directory and files
     *
     * @package        modules
     * @subpackage    files
     * @subpackage    files\api
     * @category    files module api
     * @version 1.0
     * @since 0.320
     * @return mixed Array with files
     *
     * @param array $params = array()     the params
     * @param string $params['directory']       The directory
     * @param string $params['keyword']       If set it will seach the dir and subdirs
     */
    static function get($params)
    {
        if (is_admin() == false) {
            mw_error("Must be admin");
        }

        $params = parse_params($params);
        if (!isset($params['directory'])) {
            mw_error("You must define directory");
        } else {
            $directory = $params['directory'];
        }
        $from_search = 0;
        $arrayItems = array();
        if (isset($params['search']) and strval($params['search']) != '') {
            $from_search = 1;
            $arrayItems_search = rglob($pattern = DS . '*' . $params['search'] . '*', $flags = 0, $directory);

        } else {

            //$paths = glob($directory . DS . '*', GLOB_ONLYDIR | GLOB_NOSORT);
            //$files = glob($directory . DS . '*', 0);
            //$arrayItems_search = array_merge($paths, $files);

            if (!is_dir($directory . DS)) {
                return false;
            }

            $arrayItems_search = array();
            $myDirectory = opendir($directory . DS);
// get each entry
            while ($entryName = readdir($myDirectory)) {
                if ($entryName != '..' and $entryName != '.') {
                    $arrayItems_search[] = $entryName;
                }

            }
// close directory
            closedir($myDirectory);


        }

        if (!empty($arrayItems_search)) {
            if (isset($params['sort_by']) and strval($params['sort_by']) != '') {
                if (isset($params['sort_order']) and strval($params['sort_order']) != '') {

                    $ord = SORT_DESC;
                    if (strtolower($params['sort_order']) == 'asc') {
                        $ord = SORT_ASC;
                    }

                    array_multisort(array_map($params['sort_by'], $arrayItems_search), SORT_NUMERIC, $ord, $arrayItems_search);
                    //	d($arrayItems_search);
                }
            }
            //usort($myarray, create_function('$a,$b', 'return filemtime($a) - filemtime($b);'));

            $arrayItems_f = array();
            $arrayItems_d = array();
            foreach ($arrayItems_search as $file) {
                if ($from_search == 0) {
                    $file = $directory . DS . $file;
                }
                if (is_file($file)) {
                    $df = normalize_path($file, false);
                    if (!in_array($df, $arrayItems_f)) {
                        $arrayItems_f[] = $df;
                    }
                } else {
                    $df = normalize_path($file, 1);
                    if (!in_array($df, $arrayItems_d)) {
                        $arrayItems_d[] = $df;
                    }
                }
            }
            $arrayItems['files'] = $arrayItems_f;
            $arrayItems['dirs'] = $arrayItems_d;
        }

        return $arrayItems;
        
    }
}