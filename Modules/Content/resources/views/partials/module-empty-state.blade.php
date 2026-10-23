
@if(is_live_edit())
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
