<?php
namespace MicroweberPackages\Modules\ContactForm\Http\Controllers\Admin;

use Illuminate\Http\Request;;

class AdminController extends \MicroweberPackages\App\Http\Controllers\AdminController
{
    public function index(Request $request)
    {
        return view('contact-form::admin.contact-form.index');
    }
}
