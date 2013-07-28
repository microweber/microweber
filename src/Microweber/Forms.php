<?php
namespace Microweber;


action_hook('mw_db_init', mw('Microweber\Notifications')->db_init());

class Forms
{

    function __construct()
    {
        if (!defined("MW_DB_TABLE_COUNTRIES")) {
            define('MW_DB_TABLE_COUNTRIES', MW_TABLE_PREFIX . 'countries');
        }
        if (!defined("MW_DB_TABLE_FORMS_LISTS")) {
            define('MW_DB_TABLE_FORMS_LISTS', MW_TABLE_PREFIX . 'forms_lists');
        }

        if (!defined("MW_DB_TABLE_FORMS_DATA")) {
            define('MW_DB_TABLE_FORMS_DATA', MW_TABLE_PREFIX . 'forms_data');
        }
    }

    public function get_entires($params)
    {
        $params = parse_params($params);
        $table = MW_DB_TABLE_FORMS_DATA;
        $params['table'] = $table;


        if (!isset($params["order_by"])) {
            $params["order_by"] = 'created_on desc';
        }


        //$params['debug'] = $table;
        $data = \mw('db')->get($params);
        $ret = array();
        if (is_array($data)) {

            foreach ($data as $item) {
                //d($item);
                //

                $fields = get_custom_fields('forms_data', $item['id'], 1);

                if (is_array($fields)) {
                    ksort($fields);
                    $item['custom_fields'] = array();
                    foreach ($fields as $key => $value) {
                        $item['custom_fields'][$key] = $value;
                    }
                }
                //d($fields);
                $ret[] = $item;
            }
            return $ret;
        } else {
            return $data;
        }
    }

    public function save_list($params)
    {
        $adm = is_admin();
        if ($adm == false) {
            exit('You must be admin');
        }

        $table = MW_DB_TABLE_FORMS_LISTS;

        if (isset($params['mw_new_forms_list'])) {
            $params['id'] = 0;
            $params['id'] = 0;
            $params['title'] = $params['mw_new_forms_list'];
        }
        if (isset($params['for_module'])) {
            $params['module_name'] = $params['for_module'];
        }

        $params['table'] = $table;
        $id = \mw('db')->save($table, $params);
        if (isset($params['for_module_id'])) {
            $opts = array();
            $data['module'] = $params['module_name'];
            $data['option_group'] = $params['for_module_id'];
            $data['option_key'] = 'list_id';
            $data['option_value'] = $id;
            mw('option')->save($data);
        }

        return array('success' => 'List is updated', $params);


        return $params;
    }

    public function post($params)
    {

        $adm = is_admin();

        $table = MW_DB_TABLE_FORMS_DATA;
        mw_var('FORCE_SAVE', $table);

        if (isset($params['id'])) {
            if ($adm == false) {
                return array('error' => 'Error: Only admin can edit forms!');
            }
        }
        $for = 'module';
        if (isset($params['for'])) {
            $for = $params['for'];
        }

        if (isset($params['for_id'])) {
            $for_id = $params['for_id'];
        } else if (isset($params['data-id'])) {
            $for_id = $params['data-id'];
        } else if (isset($params['id'])) {
            $for_id = $params['id'];
        }

        //$for_id =$params['id'];
        if (isset($params['rel_id'])) {
            $for_id = $params['rel_id'];
        }

        $dis_cap = mw('option')->get('disable_captcha', $for_id) == 'y';

        if ($dis_cap == false) {
            if (!isset($params['captcha'])) {
                return array('error' => 'Please enter the captcha answer!');
            } else {
                $cap = mw('user')->session_get('captcha');

                if ($cap == false) {
                    return array('error' => 'You must load a captcha first!');
                }
                if (intval($params['captcha']) != ($cap)) {
                    //     d($cap);
                    if ($adm == false) {
                        return array('error' => 'Invalid captcha answer!');
                    }
                }
            }
        }

        if ($for == 'module') {
            $list_id = mw('option')->get('list_id', $for_id);
        }
        $email_to = mw('option')->get('email_to', $for_id);
        $email_bcc = mw('option')->get('email_bcc', $for_id);
        $email_autorespond = mw('option')->get('email_autorespond', $for_id);

        $email_autorespond_subject = mw('option')->get('email_autorespond_subject', $for_id);

        if ($list_id == false) {
            $list_id = 0;
        }

        $to_save = array();
        $fields_data = array();
        $more = get_custom_fields($for, $for_id, 1);
        $cf_to_save = array();
        if (!empty($more)) {
            foreach ($more as $item) {
                if (isset($item['custom_field_name'])) {
                    $cfn = ($item['custom_field_name']);

                    $cfn2 = str_replace(' ', '_', $cfn);
                    $fffound = false;

                    if (isset($params[$cfn2])) {
                        $fields_data[$cfn2] = $params[$cfn2];
                        $item['custom_field_value'] = $params[$cfn2];
                        $fffound = 1;
                        $cf_to_save[] = $item;
                    } elseif (isset($params[$cfn])) {
                        $fields_data[$cfn] = $params[$cfn];
                        $item['custom_field_value'] = $params[$cfn2];
                        $cf_to_save[] = $item;
                        $fffound = 1;
                    }

                }
            }
        }
        $to_save['list_id'] = $list_id;
        $to_save['rel_id'] = $for_id;
        $to_save['rel'] = $for;
        //$to_save['custom_fields'] = $fields_data;

        if (isset($params['module_name'])) {
            $to_save['module_name'] = $params['module_name'];
        }

        if (isset($params['form_values'])) {
            $to_save['form_values'] = $params['form_values'];
        }

        $save = \mw('db')->save($table, $to_save);

        if (!empty($cf_to_save)) {
            $table_custom_field = MW_TABLE_PREFIX . 'custom_fields';

            foreach ($cf_to_save as $value) {

                $value['copy_of_field'] = $value['id'];

                $value['id'] = 0;
                if (isset($value['session_id'])) {
                    unset($value['session_id']);
                }
                $value['rel_id'] = $save;
                $value['rel'] = 'forms_data';

                $cf_save = \mw('db')->save($table_custom_field, $value);
            }
        }

        if (isset($params['module_name'])) {

            $pp_arr = $params;
            $pp_arr['ip'] = MW_USER_IP;
            unset($pp_arr['module_name']);
            if (isset($pp_arr['rel'])) {
                unset($pp_arr['rel']);
            }

            if (isset($pp_arr['rel_id'])) {
                unset($pp_arr['rel_id']);
            }

            if (isset($pp_arr['list_id'])) {
                unset($pp_arr['list_id']);
            }

            if (isset($pp_arr['for'])) {
                unset($pp_arr['for']);
            }

            if (isset($pp_arr['for_id'])) {
                unset($pp_arr['for_id']);
            }

            $notif = array();
            $notif['module'] = $params['module_name'];
            $notif['rel'] = 'forms_lists';
            $notif['rel_id'] = $list_id;
            $notif['title'] = "New form entry";
            $notif['description'] = "You have new form entry";
            $notif['content'] = "You have new form entry from " . mw('url')->current(1) . '<br />' . mw('format')->array_to_ul($pp_arr);
            mw('Microweber\Notifications')->save($notif);
            //	d($cf_to_save);
            if ($email_to == false) {
                $email_to = mw('option')->get('email_from', 'email');

            }
            if ($email_to != false) {
                $mail_sj = "Thank you!";
                $mail_autoresp = "Thank you for your submition! <br/>";

                if ($email_autorespond_subject != false) {
                    $mail_sj = $email_autorespond_subject;
                }
                if ($email_autorespond != false) {
                    $mail_autoresp = $email_autorespond;
                }

                $mail_autoresp = $mail_autoresp . mw('format')->array_to_ul($pp_arr);

                $user_mails = array();
                $user_mails[] = $email_to;
                if (isset($email_bcc) and (filter_var($email_bcc, FILTER_VALIDATE_EMAIL))) {
                    $user_mails[] = $email_bcc;
                }

                if (isset($cf_to_save) and !empty($cf_to_save)) {
                    foreach ($cf_to_save as $value) {
                        //	d($value);
                        $to = $value['custom_field_value'];
                        if (isset($to) and (filter_var($to, FILTER_VALIDATE_EMAIL))) {
                            //	d($to);
                            $user_mails[] = $to;
                        }
                    }
                }
                $scheduler = new \Microweber\Utils\Events();
                // schedule a global scope function:

                if (!empty($user_mails)) {
                    array_unique($user_mails);
                    foreach ($user_mails as $value) {
                        //\Microweber\email\Sender::send($value,$mail_sj,$mail_autoresp );
                        $scheduler->registerShutdownEvent("\Microweber\email\Sender::send", $value, $mail_sj, $mail_autoresp);

                    }
                }

            }
        }

        return ($save);

    }

    public function get_lists($params)
    {
        $params = parse_params($params);
        $table = MW_DB_TABLE_FORMS_LISTS;
        $params['table'] = $table;

        return mw('db')->get($params);
    }

    public function  countries_list()
    {

        $table = MW_DB_TABLE_COUNTRIES;

        $sql = "SELECT name AS country_name FROM $table   ";

        $q = \mw('db')->query($sql, __FUNCTION__ . crc32($sql), 'db');
        $res = array();
        if (is_array($q)) {
            foreach ($q as $value) {
                $res[] = $value['country_name'];
            }
            return $res;
        } else {
            self::db_init();
            return false;
        }

    }

    public function delete_entry($data)
    {

        $adm = is_admin();
        if ($adm == false) {
            return array('error' => 'Error: not logged in as admin.' . __FILE__ . __LINE__);
        }
        $table = MW_DB_TABLE_FORMS_LISTS;
        if (isset($data['id'])) {
            $c_id = intval($data['id']);


            $fields = get_custom_fields('forms_data', $data['id'], 1);

            if (is_array($fields)) {

                foreach ($fields as $key => $value) {

                    if (isset($value['id'])) {

                        $remid = $value['id'];
                        $custom_field_table = MW_TABLE_PREFIX . 'custom_fields';
                        $q = "DELETE FROM $custom_field_table WHERE id='$remid'";

                        \mw('db')->q($q);


                    }


                }


                mw('cache')->delete('custom_fields');

            }


            \mw('db')->delete_by_id('forms_data', $c_id);
        }
    }

    public function delete_list($data)
    {

        $adm = is_admin();
        if ($adm == false) {
            return array('error' => 'Error: not logged in as admin.' . __FILE__ . __LINE__);
        }
        $table = MW_DB_TABLE_FORMS_LISTS;
        if (isset($data['id'])) {
            $c_id = intval($data['id']);
            \mw('db')->delete_by_id('forms_lists', $c_id);
            \mw('db')->delete_by_id('forms_data', $c_id, 'list_id');

        }
    }

    public function db_init()
    {
        $function_cache_id = false;

        $args = func_get_args();

        foreach ($args as $k => $v) {

            $function_cache_id = $function_cache_id . serialize($k) . serialize($v);
        }

        $function_cache_id = 'forms' . __FUNCTION__ . crc32($function_cache_id);

        $cache_content = mw('cache')->get($function_cache_id, 'db');

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

        \mw('Microweber\DbUtils')->build_table($table_name, $fields_to_add);

        \mw('Microweber\DbUtils')->add_table_index('rel', $table_name, array('rel(55)'));
        \mw('Microweber\DbUtils')->add_table_index('rel_id', $table_name, array('rel_id(255)'));
        \mw('Microweber\DbUtils')->add_table_index('list_id', $table_name, array('list_id'));

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

        \mw('Microweber\DbUtils')->build_table($table_name, $fields_to_add);

        \mw('Microweber\DbUtils')->add_table_index('title', $table_name, array('title(55)'));


        $table_sql = MW_INCLUDES_DIR . 'install' . DS . 'countries.sql';

        \mw('Microweber\DbUtils')->import_sql_file($table_sql);

        mw('cache')->save(true, $function_cache_id, $cache_group = 'db');
        return true;

    }


    public function export_to_excel($params)
    {
        //this function is experimental
        set_time_limit(0);

        $adm = is_admin();
        if ($adm == false) {
            return array('error' => 'Error: not logged in as admin.' . __FILE__ . __LINE__);
        }
        if (!isset($params['id'])) {
            return array('error' => 'Please specify list id! By posting field id=the list id ');

        } else {
            $lid = intval($params['id']);
            $data = get_form_entires('limit=100000&list_id=' . $lid);
            if (is_array($data)) {
                foreach ($data as $item) {
                    if (isset($item['custom_fields'])) {
                        $custom_fields = array();
                        foreach ($item['custom_fields'] as $value) {
                            $custom_fields[$value['custom_field_name']] = $value;
                        }
                    }
                }
            }


            $csv_output = '';
            if (isset($custom_fields) and is_array($custom_fields)) {
                $csv_output = 'id,';
                $csv_output .= 'created_on,';
                $csv_output .= 'user_ip,';
                foreach ($custom_fields as $k => $item) {
                    $csv_output .= mw('format')->no_dashes($k) . ",";
                    $csv_output .= "\t";
                }
                $csv_output .= "\n";


                foreach ($data as $item) {

                    if (isset($item['custom_fields'])) {
                        $csv_output .= $item['id'] . ",";
                        $csv_output .= "\t";
                        $csv_output .= $item['created_on'] . ",";
                        $csv_output .= "\t";
                        $csv_output .= $item['user_ip'] . ",";
                        $csv_output .= "\t";

                        foreach ($item['custom_fields'] as $item1) {
                            $csv_output .= $item1['custom_field_values_plain'] . ",";
                            $csv_output .= "\t";
                        }
                        $csv_output .= "\n";
                    }
                }
            }
            $filename = 'export' . "_" . date("Y-m-d_H-i", time()) . uniqid() . '.csv';
            $filename_path = MW_CACHE_DIR . 'forms_data' . DS . 'global' . DS;
            if (!is_dir($filename_path)) {
                mkdir_recursive($filename_path);
            }
            $filename_path_full = $filename_path . $filename;
            file_put_contents($filename_path_full, $csv_output);
            $download = mw('url')->link_to_file($filename_path_full);
            return array('success' => 'Your file has been exported!', 'download' => $download);

        }


    }


    /**
     * Creates database table from array
     *
     * You can pass an array of database fields and this function will set up the same db table from it
     *
     * @example
     * <pre>
     * To create custom table use
     *
     *
     * $table_name = MW_TABLE_PREFIX . 'my_new_table'
     *
     * $fields_to_add = array();
     * $fields_to_add[] = array('updated_on', 'datetime default NULL');
     * $fields_to_add[] = array('created_by', 'int(11) default NULL');
     * $fields_to_add[] = array('content_type', 'TEXT default NULL');
     * $fields_to_add[] = array('url', 'longtext default NULL');
     * $fields_to_add[] = array('content_filename', 'TEXT default NULL');
     * $fields_to_add[] = array('title', 'longtext default NULL');
     * $fields_to_add[] = array('is_active', "char(1) default 'y'");
     * $fields_to_add[] = array('is_deleted', "char(1) default 'n'");
     *  \mw('Microweber\DbUtils')->build_table($table_name, $fields_to_add);
     * </pre>
     *
     * @desc refresh tables in DB
     * @access        public
     * @category Database
     * @package    Database
     * @subpackage Advanced
     * @param        string $table_name to alter table
     * @param        array $fields_to_add to add new columns
     * @param        array $column_for_not_drop for not drop
     * @return bool|mixed
     */
    public function build_table($table_name, $fields_to_add, $column_for_not_drop = array())
    {
        $function_cache_id = false;

        $args = func_get_args();

        foreach ($args as $k => $v) {

            $function_cache_id = $function_cache_id . serialize($k) . serialize($v);
        }

        $function_cache_id = __FUNCTION__ . $table_name . crc32($function_cache_id);

        $cache_content = mw('cache')->get($function_cache_id, 'db/' . $table_name, 'files');

        if (($cache_content) != false) {

            return $cache_content;
        }

        $query = \mw('db')->query("show tables like '$table_name'");

        if (!is_array($query)) {
            $sql = "CREATE TABLE " . $table_name . " (
			id int(11) NOT NULL auto_increment,
			PRIMARY KEY (id)

			) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;

";
            //
            //if (isset($_GET['debug'])) {
            //	d($sql);
            \mw('db')->q($sql);
            //}
        }

        if ($table_name != 'firecms_sessions') {
            if (empty($column_for_not_drop))
                $column_for_not_drop = array('id');

            $sql = "show columns from $table_name";

            $columns = \mw('db')->query($sql);

            $exisiting_fields = array();
            $no_exisiting_fields = array();

            foreach ($columns as $fivesdraft) {
                $fivesdraft = array_change_key_case($fivesdraft, CASE_LOWER);
                $exisiting_fields[strtolower($fivesdraft['field'])] = true;
            }

            for ($i = 0; $i < count($columns); $i++) {
                $column_to_move = true;
                for ($j = 0; $j < count($fields_to_add); $j++) {
                    if (in_array($columns[$i]['Field'], $fields_to_add[$j])) {
                        $column_to_move = false;
                    }
                }
                $sql = false;
                if ($column_to_move) {
                    if (!empty($column_for_not_drop)) {
                        if (!in_array($columns[$i]['Field'], $column_for_not_drop)) {
                            $sql = "ALTER TABLE $table_name DROP COLUMN {$columns[$i]['Field']} ";
                        }
                    } else {
                        $sql = "ALTER TABLE $table_name DROP COLUMN {$columns[$i]['Field']} ";
                    }
                    if ($sql) {
                        \mw('db')->q($sql);

                    }
                }
            }

            foreach ($fields_to_add as $the_field) {
                $the_field[0] = strtolower($the_field[0]);

                $sql = false;
                if (isset($exisiting_fields[$the_field[0]]) != true) {
                    $sql = "alter table $table_name add column " . $the_field[0] . " " . $the_field[1] . "";
                    \mw('db')->q($sql);
                } else {
                    //$sql = "alter table $table_name modify {$the_field[0]} {$the_field[1]} ";

                }

            }

        }

        mw('cache')->save('--true--', $function_cache_id, $cache_group = 'db/' . $table_name, 'files');
        // $fields = (array_change_key_case ( $fields, CASE_LOWER ));
        return true;
        //set_db_tables
    }

    /**
     * Add new table index if not exists
     *
     * @example
     * <pre>
     * \mw('Microweber\DbUtils')->add_table_index('title', $table_name, array('title'));
     * </pre>
     *
     * @category Database
     * @package    Database
     * @subpackage Advanced
     * @param string $aIndexName Index name
     * @param string $aTable Table name
     * @param string $aOnColumns Involved columns
     * @param bool $indexType
     */
    public function add_table_index($aIndexName, $aTable, $aOnColumns, $indexType = false)
    {
        $columns = implode(',', $aOnColumns);

        $query = \mw('db')->query("SHOW INDEX FROM {$aTable} WHERE Key_name = '{$aIndexName}';");

        if ($indexType != false) {

            $index = $indexType;
        } else {
            $index = " INDEX ";

            //FULLTEXT
        }

        if ($query == false) {
            $q = "ALTER TABLE " . $aTable . " ADD $index `" . $aIndexName . "` (" . $columns . ");";
            // var_dump($q);
            \mw('db')->q($q);
        }

    }

    /**
     * Set table's engine
     *
     * @category Database
     * @package    Database
     * @subpackage Advanced
     * @param string $aTable
     * @param string $aEngine
     */
    public function set_table_engine($aTable, $aEngine = 'MyISAM')
    {
        \mw('db')->q("ALTER TABLE {$aTable} ENGINE={$aEngine};");
    }


    /**
     * Create foreign key if not exists
     *
     * @category Database
     * @package    Database
     * @subpackage Advanced
     * @param string $aFKName Foreign key name
     * @param string $aTable Source table name
     * @param array $aColumns Source columns
     * @param string $aForeignTable Foreign table name
     * @param array $aForeignColumns Foreign columns
     * @param array $aOptions On update and on delete options
     */
    public function add_foreign_key($aFKName, $aTable, $aColumns, $aForeignTable, $aForeignColumns, $aOptions = array())
    {
        $query = \mw('db')->query("
		SELECT
		*
		FROM
		INFORMATION_SCHEMA.TABLE_CONSTRAINTS
		WHERE
		CONSTRAINT_TYPE = 'FOREIGN KEY'
		AND
		constraint_name = '{$aFKName}'
		;");

        if ($query == false) {

            $columns = implode(',', $aColumns);
            $fColumns = implode(',', $aForeignColumns);
            ;
            $onDelete = 'ON DELETE ' . (isset($aOptions['delete']) ? $aOptions['delete'] : 'NO ACTION');
            $onUpdate = 'ON UPDATE ' . (isset($aOptions['update']) ? $aOptions['update'] : 'NO ACTION');
            $q = "ALTER TABLE " . $aTable;
            $q .= " ADD CONSTRAINT `" . $aFKName . "` ";
            $q .= " FOREIGN KEY(" . $columns . ") ";
            $q .= " {$onDelete} ";
            $q .= " {$onUpdate} ";
            \mw('db')->q($q);
        }

    }
}