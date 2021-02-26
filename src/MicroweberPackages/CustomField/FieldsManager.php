<?php

namespace MicroweberPackages\CustomField;

use MicroweberPackages\CustomField\Events\CustomFieldWasDeleted;
use MicroweberPackages\CustomField\Models\CustomField;
use MicroweberPackages\CustomField\Models\CustomFieldValue;
use MicroweberPackages\View\View;

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
                $this->app = app();
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

    /* public function get_by_id($field_id)
     {
         if ($field_id != 0) {
             $find = CustomField::where('id', $field_id)->first();
             if ($find) {

                 $customField = $find->toArray();
                 $customField['value'] = '';

                 $findValues = $find->fieldValue()->get();
                 if ($findValues) {
                     $values = [];
                     foreach($findValues as $findValue) {
                         $values[] = $findValue->value;
                     }
                     $customField['field_values'] = $values;
                 }

                 return $customField;
             }
         }
     }*/


    public function parse_field_settings($fieldParse)
    {

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

    public function parse_fields_html($fieldParseInput)
    {

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
                        $make_field['show_label'] = $show_label;

                        $make_field['show_placeholder'] = $show_placeholder;
                        if ($show_placeholder) {
                            $make_field['placeholder'] = ucfirst($field_name);
                        }

                        $make_field['type'] = $field_type;
                        $make_field['options']['field_type'] = $field_type;
                        $make_field['options']['field_size'] = $field_size;
                        $make_field['options']['field_size_desktop'] = $field_size;
                        $make_field['options']['field_size_tablet'] = $field_size;
                        $make_field['options']['field_size_mobile'] = $field_size;

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
                    $this->app->cache_manager->delete('custom_fields');
                }
            }
        }
        $_mw_made_default_fields_register[$function_cache_id] = $saved_fields;

        return $saved_fields;
    }

    public function save($fieldData)
    {
        if (!is_array($fieldData)) {
            return false;
        }

        $customField = null;
        if (!empty($fieldData['id'])) {
            $customField = CustomField::where('id', $fieldData['id'])->first();
        }
        if ($customField == null) {
            $customField = new CustomField();
            $customField->name = _e($this->getFieldNameByType($fieldData['type']), true);
            if (!isset($fieldData['value'])) {
               $fieldData['value'] = $this->generateFieldNameValues($fieldData);
            }
        }

        $customField->type = $fieldData['type'];

        if (!empty($fieldData['rel_type'])) {
            $customField->rel_type = $fieldData['rel_type'];
            $customField->rel_id = $fieldData['rel_id'];
        }

        if (!empty($fieldData['error_text'])) {
            $customField->error_text = $fieldData['error_text'];
        }

        if (!empty($fieldData['name'])) {
            $customField->name = $fieldData['name'];
        }

        if (!empty($fieldData['name_key'])) {
            $customField->name_key = $fieldData['name_key'];
        }

        if (!empty($fieldData['show_label'])) {
            $customField->show_label = $fieldData['show_label'];
        }

        if (!empty($fieldData['options'])) {
            $customField->options = $fieldData['options'];
        }

        if (!empty($fieldData['placeholder'])) {
            $customField->placeholder = $fieldData['placeholder'];
        }

        if (!empty($fieldData['show_label'])) {
            $customField->show_label = $fieldData['show_label'];
        }

        if (!empty($fieldData['required'])) {
            $customField->required = $fieldData['required'];
        }

        if (!empty($fieldData['is_active'])) {
            $customField->is_active = $fieldData['is_active'];
        }

        $customField->save();

        if (!empty($fieldData['value'])) {

            // Save array string
            if (is_array($fieldData['value'])) {
                $oldValueIds = [];
                $getCustomFieldValues = CustomFieldValue::where('custom_field_id', $customField->id)->get();
                if ($getCustomFieldValues !== null) {
                    foreach ($getCustomFieldValues as $customFieldValue) {
                        $oldValueIds[] = $customFieldValue->id;
                    }
                }
                foreach ($fieldData['value'] as $iValue => $value) {

                    $saveValueId = false;
                    if (isset($oldValueIds[$iValue])) {
                        $saveValueId = $oldValueIds[$iValue];
                        unset($oldValueIds[$iValue]);
                    }

                    $customFieldValue = CustomFieldValue::where('id', $saveValueId)->first();
                    if ($customFieldValue == null) {
                        $customFieldValue = new CustomFieldValue();
                        $customFieldValue->custom_field_id = $customField->id;
                    }

                    $customFieldValue->position = $iValue;
                    $customFieldValue->value = $value;

                    if (is_array($value)) {
                        $customFieldValue->value = implode(',', array_values($value));
                    }

                    $customFieldValue->save();
                }

                if (!empty($oldValueIds)) {
                    foreach ($oldValueIds as $customFieldValueId) {
                        CustomFieldValue::where('id', $customFieldValueId)->delete();
                    }
                }
            } else {
                $getCustomFieldValues = CustomFieldValue::where('custom_field_id', $customField->id)->first();
                if ($getCustomFieldValues == null) {
                    $getCustomFieldValues = new CustomFieldValue();
                    $getCustomFieldValues->custom_field_id = $customField->id;
                }
                $getCustomFieldValues->value = $fieldData['value'];
                $getCustomFieldValues->save();
            }
        }

        return $customField->id;
    }

    public function generateFieldNameValues($fieldData)
    {
        $values = [];

        if ($fieldData['type'] == 'radio') {
            $typeText = _e('Option', true);
            $values[] = $typeText . ' 1';
            $values[] = $typeText . ' 2';
            $values[] = $typeText . ' 3';
        }

        if ($fieldData['type'] == 'checkbox') {
            $typeText = _e('Check', true);
            $values[] = $typeText . ' 1';
            $values[] = $typeText . ' 2';
            $values[] = $typeText . ' 3';
        }

        if ($fieldData['type'] == 'dropdown') {
            $typeText = _e('Select', true);
            $values[] = $typeText . ' 1';
            $values[] = $typeText . ' 2';
            $values[] = $typeText . ' 3';
        }

        return $values;
    }

    public function getFieldNameByType($type)
    {
        $fields = mw()->ui->custom_fields();

        if (isset($fields[$type])) {
            return $fields[$type];
        }
    }

    public function get_values($custom_field_id)
    {
        $id = $custom_field_id;
        if (is_array($custom_field_id)) {
            $id = implode(',', $custom_field_id);
        }

        $getCustomFieldValues = CustomFieldValue::where('custom_field_id', $id)->get();
        if ($getCustomFieldValues) {
            return $getCustomFieldValues->toArray();
        }

        return false;
    }

    public function get_value($content_id, $field_name, $return_full = false, $table = 'content')
    {
        $val = false;
        $data = $this->get([
            'rel_type'=>$table,
            'rel_id'=>$content_id,
            'return_full'=>$return_full,
        ]);
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

    public function get($params)
    {
        $getCustomFields = CustomField::query();

        if (!empty($params['id'])) {
            $getCustomFields->where('id', $params['id']);
        }

        if (!empty($params['rel_id'])) {
            $getCustomFields->where('rel_id', $params['rel_id']);
            $getCustomFields->where('rel_type', $params['rel_type']);
        }

        if (!empty($params['type'])) {
            $getCustomFields->where('type', $params['type']);
        }

        if (!empty($params['session_id'])) {
            $getCustomFields->where('session_id', $params['session_id']);
        }

        $getCustomFields = $getCustomFields->get();

        $customFields = [];

        if ($getCustomFields) {
            foreach ($getCustomFields as $customField) {

                $readyCustomField = $customField->toArray();

                $readyCustomField['value'] = '';
                $readyCustomField['values'] = [];
                $readyCustomField['values_plain'] = '';

                $getCustomFieldValue = $customField->fieldValue()->get();
                if (isset($getCustomFieldValue[0])) {
                    $readyCustomField['value'] = $getCustomFieldValue[0]->value;
                    foreach ($getCustomFieldValue as $customFieldValue) {
                        $readyCustomField['values'][] = $customFieldValue->value;
                    }
                }

                if (!empty($readyCustomField['values'])) {
                    $readyCustomField['values_plain'] = implode(',', $readyCustomField['values']);
                }

                $customFields[] = $readyCustomField;
            }
        }

        return $customFields;
    }

    /**
     * @param $it
     * @return mixed
     */
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
            $it['options'] = $it['options'];
        }

        return $it;
    }

    /**
     * @param $data
     * @return bool
     */
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

    /**
     * @param $id
     * @return bool|int
     */
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

        $findCustomField = CustomField::where('id', $id)->first();
        if ($findCustomField) {
            $getCustomFieldValues = $findCustomField->fieldValue()->get();
            if ($getCustomFieldValues) {
                foreach($getCustomFieldValues as $customFieldValue) {
                    $customFieldValue->delete();
                }
            }
            $findCustomField->delete();
        }

        event(new CustomFieldWasDeleted($id));

        return $id;
    }

    /**
     * @param int $field_id
     * @return false|mixed|string
     */
    public function make_field($field_id = 0)
    {
        if (is_array($field_id)) {
            if (!empty($field_id)) {
                return $this->make($field_id, false, 'y');
            }
        } else {
            if ($field_id != 0) {
                return $this->make($field_id);
            }
        }
    }

    public function make($params, $field_type = 'text', $settings = false)
    {

        $id = false;
        if (is_array($params)) {
            if (isset($params['id'])) {
                $id = $params['id'];
            }
        } else if (is_numeric($params)) {
            $id = $params;
        }

        $data = $this->get_by_id($id);

        if (!isset($data['type'])) {
            $data['type'] = $field_type;
        }

        // If is edit custom field from admin
        if (isset($_REQUEST['settings']) and trim($_REQUEST['settings']) == 'y') {
            $settings = true;
        }

        $field = $this->instanceField($data['type']);
        $field->setData($data);
        $field->setAdminView($settings);

        return $field->render();
    }

    public function instanceField($type)
    {
        $fieldClass = 'MicroweberPackages\\CustomField\\Fields\\' . ucfirst($type);
        $field = new $fieldClass ();

        return $field;
    }

    /**
     * @param int $field_id
     * @param string $field_type
     * @param bool $settings
     * @return false|mixed|string
     * @throws \Exception
     */
    public function make__deprecated($field_id = 0, $field_type = 'text', $settings = false)
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
        if ((isset($_REQUEST['params']) and ($_REQUEST['params']))) {
            $params = $_REQUEST['params'];
        }

        if ((isset($_REQUEST['field_id']) and ($_REQUEST['field_id']))) {
            $data['field_id'] = $_REQUEST['field_id'];
        }

        //d($data);
        //input_class

        if (isset($data['copy_from'])) {
            $copy_from = intval($data['copy_from']);
            if (is_admin() == true) {
                $form_data = $this->get_by_id($id = $copy_from);
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
        } else {
            //  $data['values'] = false;
        }

        if (isset($data['value']) and is_array($data['value'])) {
            $data['value'] = implode(',', $data['value']);
        } else {
            //  $data['value'] = false;
        }

        $data['type'] = $field_type;

        if (isset($data['options']) and is_string($data['options'])) {
            $data['options'] = $data['options'];
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

        $field_data = $data;
        $field_data['name'] = false;
        $field_data['name_key'] = false;
        $field_data['type'] = false;
        $field_data['id'] = 0;
        $field_data['placeholder'] = false;
        $field_data['error_text'] = false;
        $field_data['help'] = false;
        $field_data['values'] = array();
        $field_data['value'] = false;
        $field_data['options'] = $data['options'];
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

        if (isset($data['name_key'])) {
            $field_data['name_key'] = $data['name_key'];
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
            $field_settings['options']['required'] = true;
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
        } else {
            //     $field_data['value'] = false;
        }

        if (isset($data['error_text'])) {
            $field_data['error_text'] = $data['error_text'];
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
                $field_settings['options']['file_types'] = array_merge($field_data['options']['file_types'], $data['options']['file_types']);
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
                    foreach ($selected_address_fields as $field) {
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

        $parseView = new View($file);
        $parseView->assign('data', $field_data);
        $parseView->assign('settings', $field_settings);

        $layout = $parseView->__toString();

        if ($settings and defined('MW_API_HTML_OUTPUT')) {
            $layout = $this->app->parser->process($layout, $options = false);
        }

        return $layout;

    }
}
