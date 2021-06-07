<?php

namespace MicroweberPackages\Shop\Http\Controllers;

use Illuminate\Http\Request;

class LiveEditAdminController
{
    public function index(Request $request)
    {
        $moduleId = $request->get('id');

        return view('blog::admin.live_edit', [
            'moduleId'=>$moduleId
        ]);
    }

}
