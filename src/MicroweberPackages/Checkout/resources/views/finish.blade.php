@extends('checkout::layout')

@section('content')

    <div class="col-12">
        <form method="post" action="{{ route('checkout.shipping_method_save') }}">
            <div class="shop-cart" style="margin-top:25px;">

                <h1>Thank you, {{$order['first_name']}} {{$order['last_name']}}!</h1>
                <br />
                ORDER IS FINISHED!

             {{--   <br />
                Order info:
             @dump($order)
--}}
            </div>

            <a href="{{ site_url() }}" class="btn btn-outline-primary"><i class="mdi mdi-shopping"></i> {{ _e('Back to shopping') }}</a>
        </form>
    </div>

@endsection
