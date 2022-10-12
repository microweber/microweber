<?php
namespace MicroweberPackages\Blog\Http\Controllers\Traits;

use MicroweberPackages\Blog\FrontendFilter\FilterHelper;
use MicroweberPackages\Content\Models\Content;

trait CustomFieldsRenderTrait
{

    public function getCustomFieldByParentIdAndModuleId($parentId, $moduleId)
    {
        $query = Content::query();
        //$query->with('tagged');

        $query->where('parent', $parentId);

        $results = $query->get();

        $customFieldNames = [];
        if (!empty($results)) {
            foreach ($results as $result) {
                $resultCustomFields = $result->customField()->with('fieldValue')->get();
                foreach ($resultCustomFields as $resultCustomField) {

                    $resultCustomField->controlName = ucfirst($resultCustomField->name);
                    $controlName = get_option('filtering_by_custom_fields_control_name_' . $resultCustomField->name_key, $moduleId);
                    if (!empty(trim($controlName))) {
                        $resultCustomField->controlName = $controlName;
                    }

                    $resultCustomField->controlType = FilterHelper::getFilterControlType($resultCustomField, $moduleId);

                    $customFieldNames[$resultCustomField->name_key] = $resultCustomField;
                }
            }
        }

        return $customFieldNames;
    }
}
