@extends($extendsView ?? 'templates.bootstrap::layouts.master')

@section('title'){{ __('Vendor not found') }} · {{ get_option('website_title', 'website') ?: 'Microweber' }}@endsection

@push('meta')
    <meta name="robots" content="noindex,nofollow">
@endpush

@section('content')
    <div class="mw-vendor-missing section main-content" data-layout-container>
        <div class="my-md-5 my-5 container text-center">
            <h1 class="mw-vendor-missing__heading">{{ __('Vendor not found') }}</h1>
            <p class="text-muted mb-4">
                {!! _e('This vendor storefront does not exist or is not available yet.', true) !!}
            </p>
            <a href="{{ route('vendors.index') }}" class="btn"
               style="background-color:#0d6efd;color:#fff;border-color:#0d6efd;">
                ← {{ _e('Browse all vendors', true) }}
            </a>
        </div>
    </div>
@endsection
