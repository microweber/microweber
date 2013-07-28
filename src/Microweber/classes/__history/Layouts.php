<?php
if (!defined("MW_DB_TABLE_MODULES")) {
    define('MW_DB_TABLE_MODULES', MW_TABLE_PREFIX . 'modules');
}

if (!defined("MW_DB_TABLE_ELEMENTS")) {
    define('MW_DB_TABLE_ELEMENTS', MW_TABLE_PREFIX . 'elements');
}

if (!defined("MW_DB_TABLE_MODULE_TEMPLATES")) {
    define('MW_DB_TABLE_MODULE_TEMPLATES', MW_TABLE_PREFIX . 'module_templates');
}



class Layouts {




   static function get($params = false)
    {

        $table = MW_DB_TABLE_ELEMENTS;
        if (is_string($params)) {
            $params = parse_str($params, $params2);
            $params = $options = $params2;
        }
        $params['table'] = $table;
        $params['group_by'] = 'module';
        $params['orderby'] = 'position asc';

        $params['cache_group'] = 'elements/global';
        if (isset($params['id'])) {
            $params['limit'] = 1;
        } else {
            $params['limit'] = 1000;
        }

        if (!isset($params['ui'])) {
            //   $params['ui'] = 1;
        }

        $s = get($params);
        // d($params); d( $s);
        return $s;
    }

    function save($data_to_save)
    {

        if (is_admin() == false) {
            return false;
        }
        if (isset($data_to_save['is_element']) and $data_to_save['is_element'] == true) {
            exit(__FILE__ . __LINE__ . d($data_to_save));
        }

        $table = MW_TABLE_PREFIX . 'elements';
        $save = false;
        // d($table);
        //d($data_to_save);

        if (!empty($data_to_save)) {
            $s = $data_to_save;
            // $s["module_name"] = $data_to_save["name"];
            // $s["module_name"] = $data_to_save["name"];
            if (!isset($s["parent_id"])) {
                $s["parent_id"] = 0;
            }
            if (!isset($s["id"]) and isset($s["module"])) {
                $s["module"] = $data_to_save["module"];
                if (!isset($s["module_id"])) {
                    $save = self::get('limit=1&module=' . $s["module"]);
                    if ($save != false and isset($save[0]) and is_array($save[0])) {
                        $s["id"] = $save[0]["id"];
                        $save = save_data($table, $s);
                    } else {
                        $save = save_data($table, $s);
                    }
                }
            } else {
                $save = save_data($table, $s);
            }

            //
            // d($s);
        }

        if ($save != false) {

            cache_clean_group('elements' . DIRECTORY_SEPARATOR . '');
            cache_clean_group('elements' . DIRECTORY_SEPARATOR . 'global');
        }
        return $save;
    }




    function delete_all()
    {
        if (is_admin() == false) {
            return false;
        } else {

            $table = MW_TABLE_PREFIX . 'elements';

            $db_categories = MW_TABLE_PREFIX . 'categories';
            $db_categories_items = MW_TABLE_PREFIX . 'categories_items';

            $q = "delete from $table ";
            //   d($q);
            db_q($q);

            $q = "delete from $db_categories where rel='elements' and data_type='category' ";
            // d($q);
            db_q($q);

            $q = "delete from $db_categories_items where rel='elements' and data_type='category_item' ";
            // d($q);
            db_q($q);
            cache_clean_group('categories' . DIRECTORY_SEPARATOR . '');
            cache_clean_group('categories_items' . DIRECTORY_SEPARATOR . '');

            cache_clean_group('elements' . DIRECTORY_SEPARATOR . '');
        }
    }

}