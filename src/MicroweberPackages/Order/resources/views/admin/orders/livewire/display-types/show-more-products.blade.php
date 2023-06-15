@if(($carts->count()-1) > 0)
    <div x-data="{ open: false }">
        <button class="btn btn-link" x-on:click="open = ! open">Show {{($carts->count()-1)}} products</button>
        <div x-show="open">
            @foreach ($carts as $cart)
                @php
                    if ($loop->index == 0) {
                        continue;
                    }
                      $cartProduct = $cart->products->first();
                      if ($cartProduct == null) {
                          continue;
                      }
                @endphp
                <a class="tblr-body-color" href="{{route('admin.order.show', $order->id)}}">{{$cartProduct->title}}</a> <span class="text-muted">x{{$cart->qty}}</span> <br />
            @endforeach
        </div>
    </div>
@endif
