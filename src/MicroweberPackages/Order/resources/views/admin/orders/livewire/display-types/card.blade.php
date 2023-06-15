
@if($orders->count() > 0)
    @foreach ($orders as $order)

        @php
            $carts = [];
            if (!empty($order->cart)) {
               $carts = $order->cart;
            }
            $cart = $order->cart->first();

            $cartProduct = [];
            if (isset($cart->products)) {
                $cartProduct = $cart->products->first();
            }
        @endphp
        <div class="card my-4">
            <div class="card-body">

                <div class="d-flex flex-wrap align-items-center">
                    <div class="col-auto me-lg-3">
                        @if (isset($cartProduct) && $cartProduct != null)
                            <a href="{{route('admin.order.show', $order->id)}}">
                                <img src="{{$cartProduct->thumbnail()}}" />
                            </a>
                        @endif
                    </div>

                    <div class="col-sm col-12">
                        <span class="text-primary text-break-line-2">

                           @foreach ($carts as $cart)
                                @php
                                    $cartProduct = $cart->products->first();
                                    if ($cartProduct == null) {
                                        continue;
                                    }
                                @endphp
                                <a class="form-label font-weight-bold" href="{{route('admin.order.show', $order->id)}}">{{$cartProduct->title}}</a>
                                <small class="text-muted" style="font-size: 12px !important;">
                                    {{$order->created_at}}
                                </small>

                            @endforeach
                        </span>
                    </div>

                    <div class="col-auto px-2">
                        <span>  {{$order->customerName()}}</span>
                    </div>

                    <div class="col-auto px-2">
                        <span>  {{$order->payment_amount}} {{$order->payment_currency}} </span>
                    </div>

                    <div class="col-auto px-2">
                        @if($order->is_paid == 1)--}}
                            <span class="text-muted">{{ _e('Paid') }}</span>
                        @else
                            <span class="text-danger">{{ _e('Unpaid') }}</span>
                        @endif
                    </div>

                    <div class="col-1 text-end">

                        <div class="dropdown content-card-blade-dots-menu-wrapper">
                            <a href="#" class=" dropdown-toggle content-card-blade-dots-menu" data-bs-toggle="dropdown"></a>
                            <div class="dropdown-menu">

                                <a href="{{route('admin.order.show', $order->id)}}" class="dropdown-item ps-4">
                                    <svg class="me-1" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 0 24 24" width="18px"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M12 6c3.79 0 7.17 2.13 8.82 5.5C19.17 14.87 15.79 17 12 17s-7.17-2.13-8.82-5.5C4.83 8.13 8.21 6 12 6m0-2C7 4 2.73 7.11 1 11.5 2.73 15.89 7 19 12 19s9.27-3.11 11-7.5C21.27 7.11 17 4 12 4zm0 5c1.38 0 2.5 1.12 2.5 2.5S13.38 14 12 14s-2.5-1.12-2.5-2.5S10.62 9 12 9m0-2c-2.48 0-4.5 2.02-4.5 4.5S9.52 16 12 16s4.5-2.02 4.5-4.5S14.48 7 12 7z"/></svg>

                                    {{ _e('View order') }}
                                </a>

                                <a href="" class="dropdown-item ps-4 text-danger">
                                    <svg class="me-1 text-danger" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 0 24 24" width="18px"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M16 9v10H8V9h8m-1.5-6h-5l-1 1H5v2h14V4h-3.5l-1-1zM18 7H6v12c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7z"/></svg>
                                    <?php _e("Delete") ?>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>


        {{--                <div>--}}
        {{--                    <small class="text-muted">{{ _e('E-mail') }}:</small>--}}
        {{--                    <p> {{$order->email}}</p>--}}
        {{--                </div>--}}

        {{--                <div>--}}
        {{--                    <small class="text-muted">{{ _e('Phone') }}:</small>--}}
        {{--                    <p>{{$order->phone}}</p>--}}
        {{--                </div>--}}
        {{--            </div>--}}

        {{--            <div class="col-sm-6 col-md-4">--}}
        {{--                <h6><strong>{{ _e('Payment Information') }}</strong></h6>--}}

        {{--                <div>--}}
        {{--                    <small class="text-muted">{{ _e('Amount') }}:</small>--}}
        {{--                    <p>{{$order->payment_amount}} {{$order->payment_currency}}</p>--}}
        {{--                </div>--}}

        {{--                <div>--}}
        {{--                    <small class="text-muted">{{ _e('Payment method') }}:</small>--}}
        {{--                    <p>{{$order->paymentMethodName()}}</p>--}}
        {{--                </div>--}}
        {{--            </div>--}}

        {{--            <div class="col-sm-6 col-md-4">--}}
        {{--                <h6><strong>{{ _e('Shipping Information') }}</strong></h6>--}}

        {{--                <div>--}}
        {{--                    <small class="text-muted">{{ _e('Shipping method') }}:</small>--}}
        {{--                    <p>{{$order->shippingMethodName()}}</p>--}}
        {{--                </div>--}}

        {{--                <div>--}}
        {{--                    <small class="text-muted">{{ _e('Address') }}:</small>--}}
        {{--                    <p>{{$order->addressText()}}</p>--}}
        {{--                </div>--}}
        {{--            </div>--}}
        {{--        </div>--}}

    @endforeach
@endif

