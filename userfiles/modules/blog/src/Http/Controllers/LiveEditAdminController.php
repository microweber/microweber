<?php

namespace MicroweberPackages\Blog\Http\Controllers;

use Illuminate\Http\Request;

class LiveEditAdminController
{
    public function index(Request $request)
    {







        return view('blog::admin.live_edit', [
            'moduleId'=>$request->get('id')
        ]);
    }

}
