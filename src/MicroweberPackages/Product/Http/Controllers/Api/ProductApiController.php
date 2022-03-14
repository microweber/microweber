<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 8/19/2020
 * Time: 4:09 PM
 */

namespace MicroweberPackages\Product\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use MicroweberPackages\App\Http\Controllers\AdminDefaultController;
use MicroweberPackages\Product\Http\Requests\ProductRequest;
use MicroweberPackages\Product\Http\Requests\ProductCreateRequest;
use MicroweberPackages\Product\Http\Requests\ProductUpdateRequest;
use MicroweberPackages\Product\Repositories\ProductRepository;

class ProductApiController extends AdminDefaultController
{
    public $product;

    public function __construct(ProductRepository $product)
    {
        $this->product = $product;

    }

    /**
    /**
     * Display a listing of the product.
     *
     * @param ProductRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        return (new JsonResource(
            $this->product
                ->filter($request->all())
                ->paginate($request->get('limit', 30))
                ->appends($request->except('page'))

        ))->response();

    }

    /**
     * Store product in database
     *
     * @param ProductRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ProductRequest $request)
    {
        $result = $this->product->create($request->all());
        return (new JsonResource($result))->response();
    }

    /**
     * Display the specified resource.show
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $result = $this->product->show($id);

        return (new JsonResource($result))->response();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  ProductRequest $request
     * @param  string $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ProductRequest $request, $product)
    {

        $result = $this->product->update($request->all(), $product);
        return (new JsonResource($result))->response();
    }

    /**
     * Destroy resources by given id.
     *
     * @param string $id
     * @return void
     */
    public function destroy($id)
    {
        return (new JsonResource(['id'=>$this->product->delete($id)]));
    }
}
