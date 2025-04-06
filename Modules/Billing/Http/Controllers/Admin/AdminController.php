<?php
namespace Modules\Billing\Http\Controllers\Admin;

use Illuminate\Http\Request;

class AdminController extends \MicroweberPackages\Admin\Http\Controllers\AdminController {

    public function index(Request $request)
    {
        return view('billing::admin.index');
    }

    public function users(Request $request)
    {
        return view('billing::admin.users');
    }


    public function subscriptionPlans(Request $request)
    {
        return view('billing::admin.subscription-plans');
    }

    public function subscriptionPlanGroups(Request $request)
    {
        return view('billing::admin.subscription-plan-groups');
    }

    public function settings(Request $request)
    {
        return view('billing::admin.settings');
    }
}
