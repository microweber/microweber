<?php

namespace MicroweberPackages\Blog\Http\Controllers;

use Illuminate\Http\Request;
use MicroweberPackages\Blog\FrontendFilter\FilterHelper;
use MicroweberPackages\Content\Content;

class LiveEditAdminController
{
    public function index(Request $request)
    {
        return view('blog::admin.live_edit', [
            'moduleId'=>$request->get('id')
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
