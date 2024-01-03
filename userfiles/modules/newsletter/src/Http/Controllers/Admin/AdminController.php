<?php
namespace MicroweberPackages\Modules\Newsletter\Http\Controllers\Admin;

use Illuminate\Http\Request;

class AdminController extends \MicroweberPackages\Admin\Http\Controllers\AdminController {

    public function index(Request $request)
    {
        return view('microweber-module-newsletter::admin.index');
    }

    public function subscribers(Request $request)
    {
        return view('microweber-module-newsletter::admin.subscribers');
    }

    public function campaigns(Request $request)
    {
        return view('microweber-module-newsletter::admin.campaigns');
    }

    public function lists(Request $request)
    {
        return view('microweber-module-newsletter::admin.lists');
    }

    public function senderAccounts(Request $request)
    {
        return view('microweber-module-newsletter::admin.sender-accounts');
    }

    public function templates(Request $request)
    {
        return view('microweber-module-newsletter::admin.templates');
    }

    public function templatesEdit(Request $request,$templateId)
    {
        return view('microweber-module-newsletter::admin.templates_edit', [
            'templateId' => $templateId
        ]);
    }

    public function settings(Request $request)
    {
        return view('microweber-module-newsletter::admin.settings');
    }

}
