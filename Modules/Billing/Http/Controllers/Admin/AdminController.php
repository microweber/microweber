<?php
namespace Modules\Billing\Http\Controllers\Admin;

use Illuminate\Http\Request;

class AdminController extends \MicroweberPackages\Admin\Http\Controllers\AdminController {

    public function index(Request $request)
    {
        return redirect('/admin/billing/billing-users');
    }

    public function users(Request $request)
    {
        // Legacy livewire view; redirect to Filament resource.
        return redirect('/admin/billing/billing-users');
    }


    public function subscriptionPlans(Request $request)
    {
        // Legacy livewire view; redirect to Filament resource.
        return redirect('/admin/billing/subscription-plans');
    }

    public function subscriptionPlanGroups(Request $request)
    {
        // SubscriptionPlanGroups livewire view references an unimplemented component.
        // Redirect to the Filament subscription-plan-groups resource which IS implemented.
        return redirect('/admin/billing/subscription-plan-groups');
    }

    public function settings(Request $request)
    {
        return view('modules.billing::admin.settings');
    }
}
