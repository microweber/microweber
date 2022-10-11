<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 8/19/2020
 * Time: 4:09 PM
 */
namespace MicroweberPackages\Category\Http\Controllers\Admin;

use Illuminate\Http\Request;
use MicroweberPackages\Admin\Http\Controllers\AdminController;
use MicroweberPackages\Admin\Http\Controllers\AdminDefaultController;
use MicroweberPackages\Category\Repositories\CategoryRepository;

class CategoryShopController extends CategoryController
{
    public function index(Request $request) {
        return $this->view('category::admin.category.index', ['isShop'=>1]);
    }
}
