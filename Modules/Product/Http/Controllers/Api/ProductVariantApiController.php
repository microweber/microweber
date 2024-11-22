<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 8/19/2020
 * Time: 4:09 PM
 */

namespace Modules\Product\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use MicroweberPackages\Admin\Http\Controllers\AdminDefaultController;
use MicroweberPackages\Product\Http\Requests\ProductCreateRequest;
use Modules\Product\Repositories\ProductVariantRepository;

class ProductVariantApiController extends AdminDefaultController
{
    public $productVariant;

    public function __construct(ProductVariantRepository $productVariant)
    {
        $this->productVariant = $productVariant;

    }

    /**
     * /**
     * Display a listing of the product.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        return (new JsonResource(
            $this->productVariant
                ->filter($request->all())
                ->paginate($request->get('limit', 30))
                ->appends($request->except('page'))

        ))->response();

    }

    /**
     * Store product in database
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $result = $this->productVariant->create($request->all());
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
        $result = $this->productVariant->show($id);

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

        $result = $this->productVariant->update($request->all(), $productVariant);
        return (new JsonResource($result))->response();
    }

    /**
     * Destroy resources by given id.
     * @param string $id
     * @return void
     */
    public function destroy($id)
    {
        return (new JsonResource(['id' => $this->productVariant->delete($id)]));
    }
}
