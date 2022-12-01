<div class="mt-4">
@if($orders->count() > 0)
@foreach ($orders as $order)

    @php




        if (empty($order->cart)) {
            continue;
        }

        $carts = $order->cart ;
        if ($carts->count() == 0) {
            continue;
        }



        $cart = $order->cart->first();
        $cartProduct = $cart->products->first();
    @endphp
    <div class="card mb-2 not-collapsed-border collapsed card-order-holder bg-silver">
    <div class="card-body">

        <div class="row"  data-bs-toggle="collapse" href="#collapse-order-card-{{$order->id}}" role="button" aria-expanded="false" aria-controls="collapse-order-card-{{$order->id}}">
            <div class="col-12 col-md-6" >
                <div class="row align-items-center">
                    <div class="col item-image">
                        <div class="img-circle-holder">


                            @if (isset($cartProduct) && $cartProduct != null)
                                <a href="#">
                                    <img src="{{$cartProduct->thumbnail()}}" />
                                </a>
                            @endif

                        </div>
                    </div>
                    <div class="col item-id"><span class="text-primary">#{{$order->id}}</span></div>
                    <div class="col item-title">
                        <span class="text-primary text-break-line-2">

                           @foreach ($carts as $cart)
                                @php
                                    $cartProduct = $cart->products->first();
                                    if ($cartProduct == null) {
                                        continue;
                                    }
                                @endphp
                                <a href="#">{{$cartProduct->title}}</a> <span class="text-muted">x{{$cart->qty}}</span> <br />
                            @endforeach
                        </span>
                        <small class="text-muted">Ordered by:  {{$order->customerName()}}</small>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="row align-items-center">

                    <div class="col-6 col-sm-4 col-md item-amount">{{number_format($order->payment_amount,2)}} {{$order->payment_currency}}<br />
                        @if($order->is_paid == 1)
                            <span class="text-muterd">Paid</span>
                        @else
                            <span class="text-danger">Unpaid</span>
                        @endif
                    </div>
                    <div class="col-6 col-sm-4 col-md item-date">  {{$order->created_at}}<br /> <br /><small class="text-muted"> {{mw()->format->ago($order->created_at)}}</small></div>
                 {{--   <div class="col-12 col-sm-4 col-md item-status">
                        <span class="text-success">New</span><br /><small class="text-muted">&nbsp;</small>
                    </div>--}}
                </div>
            </div>
        </div>

        <div class="collapse" id="collapse-order-card-{{$order->id}}">
            <div class="row mt-3">
                <div class="col-12 text-center text-sm-left">
                    <a href="{{route('admin.order.show', $order->id)}}" class="btn btn-primary btn-sm btn-rounded">View order</a>
                </div>
            </div>
            <hr class="thin" />
            <div class="row">
                <div class="col-sm-6 col-md-4">
                    <h6><strong>Customer Information</strong></h6>
                    <div>
                        <small class="text-muted">Client name:</small>
                        <p> {{$order->customerName()}}</p>
                    </div>

                    <div>
                        <small class="text-muted">E-mail:</small>
                        <p> {{$order->email}}</p>
                    </div>

                    <div>
                        <small class="text-muted">Phone:</small>
                        <p>{{$order->phone}}</p>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4">
                    <h6><strong>Payment Information</strong></h6>

                    <div>
                        <small class="text-muted">Amount:</small>
                        <p>{{number_format($order->payment_amount,2)}} {{$order->payment_currency}}</p>
                    </div>

                    <div>
                        <small class="text-muted">Payment method:</small>
                        <p>{{$order->paymentMethodName()}}</p>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4">
                    <h6><strong>Shipping Information</strong></h6>

                    <div>
                        <small class="text-muted">Shipping method:</small>
                        <p>{{$order->shippingMethodName()}}</p>
                    </div>

                    <div>
                        <small class="text-muted">Address:</small>
                        <p>{{$order->addressText()}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach
@endif
</div>
