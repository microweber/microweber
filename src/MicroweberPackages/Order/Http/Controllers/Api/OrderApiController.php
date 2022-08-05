<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 8/19/2020
 * Time: 4:09 PM
 */

namespace MicroweberPackages\Order\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use MicroweberPackages\Admin\Http\Controllers\AdminDefaultController;
use MicroweberPackages\Order\Http\Requests\OrderRequest;
use MicroweberPackages\Order\Http\Requests\OrderCreateRequest;
use MicroweberPackages\Order\Http\Requests\OrderUpdateRequest;
use MicroweberPackages\Order\Repositories\OrderApiRepository;

class OrderApiController extends AdminDefaultController
{
    public $order;

    public function __construct(OrderApiRepository $order)
    {
        $this->order = $order;
    }

    /**
    /**
     * Display a listing of the order.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        return (new JsonResource(
            $this->order
                ->filter($request->all())
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
        $result = $this->order->create($request->all());
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
        $result = $this->order->show($id);

        return (new JsonResource($result))->response();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  string $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $order)
    {

        $result = $this->order->update($request->all(), $order);
        return (new JsonResource($result))->response();
    }

    /**
     * Destroy resources by given id.
     * @param string $id
     * @return void
     */
    public function destroy($id)
    {
        return (new JsonResource(['id'=>$this->order->delete($id)]));
    }
}
