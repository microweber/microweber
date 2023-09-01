<?php

namespace MicroweberPackages\CustomField;

class CustomFieldsHelper
{
    public static function generateFieldAddressValues($relType, $relId)
    {
        $fields = 'Country[type=country,field_size=12,show_placeholder=true],';
        $fields .= 'City[type=text,field_size=4,show_placeholder=true],';
        $fields .= 'State/Province[type=text,field_size=4,show_placeholder=true],';
        $fields .= 'Zip/Post code[type=text,field_size=4,show_placeholder=true],';
        $fields .= 'Address[type=textarea,field_size=12,show_placeholder=true]';

        return mw()->fields_manager->makeDefault($relType, $relId, $fields);
    }

    public static function generateFieldNameValues($type)
    {
        $values = [];

        if ($type == 'radio') {
            $typeText = _e('Option', true);
            $values[] = $typeText . ' 1';
            $values[] = $typeText . ' 2';
            $values[] = $typeText . ' 3';
        }

        if ($type == 'checkbox') {
            $typeText = _e('Check', true);
            $values[] = $typeText . ' 1';
            $values[] = $typeText . ' 2';
            $values[] = $typeText . ' 3';
        }

        if ($type == 'dropdown') {
            $typeText = _e('Select', true);
            $values[] = $typeText . ' 1';
            $values[] = $typeText . ' 2';
            $values[] = $typeText . ' 3';
        }

        return $values;
    }
}
