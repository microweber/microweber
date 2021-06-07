<?php

namespace MicroweberPackages\Blog\FrontendFilter;


class FilterHelper
{

    public static function getFilterControlType($customField, $moduleId)
    {
        $controlType = get_option('filtering_by_custom_fields_control_type_' . $customField->name_key, $moduleId);

        if (empty($controlType)) {
            $controlType = $customField->type;
            if ($customField->type == 'price') {
                $controlType = 'price_range';
            }
            if ($customField->type == 'date') {
                $controlType = 'date_range';
            }
            if ($customField->type == 'dropdown') {
                $controlType = 'select';
            }
        }

        return $controlType;
    }
}
