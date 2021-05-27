<?php

namespace MicroweberPackages\Shop\Http\Controllers;

use Illuminate\Http\Request;
use MicroweberPackages\CustomField\Models\CustomField;
use MicroweberPackages\Product\Models\Product;

class LiveEditAdminController
{
    public function index(Request $request)
    {

        $customFieldNames = [];

        $getCustomFields = CustomField::groupBy('name_key')->get();

        if (!empty($getCustomFields)) {
            foreach ($getCustomFields as $resultCustomField) {
                $customFieldNames[$resultCustomField->name_key] = $resultCustomField->name;
            }
        }

        return view('shop::admin.live_edit', [
            'moduleId'=>$request->get('id'),
            'customFieldNames'=>$customFieldNames
        ]);
    }

}
