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

    public function templatesEdit(Request $request, $templateId)
    {

        $findNewsletterTemplate = NewsletterTemplate::where('id',$templateId)->first();
        if(!$findNewsletterTemplate){
            return [
                'error' => 'Template not found'
            ];
        }

        $template = $request->json('template');

        if (!isset($template['json'])) {
            return [
                'error' => 'Template can\'t be empty'
            ];
        }
        if (!isset($template['html'])) {
            return [
                'error' => 'Template can\'t be empty'
            ];
        }
        $checkJson = json_decode($template['json'], true);
        if (!$checkJson) {
            return [
                'error' => 'Invalid json'
            ];
        }

        $findNewsletterTemplate->text = $template['html'];
        $findNewsletterTemplate->json = $template['json'];
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
