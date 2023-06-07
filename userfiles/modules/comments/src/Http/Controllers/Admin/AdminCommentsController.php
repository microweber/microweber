<?php

namespace MicroweberPackages\Modules\Comments\Http\Controllers\Admin;

use MicroweberPackages\Admin\Http\Controllers\AdminController;
use Illuminate\Http\Request;

class AdminCommentsController extends AdminController
{
    public function index(Request $request)
    {
        return view('comments::admin.index');
    }
}
