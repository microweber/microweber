<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 8/19/2020
 * Time: 4:09 PM
 */
namespace MicroweberPackages\Product\Http\Controllers\Admin;

use Illuminate\Http\Request;
use MicroweberPackages\Admin\Http\Controllers\AdminController;
use MicroweberPackages\Admin\Http\Controllers\AdminDefaultController;
use MicroweberPackages\Product\Models\Product;
use MicroweberPackages\Product\Repositories\ProductRepository;

class ProductController extends AdminController
{
    public $repository;

    public function __construct(ProductRepository $repository)
    {
        parent::__construct();

        $this->repository = $repository;
    }

    public function dashboard(Request $request) {
        return $this->view('product::admin.shop.dashboard');
    }

    public function index(Request $request) {
        return $this->view('product::admin.product.index');
    }

    public function create() {

        return $this->view('product::admin.product.edit', [
            'content_id'=>0
        ]);
    }

    public function edit(Request $request, $id) {

        return $this->view('product::admin.product.edit', [
            'content_id'=>intval($id)
        ]);
    }
}
