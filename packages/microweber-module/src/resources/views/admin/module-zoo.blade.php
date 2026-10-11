@extends('admin::layouts.app')

@section('content')
    {{-- AI-71 / TICKET-AA (cycle-84 2026-05-08): module-zoo dev/QA
         page. Auto-discovered every installed module + every skin in
         its templates folder. Reviewers can scroll the page once and
         catch visual regressions across every skin without having to
         stage 33+ live-edit pages by hand.

         Filters live in the URL — `?type=Posts` to narrow to one
         module, `?installed_only=0` to include uninstalled modules
         too. Both filters degrade gracefully when missing.

         The actual rendering is delegated to Microweber's `<module>`
         tag — same engine the live-edit canvas uses — so anything
         that breaks the zoo would also break the customer-facing
         render. --}}
    <div id="module-zoo-wrapper" class="col-xxl-10 col-lg-11 col-12 mx-auto py-4">
        <header class="mb-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h1 class="h3 mb-1">{{ __('Module Zoo') }}</h1>
                <p class="text-muted mb-0">
                    {{ __('All :count modules with every skin variation rendered side-by-side for visual review.', ['count' => $totalModuleCount]) }}
                </p>
            </div>
            <form method="get" class="d-flex flex-wrap gap-2 mw-module-zoo-filters" role="search" aria-label="{{ __('Module zoo filters') }}">
                <label for="mw-module-zoo-type" class="visually-hidden">{{ __('Filter by module name') }}</label>
                <input type="search"
                       id="mw-module-zoo-type"
                       name="type"
                       value="{{ $typeFilter }}"
                       class="form-control"
                       placeholder="{{ __('Filter by module name (e.g. Posts)') }}">
                <label class="form-check form-switch d-flex align-items-center gap-2 mb-0">
                    <input type="checkbox"
                           name="installed_only"
                           value="1"
                           class="form-check-input"
                           @if($installedOnly) checked @endif>
                    <span>{{ __('Installed only') }}</span>
                </label>
                <button type="submit" class="btn btn-outline-primary">
                    {{ __('Apply') }}
                </button>
            </form>
        </header>

        @if (empty($modules))
            <div class="alert alert-info" role="status">
                {{ __('No modules matched the current filters.') }}
            </div>
        @else
            @foreach ($modules as $module)
                <section class="mw-module-zoo-section card mb-5"
                         id="mw-module-zoo-{{ $module['name'] }}"
                         aria-labelledby="mw-module-zoo-{{ $module['name'] }}-h">
                    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <div class="d-flex align-items-center gap-3">
                            @if (! empty($module['icon']))
                                <span class="mw-module-zoo-icon" aria-hidden="true">
                                    {!! $module['icon'] !!}
                                </span>
                            @endif
                            <div>
                                <h2 id="mw-module-zoo-{{ $module['name'] }}-h" class="h5 mb-0">
                                    {{ $module['title'] }}
                                </h2>
                                @if (! empty($module['description']))
                                    <small class="text-muted">{{ $module['description'] }}</small>
                                @endif
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-secondary">
                                {{ trans_choice('{1} :count skin|[2,*] :count skins', count($module['skins']), ['count' => count($module['skins'])]) }}
                            </span>
                            <code class="small text-muted">{{ $module['name'] }}</code>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            @foreach ($module['skins'] as $skin)
                                <div class="col-12 col-lg-6">
                                    <article class="mw-module-zoo-skin border rounded p-3 h-100"
                                             aria-labelledby="mw-module-zoo-{{ $module['name'] }}-{{ $skin }}-h">
                                        <header class="d-flex justify-content-between align-items-center mb-3">
                                            <h3 id="mw-module-zoo-{{ $module['name'] }}-{{ $skin }}-h" class="h6 mb-0">
                                                {{ $skin }}
                                            </h3>
                                            <code class="small text-muted">template="{{ $skin }}"</code>
                                        </header>
                                        <div class="mw-module-zoo-skin-preview"
                                             data-mw-module-zoo-module="{{ $module['name'] }}"
                                             data-mw-module-zoo-skin="{{ $skin }}">
                                            <module type="{{ $module['name'] }}"
                                                    template="{{ $skin }}"
                                                    id="mw-module-zoo-{{ $module['name'] }}-{{ $skin }}"/>
                                        </div>
                                    </article>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>
            @endforeach
        @endif
    </div>
@endsection
