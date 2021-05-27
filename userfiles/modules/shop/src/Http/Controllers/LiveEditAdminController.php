<?php

namespace MicroweberPackages\Shop\Http\Controllers;

use Illuminate\Http\Request;
use MicroweberPackages\Product\Models\Product;

class LiveEditAdminController
{
    public function index(Request $request)
    {
        /*$query = Product::query();
        $query->with('tagged');
        //  $query->where('parent_id', $this->getMainPageId());

        $results = $query->get();*/

        $customFieldNames = [];

        /*if (!empty($results)) {
            foreach ($results as $result) {
                $resultCustomFields = $result->customField()->with('fieldValue')->get();
                foreach ($resultCustomFields as $resultCustomField) {
                    $customFieldNames[$resultCustomField->name_key] = $resultCustomField->name;
                }
            }
        }*/

        return view('shop::admin.live_edit', [
            'moduleId'=>$request->get('id'),
            'customFieldNames'=>$customFieldNames
        ]);
    }

}
