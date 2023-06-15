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

class CategoryController extends AdminController
{
    public $repository;

    public function __construct(CategoryRepository $repository)
    {
        parent::__construct();

        $this->repository = $repository;
    }

    public function index(Request $request) {
        return view('category::admin.category.index');
    }

    public function create() {

        $parent = request()->get('parent', false);
        if ($parent == 'shop') {
            return redirect(route('admin.shop.category.create', ['parent' => 'shop']));
        }

        return view('category::admin.category.create', [
            'id'=>0,
            'parent'=>$parent
        ]);
    }

    public function show(Request $request, $id) {
        return view('category::admin.category.show', [
            'id'=>$id
        ]);
    }

    public function edit(Request $request, $id) {

        return view('category::admin.category.edit', [
            'id'=>$id
        ]);
    }
}
