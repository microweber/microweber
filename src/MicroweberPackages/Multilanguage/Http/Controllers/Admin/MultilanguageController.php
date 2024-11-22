<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 8/19/2020
 * Time: 4:09 PM
 */
namespace MicroweberPackages\Multilanguage\Http\Controllers\Admin;

use Illuminate\Http\Request;
use MicroweberPackages\Admin\Http\Controllers\AdminController;

class MultilanguageController extends AdminController
{
    public function index(Request $request) {

        return view('multilanguage::admin.multilanguage.index');
    }

}
