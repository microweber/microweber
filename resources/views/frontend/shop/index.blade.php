{{--
    task-2026-05-17-66a21a / AI-849 — frontend /shop chrome.

    Rendered by Modules\Shop\Http\Controllers\ShopController@index.
    Replaces the Templates/Bootstrap clean.blade.php "Describe your
    company / Call to action / The Feature Title / Pictures In The
    Sky / $79 iWork '08" hardcoded fixture placeholder that pre-fix
    was the user-visible page for /shop AND every /shop/{path}
    subroute. Stage-3 propagation-without-renderer-update closure on
    the primary commerce surface (sibling lineage AI-755/795/837).

    AI-795 standing chrome-application checklist:
      1. Extends active template master with Bootstrap fallback
         (AI-757 pattern; $extendsView resolved by the controller).
      2. Semantic chrome container (.mw-frontend-shop).
      3. Recovery / empty-state context (shop heading + product grid
         embed via the existing <module type="shop" /> Microweber
         module renderer; falls through to a friendly empty-state
         when no products exist).
      4. Correct HTTP status (200 OK — /shop is a legitimate commerce
         surface, not an error) + X-Robots-Tag: noindex ONLY when the
         catalogue is empty (commerce surfaces with real catalogue
         MUST be indexable for SEO; controller emits noindex header
         only when $hasProducts === false).
      5. Pinned by Shop66a21aAI849ShopStubElimContractTest.

    AI-849 Slice A scope: minimum-viable chrome to close the stub-
    renderer defect. AI-849b follow-up: per-subroute differentiation
    (filter to category for /shop/category, filter to featured for
    /shop/featured, etc.) — currently every subroute renders the same
    chrome which is sufficient to drop the stub leak. The
    <module type="shop" /> embed below renders the existing
    ShopComponent Livewire product grid which already supports the
    standard category / price / tag filters as URL query params.
--}}
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
                        {{-- AI-849 Slice A — render the existing ShopComponent
                             Livewire product grid via the Microweber module
                             parser. The <module type="shop" /> tag resolves
                             to Modules/Shop/resources/views/templates/default.blade.php
                             which mounts the ShopComponent Livewire grid. --}}
                        <div class="mw-frontend-shop__grid">
                            {!! parse_modules_html('<module type="shop" />') !!}
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
