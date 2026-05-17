{{--
    task-2026-05-17-3e91f4 / AI-837 — frontend /search results chrome.

    Rendered by Modules\Search\Http\Controllers\SearchController@index.
    Replaces the Templates/Bootstrap clean.blade.php "My title / My text
    content" hardcoded placeholder that pre-fix was the user-visible
    page for /search?q=X. Stage-3 propagation-without-renderer-update
    closure (sibling lineage AI-735 / AI-793 / AI-795 / AI-735b).

    AI-795 standing chrome-application checklist:
      1. Extends active template master with Bootstrap fallback
         (AI-757 pattern; $extendsView resolved by the controller).
      2. Semantic chrome container (.mw-frontend-search-results).
      3. Recovery / empty-state context (search input + helper copy).
      4. Correct HTTP status (200 OK — search is a legitimate surface,
         not an error) + noindex headers (X-Robots-Tag set by the
         controller; <meta name="robots" content="noindex,nofollow">
         set here as belt-and-braces).
      5. Pinned by SearchA1B2c3AI837StubElimContractTest.

    AI-837 Slice A scope: minimum-viable chrome to close the stub-
    renderer defect. Wiring the existing SearchComponent Livewire
    component for live results is filed as AI-837b follow-up — the
    component currently mounts on `keyword` query param, the audit
    used `q` (W3C-canonical), and a clean integration needs a small
    component-side mount() adjustment before shipping.
--}}
@extends($extendsView ?? 'templates.bootstrap::layouts.master')

@section('title'){{ $searchQuery !== '' ? __('Search results for') . " \"{$searchQuery}\"" : __('Search') }} · {{ get_option('website_title', 'website') ?: 'Microweber' }}@endsection

@push('meta')
    <meta name="robots" content="noindex,nofollow">
@endpush

@section('content')
    <div class="mw-frontend-search-results section main-content" data-layout-container>
        <div class="my-md-5 my-3 container">
            <div class="row">
                <div class="col-12 col-lg-10 col-xl-8 mx-auto">
                    @if ($searchQuery !== '')
                        <h1 class="mw-frontend-search-results__heading mb-3">
                            {{ __('Search results for') }} "{{ $searchQuery }}"
                        </h1>
                        <p class="mw-frontend-search-results__hint text-muted mb-4">
                            {!! _e('No matching pages or products were found.', true) !!}
                        </p>
                    @else
                        <h1 class="mw-frontend-search-results__heading mb-3">
                            {{ __('Search') }}
                        </h1>
                        <p class="mw-frontend-search-results__hint text-muted mb-4">
                            {!! _e('Type a term in the box below to search this site.', true) !!}
                        </p>
                    @endif

                    <form class="mw-frontend-search-results__form mb-4" method="get" action="{{ url('search') }}" role="search">
                        <label class="mw-frontend-search-results__label sr-only" for="mw-frontend-search-results-input">{{ __('Search') }}</label>
                        <div class="input-group">
                            <input
                                id="mw-frontend-search-results-input"
                                class="form-control"
                                type="search"
                                name="q"
                                value="{{ $searchQuery }}"
                                placeholder="{{ __('Search this site') }}"
                                autocomplete="off"
                                inputmode="search"
                                aria-label="{{ __('Search this site') }}"
                            >
                            <button type="submit" class="btn mw-frontend-search-results__submit" style="background-color: #0d6efd; color: #fff; border-color: #0d6efd;">
                                {{ __('Search') }}
                            </button>
                        </div>
                    </form>

                    <a href="{{ url('/') }}" class="mw-frontend-search-results__cta">
                        ← {{ __('Return home') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
