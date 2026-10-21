@extends($extendsView ?? 'templates.bootstrap::layouts.master')

@section('title'){{ __('Vendors') }} · {{ get_option('website_title', 'website') ?: 'Microweber' }}@endsection

@push('meta')
    @if ($vendors->isEmpty())
        <meta name="robots" content="noindex,nofollow">
    @endif
@endpush

@section('content')
    <div class="mw-vendors-directory section main-content" data-layout-container>
        <div class="my-md-5 my-3 container">
            <header class="mb-4">
                <h1 class="mw-vendors-directory__heading">{{ __('Vendors') }}</h1>
                <p class="text-muted">{!! _e('Browse our marketplace sellers and their products.', true) !!}</p>
            </header>

            @if ($vendors->isNotEmpty())
                <div class="row g-4">
                    @foreach ($vendors as $vendor)
                        <div class="col-12 col-md-6 col-lg-4">
                            <a class="mw-vendors-directory__card card h-100 text-decoration-none text-reset border-0 shadow-sm"
                               href="{{ route('vendors.show', $vendor->slug) }}">
                                @if (!empty($vendor->logo))
                                    <img src="{{ $vendor->logo }}" alt="{{ $vendor->display_name }}"
                                         class="card-img-top" style="height:160px;object-fit:cover;">
                                @endif
                                <div class="card-body">
                                    <h2 class="h5 mb-1">{{ $vendor->display_name }}</h2>
                                    @if (!empty($vendor->description))
                                        <p class="text-muted mb-0">{{ \Illuminate\Support\Str::limit(strip_tags($vendor->description), 120) }}</p>
                                    @endif
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center my-5">
                    <p class="text-muted mb-4">{!! _e('No vendors are available yet.', true) !!}</p>
                    <a href="{{ url('/') }}" class="btn"
                       style="background-color:#0d6efd;color:#fff;border-color:#0d6efd;">
                        ← {{ __('Return home') }}
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
