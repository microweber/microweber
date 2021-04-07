@extends('checkout::layout')

@section('content')
    <form method="post" action="{{ route('checkout.payment_method_save') }}">

        <a href="{{ route('checkout.shipping_method') }}" class="btn btn-outline-primary"><i class="mdi mdi-arrow-left"></i> {{ _e('Back') }}</a>
        <div class="shop-cart mt-3">
            <label class="font-weight-bold control-label mb-0"><?php _e("Personal information"); ?></label>
            <small class="text-muted d-block mb-2"> <?php _e("Your information"); ?></small>

            <div class="card mb-3">
                <div class="card-body d-flex p-3">

                    <div class="col-10">
                        <?php if (!empty($checkout_session['first_name'])) echo $checkout_session['first_name']; ?>
                        <?php if (!empty($checkout_session['last_name'])) echo $checkout_session['last_name']; ?>
                        <br/>
                        <?php if (!empty($checkout_session['phone'])) echo $checkout_session['phone']; ?>
                        <br />
                        <?php if (!empty($checkout_session['email'])) echo $checkout_session['email']; ?>
                        <br />
                    </div>

                    <div class="col-2 justify-content-end text-right align-self-top px-0">
                        <a href="{{ route('checkout.contact_information') }}" class="btn btn-link text-right">{{ _e('Edit') }}</a>
                    </div>
                </div>
            </div>

            <label class="font-weight-bold control-label mb-0 pt-2"><?php _e("Shipping method"); ?></label>
            <small class="text-muted d-block mb-2"> <?php _e("Your choice"); ?></small>

            <div class="card mb-3">
                <div class="card-body d-flex p-3">
                    <div class="col-10">
                        <?php if (!empty($checkout_session['shipping_gw'])) echo $checkout_session['shipping_gw']; ?>
                    </div>

                    <div class="col-2 justify-content-end text-right align-self-top px-0">
                        <a href="{{ route('checkout.shipping_method') }}" class="btn btn-link text-right">{{ _e('Edit') }}</a>
                    </div>
                </div>
            </div>

            <module type="shop/payments" template="checkout_v2" />

            <module type="shop/checkout/terms" />

        </div>

        <button type="submit" class="btn btn-primary w-100">{{ _e('Finish your order') }}</button>
    </form>

@endsection
