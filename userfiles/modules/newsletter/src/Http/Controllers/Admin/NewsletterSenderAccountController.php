<?php
namespace MicroweberPackages\Modules\Newsletter\Http\Controllers\Admin;

use Illuminate\Http\Request;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterSenderAccount;

class NewsletterSenderAccountController extends \MicroweberPackages\Admin\Http\Controllers\AdminController {


    public function save(Request $request) {

        $request->validate([
           'name'=>'required',
           'from_name'=>'required',
           'from_email'=>'required',
           'reply_email'=>'required',
           'account_type'=>'required'
        ]);

       return NewsletterSenderAccount::create($request->all());

    }


}
