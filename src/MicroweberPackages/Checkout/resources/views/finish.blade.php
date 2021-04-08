@extends('checkout::layout')

@section('checkout_sidebar')
@endsection

@section('content')

    <div class="col-12">
        <form method="post" action="{{ route('checkout.shipping_method_save') }}">
            <div class="shop-cart m-t-100 text-center">

                <label class="control-label mb-0"><?php _e("Your order is completed"); ?></label>
                <h4><?php _e("Thank you"); ?>!</h4>


                <label class="control-label mt-4 mb-0"><?php _e("Order number"); ?></label>
                <h4><?php _e($order['id']); ?>!</h4>

                <div class="mt-4">
                    <a href="{{ site_url() }}"><?php _e("Go to website"); ?></a>
                </div>

            </div>

        </form>
    </div>

@endsection
