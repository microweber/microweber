<?php

namespace MicroweberPackages\Blog\Http\Controllers;

use Illuminate\Http\Request;
use MicroweberPackages\Post\Models\Post;

class LiveEditAdminController
{
    public function index(Request $request)
    {
        $query = Post::query();
        $query->with('tagged');
        //  $query->where('parent_id', $this->getMainPageId());

        $results = $query->get();

        $customFieldNames = [];
        if (!empty($results)) {
            foreach ($results as $result) {
                $resultCustomFields = $result->customField()->with('fieldValue')->get();
                foreach ($resultCustomFields as $resultCustomField) {
                    $customFieldNames[$resultCustomField->name_key] = $resultCustomField->name;
                }
            }
        }

        return view('blog::admin.live_edit', [
            'moduleId'=>$request->get('id'),
            'customFieldNames'=>$customFieldNames
        ]);
    }

}
