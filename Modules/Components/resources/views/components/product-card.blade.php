<div {{ $attributes->merge(['class' => 'card h-100 overflow-hidden ' . ($inStock ? '' : 'opacity-75 ') . $class]) }}>
    @if($image)
        @if($link)
            <a href="{{ $link }}">
                <img src="{{ $image }}" class="card-img-top" alt="{{ $title }}" style="object-fit: contain; height: 250px;">
            </a>
        @else
            <img src="{{ $image }}" class="card-img-top" alt="{{ $title }}" style="object-fit: contain; height: 250px;">
        @endif
    @endif
    <div class="card-body d-flex flex-column">
        @if($title)
            @if($link)
                <a href="{{ $link }}" class="text-decoration-none text-dark">
                    <h5 class="card-title">{{ $title }}</h5>
                </a>
            @else
                <h5 class="card-title">{{ $title }}</h5>
            @endif
        @endif
        @if($description)
            <p class="card-text text-muted">{{ $description }}</p>
        @endif
        @if(isset($body))
            {{ $body }}
        @endif
        <div class="mt-auto">
            @if($price)
                <div class="d-flex align-items-center mb-2">
                    @if($originalPrice)
                        <span class="text-decoration-line-through text-muted me-2">{{ $originalPrice }}</span>
                    @endif
                    <span class="h5 mb-0">{{ $price }}</span>
                </div>
            @endif
            @if($inStock && $price && $contentId)
                <button type="button"
                        class="btn btn-primary w-100"
                        data-mw-cart-add-and-checkout="{{ $contentId }}">
                    <i class="mdi mdi-cart me-1"></i>{{ $addToCartText }}
                </button>
            @endif
        </div>
        {{ $slot }}
    </div>
</div>