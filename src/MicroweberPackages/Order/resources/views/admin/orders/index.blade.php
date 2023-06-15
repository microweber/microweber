@extends('admin::layouts.app')

@section('content')

<div class="card-body col-xl-10 mx-auto mb-3">
    <div class="card-header d-flex col-12 align-items-center justify-content-between px-md-4">

        <div class="col d-flex justify-content-md-start justify-content-center align-items-center px-0">

            <h1 class="main-pages-title">{{_e('Orders')}}</h1>


        </div>

        SEARCH




        <div class="col d-flex justify-content-md-end justify-content-end  px-0">

            <a href="{{route('admin.order.abandoned')}}" class="btn btn-outline-dark me-md-4 me-1">
                {{_e('Abandoned Carts')}}
            </a>

            <a href="javascript:mw_admin_add_order_popup()" class="btn btn-dark">
                <svg fill="currentColor" class="me-2" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M446.667 856V609.333H200v-66.666h246.667V296h66.666v246.667H760v66.666H513.333V856h-66.666Z"/></svg>
                {{_e("Create New Order")}}
            </a>

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

