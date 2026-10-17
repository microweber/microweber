
@extends($extendsView ?? 'templates.bootstrap::layouts.master')

@section('title'){{ $hasProducts ? __('Shop') : __('Shop') }} · {{ get_option('website_title', 'website') ?: 'Microweber' }}@endsection

@push('meta')
    @if (! $hasProducts)
        <meta name="robots" content="noindex,nofollow">
    @endif
@endpush

@section('content')
    <div class="mw-frontend-shop section main-content" data-layout-container>
        <div class="my-md-5 my-3 container">
            <div class="row">
                <div class="col-12">
                    <header class="mw-frontend-shop__header mb-4">
                        <h1 class="mw-frontend-shop__heading">{{ __('Shop') }}</h1>
                        @if ($hasProducts)
                            <p class="mw-frontend-shop__hint text-muted">
                                {!! _e('Browse our products below.', true) !!}
                            </p>
                        @else
                            <p class="mw-frontend-shop__hint text-muted">
                                {!! _e('No products are available yet.', true) !!}
                            </p>
                        @endif
                    </header>

                    @if ($hasProducts)

                        <div class="mw-frontend-shop__grid">
                            <module type="shop" />
                        </div>
                    @else
                        <div class="mw-frontend-shop__empty text-center my-5">
                            <p class="text-muted mb-4">
                                {!! _e('Check back soon for new arrivals.', true) !!}
                            </p>
                            <a href="{{ url('/') }}" class="mw-frontend-shop__cta btn" style="background-color: #0d6efd; color: #fff; border-color: #0d6efd;">
                                ← {{ __('Return home') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
