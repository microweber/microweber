<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 8/19/2020
 * Time: 4:09 PM
 */

namespace MicroweberPackages\Product\Http\Controllers\Admin;

use MicroweberPackages\App\Http\Controllers\AdminDefaultController;
use MicroweberPackages\Crud\Traits\HasCrudActions;
use MicroweberPackages\Product\Http\Requests\ProductRequest;
use MicroweberPackages\Product\Product;
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

    /**
     * @OA\Get(
     *      path="/admin/products",
     *      operationId="listProducts",
     *      tags={"Products"},

     *      summary="Get list of products",
     *      description="Returns list of products",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     */

    /**
     * @OA\Post(
     * path="/admin/products/store",
     * summary="Store product in database.",
     * description="Title, price descriptions.",
     * operationId="storeProduct",
     * tags={"Products"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Set the title, description and price",
     *    @OA\JsonContent(
     *       required={"title","price"},
     *       @OA\Property(property="title", type="string", format="text", example="Apple Mac"),
     *       @OA\Property(property="price", type="string", format="text", example="1500")
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Success response",
     *    @OA\JsonContent(
     *       @OA\Property(property="id", type="string", example="1234")
     *        )
     *     )
     * )
     */

}