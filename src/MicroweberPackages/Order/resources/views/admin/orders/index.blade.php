<div class="card style-1 mb-3">
    <div class="card-header d-flex col-12 align-items-center justify-content-between px-md-4">

        <div class="col d-flex justify-content-md-start justify-content-center align-items-center px-0">

            <h5 class="mb-0 d-none d-sm-flex">
                <i class="mdi mdi-shopping text-primary mr-md-3 mr-1 justify-contetn-center"></i>
                <strong  >{{_e('Shop')}} {{_e('Orders')}}</strong>
            </h5>


        </div>
        <div class="col d-flex justify-content-md-end justify-content-end  px-0">

            <a href="{{route('admin.order.abandoned')}}" class="btn btn-outline-warning btn-sm ms-md-4 ms-1">{{_e('Abandoned Carts')}}</a>
            <a href="javascript:mw_admin_add_order_popup()" class="btn btn-outline-success btn-sm ms-md-4 ms-1">{{_e('Add Order')}}</a>

        </div>
    </div>

    <div class="card-body pt-3">

        @if($getOrder)
        <livewire:admin-orders-filters />
        @else
            <div class="no-items-found orders">
                <div class="row">
                    <div class="col-12">
                        <div class="no-items-box" style="background-image: url('<?php print modules_url(); ?>microweber/api/libs/mw-ui/assets/img/no_orders.svg'); ">
                            <h4>You don’t have any orders yet</h4>
                            <p>Here you can track your orders</p>
                            <br/>
                            <a href="javascript:mw_admin_add_order_popup()" class="btn btn-primary btn-rounded">{{_e('Add Order')}}</a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

