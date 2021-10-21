@extends('checkout::layout')

@section('logo-right-link')
    <div class="ml-auto align-self-center">
        <a href="{{ route('checkout.contact_information') }}" class="btn btn-link text-end">{{ _e('Back') }}</a>
    </div>
@endsection

@section('steps_content')

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

    <form method="post" action="{{ route('checkout.shipping_method_save') }}">
        @csrf
        <div class="shop-cart mt-5">
            <label class="font-weight-bold control-label mb-0"><?php _e("Personal information"); ?></label>
            <small class="text-muted d-block mb-2"> <?php _e("Your information"); ?></small>

            @include('checkout::contact_information_card')

            <div class="shop-cart-shipping mb-3">
                 <module type="shop/shipping" class="no-settings" @if(isset($checkout_session['shipping_gw'])) selected_provider="{{$checkout_session['shipping_gw']}}" @endif template="checkout_v2" data-store-values="true" />
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100" dusk="checkout-continue">{{ _e('Continue') }}</button>
    </form>
@endsection
