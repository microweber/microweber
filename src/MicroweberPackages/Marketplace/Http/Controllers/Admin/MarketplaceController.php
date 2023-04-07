<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 8/19/2020
 * Time: 4:09 PM
 */
namespace MicroweberPackages\Marketplace\Http\Controllers\Admin;

use Illuminate\Http\Request;
use MicroweberPackages\Admin\Http\Controllers\AdminController;

class MarketplaceController extends AdminController
{
    public function index(Request $request) {
        return view('marketplace::admin.marketplace.index');
    }
}
