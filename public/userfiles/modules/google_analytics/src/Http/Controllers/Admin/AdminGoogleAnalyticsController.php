<?php

namespace MicroweberPackages\Modules\GoogleAnalytics\Http\Controllers\Admin;

use MicroweberPackages\Admin\Http\Controllers\AdminController;
use Illuminate\Http\Request;

class AdminGoogleAnalyticsController extends AdminController
{
    public function index(Request $request)
    {
        return view('google_analytics::admin.index');
    }
}
