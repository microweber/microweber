@extends($extendsView ?? 'templates.bootstrap::layouts.master')

@section('title'){{ $vendor->display_name }} · {{ get_option('website_title', 'website') ?: 'Microweber' }}@endsection

@push('meta')
    @if ($products->isEmpty())
        <meta name="robots" content="noindex,nofollow">
    @endif
    <meta name="description" content="{{ \Illuminate\Support\Str::limit(strip_tags($vendor->description ?? ''), 160) }}">
@endpush

@section('content')
    <div class="mw-vendor-shop section main-content" data-layout-container>

        @if (!empty($vendor->banner))
            <div class="mw-vendor-shop__banner"
                 style="background-image:url('{{ $vendor->banner }}'); min-height: 220px; background-size: cover; background-position: center;"></div>
        @endif

        <div class="my-md-5 my-3 container">
            <header class="mw-vendor-shop__header mb-4 d-flex align-items-center flex-wrap gap-3">
                @if (!empty($vendor->logo))
                    <img src="{{ $vendor->logo }}" alt="{{ $vendor->display_name }}"
                         class="mw-vendor-shop__logo rounded"
                         style="width:72px;height:72px;object-fit:cover;">
                @endif
                <div>
                    <h1 class="mw-vendor-shop__heading mb-1">{{ $vendor->display_name }}</h1>
                    @if (!empty($vendor->description))
                        <p class="mw-vendor-shop__bio text-muted mb-0">{{ \Illuminate\Support\Str::limit(strip_tags($vendor->description), 240) }}</p>
                    @endif
                </div>
            </header>

            @if ($products->isNotEmpty())
                <div class="mw-vendor-shop__grid row g-4">
                    @foreach ($products as $product)
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="mw-vendor-product card h-100 border-0 shadow-sm">
                                <a class="text-decoration-none text-reset" href="{{ content_link($product->id) }}">
                                    <div class="mw-vendor-product__image"
                                         style="aspect-ratio:1/1;overflow:hidden;">
                                        {!! responsive_thumbnail($product->mediaUrl(), 800, 600, [
                                            'alt' => $product->title,
                                            'class' => 'w-100 h-100',
                                            'style' => 'object-fit:cover;',
                                        ]) !!}
                                    </div>
                                    <div class="card-body pb-0">
                                        <h4 class="h6 mb-1">{{ $product->title }}</h4>
                                        <p class="mw-vendor-product__price fw-bold mb-2">{!! currency_format($product->price) !!}</p>
                                    </div>
                                </a>
                                <div class="card-body pt-0">
                                    <button type="button"
                                            class="mw-add-to-cart-btn btn w-100"
                                            style="background-color:#0d6efd;color:#fff;border-color:#0d6efd;"
                                            data-content-id="{{ $product->id }}"
                                            data-price="{{ $product->price }}"
                                            data-title="{{ e($product->title) }}">
                                        {{ _e('Add to cart', true) }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="mw-vendor-shop__empty text-center my-5">
                    <p class="text-muted mb-4">{!! _e('This vendor has no products yet.', true) !!}</p>
                    <a href="{{ route('vendors.index') }}" class="btn"
                       style="background-color:#0d6efd;color:#fff;border-color:#0d6efd;">
                        ← {{ _e('Browse other vendors', true) }}
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
