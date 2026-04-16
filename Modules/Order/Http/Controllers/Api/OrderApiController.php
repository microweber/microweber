<?php

namespace Modules\Order\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use MicroweberPackages\Admin\Http\Controllers\AdminDefaultController;
use Modules\Order\Models\Order;

class OrderApiController extends AdminDefaultController
{
    /**
     * Display a listing of the order.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        return (new JsonResource(
            Order::filter($request->all())
                ->paginate($request->get('limit', 30))
                ->appends($request->except('page'))
        ))->response();
    }

    /**
     * Store order in database
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $result = Order::create($request->all());
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
        $result = Order::find($id);
        return (new JsonResource($result))->response();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param string $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $order)
    {
        $record = Order::findOrFail($order);
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
        $record = Order::findOrFail($id);
        $record->delete();
        return new JsonResource(['id' => $id]);
    }
}
