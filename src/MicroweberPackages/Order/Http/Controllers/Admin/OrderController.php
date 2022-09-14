<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 8/19/2020
 * Time: 4:09 PM
 */
namespace MicroweberPackages\Order\Http\Controllers\Admin;

use Illuminate\Http\Request;
use MicroweberPackages\Admin\Http\Controllers\AdminController;
use MicroweberPackages\Order\Models\Order;

class OrderController extends AdminController
{
    public function index(Request $request) {
        return $this->view('order::admin.orders.index');
    }


    public function show($id)
    {
        $order = Order::where('id',$id)->first();

        if ($order == false) {
            return redirect(route('admin.order.index'));
        }

        if ($order->order_status == 'new') {
            $order->order_status = 'pending';
            $order->save();
        }

        return $this->view('order::admin.orders.show', [
            'order'=>$order
        ]);
    }

}
