<?php

class ModuleUtils
{

    static function reorder_modules($data)
    {

        $adm = is_admin();
        if ($adm == false) {
            error('Error: not logged in as admin.' . __FILE__ . __LINE__);
        }

        $table = MW_TABLE_PREFIX . 'modules';
        foreach ($data as $value) {
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

    static function delete_module($id)
    {
        if (is_admin() == false) {
            return false;
        }
        $id = intval($id);

        $table = MW_TABLE_PREFIX . 'modules';
        $db_categories = MW_TABLE_PREFIX . 'categories';
        $db_categories_items = MW_TABLE_PREFIX . 'categories_items';

        $q = "delete from $table where id={$id}";
        db_q($q);

        $q = "delete from $db_categories_items where rel='modules' and data_type='category_item' and rel_id={$id}";
        db_q($q);
        cache_clean_group('categories' . DIRECTORY_SEPARATOR . '');
        // cache_clean_group('categories_items' . DIRECTORY_SEPARATOR . '');

        cache_clean_group('modules' . DIRECTORY_SEPARATOR . '');
    }


   static function delete_all()
    {
        if (is_admin() == false) {
            return false;
        } else {

            $table = MW_TABLE_PREFIX . 'modules';
            $db_categories = MW_TABLE_PREFIX . 'categories';
            $db_categories_items = MW_TABLE_PREFIX . 'categories_items';

            $q = "DELETE FROM $table ";
            db_q($q);

            $q = "DELETE FROM $db_categories WHERE rel='modules' AND data_type='category' ";
            db_q($q);

            $q = "DELETE FROM $db_categories_items WHERE rel='modules' AND data_type='category_item' ";
            db_q($q);
            cache_clean_group('categories' . DIRECTORY_SEPARATOR . '');
            cache_clean_group('categories_items' . DIRECTORY_SEPARATOR . '');

            cache_clean_group('modules' . DIRECTORY_SEPARATOR . '');
        }
    }

}