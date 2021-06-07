<?php

namespace MicroweberPackages\Blog\Http\Controllers;

use Illuminate\Http\Request;
use MicroweberPackages\Blog\FrontendFilter\FilterHelper;
use MicroweberPackages\Content\Content;

class LiveEditAdminController
{
    public function index(Request $request)
    {
        $pages = \MicroweberPackages\Content\Content::where('content_type', 'page')
            ->where('subtype','dynamic')
            // ->where('is_shop', 0)
            ->get();

        return view('blog::admin.live_edit', [
            'moduleId'=>$request->get('id'),
            'pages'=>$pages
        ]);
    }

    public function getCustomFieldsTableFromPage(Request $request)
    {
        $query = Content::query();
        //$query->with('tagged');

        $contentFromId = $request->get('contentFromId');
        $moduleId = $request->get('moduleId');

        $query->where('parent', $contentFromId);

        $results = $query->get();

        $customFieldNames = [];
        if (!empty($results)) {
            foreach ($results as $result) {
                $resultCustomFields = $result->customField()->with('fieldValue')->get();
                foreach ($resultCustomFields as $resultCustomField) {

                    $resultCustomField->controlName = ucfirst($resultCustomField->name);
                    $controlName = get_option('filtering_by_custom_fields_control_name_' . $resultCustomField->name_key, $moduleId);
                    if ($controlName) {
                        $resultCustomField->controlName = $controlName;
                    }

                    $resultCustomField->controlType = FilterHelper::getFilterControlType($resultCustomField, $moduleId);

                    $customFieldNames[$resultCustomField->name_key] = $resultCustomField;
                }
            }
        }


        return view('blog::admin.ajax_custom_fields_table', [
            'moduleId'=>$moduleId,
            'customFieldNames'=>$customFieldNames
        ]);
    }

}
