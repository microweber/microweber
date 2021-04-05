@extends('checkout::layout')

@section('content')

    <div class="col-12">
        <form method="post" action="{{ route('checkout.contact_information_save') }}">

        <a href="{{ site_url() }}" class="btn btn-outline-primary"><i class="mdi mdi-arrow-left"></i> {{ _e('Back to shopping') }}</a>
        <a href="{{ route('checkout.cart') }}" class="btn btn-outline-primary"><i class="mdi mdi-cart"></i> {{ _e('Back to cart') }}</a>
        <div class="shop-cart" style="margin-top:25px;">

            <div class="m-t-20 edit nodrop" field="checkout_personal_information_title">
                <small class="pull-right text-muted">*Fields are required</small>
                <label class="control-label">Personal Information</label>
                <small class="text-muted d-block mb-2"> <?php _e("Add your personal information"); ?></small>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="exampleInputFirstName"><?php _e("First Name"); ?></label>
                        <input required name="first_name" type="text" value="<?php if (!empty($checkout_session['first_name'])) echo $checkout_session['first_name']; ?>" class="form-control"
                               placeholder="<?php _e("First Name"); ?>">
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="exampleInputLastName"><?php _e("Last Name"); ?></label>
                        <input required name="last_name" type="text" value="<?php if (!empty($checkout_session['last_name'])) echo $checkout_session['last_name']; ?>" class="form-control"
                               placeholder="<?php _e("Last Name"); ?>">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-6">
                    <div class="form-group m-0">
                        <label for="exampleInputEmail1"><?php _e("Email"); ?></label>
                        <input required name="email" type="email" value="<?php if (!empty($checkout_session['email'])) echo $checkout_session['email']; ?>" class="form-control"
                               placeholder="<?php _e("Enter email"); ?>">
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="exampleInputPhone"><?php _e("Phone"); ?></label>
                        <input required name="phone" type="text" value="<?php if (!empty($checkout_session['phone'])) echo $checkout_session['phone']; ?>" class="form-control"
                               placeholder="<?php _e("Enter phone"); ?>">
                    </div>
                </div>
            </div>

        </div>

            <button type="submit" class="btn btn-info">{{ _e('Continue') }}</button>
        </form>
    </div>

@endsection
