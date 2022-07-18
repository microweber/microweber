<?php

namespace MicroweberPackages\ContentFilter\Http\Controllers;


use Illuminate\Http\Request;
use MicroweberPackages\Admin\Http\Controllers\AdminDefaultController;

class ContentFilterAdminController extends AdminController
{

    public function index(Request $request)
    {
        return $this->view('content_filter::admin');

    }

    public function show()
    {

    }
}
