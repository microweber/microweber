<?php

namespace Modules\Product\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use MicroweberPackages\Admin\Http\Controllers\AdminDefaultController;
use Modules\Product\Http\Requests\ProductRequest;
use Modules\Product\Http\Requests\ProductUpdateRequest;
use Modules\Product\Models\Product;

class ProductApiController extends AdminDefaultController
{
    /**
     * Display a listing of the product.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->has('filter')) {
            $query->filter($request->all());
        }

        $products = $query->paginate($request->get('limit', 30))
            ->appends($request->except('page'));

        return (new JsonResource($products))->response();
    }

    /**
     * Store product in database
     *
     * @param ProductRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ProductRequest $request)
    {


        $product = Product::create($request->all());
        return (new JsonResource($product))->response();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return (new JsonResource($product))->response();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ProductUpdateRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ProductUpdateRequest $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update($request->all());

        return (new JsonResource($product))->response();
    }

    /**
     * Destroy resources by given id.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return (new JsonResource(['id' => $id]));
    }
}
