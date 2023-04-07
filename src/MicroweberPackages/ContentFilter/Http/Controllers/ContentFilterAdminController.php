<?php

namespace MicroweberPackages\ContentFilter\Http\Controllers;


use Illuminate\Http\Request;
use MicroweberPackages\Admin\Http\Controllers\AdminController;
use MicroweberPackages\Admin\Http\Controllers\AdminDefaultController;

class ContentFilterAdminController extends AdminController
{

    public function index(Request $request)
    {
        return view('content_filter::admin');

    }

    public function show()
    {

    }
}
