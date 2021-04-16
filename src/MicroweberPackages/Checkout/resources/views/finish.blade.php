@extends('checkout::layout')

@section('checkout_sidebar')
    <div class="col-lg-6 col-12 checkout-v2-sidebar h-100 d-lg-block d-none">
            <div class="row align-self-center justify-content-center">
                <i class="checkout-v2-finish-icon mdi mdi-checkbox-marked-circle-outline"></i>
            </div>
    </div>
@endsection

@section('content')
    <div class="col-6 d-flex justify-content-center align-items-center">
        <div class="shop-cart text-center">
            {{--<div class="d-flex justify-content-center">@include('checkout::logo')</div>--}}

            <label class="control-label mb-0"><?php _e("Your order is completed"); ?></label>
            <h4><?php _e("Thank you"); ?>!</h4>

            <label class="control-label mt-4 mb-0"><?php _e("Order number"); ?></label>
            <h4><?php print($order['id']); ?></h4>

            <div class="mt-4">
                <a href="{{ site_url() }}"><?php _e("Go to website"); ?></a>
            </div>
        </div>
    </div>
@endsection
