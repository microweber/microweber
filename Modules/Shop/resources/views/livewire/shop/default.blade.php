<div>
    <div class="products-list">
        @foreach($products as $product)
            <div class="product-item">
                <h3>{{ $product->title }}</h3>
                @if($product->price)
                    <div class="price">{{ currency_format($product->price) }}</div>
                @endif
                @if($product->description)
                    <div class="description">{{ $product->description }}</div>
                @endif
            </div>
        @endforeach
    </div>
</div>
