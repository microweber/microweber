<?php
namespace Microweber;


class Forms
{
    public $app;

    function __construct($app = null)
    {

        if (is_object($app)) {
            $this->app = $app;
        } else {
            $this->app = Application::getInstance();
        }

        if (!defined("MW_DB_TABLE_COUNTRIES")) {
            define('MW_DB_TABLE_COUNTRIES', MW_TABLE_PREFIX . 'countries');
        }
        if (!defined("MW_DB_TABLE_FORMS_LISTS")) {
            define('MW_DB_TABLE_FORMS_LISTS', MW_TABLE_PREFIX . 'forms_lists');
        }

        if (!defined("MW_DB_TABLE_FORMS_DATA")) {
            define('MW_DB_TABLE_FORMS_DATA', MW_TABLE_PREFIX . 'forms_data');
            $this->db_init();
        }
    }

    public function db_init()
    {
        $function_cache_id = false;
        $force = false;
        $args = func_get_args();

        foreach ($args as $k => $v) {

            $function_cache_id = $function_cache_id . serialize($k) . serialize($v);
        }

        $function_cache_id = 'forms_' . __FUNCTION__ . crc32($function_cache_id);

        $cache_content = $this->app->cache->get($function_cache_id, 'db');

        if ($force == false and ($cache_content) != false) {

            return $cache_content;
        }

        $table_name = MW_DB_TABLE_FORMS_DATA;

        $fields_to_add = array();

        //$fields_to_add[] = array('updated_on', 'dateTime');
        $fields_to_add[] = array('created_on', 'dateTime');
        $fields_to_add[] = array('created_by', 'integer');
        //$fields_to_add[] = array('edited_by', 'integer');
        $fields_to_add[] = array('rel', 'longText');
        $fields_to_add[] = array('rel_id', 'longText');
        //$fields_to_add[] = array('position', 'integer');
        $fields_to_add[] = array('list_id', 'int(11) default 0');
        $fields_to_add[] = array('form_values', 'longText');
        $fields_to_add[] = array('module_name', 'longText');

        $fields_to_add[] = array('url', 'longText');
        $fields_to_add[] = array('user_ip', 'longText');

        $this->app->database->build_table($table_name, $fields_to_add);

        $this->app->database->add_table_index('rel', $table_name, array('rel(55)'));
        $this->app->database->add_table_index('rel_id', $table_name, array('rel_id(255)'));
        $this->app->database->add_table_index('list_id', $table_name, array('list_id'));

        $table_name = MW_DB_TABLE_FORMS_LISTS;

        $fields_to_add = array();

        //$fields_to_add[] = array('updated_on', 'dateTime');
        $fields_to_add[] = array('created_on', 'dateTime');
        $fields_to_add[] = array('created_by', 'integer');
        $fields_to_add[] = array('title', 'longText');
        $fields_to_add[] = array('description', 'longText');
        $fields_to_add[] = array('custom_data', 'longText');

        $fields_to_add[] = array('module_name', 'longText');
        $fields_to_add[] = array('last_export', 'dateTime');
        $fields_to_add[] = array('last_sent', 'dateTime');

        $this->app->database->build_table($table_name, $fields_to_add);

        $this->app->database->add_table_index('title', $table_name, array('title(55)'));


        $table_sql = MW_INCLUDES_DIR . 'install' . DS . 'countries.sql';

        $this->app->database->import_sql_file($table_sql);

        $this->app->cache->save(true, $function_cache_id, $cache_group = 'db');
        return true;

    }

    public function get_entires($params)
    {
        $params = parse_params($params);
        $table = MW_DB_TABLE_FORMS_DATA;
        $params['table'] = $table;


        if (!isset($params["order_by"])) {
            $params["order_by"] = 'created_on desc';
        }

        $data = $this->app->database->get($params);
        $ret = array();
        if (is_array($data)) {
            foreach ($data as $item) {
                $fields = $this->app->fields->get('forms_data', $item['id'], 1);

                if (is_array($fields)) {
                    ksort($fields);
                    $item['custom_fields'] = array();
                    foreach ($fields as $key => $value) {
                        $item['custom_fields'][$key] = $value;
                    }
                }

                $ret[] = $item;
            }
            return $ret;
        } else {
            return $data;
        }
    }

    public function save_list($params)
    {
        $adm = $this->app->user->is_admin();
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
        $id = $this->app->database->save($table, $params);
        if (isset($params['for_module_id'])) {
            $data = array();
            $data['module'] = $params['module_name'];
            $data['option_group'] = $params['for_module_id'];
            $data['option_key'] = 'list_id';
            $data['option_value'] = $id;
            $this->app->option->save($data);
        }

        return array('success' => 'List is updated', $params);

    }

    public function post($params)
    {


        $adm = $this->app->user->is_admin();
        if (defined("MW_API_CALL")) {
//            $validate_token = $this->app->user->csrf_validate($params);
//            if (!$adm) {
//                if ($validate_token == false) {
//                    return array('error' => 'Invalid token!');
//                }
//            }
        }

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

        if (isset($params['rel_id'])) {
            $for_id = $params['rel_id'];
        }

        if (!isset($for_id)) {
            return array('error' => 'Please provide for_id parameter with module id');
        }


        $dis_cap = $this->app->option->get('disable_captcha', $for_id) == 'y';

        if ($dis_cap == false) {
            if (!isset($params['captcha'])) {
                return array('error' => 'Please enter the captcha answer!');
            } else {
                $cap = $this->app->user->session_get('captcha');

                if ($for_id != false) {
                    $captcha_sid = 'captcha_' . $for_id;
                    $cap_sid = $this->app->user->session_get($captcha_sid);
                    if ($cap_sid != false) {
                        $cap = $cap_sid;
                    }
                }


                if ($cap == false) {
                    return array('error' => 'You must load a captcha first!');
                }
                if (intval($params['captcha']) != ($cap)) {

                    if ($adm == false) {
                        return array('error' => 'Invalid captcha answer!');
                    }
                }
            }
        }

        if ($for == 'module') {
            $list_id = $this->app->option->get('list_id', $for_id);
        }
        $email_to = $this->app->option->get('email_to', $for_id);
        $email_bcc = $this->app->option->get('email_bcc', $for_id);
        $email_autorespond = $this->app->option->get('email_autorespond', $for_id);


        $email_autorespond_subject = $this->app->option->get('email_autorespond_subject', $for_id);

        if (!isset($list_id) or $list_id == false) {
            $list_id = 0;
        }

        $to_save = array();
        $fields_data = array();
        $more = $this->app->fields->get($for, $for_id, 1);
        $cf_to_save = array();
        if (!empty($more)) {
            foreach ($more as $item) {
                if (isset($item['custom_field_name'])) {
                    $cfn = ($item['custom_field_name']);

                    $cfn2 = str_replace(' ', '_', $cfn);


                    if (isset($params[$cfn2]) and $params[$cfn2] != false) {
                        $fields_data[$cfn2] = $params[$cfn2];
                        $item['custom_field_value'] = $item['value'] = $params[$cfn2];
                        $cf_to_save[$cfn] = $item;
                    } elseif (isset($params[$cfn]) and $params[$cfn] != false) {
                        $fields_data[$cfn] = $params[$cfn];
                        $item['custom_field_value'] = $item['value'] = $params[$cfn2];
                        $cf_to_save[$cfn] = $item;
                    }

                }
            }
        }
        $to_save['list_id'] = $list_id;
        $to_save['rel_id'] = $for_id;
        $to_save['rel'] = $for;
        // $to_save['allow_html'] = 1;
        //$to_save['custom_fields'] = $fields_data;

        if (isset($params['module_name'])) {
            $to_save['module_name'] = $params['module_name'];
        }

        if (isset($params['form_values'])) {
            $to_save['form_values'] = $params['form_values'];
        }

        $save = $this->app->database->save($table, $to_save);

        if (!empty($cf_to_save)) {
            $table_custom_field = MW_TABLE_PREFIX . 'custom_fields';
            foreach ($cf_to_save as $key => $value) {
                $new_field = array();
                $new_field['copy_of_field'] = $value['id'];
                $new_field['id'] = 0;
                if (isset($value['session_id'])) {
                    unset($value['session_id']);
                }
                $new_field['rel_id'] = $save;
                $new_field['rel'] = 'forms_data';
                $new_field['allow_html'] = 1;
                $new_field['custom_field_value'] = $value['custom_field_value'];
                $new_field['custom_field_type'] = $value['custom_field_type'];
                $new_field['custom_field_name'] = $key;
                $cf_save = $this->app->database->save($table_custom_field, $new_field);
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
            $notif['content'] = "You have new form entry from " . $this->app->url->current(1) . '<br />' . $this->app->format->array_to_ul($pp_arr);
            $this->app->notifications->save($notif);
        
            if ($email_to == false) {
                $email_to = $this->app->option->get('email_from', 'email');
            }
            $admin_user_mails = array();
            if ($email_to == false) {
                $admins = $this->app->user->get_all('is_admin=y');
                if (is_array($admins) and !empty($admins)) {
                    foreach ($admins as $admin) {
                        if (isset($admin['email']) and (filter_var($admin['email'], FILTER_VALIDATE_EMAIL))) {
                            $admin_user_mails[] = $admin['email'];
                            $email_to = $admin['email'];
                        }
                    }
                }
            }


            if ($email_to != false) {
                $mail_sj = "Thank you!";
                $mail_autoresp = "Thank you for your request!";

                if ($email_autorespond_subject != false) {
                    $mail_sj = $email_autorespond_subject;
                }
                if ($email_autorespond != false) {
                    $mail_autoresp = $email_autorespond;
                }

                $mail_autoresp = $mail_autoresp . $this->app->format->array_to_ul($pp_arr);

                $user_mails = array();
                if (isset($admin_user_mails) and !empty($admin_user_mails)) {
                    $user_mails = $admin_user_mails;
                }

                $user_mails[] = $email_to;
                if (isset($email_bcc) and (filter_var($email_bcc, FILTER_VALIDATE_EMAIL))) {
                    $user_mails[] = $email_bcc;
                }


                if (isset($cf_to_save) and !empty($cf_to_save)) {
                    foreach ($cf_to_save as $value) {
                        $to = $value['custom_field_value'];
                        if (isset($to) and (filter_var($to, FILTER_VALIDATE_EMAIL))) {
                            $user_mails[] = $to;
                        }
                    }
                }
                $scheduler = new \Microweber\Utils\Events();
                if (!empty($user_mails)) {
                    array_unique($user_mails);
                    foreach ($user_mails as $value) {
                        \Microweber\email\Sender::send($value, $mail_sj, $mail_autoresp);
                        // $scheduler->registerShutdownEvent("\Microweber\email\Sender::send", $value, $mail_sj, $mail_autoresp);
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
        return $this->app->database->get($params);
    }

    public function  countries_list($force = false)
    {

        $function_cache_id = false;
        $args = func_get_args();
        foreach ($args as $k => $v) {
            $function_cache_id = $function_cache_id . serialize($k) . serialize($v);
        }
        $function_cache_id = 'forms_' . __FUNCTION__ . crc32($function_cache_id);
        $cache_content = $this->app->cache->get($function_cache_id, 'forms');
        if ($force == false and ($cache_content) != false) {

            return $cache_content;
        }

        $table = MW_DB_TABLE_COUNTRIES;

        if (!$this->app->database->table_exist($table)) {
            $this->db_init();
        }

        $sql = "SELECT name AS country_name FROM $table   ";
        $q = $this->app->database->query($sql, 'get_countries_list' . crc32($sql), 'forms');
        $res = array();
        if (is_array($q) and !empty($q)) {
            foreach ($q as $value) {
                $res[] = $value['country_name'];
            }
            $this->app->cache->save($res, $function_cache_id, $cache_group = 'forms');
            return $res;
        } else {
            $this->db_init();
            $this->app->cache->delete('forms');
            return false;
        }

    }

    public function delete_entry($data)
    {

        $adm = $this->app->user->is_admin();
        if ($adm == false) {
            return array('error' => 'Error: not logged in as admin.' . __FILE__ . __LINE__);
        }

        if (isset($data['id'])) {
            $c_id = intval($data['id']);

            $fields = $this->app->fields->get('forms_data', $data['id'], 1);

            if (is_array($fields)) {
                foreach ($fields as $key => $value) {
                    if (isset($value['id'])) {
                        $remid = $value['id'];
                        $custom_field_table = MW_TABLE_PREFIX . 'custom_fields';
                        $q = "DELETE FROM $custom_field_table WHERE id='$remid'";
                        $this->app->database->q($q);
                    }
                }
                $this->app->cache->delete('custom_fields');
            }

            $this->app->database->delete_by_id('forms_data', $c_id);
        }
        return true;
    }

    public function delete_list($data)
    {

        $adm = $this->app->user->is_admin();
        if ($adm == false) {
            return array('error' => 'Error: not logged in as admin.' . __FILE__ . __LINE__);
        }

        if (isset($data['id'])) {
            $c_id = intval($data['id']);
            $this->app->database->delete_by_id('forms_lists', $c_id);
            $this->app->database->delete_by_id('forms_data', $c_id, 'list_id');

        }
        return true;
    }

    public function export_to_excel($params)
    {
        //this function is experimental
        set_time_limit(0);

        $adm = $this->app->user->is_admin();
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
            $surl = $this->app->url->site();
            $csv_output = '';
            if (isset($custom_fields) and is_array($custom_fields)) {
                $csv_output = 'id,';
                $csv_output .= 'created_on,';
                $csv_output .= 'user_ip,';
                foreach ($custom_fields as $k => $item) {
                    $csv_output .= $this->app->format->no_dashes($k) . ",";
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

                            $output_val = false;
                            if (isset($item1['custom_field_values_plain']) and $item1['custom_field_values_plain'] != '') {
                                $output_val = $item1['custom_field_values_plain'];
                            } elseif (isset($item1['custom_field_values']) and $item1['custom_field_values'] != '') {
                                $output_val = $item1['custom_field_values'];
                            } elseif (isset($item1['custom_field_value']) and $item1['custom_field_value'] != '') {
                                $output_val = $item1['custom_field_value'];
                            }

                            $output_val = str_replace('{SITE_URL}', $surl, $output_val);

                            $csv_output .= $output_val . ",";
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
            $download = $this->app->url->link_to_file($filename_path_full);
            return array('success' => 'Your file has been exported!', 'download' => $download);

        }
    }

}