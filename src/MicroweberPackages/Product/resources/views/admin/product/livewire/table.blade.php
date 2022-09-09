<div>
    <table class="table">
        <thead>
        <tr>
            <th scope="col"><input type="checkbox"></th>
            <th scope="col">Image</th>
            <th scope="col">Title</th>
            <th scope="col">Price</th>
            <th scope="col">Stock</th>
            <th scope="col">Sales</th>
            <th scope="col">Quantity</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($products as $product)
        <tr>
            <td><input type="checkbox"></td>
            <td>
                <img src="{{$product->thumbnail()}}" class="w-8 h-8 rounded-full">
            </td>
            <td>{{$product->title}}</td>
            <td>
                @php
                if ($product->hasSpecialPrice()) {
                    $price = '<span class="h6" style="text-decoration: line-through;">'.currency_format($product->price).'</span>';
                    $price .= '<br /><span class="h5">'.currency_format($product->specialPrice).'</span>';
                } else {
                    $price = '<span class="h5">'.currency_format($product->price).'</span>';
                }
                @endphp

                {!! $price !!}
            </td>
            <td>
                @php
                  if ($product->InStock) {
                    $stock = '<span class="badge badge-success badge-sm">In stock</span>';
                } else {
                    $stock = '<span class="badge badge-danger badge-sm">Out Of Stock</span>';
                }
                @endphp
                {!! $stock !!}
            </td>
            <td>
                @php
                $ordersUrl = route('admin.order.index') . '?productId='.$product->id;
                if ($product->salesCount == 1) {
                    $sales = '<a href="'.$ordersUrl.'"><span class="text-green">'.$product->salesCount.' sale</span></a>';
                } else if ($product->salesCount > 1) {
                    $sales = '<a href="'.$ordersUrl.'"><span class="text-green">'.$product->salesCount.' sales</span></a>';
                } else {
                    $sales = '<span>'.$product->salesCount.' sales</span>';
                }
                @endphp
                {!! $sales !!}
            </td>
            <td>
            @php
                if ($product->qty == 'nolimit') {
                      $quantity = '<i class="fa fa-infinity" title="Unlimited Quantity"></i>';
                  } else if ($product->qty == 0) {
                      $quantity = '<span class="text-small text-danger">0</span>';
                  } else {
                      $quantity = $product->qty;
                  }
            @endphp
                {!! $quantity !!}
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>


{{ $products->links() }}
