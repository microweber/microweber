{{--
    task-2026-06-07-pmprod — shared admin / Live-Edit empty state for content
    LIST modules (posts / pages / products / generic content).

    Single source of markup. The view-model (type, copy, CTA, re-scope
    secondary link) is resolved in PHP by
    \Modules\Content\Services\ContentModuleEmptyState::resolve() so the six
    list templates (default, skin-1, masonry, search, sidebar, dictionary)
    no longer each carry a drifting ~50-line copy of this logic.

    Usage:  @include('modules.content::partials.module-empty-state', ['params' => $params])

    Wrapped in is_admin() so it stays visible to editors but never leaks onto
    anonymous public pages (AI-104). The secondary re-scope link is live-edit
    only and dispatches onModuleSettingsRequest on the closest .module wrapper
    (moduleSettingsDispatch in api-core/handles-content/module.js). Its onclick
    uses single quotes only — no embedded double quote — so the HTML attribute
    parser stays intact (parser-meaningful-character family).
--}}
@if(is_admin())
    @php
        $mwEmpty = \Modules\Content\Services\ContentModuleEmptyState::resolve($params ?? []);
    @endphp
    <div class="mw-canvas-empty-state" data-mw-content-type="{{ e($mwEmpty['type'] ?? 'unknown') }}">
        <h3 class="mw-canvas-empty-state__title">{{ $mwEmpty['title'] }}</h3>
        <p class="mw-canvas-empty-state__body">{{ $mwEmpty['body'] }}</p>
        <a class="mw-canvas-empty-state__cta" href="{{ $mwEmpty['ctaHref'] }}" aria-label="{{ $mwEmpty['ctaLabel'] }}">{{ $mwEmpty['ctaLabel'] }}</a>
        @if($mwEmpty['showSecondary'])
            <a class="mw-canvas-empty-state__secondary" href="javascript:void(0)" onclick="var m=this.closest('.module');if(m&&window.mw&&mw.app&&mw.app.editor){mw.app.editor.dispatch('onModuleSettingsRequest',m);}return false;" aria-label="{{ $mwEmpty['secondaryAria'] }}">{{ $mwEmpty['secondaryLabel'] }}</a>
        @endif
    </div>
@endif
