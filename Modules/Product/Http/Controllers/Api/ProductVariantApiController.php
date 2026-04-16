<?php

namespace Modules\Product\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use MicroweberPackages\Admin\Http\Controllers\AdminDefaultController;
use Modules\Product\Models\ProductVariant;

class ProductVariantApiController extends AdminDefaultController
{
    /**
     * Display a listing of the product variants.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        return (new JsonResource(
            ProductVariant::filter($request->all())
                ->paginate($request->get('limit', 30))
                ->appends($request->except('page'))
        ))->response();
    }

    /**
     * Store product variant in database.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $result = ProductVariant::create($request->all());
        return (new JsonResource($result))->response();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $result = ProductVariant::find($id);
        return (new JsonResource($result))->response();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param string $productVariant
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $productVariant)
    {
        $record = ProductVariant::findOrFail($productVariant);
        $record->update($request->all());
        return (new JsonResource($record))->response();
    }

    /**
     * Destroy resources by given id.
     *
     * @param string $id
     * @return JsonResource
     */
    public function destroy($id)
    {
        $record = ProductVariant::findOrFail($id);
        $record->delete();
        return new JsonResource(['id' => $id]);
    }
}
