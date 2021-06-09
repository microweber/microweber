<?php

namespace MicroweberPackages\Shop\Http\Controllers;

use Illuminate\Http\Request;

class LiveEditAdminController
{
    public function index(Request $request)
    {
        $moduleId = $request->get('id');

        $pages = \MicroweberPackages\Content\Content::where('content_type', 'page')
            //->where('subtype','dynamic')
             ->where('is_shop', 1)
            ->get();

        return view('blog::admin.live_edit', [
            'moduleId'=>$moduleId,
            'pages'=>$pages
        ]);
    }

}
