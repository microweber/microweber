@extends('admin::layouts.app')

@section('content')

<div class="card-body col-xl-10 mx-auto mb-3">
    <div class="card-header d-flex col-12 align-items-center justify-content-between px-md-4">

        <div class="col d-flex justify-content-md-start justify-content-center align-items-center px-0">

            <h1 class="main-pages-title">

                <strong  >{{_e('Shop')}} {{_e('Orders')}}</strong>
            </h1>


        </div>


        <div class="col ms-4 input-icon">
            <div class="input-group input-group-flat ">
                <input type="text" wire:model.debounce.500ms="filters.keyword" placeholder="<?php _e("Search by keyword"); ?>..." class="form-control" autocomplete="off">
                <span class="input-group-text">
                    <a href="#" class="link-secondary ms-2" data-bs-toggle="tooltip" aria-label="<?php _e("Search filter")  ?>" data-bs-original-title="<?php _e("Search settings")  ?>"><!-- Download SVG icon from http://tabler-icons.io/i/adjustments -->
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M4 10a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path><path d="M6 4v4"></path><path d="M6 12v8"></path><path d="M10 16a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path><path d="M12 4v10"></path><path d="M12 18v2"></path><path d="M16 7a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path><path d="M18 4v1"></path><path d="M18 9v11"></path></svg>
                    </a>
               </span>
            </div>

        </div>


        <div class="col d-flex justify-content-md-end justify-content-end  px-0">

            <a href="{{route('admin.order.abandoned')}}" class="btn btn-outline-warning btn-sm ms-md-4 ms-1">{{_e('Abandoned Carts')}}</a>
            <a href="javascript:mw_admin_add_order_popup()" class="btn btn-outline-success btn-sm ms-md-4 ms-1">{{_e('Add Order')}}</a>

        </div>
    </div>

    <div class=" ">

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

@endsection

