@extends('checkout::layout')

@section('content')

    <div class="col-8">

        faila se namira v: /src/MicroweberPackages/Checkout/resources/views/shipping_method.blade.php

        <form method="post" action="{{ route('checkout.shipping_method_save') }}">

            <a href="{{ site_url() }}" class="btn btn-outline-primary"><i class="mdi mdi-cart"></i> {{ _e('Back to website') }}</a>
            <div class="shop-cart" style="margin-top:25px;">

                <div class="card" style="margin-bottom:15px;">
                    <div class="card-body">
                        Contact info:
                        <br />
                        Name: <?php if (!empty($checkout_session['first_name'])) echo $checkout_session['first_name']; ?>
                        <?php if (!empty($checkout_session['last_name'])) echo $checkout_session['last_name']; ?>
                        <br/>
                        Phone: <?php if (!empty($checkout_session['phone'])) echo $checkout_session['phone']; ?>
                        <br />
                        E-mail: <?php if (!empty($checkout_session['email'])) echo $checkout_session['email']; ?>
                        <br />
                        <a href="{{ route('checkout.contact_information') }}" class="btn btn-outline-primary"><i class="mdi mdi-pencil"></i> {{ _e('Edit contact information') }}</a>
                    </div>
                </div>

                <div class="shop-cart-shipping" style="margin-bottom:20px;">
                     <module type="shop/shipping" template="checkout_v2" data-store-values="true" />
                </div>
            </div>

            <button type="submit" class="btn btn-info">{{ _e('Continue') }}</button>
        </form>
    </div>

    <div class="col-4">
        <module type="shop/cart" template="checkout_v2_sidebar" data-checkout-link-enabled="n" />
    </div>

@endsection
