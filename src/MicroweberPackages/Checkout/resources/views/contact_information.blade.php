@extends('checkout::layout')

@section('logo-right-link')
<div class="ml-auto align-self-center">
    <a href="{{ shop_url() }}" class="btn btn-link text-end text-right">{{ _e('Continue shopping') }}</a>
</div>
@endsection

@section('steps_content')

{{--faila se namira v: /src/MicroweberPackages/Checkout/resources/views/contact_information.blade.php--}}
    <form method="post" action="{{ route('checkout.contact_information_save') }}">
        @csrf
        <div class="mt-5 edit nodrop" field="checkout_personal_information_title">
            <h4 class="mb-0"><?php _e("Personal Information"); ?></h4>
            <small class="text-muted d-block mb-2"> <?php _e("Please fill the fields bellow"); ?></small>
        </div>

        <div class="form-group @if(isset($errors['first_name'])) has-danger @endif">
            <label><?php _e("First Name"); ?></label>
            <input name="first_name" type="text" value="<?php if (!empty($checkout_session['first_name'])) echo $checkout_session['first_name']; ?>" class="form-control @if(isset($errors['first_name'])) is-invalid @endif">

            @if(isset($errors['first_name']))<span class="invalid-feedback">{{$errors['first_name'][0]}}</span>@endif
        </div>
        <div class="form-group @if(isset($errors['last_name'])) has-danger @endif">
            <label><?php _e("Last Name"); ?></label>
            <input name="last_name" type="text" value="<?php if (!empty($checkout_session['last_name'])) echo $checkout_session['last_name']; ?>" class="form-control @if(isset($errors['last_name'])) is-invalid @endif">
            @if(isset($errors['last_name']))<span class="invalid-feedback">{{$errors['last_name'][0]}}</span>@endif
        </div>

        <div class="form-group @if(isset($errors['email'])) has-danger @endif">
            <label><?php _e("Email"); ?></label>
            <input name="email" type="email" value="<?php if (!empty($checkout_session['email'])) echo $checkout_session['email']; ?>" class="form-control @if(isset($errors['email'])) is-invalid @endif">
            @if(isset($errors['email']))<span class="invalid-feedback">{{$errors['email'][0]}}</span>@endif
        </div>

        <div class="form-group @if(isset($errors['phone'])) has-danger @endif">
            <label><?php _e("Phone"); ?></label>
            <input name="phone" type="text" value="<?php if (!empty($checkout_session['phone'])) echo $checkout_session['phone']; ?>" class="form-control @if(isset($errors['phone'])) is-invalid @endif">
            @if(isset($errors['phone']))<span class="invalid-feedback">{{$errors['phone'][0]}}</span>@endif
        </div>

        <button type="submit" class="btn btn-primary w-100 mt-3 js-checkout-continue">{{ _e('Continue') }}</button>
    </form>
@endsection
