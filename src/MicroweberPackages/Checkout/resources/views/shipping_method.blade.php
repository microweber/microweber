@extends('checkout::layout')

@section('content')

    <div class="col-12">


        <form method="post" action="{{ route('checkout.shipping_method_save') }}">

            <a href="{{ site_url() }}" class="btn btn-outline-primary"><i class="mdi mdi-arrow-left"></i> {{ _e('Back to shopping') }}</a>
            <a href="{{ route('checkout.cart') }}" class="btn btn-outline-primary"><i class="mdi mdi-cart"></i> {{ _e('Back to cart') }}</a>
            <a href="{{ route('checkout.contact_information') }}" class="btn btn-outline-primary"><i class="mdi mdi-phone"></i> {{ _e('Back to contact information') }}</a>
            <div class="shop-cart" style="margin-top:25px;">

                <div class="card" style="margin-bottom:15px">
                    <div class="card-body">
                        Contact info:
                        <br />
                        Name: <?php if (!empty($checkout_session['first_name'])) echo $checkout_session['first_name']; ?>
                        <?php if (!empty($checkout_session['last_name'])) echo $checkout_session['last_name']; ?>
                        <br/>
                        Phone: <?php if (!empty($checkout_session['phone'])) echo $checkout_session['phone']; ?>
                        <br />
                        E-mail: <?php if (!empty($checkout_session['email'])) echo $checkout_session['email']; ?>
                    </div>
                </div>

                <module type="shop/shipping" data-store-values="true" />

            </div>

            <button type="submit" class="btn btn-info">{{ _e('Continue') }}</button>
        </form>
    </div>

@endsection
