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

    public function create() {

        return $this->view('category::admin.category.edit', [
            'id'=>0
        ]);
    }

    public function edit(Request $request, $id) {

        return $this->view('category::admin.category.edit', [
            'id'=>$id
        ]);
    }
}
