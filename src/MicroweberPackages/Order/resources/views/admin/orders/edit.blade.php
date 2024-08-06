@extends('admin::layouts.app')

@section('content')

    @include('order::admin.orders.partials.javascripts')

    <div class="main-toolbar">
        <a href="{{route('admin.order.index')}}" class="btn btn-link text-silver px-0" data-bs-toggle="tooltip" data-title="Back to list"><i class="mdi mdi-chevron-left"></i> <?php _e('Back to orders'); ?></a>
    </div>

    <div class="col-xxl-10 col-lg-11 col-12 mx-auto">

        <form action="{{ route('admin.order.update', $order['id']) }}" method="POST">

            <label class="form-label font-weight-bold mb-3"><?php _e('Shipping details'); ?></label>
            <div class="card mb-5 ">
                <div class="card-body">
                    <div class="row py-0">

                        <?php
                        if ($order['shipping_service'] == 'shop/shipping/gateways/country'):
                            ?>
                        <div class="col-md-6">

                            <div class="mb-2">
                                <label class="font-weight-bold"><?php _e("Country"); ?>:</label>
                                <input type="text" name="country" class="form-control" value="<?php print $order['country'] ?? '' ?>">
                            </div>

                            <div class="mb-2">
                                <label class="font-weight-bold"><?php _e("City"); ?>:</label>
                                <input type="text" name="city" class="form-control" value="<?php print $order['city']  ?? '' ?>">
                            </div>

                            <div class="mb-2">
                                <label class="font-weight-bold"><?php _e("State"); ?>:</label>
                                <input type="text" name="state" class="form-control" value="<?php print $order['state']  ?? '' ?>">
                            </div>

                            <div class="mb-2">
                                <label class="font-weight-bold"><?php _e("Post code"); ?>:</label>
                                <input type="text" name="zip" class="form-control" value="<?php print $order['zip']  ?? '' ?>">
                            </div>

                            <div class="mb-2">
                                <label class="font-weight-bold"><?php _e("Address"); ?>:</label>
                                <input type="text" name="address" class="form-control" value="<?php print $order['address'] ?? '' ?>">
                            </div>

                            <div class="mb-2">
                                <label class="font-weight-bold"><?php _e("Address 2"); ?>:</label>
                                <input type="text" name="address2" class="form-control" value="<?php print $order['address2']  ?? '' ?>">
                            </div>

                            <div class="mb-4">
                                <label class="font-weight-bold"><?php _e("Phone"); ?>:</label>
                                <input type="text" name="phone" class="form-control" value="<?php print $order['phone']  ?? '' ?>">
                            </div>

                            <div class="mb-2">
                                <label class="font-weight-bold"><?php _e('Additional information'); ?>:</label>
                                <textarea name="other_info" class="form-control"><?php print $order['other_info']  ?? '' ?></textarea>
                            </div>
                        </div>

                        <div class="col-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-outline-primary btn-sm "> <?php _e("Save") ?></button>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection
