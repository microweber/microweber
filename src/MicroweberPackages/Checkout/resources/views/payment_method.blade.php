@extends('checkout::layout')

@section('content')

    faila se namira v: /src/MicroweberPackages/Checkout/resources/views/payment_method.blade.php

    <div class="col-8">
        <form method="post" action="{{ route('checkout.payment_method_save') }}">

            <a href="{{ route('checkout.cart') }}" class="btn btn-outline-primary"><i class="mdi mdi-cart"></i> {{ _e('Back to cart') }}</a>
            <a href="{{ route('checkout.contact_information') }}" class="btn btn-outline-primary"><i class="mdi mdi-phone"></i> {{ _e('Back to contact information') }}</a>
            <a href="{{ route('checkout.shipping_method') }}" class="btn btn-outline-primary"><i class="mdi mdi-shipping"></i> {{ _e('Back to shipping information') }}</a>
            <div class="shop-cart" style="margin-top:25px;margin-bottom:25px;">


                <module type="shop/payments" template="checkout_v2" />

                <module type="shop/checkout/terms" />

            </div>

            <button type="submit" class="btn btn-info">{{ _e('Continue') }}</button>
        </form>
    </div>

    <div class="col-4">
        <module type="shop/cart" template="checkout_v2_sidebar" data-checkout-link-enabled="n" />
    </div>

@endsection
