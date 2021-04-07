@extends('checkout::layout')

@section('content')

{{--faila se namira v: /src/MicroweberPackages/Checkout/resources/views/contact_information.blade.php--}}
    <form method="post" action="{{ route('checkout.contact_information_save') }}">
        <div class="m-t-20 edit nodrop" field="checkout_personal_information_title">
            <h4 class="mb-0"><?php _e("Personal Information"); ?></h4>
            <small class="text-muted d-block mb-2"> <?php _e("Please fill the fields bellow"); ?></small>
        </div>

        <div class="form-group">
            <label for="exampleInputFirstName"><?php _e("First Name"); ?></label>
            <input name="first_name" type="text" value="<?php if (!empty($checkout_session['first_name'])) echo $checkout_session['first_name']; ?>" class="form-control"
                   placeholder="<?php _e("First Name"); ?>">
        </div>
        <div class="form-group">
            <label for="exampleInputLastName"><?php _e("Last Name"); ?></label>
            <input name="last_name" type="text" value="<?php if (!empty($checkout_session['last_name'])) echo $checkout_session['last_name']; ?>" class="form-control"
                   placeholder="<?php _e("Last Name"); ?>">
        </div>

        <div class="form-group">
            <label for="exampleInputEmail1"><?php _e("Email"); ?></label>
            <input name="email" type="email" value="<?php if (!empty($checkout_session['email'])) echo $checkout_session['email']; ?>" class="form-control"
                   placeholder="<?php _e("Enter email"); ?>">
        </div>
        <div class="form-group">
            <label for="exampleInputPhone"><?php _e("Phone"); ?></label>
            <input name="phone" type="text" value="<?php if (!empty($checkout_session['phone'])) echo $checkout_session['phone']; ?>" class="form-control"
                   placeholder="<?php _e("Enter phone"); ?>">
        </div>

        <?php if (get_option('enable_coupons', 'shop') == 1): ?>
        <?php
        $discountData = app()->cart_manager->totals('discount');
        ?>
        <module type="shop/coupons" template="modal" />
        <?php endif; ?>

        <button type="submit" class="btn btn-primary w-100 mt-3">{{ _e('Continue') }}</button>
    </form>
@endsection
