@extends('checkout::layout')

@section('content')

    <div class="col-12">
        <form method="post" action="{{ route('checkout.shipping_method_save') }}">
            <div class="shop-cart" style="margin-top:25px;">

                <h1>Thank you, {{$order['first_name']}} {{$order['last_name']}}!</h1>

             {{--   <br />
                Order info:
             @dump($order)
--}}
            </div>

        </form>
    </div>

@endsection
