<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 8/19/2020
 * Time: 4:09 PM
 */

namespace MicroweberPackages\Product\Http\Controllers\Api;

use MicroweberPackages\App\Http\Controllers\AdminDefaultController;
use MicroweberPackages\Crud\Traits\HasCrudActions;
use MicroweberPackages\Product\Http\Requests\ProductRequest;
use MicroweberPackages\Product\Repositories\ProductRepository;

class ProductsController extends AdminDefaultController
{
    use HasCrudActions;

    public $repository;
    public $validator = ProductRequest::class;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

}