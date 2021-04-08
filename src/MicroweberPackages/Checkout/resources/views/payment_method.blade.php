@extends('checkout::layout')


@section('logo-right-link')
    <div class="ml-auto align-self-center">
        <a href="{{ route('checkout.shipping_method') }}" class="btn btn-link text-right">{{ _e('Back') }}</a>
    </div>
@endsection

@section('content')

    @if (isset($errors))
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors as $fields)
                    @foreach ($fields as $field)
                        <li>{!! $field !!}</li>
                    @endforeach
                @endforeach
            </ul>
        </div>
    @endif

    <form method="post" action="{{ route('checkout.payment_method_save') }}">

        <div class="shop-cart mt-3">
            <label class="font-weight-bold control-label mb-0"><?php _e("Personal information"); ?></label>
            <small class="text-muted d-block mb-2"> <?php _e("Your information"); ?></small>

            @include('checkout::contact_information_card')

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
