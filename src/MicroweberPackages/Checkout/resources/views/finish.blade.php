@extends('checkout::layout')

@section('checkout_sidebar')
    <div class="col-lg-6 col-12 checkout-v2-sidebar h-100 d-lg-block d-none">
            <div class="row align-self-center justify-content-center">
                <i class="checkout-v2-finish-icon mdi mdi-checkbox-marked-circle-outline"></i>
            </div>
    </div>
@endsection

@section('content')
    <div class="col-lg-6 col-12 d-flex justify-content-center align-items-center">
        <div class="shop-cart text-center">
            {{--<div class="d-flex justify-content-center">@include('checkout::logo')</div>--}}

            <?php
              $order_completed_message = get_option('shop_order_completed_message', 'shop');
              if($order_completed_message != '') {
              ?>
                <h4><?php _e("Your order number");?> <?php print ($order['id']);?> <?php _e("is completed"); ?></h4>
              <?php
                print $order_completed_message;
              } else {
            ?>
                <label class="control-label mb-0"><?php _e("Your order number");?> <strong><?php print ($order['id']);?></strong> <?php _e("is completed"); ?></label>
                <h4><?php _e("Thank you for your order!"); ?></h4>
            <?php
              }
            ?>

            <div class="edit" field="checkout_finish_button" rel="global">
                <div class="mt-4">
                    <a href="{{ site_url() }}"><?php _e("Back to website"); ?></a>
                </div>
            </div>
        </div>
    </div>
@endsection
