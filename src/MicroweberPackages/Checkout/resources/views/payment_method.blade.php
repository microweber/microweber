@extends('checkout::layout')


@section('logo-right-link')
    <div class="ml-auto align-self-center">
        <a href="{{ route('checkout.shipping_method') }}" class="btn btn-link text-right">{{ _e('Back') }}</a>
    </div>
@endsection

@section('content')

    <form method="post" action="{{ route('checkout.payment_method_save') }}">
        <div class="shop-cart mt-5">
            <label class="font-weight-bold control-label mb-0"><?php _e("Personal information"); ?></label>
            <small class="text-muted d-block mb-2"> <?php _e("Your information"); ?></small>

            @include('checkout::contact_information_card')

            <label class="font-weight-bold control-label mb-0 pt-2"><?php _e("Shipping method"); ?></label>
            <small class="text-muted d-block mb-2"> <?php _e("Your choice"); ?></small>

            <div class="card mb-3">
                <div class="card-body d-flex p-3">
                    <div class="col-10">
                        <?php
                        if (!empty($checkout_session['shipping_gw'])) {
                        $shippingGatewayModuleInfo = module_info($checkout_session['shipping_gw']);
                        ?>
                            <i class="<?php echo $shippingGatewayModuleInfo['settings']['icon_class'];?>" style="font-size:38px"></i>  <?php echo $shippingGatewayModuleInfo['name'];?>

                            <?php
                            $instructions = app()->shipping_manager->driver($checkout_session['shipping_gw'])->instructions($checkout_session);
                            if (!empty($instructions)) {
                                echo '<br />' . $instructions;
                            }
                            ?>

                        <?php } ?>
                        <?php if(!empty($checkout_session['country'])):?>
                            <hr />
                            <?php if (!empty($checkout_session['country'])) { echo $checkout_session['country']; } ?>
                            <?php if (!empty($checkout_session['city'])) {  echo ', ' . $checkout_session['city']; } ?>
                            <?php if (!empty($checkout_session['address'])) { echo  ', ' .  $checkout_session['address']; } ?>
                            <?php if (!empty($checkout_session['zip'])) { echo  ',  ' .  $checkout_session['zip'] . '<br />'; } ?>

                            <?php if (!empty($checkout_session['other_info'])) { echo '<div class="mt-2"><i class="mdi mdi-comment"></i> ' .  $checkout_session['other_info'] . '<br /></div>'; } ?>

                        <?php endif; ?>
                    </div>

                    <div class="col-2 justify-content-end text-right align-self-top px-0">
                        <a href="{{ route('checkout.shipping_method') }}" class="btn btn-link text-right">{{ _e('Edit') }}</a>
                    </div>
                </div>
            </div>

            @if (isset($errors))
                <ul class="list-group">
                    @foreach ($errors as $fields)
                        @foreach ($fields as $field)
                            <li class="list-group-item list-group-item-danger">{!! $field !!}</li>
                        @endforeach
                    @endforeach
                </ul>
            @endif

            <module type="shop/payments" @if(isset($checkout_session['payment_gw'])) selected_provider="{{$checkout_session['payment_gw']}}" @endif  template="checkout_v2" />

            <module type="shop/checkout/terms" template="checkout_v2" />
        </div>
        <button type="submit" class="btn btn-primary w-100 js-finish-your-order"> {{ _e('Finish your order') }}</button>
    </form>

@endsection
