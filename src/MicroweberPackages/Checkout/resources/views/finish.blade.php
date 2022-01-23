@extends('checkout::layout')

@section('checkout_sidebar')
    <div class="col-lg-6 col-12 checkout-v2-sidebar h-100 d-lg-block d-none">
            <div class="row align-self-center justify-content-center">
                <i class="checkout-v2-finish-icon mdi mdi-checkbox-marked-circle-outline text-center"></i>
            </div>
    </div>
@endsection

@section('content')
    <div class="col-lg-6 col-12 d-flex justify-content-center align-items-center">
        <div class="shop-cart text-center">
            {{--<div class="d-flex justify-content-center">@include('checkout::logo')</div>--}}


            @if (\Session::has('success'))
                <div class="row" >
                    <ul class="list-group">
                        <li class="list-group-item list-group-item-action list-group-item-success">{!! \Session::get('success') !!}</li>
                    </ul>
                </div>
            @endif


            @if (\Session::has('error'))
                <div class="row" >
                    <ul class="list-group">
                        <li class="list-group-item list-group-item-action list-group-item-danger">{!! \Session::get('error') !!}</li>
                    </ul>
                </div>
            @endif


            <div class="edit" field="checkout_finish_message" rel="global">
                <label class="control-label mb-0"><?php _e("Your order is completed"); ?></label>
                <h4><?php _e("Thank you"); ?>!</h4>
            </div>

            <label class="control-label mt-4 mb-0"><?php _e("Order number"); ?></label>
            <h4 dusk="order-number"><?php print($order['id']); ?></h4>

            <div class="edit" field="checkout_finish_button" rel="global">
                <div class="mt-4">
                    <a href="{{ site_url() }}"><?php _e("Go to website"); ?></a>
                </div>
            </div>
        </div>
    </div>
@endsection
