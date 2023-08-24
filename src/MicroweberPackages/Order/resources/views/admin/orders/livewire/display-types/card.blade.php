
@if($orders->count() > 0)
    @foreach ($orders as $order)

        @php
            $cartProducts = $order->cartProducts();
            $cartProduct = $cartProducts['firstProduct'];
            $carts = $cartProducts['products'];
        @endphp
        <div class="card my-4">
            <div class="card-body">

                <div class="d-flex flex-wrap align-items-center">
                    <div class="col-auto me-md-3 mb-md-0 mb-2 ms-2">
                        @if (isset($cartProduct) && $cartProduct != null)
                            <a href="{{route('admin.order.show', $order->id)}}">
                                <img src="{{$cartProduct->thumbnail()}}" />
                            </a>
                        @else
                            <img src="{{thumbnail(120,120)}}" />
                        @endif
                    </div>

                    <div class="col-sm col-12">

                        @if(isset($cartProduct->title))
                            <a class="form-label font-weight-bold tblr-body-color" href="{{route('admin.order.show', $order->id)}}">{{$cartProduct->title}}</a>
                        @else
                            <span class="form-label text-muted font-weight-bold tblr-body-color">
                                {{ _e('Product is no longer available') }}
                            </span>
                        @endif

                        <small class="text-muted" style="font-size: 12px !important;">
                            {{ _e("Updated") }}: {{$order->updated_at->format('M d, Y, h:i A')}}
                        </small>

                        @include('order::admin.orders.livewire.display-types.show-more-products', ['carts'=>$carts])

                    </div>

                    <div class="col-auto px-2">
                        <span>  {{$order->customerName()}}</span>
                    </div>

                    <div class="col-auto px-2">
                        <span>  {{$order->payment_amount}} {{$order->payment_currency}} </span>
                    </div>

                    <div class="col-auto px-2">
                        @if($order->is_paid == 1)
                            <span class="text-muted">{{ _e('Paid') }}</span>
                        @else
                            <span class="text-danger">{{ _e('Unpaid') }}</span>
                        @endif
                    </div>

                    <div class="col-1 text-end">

                        <div class="dropdown content-card-blade-dots-menu-wrapper">
                            <a href="#" class="dropdown-toggle content-card-blade-dots-menu" data-bs-toggle="dropdown"></a>
                            <div class="dropdown-menu">

                                <a href="{{route('admin.order.show', $order->id)}}" class="dropdown-item ps-4">
                                    <svg class="me-1" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 0 24 24" width="18px"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M12 6c3.79 0 7.17 2.13 8.82 5.5C19.17 14.87 15.79 17 12 17s-7.17-2.13-8.82-5.5C4.83 8.13 8.21 6 12 6m0-2C7 4 2.73 7.11 1 11.5 2.73 15.89 7 19 12 19s9.27-3.11 11-7.5C21.27 7.11 17 4 12 4zm0 5c1.38 0 2.5 1.12 2.5 2.5S13.38 14 12 14s-2.5-1.12-2.5-2.5S10.62 9 12 9m0-2c-2.48 0-4.5 2.02-4.5 4.5S9.52 16 12 16s4.5-2.02 4.5-4.5S14.48 7 12 7z"/></svg>

                                    {{ _e('View order') }}
                                </a>

                                <button wire:click="delete('{{$order->id}}')" class="dropdown-item ps-4 text-danger">
                                    <svg class="me-1 text-danger" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 0 24 24" width="18px"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M16 9v10H8V9h8m-1.5-6h-5l-1 1H5v2h14V4h-3.5l-1-1zM18 7H6v12c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7z"/></svg>
                                    <?php _e("Delete") ?>
                                </button>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    @endforeach
@endif

