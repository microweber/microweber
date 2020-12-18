<?php

namespace Microweber\Providers;

api_expose_admin('fields/reorder');
api_expose_admin('fields/delete');
api_expose_admin('fields/make');
api_expose_admin('fields/save');
$_mw_made_default_fields_register = array();

class FieldsManager
{
    public $app;
    public $tables = array();
    public $table = 'custom_fields';
    public $table_values = 'custom_fields_values';
    private $skip_cache = false;

    public function __construct($app = null)
    {
        if (!is_object($this->app)) {
            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = mw();
            }
        }
        $this->tables = $this->app->content_manager->tables;
    }

    public function get_by_id($field_id)
    {
        if ($field_id != 0) {
            $params = array();
            $params['id'] = $field_id;
            $params['return_full'] = true;

            $data = $this->get($params);

            if (isset($data[0])) {
                return $data[0];
            }
        }
    }


    public function parse_field_settings($fieldParse) {

        preg_match_all(
            '/(\[.*\])/',
            $fieldParse,
            $fieldParseMatches,
            PREG_PATTERN_ORDER
        );

        if (isset($fieldParseMatches[0][0])) {
            $fieldParse = $fieldParseMatches[0][0];
            $fieldParse = str_replace('[', false, $fieldParse);
            $fieldParse = str_replace(']', false, $fieldParse);
            $fieldParse = str_replace(',', '&', $fieldParse);
            $fieldParse = str_replace(' ', false, $fieldParse);
            parse_str($fieldParse, $fieldParseQuery);

            return $fieldParseQuery;
        }

        return false;
    }

    public function parse_fields_html($fieldParseInput) {

        if (is_array($fieldParseInput)) {
            return $fieldParseInput;
        }

        preg_match_all(
            '/(\[.*?\])/',
            $fieldParseInput,
            $fieldParseMatches,
            PREG_PATTERN_ORDER
        );

        // Clear comas from settings
        if (isset($fieldParseMatches[0])) {
            foreach ($fieldParseMatches[0] as $fieldParseMatch) {

                $fieldParseReady = str_replace(',', '&', $fieldParseMatch);
                $fieldParseReady = str_replace(' ', false, $fieldParseReady);

                $fieldParseInput = str_replace($fieldParseMatch, $fieldParseReady, $fieldParseInput);
            }
        }

        $readyFields = array();

        $explodeFields = explode(',', $fieldParseInput);
        $explodeFields = array_trim($explodeFields);

        if (is_array($explodeFields) && !empty($explodeFields)) {
            foreach ($explodeFields as $field) {

                $fieldsSettings = $this->parse_field_settings($field);
                $fieldName = preg_replace('/(\[.*?\])/', false, $field);
                $readyFields[] = array(
                    'name' => $fieldName,
                    'settings' => $fieldsSettings
                );
            }
        }

        return $readyFields;
    }

    public function make_default($rel, $rel_id, $fields_csv_str)
    {
        global $_mw_made_default_fields_register;
        
        if (!defined('SKIP_CF_ADMIN_CHECK')) {
            define('SKIP_CF_ADMIN_CHECK', 1);
        }

        // return false;
        $id = $this->app->user_manager->is_admin();
        if ($id == false) {
            //return false;
        }

        $function_cache_id = false;

        $args = func_get_args();

        foreach ($args as $k => $v) {
            $function_cache_id = $function_cache_id . serialize($k) . serialize($v);
        }

        $function_cache_id = 'fields_' . __FUNCTION__ . crc32($function_cache_id);

        $is_made = $this->app->option_manager->get($function_cache_id, 'make_default_custom_fields');

        $make_field = array();

        $make_field['rel_type'] = $rel;
        $make_field['rel_id'] = $rel_id;
      //  $is_made = $this->get_all($make_field);

        if (isset($_mw_made_default_fields_register[$function_cache_id])) {
            return $_mw_made_default_fields_register[$function_cache_id];
        }

        if ($is_made) {
            return;
        }

       /* if (is_array($is_made) and !empty($is_made)) {
            return;
        }
        */

        $table_custom_field = $this->table;

        $saved_fields = array();
        if (isset($rel)) {
            $rel = $this->app->database_manager->escape_string($rel);

            $rel = $this->app->database_manager->assoc_table_name($rel);
            $rel_id = $this->app->database_manager->escape_string($rel_id);

            $fields_csv_str = $this->parse_fields_html($fields_csv_str);

            $pos = 0;
            if (is_array($fields_csv_str)) {
                foreach ($fields_csv_str as $field) {

                    $field_name = $field['name'];

                    $show_placeholder = false;
                    $show_label = true;
                    $existing = array();
                    $as_text_area = false;
                    $field_type = 'text';
                    $field_size = 12;
                    $field_name_lower = strtolower($field_name);

                    if (strpos($field_name_lower, 'message') !== false) {
                        $as_text_area = true;
                        $field_type = 'text';
                    }

                    $fields = mw()->ui->custom_fields();
                    if (array_key_exists($field_name_lower, $fields)) {
                        $field_type = $field_name;
                    }
                    if (in_array($field_name_lower, $fields)) {
                        $field_type = $field_name;
                    }

                    if (isset($field['settings']['type'])) {
                        if ($field['settings']['type'] == 'textarea') {
                            $as_text_area = true;
                            $field_type = 'text';
                        } else {
                            $field_type = $field['settings']['type'];
                        }
                    }

                    if (isset($field['settings']['show_label'])) {
                        if ($field['settings']['show_label'] == 'false' || $field['settings']['show_label'] == 0 || $field['settings']['show_label'] == '0') {
                            $show_label = false;
                        }
                        if ($field['settings']['show_label'] == 'true' || $field['settings']['show_label'] == 1 || $field['settings']['show_label'] == '1') {
                            $show_label = true;
                        }
                    }

                    if (isset($field['settings']['show_placeholder'])) {
                        if ($field['settings']['show_placeholder'] == 'false' || $field['settings']['show_placeholder'] == 0 || $field['settings']['show_placeholder'] == '0') {
                            $show_placeholder = false;
                        }
                        if ($field['settings']['show_placeholder'] == 'true' || $field['settings']['show_placeholder'] == 1 || $field['settings']['show_placeholder'] == '1') {
                            $show_placeholder = true;
                            $show_label = false;
                        }
                    }

                    if (isset($field['settings']['field_size'])) {
                        $field_size = $field['settings']['field_size'];
                    }

                    $existing['name'] = $field_name;
                    $existing['type'] = $field_type;
                    $existing['rel_type'] = $rel;
                    $existing['rel_id'] = $rel_id;
                   // $existing['no_cache'] = $rel_id;
                    $existing = $this->get_all($existing);
                    if ($existing == false or is_array($existing) == false) {

                        $make_field = array();
                        $make_field['rel_type'] = $rel;
                        $make_field['rel_id'] = $rel_id;
                        $make_field['position'] = $pos;
                        $make_field['name'] = ucfirst($field_name);
                        $make_field['value'] = '';
                        $make_field['show_label'] = $show_label;

                        $make_field['show_placeholder'] = $show_placeholder;
                        if ($show_placeholder) {
                            $make_field['placeholder'] = ucfirst($field_name);
                        }

                        $make_field['type'] = $field_type;
                        $make_field['options']['field_type'] = $field_type;
                        $make_field['options']['field_size'] = $field_size;

                        if ($as_text_area) {
                            $make_field['options']['as_text_area'] = $as_text_area;
                        }

                        $saved_fields[] = $this->save($make_field);
                        ++$pos;
                    }
                }

                $option = array();
                $option['option_value'] = true;
                $option['option_key'] = $function_cache_id;
                $option['option_group'] = 'make_default_custom_fields';

                $this->app->option_manager->save($option);
                if ($pos > 0) {
                    $this->app->cache_manager->delete('custom_fields/global');
                }
            }
        }
        $_mw_made_default_fields_register[$function_cache_id] = $saved_fields;

        return $saved_fields;
    }

    public function save($data)
    {
        if (is_string($data)) {
            $data = parse_params($data);
        }

        $table_custom_field = $this->table;
        $table_values = $this->table_values;
        if (isset($data['cf_id']) and !isset($data['id'])) {
            $data['id'] = $data['cf_id'];
        }
        if (isset($data['field_type']) and !isset($data['type'])) {
            $data['type'] = $data['field_type'];
        }
        if (isset($data['custom_field_type']) and !isset($data['type'])) {
            $data['type'] = $data['custom_field_type'];
        }

        if (isset($data['field_value'])) {
            $data['value'] = $data['field_value'];
        }
        if (isset($data['field_name']) and !isset($data['name'])) {
            $data['name'] = $data['field_name'];
        }

        if (isset($data['for']) and !isset($data['rel_type'])) {
            $data['rel_type'] = $data['for'];
        }

        if (isset($data['type']) and !isset($data['name'])) {
            $data['name'] = $data['type'];
        }

        if (isset($data['rel']) and !isset($data['rel_type'])) {
            $data['rel_type'] = $data['rel'];
        }

        if (isset($data['options']['field_type']) && !empty($data['options']['field_type'])) {
            $data['type'] = $data['options']['field_type'];
        }

        if (isset($data['custom_field_show_label'])) {
            if ($data['custom_field_show_label'] == 'y') {
                $data['show_label'] = 1;
            } else {
                $data['show_label'] = 0;
            }
        }

        if (isset($data['show_label'])) {
            if ($data['show_label'] == true) {
                $data['show_label'] = 1;
            }
            if ($data['show_label'] == false) {
                $data['show_label'] = 0;
            }
        }

        if (isset($data['show_placeholder'])) {
            if ($data['show_placeholder'] == true) {
                $data['show_placeholder'] = 1;
            }
            if ($data['show_placeholder'] == false) {
                $data['show_placeholder'] = 0;
            }
        }

        $data_to_save = ($data);
        $data_to_save = $this->unify_params($data_to_save);

        if (isset($data_to_save['for_id'])) {
            $data_to_save['rel_id'] = $data_to_save['for_id'];
        }
        if (isset($data_to_save['id'])) {
            $data_to_save['cf_id'] = $data_to_save['id'];
        }
        if (isset($data_to_save['cf_id'])) {
            $data_to_save['id'] = intval($data_to_save['cf_id']);
            $table_custom_field = $this->table;
            $form_data_from_id = $this->app->database_manager->get_by_id($table_custom_field, $data_to_save['id'], $is_this_field = false);
            if (isset($form_data_from_id['id'])) {
                if (!isset($data_to_save['rel_type'])) {
                    $data_to_save['rel_type'] = $form_data_from_id['rel_type'];
                }
                if (!isset($data_to_save['rel_id'])) {
                    $data_to_save['rel_id'] = $form_data_from_id['rel_id'];
                }
                if (isset($form_data_from_id['type']) and $form_data_from_id['type'] != '' and (!isset($data_to_save['type']) or ($data_to_save['type']) == '')) {
                    $data_to_save['type'] = $form_data_from_id['type'];
                }
                if (isset($form_data_from_id['name']) and $form_data_from_id['name'] != '' and (!isset($data_to_save['name']) or ($data_to_save['name']) == '')) {
                    $data_to_save['name'] = $form_data_from_id['name'];
                }
            }

            if (isset($data_to_save['copy_rel_id'])) {
                $cp = $this->app->database_manager->copy_row_by_id($table_custom_field, $data_to_save['cf_id']);
                $data_to_save['id'] = $cp;
                $data_to_save['rel_id'] = $data_to_save['copy_rel_id'];
            }
        }

        if (!isset($data_to_save['rel_type'])) {
            $data_to_save['rel_type'] = 'content';
        }
        $data_to_save['rel_type'] = $this->app->database_manager->assoc_table_name($data_to_save['rel_type']);
        if (!isset($data_to_save['rel_id'])) {
            $data_to_save['rel_id'] = '0';
        }
        if (isset($data['options'])) {
            $data_to_save['options'] = $this->_encode_options($data['options']);
        }

        $data_to_save['session_id'] = mw()->user_manager->session_id();

        if (!isset($data_to_save['value']) and isset($data_to_save['field_value'])) {
            $data_to_save['value'] = $data_to_save['field_value'];
        } elseif (isset($data_to_save['values'])) {
            $data_to_save['value'] = $data_to_save['values'];
        }

        if ((!isset($data_to_save['id']) or ($data_to_save['id']) == 0) and !isset($data_to_save['is_active'])) {
            $data_to_save['is_active'] = 1;
        }

        if (!isset($data_to_save['type']) or trim($data_to_save['type']) == '') {
            return array('error' => 'You must set type');
        } else {

            if (isset($data_to_save['name']) && empty($data_to_save['name'])) {
                return array('error' => 'You must set name');
            }

            if (isset($data_to_save['name'])) {
                $cf_k = $data_to_save['name'];
                if ($cf_k != false and !isset($data_to_save['name_key'])) {
                    $data_to_save['name_key'] = $this->app->url_manager->slug(strtolower($cf_k));
                }
            }

            $data_to_save['allow_html'] = true;
            if (!isset($data_to_save['id'])) {
                $data_to_save['id'] = 0;
            }

            // $this->skip_cache = true;
            $data_to_save['table'] = $table_custom_field;
            $data_to_save['allow_html'] = false;
            $data_to_save_parent = $data_to_save;
            if (isset($data_to_save_parent['value'])) {
                unset($data_to_save_parent['value']);
            }

            $save = $this->app->database_manager->save($data_to_save_parent);

            if (!isset($data_to_save['value'])) {
                if ($data_to_save['type'] == 'radio' || $data_to_save['type'] == 'checkbox' || $data_to_save['type'] == 'dropdown') {
                    $data_to_save['value'][] = 'option 1';
                    $data_to_save['value'][] = 'option 2';
                    $data_to_save['value'][] = 'option 3';
                }
            }

            if (isset($data_to_save['value'])) {
                $custom_field_id = $save;
                $values_to_save = array();
                if (!is_array($data_to_save['value'])) {
                    $values_to_save = array($data_to_save['value']);
                } elseif (is_array($data_to_save['value'])) {
                    $values_to_save = ($data_to_save['value']);
                }

                if (!empty($values_to_save)) {
                    $check_existing = array();
                    $check_existing['table'] = $table_values;
                    $check_existing['custom_field_id'] = $custom_field_id;
                    $check_old = $this->app->database_manager->get($check_existing);
                    $i = 0;
                    foreach ($values_to_save as $value_to_save) {
                        $save_value = array();
                        if (isset($check_old[$i]) and isset($check_old[$i]['id'])) {
                            $save_value['id'] = $check_old[$i]['id'];
                            unset($check_old[$i]);
                        }
                        $save_value['custom_field_id'] = $custom_field_id;
                        $save_value['value'] = $value_to_save;
                        if (is_array($value_to_save)) {
                            $save_value['value'] = implode(',', array_values($value_to_save));
                        }
                        $save_value['position'] = $i;
                        $save_value['allow_html'] = false;
                        $save_value = $this->app->database_manager->save($table_values, $save_value);
                        ++$i;
                    }
                    if (!empty($check_old)) {
                        $remove_old_ids = array();
                        foreach ($check_old as $remove) {
                            $remove_old_ids[] = $remove['id'];
                        }
                        if (!empty($remove_old_ids)) {
                            $remove_old = $this->app->database_manager->delete_by_id($table_values, $remove_old_ids);
                        }
                    }
                }
            }

            $this->app->cache_manager->delete('custom_fields/' . $save);
            $this->app->cache_manager->delete('custom_fields');

            return $save;
        }
    }

    public function get_values($custom_field_id)
    {
        $id = $custom_field_id;
        if (is_array($custom_field_id)) {
            $id = implode(',', $custom_field_id);
        }
        $table = $this->table_values;
        $params = array();
        $params['table'] = $table;
        $params['limit'] = 99999;
        $params['custom_field_id'] = '[in]' . $id;
        $data = $this->app->database_manager->get($params);

        return $data;
    }

    public function get_value($content_id, $field_name, $return_full = false, $table = 'content')
    {
        $val = false;
        $data = $this->get($table, $id = $content_id, $return_full, $field_for = false, $debug = false, $field_type = false, $for_session = false);
        foreach ($data as $item) {
            if (isset($item['name']) and
                ((strtolower($item['name']) == strtolower($field_name))
                    or (strtolower($item['type']) == strtolower($item['type'])))
            ) {
                $val = $item['value'];
            }
        }

        return $val;
    }

    public function get_all($params)
    {
        if (!is_array($params)) {
            $params = parse_params($params);
        }
        $table_custom_field = $this->table;
        $params['table'] = $table_custom_field;

        return $this->app->database_manager->get($params);
    }

    public function get($table, $id = 0, $return_full = false, $field_for = false, $debug = false, $field_type = false, $for_session = false)
    {
        $params = array();
        $no_cache = false;
        $table_assoc_name = false;
        // $id = intval ( $id );
        if (is_string($table)) {
            $params = $params2 = parse_params($table);
            if (!is_array($params2) or (is_array($params2) and count($params2) < 2)) {
                $id = trim($id);
                $table = $this->app->database_manager->escape_string($table);
                if ($table != false) {
                    $table_assoc_name = $this->app->database_manager->assoc_table_name($table);
                } else {
                    $table_assoc_name = 'MW_ANY_TABLE';
                }
                if ($table_assoc_name == false) {
                    $table_assoc_name = $this->app->database_manager->assoc_table_name($table_assoc_name);
                }
                $params['rel_type'] = $table_assoc_name;
            } else {
                $params = $params2;
            }
        } elseif (is_array($table)) {
            $params = $table;
        }

        $params = $this->unify_params($params);

        if (!isset($table_assoc_name)) {
            if (isset($params['for'])) {
                $params['rel_type'] = $table_assoc_name = $this->app->database_manager->assoc_table_name($params['for']);
            }
        } else {
            // $params['rel_type'] = $table_assoc_name;
        }

        if (isset($params['debug'])) {
            $debug = $params['debug'];
        }
        if (isset($params['for'])) {
            if (!isset($params['rel_type']) or $params['rel_type'] == false) {
                $params['for'] = $params['rel_type'];
            }
        }
        if (isset($params['for_id'])) {
            $params['rel_id'] = $id = $this->app->database_manager->escape_string($params['for_id']);
        }

        if (isset($params['field_type'])) {
            $params['type'] = $params['field_type'];
        }
        if (isset($params['field_value'])) {
            $params['value'] = $params['field_value'];
        }

        if (isset($params['no_cache'])) {
            $no_cache = $params['no_cache'];
        }

        if (isset($params['return_full'])) {
            $return_full = $params['return_full'];
        }

        if (isset($params['is_active']) and strtolower(trim($params['is_active'])) == 'any') {
        } elseif (isset($params['is_active']) and $params['is_active'] == 0) {
            $custom_field_is_active = 0;
        } else {
            $custom_field_is_active = 1;
        }

        $table_custom_field = $this->table;
        $params['table'] = $table_custom_field;
        if (isset($custom_field_is_active)) {
            $params['is_active'] = $custom_field_is_active;
        }
        if (strval($table_assoc_name) != '') {
            if ($field_for != false) {
                $field_for = trim($field_for);
                $field_for = $this->app->database_manager->escape_string($field_for);
                $params['name'] = $field_for;
            }

            if (isset($params['rel_type']) and $params['rel_type'] == 'MW_ANY_TABLE') {
                unset($params['rel_type']);
            }

            $sidq = '';
            if (intval($id) == 0 and $for_session != false) {
                if (is_admin() != false) {
                    $sid = mw()->user_manager->session_id();
                    $params['session_id'] = $sid;
                }
            }

            if ($id != 'all' and $id != 'any') {
                $id = $this->app->database_manager->escape_string($id);

                $params['rel_id'] = $id;

            }
        }
        if (isset($params['content'])) {
            unset($params['content']);
        }
        if (!isset($params['order_by']) and !isset($params['orderby'])) {
            $params['order_by'] = 'position asc';
        }

        if (empty($params)) {
            return false;
        }

        $q = $this->app->database_manager->get($params);

        if (!empty($q)) {
            $get_values = array();
            $fields = array();
            foreach ($q as $k => $v) {
                $get_values[] = $v['id'];
            }

            $vals = $this->get_values($get_values);

            foreach ($q as $k => $v) {
                if (isset($v['options']) and is_string($v['options'])) {
                    $v['options'] = $this->_decode_options($v['options']);
                }
                $default_values = $v;
                $default_values['values_plain'] = '';
                $default_values['value'] = array();
                $default_values['values'] = array();

                if (!empty($vals)) {
                    foreach ($vals as $val) {
                        if ($val['custom_field_id'] == $v['id']) {
                            $default_values['value'][] = $val['value'];
                            $default_values['values'][] = $val['value'];
                        }
                    }
                }
                if (!empty($default_values['value'])) {

//                    if (count($default_values['value']) == 1) {
//                        $default_values['value'] = reset($default_values['value']);
//                    }

                    $default_values['value_plain'] = $default_values['value'];
                    $default_values['value_plain'] = $default_values['value'];
                    if (is_array($default_values['value'])) {
                        $default_values['value'] = reset($default_values['value']);
                        $default_values['value_plain'] = $default_values['value'];

                    }

                } else {
                    $default_values['value'] = false;
                    $default_values['value_plain'] = false;
                }

                $fields[$k] = $default_values;
            }

            $q = $fields;
        }

        if (isset($params['fields'])) {
            return $q;
        }
        if (!empty($q)) {
            $the_data_with_custom_field__stuff = array();

            if ($return_full == true) {
                $to_ret = array();
                $i = 1;
                foreach ($q as $it) {
                    $it = $this->decode_array_vals($it);
                    //  $it['type'] = $it['type'];
                    $it['position'] = $i;
                    if (isset($it['options']) and is_string($it['options'])) {
                        // $it['options'] = $this->_decode_options($it['options']);
                    }

                    $it['title'] = $it['name'];
                    $to_ret[] = $it;
                    ++$i;
                }

                return $to_ret;
            }

            $append_this = array();
            if (is_array($q) and !empty($q)) {
                foreach ($q as $q2) {
                    $i = 0;

                    $the_name = false;

                    $the_val = false;

                    foreach ($q2 as $cfk => $cfv) {
                        if ($cfk == 'name') {
                            $the_name = $cfv;
                        }

                        if ($cfk == 'value') {
                            $the_val = $cfv;
                        }

                        ++$i;
                    }

                    if ($the_name != false and $the_val !== null) {
                        if ($return_full == false) {
                            $the_name = strtolower($the_name);

                            $the_data_with_custom_field__stuff[$the_name] = $the_val;
                        } else {
                            $the_data_with_custom_field__stuff[$the_name] = $q2;
                        }
                    }
                }
            }

            $result = $the_data_with_custom_field__stuff;
            //$result = (array_change_key_case($result, CASE_LOWER));
            $result = $this->app->url_manager->replace_site_url_back($result);

            //

            return $result;
        }

        return $q;
    }

    public function unify_params($data)
    {
        if (isset($data['rel_type'])) {
            if ($data['rel_type'] == 'content' or $data['rel_type'] == 'page' or $data['rel_type'] == 'post') {
                $data['rel_type'] = 'content';
            }
         //   $data['rel_type'] = $data['rel_type'];
        }

        if (isset($params['content_id'])) {
            $params['for'] = 'content';
            $params['for_id'] = $params['content_id'];
        }
        if (isset($data['content_id'])) {
            $data['rel_type'] = 'content';
            $data['rel_id'] = intval($data['content_id']);
        }

        if (!isset($data['type']) and isset($data['field_type']) and $data['field_type'] != '') {
            $data['type'] = $data['field_type'];
        }

        if (isset($data['field_type']) and (!isset($data['type']))) {
            $data['type'] = $data['field_type'];
        }
        if (isset($data['field_name']) and (!isset($data['name']))) {
            $data['name'] = $data['field_name'];
        }
        if (isset($data['field_value'])) {
            $data['value'] = $data['value'] = $data['field_value'];
        }

        if (!isset($data['name']) and isset($data['field_name']) and $data['field_type'] != '') {
            $data['name'] = $data['field_name'];
        }

        if (!isset($data['value']) and isset($data['field_value']) and $data['field_value'] != '') {
            $data['value'] = $data['field_value'];
        }
        if (!isset($data['rel_type']) and isset($data['for'])) {
            $data['rel_type'] = $this->app->database_manager->assoc_table_name($data['for']);
        }

        if (!isset($data['cf_id']) and isset($data['id'])) {
            //   $data['cf_id'] = $data['id'];
        }
        if (!isset($data['rel_id'])) {
            if (isset($data['data-id'])) {
                $data['rel_id'] = $data['data-id'];
            }
        }
        if (!isset($data['is_active']) and isset($data['cf_id']) and $data['cf_id'] == 0) {
            $data['is_active'] = 1;
        }

        if (!isset($data['rel_type']) and isset($data['for'])) {
            $data['rel_type'] = $data['for'];
        }
        if (isset($data['for_id'])) {
            $data['rel_id'] = $data['for_id'];
        }

        return $data;
    }

    public function decode_array_vals($it)
    {
        if (isset($it['value'])) {
            if (isset($it['value']) and is_string($it['value']) and strtolower($it['value']) == 'array') {
                if (isset($it['values']) and is_string($it['values'])) {
                    $try = base64_decode($it['values']);
                    if ($try != false and strlen($try) > 5) {
                        $it['values'] = unserialize($try);
                    }
                    if (isset($it['values']['value'])) {
                        $temp = $it['values']['value'];
                        if (is_array($it['values']['value'])) {
                            $temp = array();
                            foreach ($it['values']['value'] as $item1) {
                                if ($item1 != false) {
                                    $item1 = explode(',', $item1);
                                    $temp = array_merge($temp, $item1);
                                }
                            }
                        }
                        $it['values'] = $temp;
                    }
                }
            }
        }

        if (isset($it['options']) and is_string($it['options'])) {
            $it['options'] = $this->_decode_options($it['options']);
        }

        return $it;
    }

    public function reorder($data)
    {
        $adm = $this->app->user_manager->is_admin();
        if ($adm == false) {
            $this->app->error('Error: not logged in as admin.' . __FILE__ . __LINE__);
        }

        $table = $this->table;

        foreach ($data as $value) {
            if (is_array($value)) {
                $indx = array();
                $i = 0;
                foreach ($value as $value2) {
                    $indx[$i] = $value2;
                    ++$i;
                }

                $this->app->database_manager->update_position_field($table, $indx);

                return true;
            }
        }
    }

    public function delete($id)
    {
        $uid = $this->app->user_manager->is_admin();
        if (defined('MW_API_CALL') and $uid == false) {
            exit('Error: not logged in as admin.' . __FILE__ . __LINE__);
        }
        if (is_array($id)) {
            extract($id);
        }

        $id = intval($id);
        if (isset($cf_id)) {
            $id = intval($cf_id);
        }

        if ($id == 0) {
            return false;
        }

        $custom_field_table = $this->table;
        $custom_field_table_values = $this->table_values;
        $this->app->database_manager->delete_by_id($custom_field_table, $id);
        $this->app->database_manager->delete_by_id($custom_field_table_values, $id, 'custom_field_id');
        $this->app->cache_manager->delete('custom_fields');

		if(mw()->modules->is_installed('shop/offers')){
			$this->app->database_manager->delete_by_id('offers', $id, 'price_id');
		}

        return $id;
    }

    public function make_field($field_id = 0, $field_type = 'text', $settings = false)
    {
        $data = false;
        $form_data = array();
        if (is_array($field_id)) {
            if (!empty($field_id)) {
                $data = $field_id;

                return $this->make($field_id, false, 'y');
            }
        } else {
            if ($field_id != 0) {
                return $this->make($field_id);

                //
                // $this->app->error('no permission to get data');
                //  $form_data = $this->app->database_manager->get_by_id('custom_fields', $id = $field_id, $is_this_field = false);
            }
        }
    }

    /**
     * make_field.
     *
     * @desc        make_field
     *
     * @category    forms
     *
     * @author      Microweber
     *
     * @link        http://microweber.com
     *
     * @param string $field_type
     * @param string $field_id
     * @param array $settings
     */
    public function make($field_id = 0, $field_type = 'text', $settings = false)
    {
        if (is_array($field_id)) {
            if (!empty($field_id)) {
                $data = $field_id;
            }
        } else {
            if ($field_id != 0) {
                $data = $this->get_by_id($id = $field_id);
            }
        }
        if (isset($data['settings']) or (isset($_REQUEST['settings']) and trim($_REQUEST['settings']) == 'y')) {
            $settings = true;
        }
        
        $params = false;
        if ((isset($_REQUEST['params']) and ($_REQUEST['params']) )) {
        	$params = $_REQUEST['params'];
        }
        
        if ((isset($_REQUEST['field_id']) and ($_REQUEST['field_id']) )) {
        	$data['field_id'] = $_REQUEST['field_id'];
        }

  		 //d($data);
        //input_class
        
        if (isset($data['copy_from'])) {
            $copy_from = intval($data['copy_from']);
            if (is_admin() == true) {
                $table_custom_field = $this->table;
                $form_data = $this->app->database_manager->get_by_id($table_custom_field, $id = $copy_from);
                if (is_array($form_data)) {
                    $field_type = $form_data['type'];
                    $data['id'] = 0;
                    if (isset($data['save_on_copy'])) {
                        $cp = $form_data;
                        $cp['id'] = 0;
                        $cp['copy_of_field'] = $copy_from;
                        if (isset($data['rel_type'])) {
                            $cp['rel_type'] = ($data['rel_type']);
                        }
                        if (isset($data['rel_id'])) {
                            $cp['rel_id'] = ($data['rel_id']);
                        }
                        $this->save($cp);
                        $data = $cp;
                    } else {
                        $data = $form_data;
                    }
                }
            }
        } elseif (isset($data['field_id'])) {
            $data = $this->get_by_id($id = $data['field_id']);
        }

        if (isset($data['type'])) {
            $field_type = $data['type'];
        }

        if (!isset($data['custom_field_required'])) {
            $data['custom_field_required'] = 'n';
        }

        if (isset($data['type'])) {
            $field_type = $data['type'];
        }

        if (isset($data['field_type'])) {
            $field_type = $data['field_type'];
        }

        if (isset($data['field_values']) and !isset($data['value'])) {
            $data['values'] = $data['field_values'];
        }

        if (isset($data['value']) and is_array($data['value'])) {
            $data['value'] = implode(',', $data['value']);
        }
		
        $data['type'] = $field_type;
        
        if (isset($data['options']) and is_string($data['options'])) {
            $data['options'] = $this->_decode_options($data['options']);
        }

        $data = $this->app->url_manager->replace_site_url_back($data);

        $template_files = $this->get_template_files($data);

        if ($settings || isset($data['settings'])) {
            $file = $template_files['settings_file'];
        } else {
            $file = $template_files['preview_file'];
        }

        if (!is_file($file)) {
           return;
        }

        $field_data = array();
        $field_data['name'] = false;
        $field_data['type'] = false;
        $field_data['id'] = 0;
        $field_data['placeholder'] = false;
        $field_data['help'] = false;
        $field_data['values'] = array();
        $field_data['value'] = false;
        $field_data['options'] = array();
        $field_data['options']['old_price'] = false;

        $field_settings = array();
        $field_settings['rel_id'] = false;
        $field_settings['rel_type'] = false;
        $field_settings['required'] = false;
        $field_settings['class'] = false;
        $field_settings['field_size'] = 12;
        $field_settings['as_text_area'] = false;
        $field_settings['multiple'] = false;
        $field_settings['type'] = 'button';
        $field_settings['rows'] = '5';
        $field_settings['make_select'] = false;
        $field_settings['options']['file_types'] = array();
        $field_settings['show_label'] = true;

        if (isset($data['id'])) {
            $field_data['id'] = $data['id'];
        }

        if (isset($data['placeholder'])) {
            $field_data['placeholder'] = $data['placeholder'];
        }

        if (isset($data['make_select'])) {
            $field_settings['make_select'] = $data['make_select'];
        }

        if (isset($data['name'])) {
            $field_data['name'] = $data['name'];
        }

        if (isset($data['type'])) {
            $field_data['type'] = $data['type'];
        }

        if (isset($data['rel_type'])) {
            $field_settings['rel_type'] = $data['rel_type'];
        }
        if (isset($data['rel_id'])) {
            $field_settings['rel_id'] = $data['rel_id'];
        }

        if (isset($data['help'])) {
            $field_data['help'] = $data['help'];
        }

        if (isset($data['options']['old_price'])) {
            $field_data['options']['old_price'] = $data['options']['old_price'];
        }

        if (isset($data['options']['field_size_class'])) {
            $field_settings['class'] = $data['options']['field_size_class'];
        }

        if (isset($data['options']['field_size'])) {
            $field_settings['field_size'] = $data['options']['field_size'];
        }

        if (isset($data['options']['required'])) {
            $field_settings['required'] = true;
        }

        if (isset($data['show_label'])) {
            $field_settings['show_label'] = $data['show_label'];
        }

        if (isset($data['params']['input_class'])) {
            $field_settings['class'] = $data['params']['input_class'];
        }

        // For input to textarea
        if (isset($data['options']['as_text_area'])) {
            $field_settings['as_text_area'] = true;
        }

        // For dropdown select
        if (isset($data['options']['multiple'])) {
            $field_settings['multiple'] = true;
        }

        // For textarea
        if (isset($data['options']['rows'])) {
            $field_settings['rows'] = $data['options']['rows'];
        }

        if (isset($data['value'])) {
            $field_data['value'] = $data['value'];
        }

        if (is_array($data['value'])) {
            $field_data['value'] = implode(',', $data['value']);
        }

        if (is_array($data['values']) && !empty($data['values'])) {
            $field_data['values'] = $data['values'];
        }

        if (isset($data['options']['field_type'])) {
            $field_settings['type'] = $data['options']['field_type'];
        }

        // For file upload
        if ($data['type'] == 'upload') {
            if (is_array($data['options']) && isset($data['options']['file_types'])) {
                $field_settings['options']['file_types'] = array_merge($field_data['options'], $data['options']['file_types']);
            }
        }

        // For address type options
        if ($data['type'] == 'address') {

            if ($data['values'] == false || !is_array($data['values']) || !is_array($data['values'][0])) {

                $default_address_fields = array('country' => 'Country', 'city' => 'City', 'zip' => 'Zip/Post code', 'state' => 'State/Province', 'address' => 'Address');

                $field_data['default_address_fields'] = $default_address_fields;

                $skip_fields = array();
                if (isset($params['skip-fields']) and $params['skip-fields'] != '') {
                    $skip_fields = explode(',', $params['skip-fields']);
                    $skip_fields = array_trim($skip_fields);
                }

                $selected_address_fields = array();
                if (isset($data['options']['country'])) {
                    $selected_address_fields[] = 'country';
                }
                if (isset($data['options']['city'])) {
                    $selected_address_fields[] = 'city';
                }
                if (isset($data['options']['zip'])) {
                    $selected_address_fields[] = 'zip';
                }
                if (isset($data['options']['state'])) {
                    $selected_address_fields[] = 'state';
                }
                if (isset($data['options']['address'])) {
                    $selected_address_fields[] = 'address';
                }

                if (!empty($selected_address_fields)) {
                    $new_address_fields = array();
                    foreach($selected_address_fields as $field) {
                        if (isset($default_address_fields[$field])) {
                            $new_address_fields[$field] = $default_address_fields[$field];
                        }
                    }
                    $default_address_fields = $new_address_fields;
                }
                $field_data['values'] = array_merge($field_data['values'], $default_address_fields);
            }
            $field_data['countries'] = mw()->forms_manager->countries_list();
        }

        $parseView = new \Microweber\View($file);
        $parseView->assign('data', $field_data);
        $parseView->assign('settings', $field_settings);

        $layout = $parseView->__toString();

        if($settings and defined('MW_API_HTML_OUTPUT')){
            $layout = $this->app->parser->process($layout, $options = false);
        }

        return $layout;

    }

    public function get_template_files_by_type($data, $type)
    {
        $preview_file = false;

        $template_name = $this->get_template_name($data);
        $default_template_name = $this->get_default_template_name($data);

        $ovewrite_templates_path = ACTIVE_TEMPLATE_DIR . 'modules' . DS . 'custom_fields' . DS . 'templates';
        $original_tempaltes_path = modules_path() . 'custom_fields' . DS . 'templates';

        // Try to open overwrite template files
        $overwrite_template_file_preview = $ovewrite_templates_path . DS . $template_name . DS . $type . '.php';

        // Try to open original template files
        $original_template_file_preview = $original_tempaltes_path . DS . $template_name . DS . $type . '.php';

        // Get default tempalte files
        $default_template_file_preview = $original_tempaltes_path . DS .  $default_template_name . DS . $type . '.php';

        // Try to get overwrite template file
        if (is_file($overwrite_template_file_preview)) {
            $preview_file = $overwrite_template_file_preview;
        }

        // Try to get template file for current theme
        if (!$preview_file) {
            if (is_file($original_template_file_preview)) {
                $preview_file = $original_template_file_preview;
            }
        }

        // Get default template file
        if (!$preview_file) {
            if (is_file($default_template_file_preview)) {
                $preview_file = $default_template_file_preview;
            }
        }

        return $preview_file;
    }

    public function get_template_files($data)
    {
        $preview_file = $this->get_template_files_by_type($data, $data['type']);
        if (!$preview_file) {
            $preview_file = $this->get_template_files_by_type($data, 'text');
        }

        $settings_file = modules_path() . DS . 'microweber' . DS . 'custom_fields' . DS . $data['type'] . '_settings.php';
        if (!is_file($settings_file)) {
            $settings_file = modules_path() . DS . 'microweber' . DS . 'custom_fields' . DS . 'text_settings.php';
        }

        $settings_file = normalize_path($settings_file, FALSE);
        $preview_file = normalize_path($preview_file, FALSE);

        return array('preview_file'=>$preview_file, 'settings_file'=>$settings_file);
    }

    public function get_template_name($data)
    {
        $template_name = false;
        $template_from_option = false;
        $template_from_html_option = false;

        if (isset($data['params']['id'])) {
            if (get_option('data-template', $data['params']['id'])) {
                $template_from_option = get_option('data-template', $data['params']['id']);
            }
        }

        if (isset($data['id'])) {
            if (get_option('data-template', $data['id'])) {
                $template_from_option = get_option('data-template', $data['id']);
            }
        }

        if (isset($data['params']['template']) && $data['params']['template']) {
            $template_from_html_option = $data['params']['template'];
        }

        // Get from html option
        if (!$template_from_option && $template_from_html_option) {
            $template_name = $template_from_html_option;
        }

        if ($template_from_option) {
            $template_name = $template_from_option;
        }

        if ($template_name) {
            $template_name_exp = explode('/', $template_name);
            if (!empty($template_name_exp[0])) {
                $template_name = $template_name_exp[0];
            }
        }

        if (!$template_name) {
            return $this->get_default_template_name();
        }

        return $template_name;
    }

    public function get_default_template_name() {

        $template_name = false;

        if (!$template_name) {
            $template_name = template_framework();
        }

        if (!$template_name) {
            $template_name = 'mw-ui';
        }

        return $template_name;
    }

    /**
     * names_for_table.
     *
     * @desc        names_for_table
     *
     * @category    forms
     *
     * @author      Microweber
     *
     * @link        http://microweber.com
     *
     * @param string $table
     */
    public function names_for_table($table)
    {
        $table = $this->app->database_manager->escape_string($table);
        $table1 = $this->app->database_manager->assoc_table_name($table);

        $table = $this->table;
        $q = false;
        $results = false;

        $q = "SELECT *, count(id) AS qty FROM $table WHERE   type IS NOT NULL AND rel_type='{$table1}' AND name!='' GROUP BY name, type ORDER BY qty DESC LIMIT 100";
        $crc = (crc32($q));

        $cache_id = __FUNCTION__ . '_' . $crc;

        $results = $this->app->database_manager->query($q, $cache_id, 'custom_fields/global');

        if (is_array($results)) {
            return $results;
        }
    }

    private function _encode_options($data)
    {
        return json_encode($data);
    }

    private function _decode_options($data)
    {
        return @json_decode($data, true);
    }
}
