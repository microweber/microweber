<?php

namespace MicroweberPackages\Template\Concerns;

/**
 * Extracted from ModuleManager to decompose the god class. Composed back via
 * `use`, so $this state and inter-method calls are unchanged.
 */
trait ReadsTemplateFields
{
    public function get_data_field_title($field, $type = 'general')
    {
        $fieldTitle = '';
        $dataFields = $this->get_data_fields($type);

        foreach ($dataFields as $dataFieldKey => $dataFieldTitle) {
            if ($field == $dataFieldKey) {
                $fieldTitle = $dataFieldTitle;
                break;
            }
        }
        return $fieldTitle;
    }

    public function get_data_fields($type = 'general')
    {
        $templateConfig = $this->get_config();

        $dataFields = [];
        if (isset($templateConfig['data-fields-' . $type]) && !empty($templateConfig['data-fields-' . $type])) {
            foreach ($templateConfig['data-fields-' . $type] as $templateField) {
                $dataFields[$templateField['name']] = $templateField['title'];
            }
        }

        return $dataFields;
    }

    public function get_edit_field_title($field, $type = 'general')
    {
        $fieldTitle = '';
        $editFields = $this->get_edit_fields($type);
        foreach ($editFields as $editFieldKey => $editFieldTitle) {
            if ($field == $editFieldKey) {
                $fieldTitle = $editFieldTitle;
                break;
            }
        }
        return $fieldTitle;
    }

    public function get_edit_fields($type = 'general')
    {
        $templateConfig = $this->get_config();

        $editFields = [];
        if (isset($templateConfig['edit-fields-' . $type]) && !empty($templateConfig['edit-fields-' . $type])) {
            foreach ($templateConfig['edit-fields-' . $type] as $templateField) {
                $editFields[$templateField['name']] = $templateField['title'];
            }
        }

        return $editFields;
    }
}
