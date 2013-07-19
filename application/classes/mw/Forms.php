<?php
namespace mw;

class Forms {

    static function countries_list() {

        $table = MW_DB_TABLE_COUNTRIES;

        $sql = "SELECT name as country_name from $table   ";

        $q = db_query($sql, __FUNCTION__ . crc32($sql), 'db');
        $res = array();
        if (isarr($q)) {
            foreach ($q as $value) {
                $res[] = $value['country_name'];
            }
            return $res;
        } else {
            self::db_init();
            return false;
        }

    }



    static function db_init() {
        $function_cache_id = false;

        $args = func_get_args();

        foreach ($args as $k => $v) {

            $function_cache_id = $function_cache_id . serialize($k) . serialize($v);
        }

        $function_cache_id = 'countries'.__FUNCTION__ . crc32($function_cache_id);

        $cache_content = cache_get_content($function_cache_id, 'db');

        if (($cache_content) != false) {

            return $cache_content;
        }

        $table_name = MW_DB_TABLE_FORMS_DATA;

        $fields_to_add = array();

        //$fields_to_add[] = array('updated_on', 'datetime default NULL');
        $fields_to_add[] = array('created_on', 'datetime default NULL');
        $fields_to_add[] = array('created_by', 'int(11) default NULL');
        //$fields_to_add[] = array('edited_by', 'int(11) default NULL');
        $fields_to_add[] = array('rel', 'TEXT default NULL');
        $fields_to_add[] = array('rel_id', 'TEXT default NULL');
        //$fields_to_add[] = array('position', 'int(11) default NULL');
        $fields_to_add[] = array('list_id', 'int(11) default 0');
        $fields_to_add[] = array('form_values', 'TEXT default NULL');
        $fields_to_add[] = array('module_name', 'TEXT default NULL');

        $fields_to_add[] = array('url', 'TEXT default NULL');
        $fields_to_add[] = array('user_ip', 'TEXT default NULL');

        set_db_table($table_name, $fields_to_add);

        db_add_table_index('rel', $table_name, array('rel(55)'));
        db_add_table_index('rel_id', $table_name, array('rel_id(255)'));
        db_add_table_index('list_id', $table_name, array('list_id'));

        $table_name = MW_DB_TABLE_FORMS_LISTS;

        $fields_to_add = array();

        //$fields_to_add[] = array('updated_on', 'datetime default NULL');
        $fields_to_add[] = array('created_on', 'datetime default NULL');
        $fields_to_add[] = array('created_by', 'int(11) default NULL');
        $fields_to_add[] = array('title', 'longtext default NULL');
        $fields_to_add[] = array('description', 'TEXT default NULL');
        $fields_to_add[] = array('custom_data', 'TEXT default NULL');

        $fields_to_add[] = array('module_name', 'TEXT default NULL');
        $fields_to_add[] = array('last_export', 'datetime default NULL');
        $fields_to_add[] = array('last_sent', 'datetime default NULL');

        set_db_table($table_name, $fields_to_add);

        db_add_table_index('title', $table_name, array('title(55)'));




        $table_sql = INCLUDES_PATH . 'install' . DS . 'countries.sql';

        import_sql_from_file($table_sql);




        cache_save(true, $function_cache_id, $cache_group = 'db');
        return true;

    }
}