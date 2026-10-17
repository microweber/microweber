
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
                    @php $mwResults = $searchResults ?? collect(); @endphp
                    @if ($searchQuery !== '')
                        <h1 class="mw-frontend-search-results__heading mb-3">
                            {{ __('Search results for') }} "{{ $searchQuery }}"
                        </h1>

                        @if ($mwResults->isEmpty())
                            <p class="mw-frontend-search-results__hint text-muted mb-4">
                                {!! _e('No matching pages or products were found.', true) !!}
                            </p>
                        @else
                            <p class="mw-frontend-search-results__count text-muted mb-4">
                                {{ trans_choice('{1}:count result|[2,*]:count results', $mwResults->count(), ['count' => $mwResults->count()]) }}
                            </p>
                            <ul class="mw-frontend-search-results__list list-unstyled mb-4">
                                @foreach ($mwResults as $mwResult)
                                    <li class="mw-frontend-search-results__item mb-3" data-mw-content-type="{{ e($mwResult['type']) }}">
                                        <a class="mw-frontend-search-results__link h5 d-block mb-1" href="{{ $mwResult['link'] }}">{{ $mwResult['title'] }}</a>
                                        @if ($mwResult['excerpt'] !== '')
                                            <p class="mw-frontend-search-results__excerpt text-muted mb-1">{{ $mwResult['excerpt'] }}</p>
                                        @endif
                                        <span class="mw-frontend-search-results__badge badge bg-light text-muted">{{ ucfirst($mwResult['type']) }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
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
