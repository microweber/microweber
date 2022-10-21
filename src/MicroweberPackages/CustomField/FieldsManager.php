<?php

namespace MicroweberPackages\CustomField;

use MicroweberPackages\CustomField\Fields\Text;
use MicroweberPackages\Helper\HTMLClean;
use MicroweberPackages\Helper\XSSSecurity;
use function Matrix\trace;
use MicroweberPackages\CustomField\Events\CustomFieldWasDeleted;
use MicroweberPackages\CustomField\Fields\Address;
use MicroweberPackages\CustomField\Models\CustomField;
use MicroweberPackages\CustomField\Models\CustomFieldValue;
use MicroweberPackages\View\View;


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

    public function getById($field_id)
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

    public function parseFieldSettings($fieldParse)
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

    public function parseFieldsHtml($fieldParseInput)
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

                $fieldsSettings = $this->parseFieldSettings($field);
                $fieldName = preg_replace('/(\[.*?\])/', false, $field);
                $readyFields[] = array(
                    'name' => $fieldName,
                    'settings' => $fieldsSettings
                );
            }
        }

        return $readyFields;
    }

    public function makeDefault($rel, $rel_id, $fields_csv_str)
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

        //  $is_made = $this->app->option_manager->get($function_cache_id, 'make_default_custom_fields');

        $make_field = array();

        $make_field['rel_type'] = $rel;
        $make_field['rel_id'] = $rel_id;
        //  $is_made = $this->get_all($make_field);

        if (isset($_mw_made_default_fields_register[$function_cache_id])) {
            return $_mw_made_default_fields_register[$function_cache_id];
        }

        /* if ($is_made) {
             return;
         }*/

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

            $fields_csv_str = $this->parseFieldsHtml($fields_csv_str);

            $pos = 0;
            if (is_array($fields_csv_str)) {
                foreach ($fields_csv_str as $field) {

                    $field_name = $field['name'];

                    $show_placeholder = false;
                    $required = false;
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

                    $show_label = true;
                    if (isset($field['settings']['show_label'])) {
                        if ($field['settings']['show_label'] == 'false' || $field['settings']['show_label'] == 0 || $field['settings']['show_label'] == '0') {
                            $show_label = false;
                        }
                        if ($field['settings']['show_label'] == 'true' || $field['settings']['show_label'] == 1 || $field['settings']['show_label'] == '1') {
                            $show_label = true;
                        }
                    }

                    if (isset($field['settings']['required'])) {
                        if ($field['settings']['required'] == 'false' || $field['settings']['required'] == 0 || $field['settings']['required'] == '0') {
                            $required = false;
                        }
                        if ($field['settings']['required'] == 'true' || $field['settings']['required'] == 1 || $field['settings']['required'] == '1') {
                            $required = true;
                        }
                    }

                    $show_placeholder = false;
                    if (isset($field['settings']['show_placeholder'])) {
                        switch ($field['settings']['show_placeholder']) {
                            case "true":
                            case "1":
                            case 1:
                                $show_placeholder = true;
                                break;
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
                    $existing = $this->getAll($existing);
                    if ($existing == false or is_array($existing) == false) {

                        $make_field = array();
                        $make_field['rel_type'] = $rel;
                        $make_field['rel_id'] = $rel_id;
                        $make_field['position'] = $pos;
                        $make_field['name'] = ucfirst($field_name);
                        $make_field['show_label'] = $show_label;
                        $make_field['required'] = $required;

                        $make_field['show_placeholder'] = $show_placeholder;
                        if ($show_placeholder) {
                            $make_field['placeholder'] = ucfirst($field_name);
                        }

                        $make_field['type'] = $field_type;
                        $make_field['options']['show_placeholder'] = $show_placeholder;
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

                /*    $option = array();
                    $option['option_value'] = true;
                    $option['option_key'] = $function_cache_id;
                    $option['option_group'] = 'make_default_custom_fields';

                    $this->app->option_manager->save($option);*/

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

        $xssClean = new HTMLClean();
        $fieldData = $xssClean->cleanArray($fieldData);

        if (isset($fieldData['copy_of']) and $fieldData['copy_of']) {

            $existing = array();
            $existing['id'] = $fieldData['copy_of'];
            $existing['single'] = 1;
            $existing = $this->getAll($existing);

            if ($existing) {

                $getCustomFieldValues = CustomFieldValue::where('custom_field_id', $fieldData['copy_of'])->get();
                if ($getCustomFieldValues) {
                    foreach ($getCustomFieldValues as $customFieldValue){
                        $fieldData['value'][]  = $customFieldValue->value;
                    }
                }

                $fieldData['type'] = $existing['type'];
                $fieldData['name'] = $existing['name'];
                $fieldData['name_key'] = $existing['name_key'];
                $fieldData['options'] = $existing['options'];
                $fieldData['show_label'] = $existing['show_label'];
                $fieldData['is_active'] = $existing['is_active'];
                $fieldData['required'] = $existing['required'];
                $fieldData['placeholder'] = $existing['placeholder'];
                $fieldData['error_text'] = $existing['error_text'];
                $fieldData['set_copy_of'] = $existing['id'];

                unset($fieldData['copy_of']);


                return $this->save($fieldData);


            } else {
                // field to copy not found
                return false;
            }

        }

        if (isset($fieldData['type']) and $fieldData['type'] == 'address') {

            // Generate address fields
            $fields_csv_str = 'Country[type=country,field_size=12,show_placeholder=true],';
            $fields_csv_str .= 'City[type=text,field_size=4,show_placeholder=true],';
            $fields_csv_str .= 'State/Province[type=text,field_size=4,show_placeholder=true],';
            $fields_csv_str .= 'Zip/Post code[type=text,field_size=4,show_placeholder=true],';
            $fields_csv_str .= 'Address[type=textarea,field_size=12,show_placeholder=true]';

            $saved[] = mw()->fields_manager->makeDefault($fieldData['rel_type'], $fieldData['rel_id'], $fields_csv_str);

            return $saved;
        }

        $customField = null;
        if (!empty($fieldData['id']) and $fieldData['id'] != 0) {
            $customField = CustomField::where('id', $fieldData['id'])->first();
        }

        if ($customField == null) {
            $customField = new CustomField();
            $customField->name = $this->getFieldNameByType($fieldData['type']);

            if (!isset($fieldData['value'])) {
                $fieldData['value'] = $this->generateFieldNameValues($fieldData);
            }

            $countDuplicates = CustomField::where('rel_type', $fieldData['rel_type'])
                ->where('rel_id', $fieldData['rel_id'])
                ->where('type', $fieldData['type'])
                ->count();

            if ($countDuplicates > 0) {
                $customField->name = $customField->name . ' ('.($countDuplicates+1).')';
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

        $customField->show_label = true;
        if (isset($fieldData['show_label'])) {
            $customField->show_label = $fieldData['show_label'];
        }

        if (!empty($fieldData['options'])) {
            $customField->options = $fieldData['options'];
        }

        if (!empty($fieldData['placeholder'])) {
            $customField->placeholder = $fieldData['placeholder'];
        }

        if (!empty($fieldData['required'])) {
            $customField->required = $fieldData['required'];
        } else {
            $customField->required = false;
        }

        if (!empty($fieldData['is_active'])) {
            $customField->is_active = $fieldData['is_active'];
        }

        if (isset($fieldData['set_copy_of']) and !empty($fieldData['set_copy_of'])) {
            $customField->copy_of_field = $fieldData['set_copy_of'];
            $customField->session_id = app()->user_manager->session_id();
        }


        $customField->save();

        if (isset($fieldData['value'])) {
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

        app()->custom_field_repository->clearCache();

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

    public function getValues($custom_field_id)
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

    public function getValue($content_id, $field_name, $return_full = false, $table = 'content')
    {
        $val = false;
        $data = $this->get([
            'rel_type' => $table,
            'rel_id' => $content_id,
            'return_full' => $return_full,
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

    public function getAll($params)
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
        return $this->app->custom_field_repository->get($params);
    }

    /**
     * @param $it
     * @return mixed
     */
    public function decodeArrayVals($it)
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
            $this->app->error('Error: not logged in as admin.');
        }

        foreach ($data as $value) {
            if (is_array($value)) {
                foreach ($value as $position => $customFieldId) {
                    $findCustomField = CustomField::where('id', $customFieldId)->first();
                    if ($findCustomField) {
                        $findCustomField->position = $position;
                        $findCustomField->save();
                    }
                }
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
                foreach ($getCustomFieldValues as $customFieldValue) {
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
    public function makeField($field_id = 0)
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

        $data = $this->getById($id);

        if (!isset($data['type'])) {
            $data['type'] = $field_type;
        }

        // If is edit custom field from admin
        if (isset($_REQUEST['settings']) and trim($_REQUEST['settings']) == 'y') {
            $settings = true;
        }

        if (isset($params['params']['template'])) {
            $data['params']['template'] = $params['params']['template'];
        }

        if (!isset($data['name'])) {
            $data['name'] = $data['type'];
            $data['name_key'] = $data['type'];
        }

        $field = $this->instanceField($data['type']);
        $field->setData($data);
        $field->setAdminView($settings);

        return $field->render();
    }

    public function instanceField($type)
    {
        $fieldClass = 'MicroweberPackages\\CustomField\\Fields\\' . ucfirst($type);
        if (class_exists($fieldClass, true)) {
            $field = new $fieldClass ();
        } else {
            $field = new Text();
        }

        return $field;
    }
}
