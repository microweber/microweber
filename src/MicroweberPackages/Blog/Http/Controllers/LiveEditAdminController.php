<?php

namespace MicroweberPackages\Blog\Http\Controllers;

use Illuminate\Http\Request;
use MicroweberPackages\Blog\FrontendFilter\FilterHelper;
use MicroweberPackages\Post\Models\Post;

class LiveEditAdminController
{
    public function index(Request $request)
    {
        $query = Post::query();
        $query->with('tagged');

        $contentFromId = get_option('content_from_id', $request->get('id'));
        if ($contentFromId) {
            $query->where('parent', $contentFromId);
        }

        $results = $query->get();

        $moduleId = $request->get('id');

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

        return view('blog::admin.live_edit', [
            'moduleId'=>$request->get('id'),
            'customFieldNames'=>$customFieldNames
        ]);
    }

}
