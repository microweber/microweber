
<div class="card mt-4">
  <div class="table-responsive__">
     <table class="table card-table table-vcenter text-nowrap datatable">

      <thead>
      <tr class="bg-light">
          <th scope="col"> <input type="checkbox" wire:model="selectAll" class=""> </th>
          @if($showColumns['id'])
              @include('order::admin.orders.livewire.table-includes.table-th',['name'=>'ID', 'key'=>'id', 'filters'=>$filters])
          @endif

          @if($showColumns['image'])
              <th scope="col">{{ _e('Image') }}</th>
          @endif

          @if($showColumns['products'])
              <th scope="col">{{ _e('Products') }}</th>
          @endif

          @if($showColumns['customer'])
              <th scope="col">{{ _e('Customer') }}</th>
          @endif

          @if($showColumns['shipping_method'])
              <th scope="col">{{ _e('Shipping Method') }}</th>
          @endif

          @if($showColumns['payment_method'])
              <th scope="col">{{ _e('Payment Method') }}</th>
          @endif

          @if($showColumns['total_amount'])
              <th scope="col">{{ _e('Total Amount') }}</th>
          @endif

          @if($showColumns['created_at'])
              <th scope="col">{{ _e('Created At') }}</th>
          @endif
          @if($showColumns['updated_at'])
              <th scope="col">{{ _e('Updated At') }}</th>
          @endif

          @if($showColumns['actions'])
              <th scope="col">{{ _e('Actions') }}</th>
          @endif
      </tr>
      </thead>
      <tbody>
      @foreach ($orders as $order)

          @php
              $cartProducts = $order->cartProducts();
              $cartProduct = $cartProducts['firstProduct'];
              $carts = $cartProducts['products'];
          @endphp

          <tr class="manage-post-item">
              <td>
                  <input type="checkbox" value="{{ $order->id }}" wire:model="checked">
              </td>
              @if($showColumns['id'])
                  <td>
                      {{ $order->id }}
                  </td>
              @endif

              @if($showColumns['image'])
                  <td>
                      @if (isset($cartProduct) && $cartProduct != null)
                          <a href="{{route('admin.order.show', $order->id)}}">
                                  <img src="{{$cartProduct->thumbnail()}}" />
                          </a>
                      @else
                          <img src="{{thumbnail(120,120)}}" />
                      @endif
                  </td>
              @endif

              @if($showColumns['products'])
                  <td>
                      @if(isset($cartProduct->title))
                        <a class="tblr-body-color form-label font-weight-bold" href="{{route('admin.order.show', $order->id)}}">{{$cartProduct->title}}</a> <span class="text-muted">x{{$cartProduct->qty}}</span> <br />
                      @else
                          <span class="form-label text-muted font-weight-bold tblr-body-color">
                                {{ _e('Product is no longer available') }}
                            </span>
                      @endif

                      @include('order::admin.orders.livewire.display-types.show-more-products', ['carts'=>$carts])

                  </td>
              @endif

              @if($showColumns['customer'])
                  <td>
                      {{$order->customerName()}}
                  </td>
              @endif

              @if($showColumns['shipping_method'])
                  <td>
                      {{$order->shippingMethodName()}}
                  </td>
              @endif
              @if($showColumns['payment_method'])
                  <td>
                     <div class="mb-1">
                         {{$order->paymentMethodName()}}
                     </div>
                      @if($order->is_paid == 1)
                          <span class="badge font-weight-normal bg-success">{{ _e('Paid') }}</span>
                      @else
                          <span class="badge font-weight-normal bg-danger">{{ _e('Unpaid') }}</span>
                      @endif
                  </td>
              @endif

              @if($showColumns['total_amount'])
                  <td>
                      <div class="mb-1">{{currency_format($order->payment_amount, $order->payment_currency)}}</div>

                      @if($order->order_status == 'pending')
                          <span class="badge font-weight-normal bg-warning text-white">{{ _e('Pending') }}</span>
                      @elseif($order->order_status == 'new')
                          <span class="badge font-weight-normal bg-primary">{{ _e('New order') }}</span>
                      @elseif($order->order_status == 'completed')
                          <span class="badge font-weight-normal bg-success">{{ _e('Completed') }}</span>
                      @else
                          <span class="badge font-weight-normal bg-primary">{{$order->order_status}}</span>
                      @endif
                  </td>
              @endif

              @if($showColumns['created_at'])
                  <td style="text-align: center">
                      {{$order->created_at}}<br />  {{mw()->format->ago($order->created_at)}}
                  </td>
              @endif
              @if($showColumns['updated_at'])
                  <td style="text-align: center">
                      {{$order->updated_at}}<br />  {{mw()->format->ago($order->updated_at)}}
                  </td>
              @endif

              @if($showColumns['actions'])
                  <td class="text-center">
                      <div class="dropdown content-card-blade-dots-menu-wrapper">
                          <a href="#" class=" dropdown-toggle content-card-blade-dots-menu dots-menu-3" data-bs-toggle="dropdown"></a>
                          <div class="dropdown-menu">

                              <a href="{{route('admin.order.show', $order->id)}}" class="dropdown-item ps-4">
                                  <svg class="me-1" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 0 24 24" width="18px"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M12 6c3.79 0 7.17 2.13 8.82 5.5C19.17 14.87 15.79 17 12 17s-7.17-2.13-8.82-5.5C4.83 8.13 8.21 6 12 6m0-2C7 4 2.73 7.11 1 11.5 2.73 15.89 7 19 12 19s9.27-3.11 11-7.5C21.27 7.11 17 4 12 4zm0 5c1.38 0 2.5 1.12 2.5 2.5S13.38 14 12 14s-2.5-1.12-2.5-2.5S10.62 9 12 9m0-2c-2.48 0-4.5 2.02-4.5 4.5S9.52 16 12 16s4.5-2.02 4.5-4.5S14.48 7 12 7z"/></svg>

                                  {{ _e('View') }}
                              </a>

                              <a wire:click="delete('{{$order->id}}')" class="dropdown-item ps-4 text-danger">
                                  <svg class="me-1 text-danger" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 0 24 24" width="18px"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M16 9v10H8V9h8m-1.5-6h-5l-1 1H5v2h14V4h-3.5l-1-1zM18 7H6v12c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7z"/></svg>
                                  <?php _e("Delete") ?>
                              </a>

                          </div>
                      </div>
                  </td>
              @endif

          </tr>
      @endforeach
      </tbody>
    </table>
  </div>
  </div>

