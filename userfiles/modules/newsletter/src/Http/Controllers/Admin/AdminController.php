<?php
namespace MicroweberPackages\Modules\Newsletter\Http\Controllers\Admin;

use Illuminate\Http\Request;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterTemplate;

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

        $findNewsletterTemplate = NewsletterTemplate::where('id',$templateId)->first();
        if(!$findNewsletterTemplate){
            return [
                'error' => 'Template not found'
            ];
        }

        $findNewsletterTemplate->text = $request->get('html');
        $findNewsletterTemplate->save();

        return [
            'success' => 'Template updated'
        ];

    }

    public function settings(Request $request)
    {
        return view('microweber-module-newsletter::admin.settings');
    }

}
