@php
    /*
    type: layout
    name: Default
    description: Default language switcher template
    */
@endphp

{{--
    Language switcher — matches the Currency switcher's token-driven skin so the
    two sit together cohesively in a storefront header. Markup only: the skin CSS
    and the self-contained toggle JS live in this module's resources
    (resources/assets/css|js/lang-switcher.*) and are injected into the page
    <head> by MultilanguageServiceProvider when multilanguage is active — so they
    load once per page instead of inline per render, on any template.
--}}
@php
    // Self-contained skin: carry the switcher CSS WITH the module so it is styled
    // on EVERY template, not only where the header flushes the template_head()
    // <link> (it loaded on Plain but was missing on Big2, leaving the switcher
    // unstyled: static menu, transparent panel, black-on-black label). Read once
    // per request (static cache) and inline it. Harmless if the external <link>
    // also loads — identical rules. Falls back to the asset URL if the file is
    // absent.
    static $__mwLangSwitcherCss = null;
    if ($__mwLangSwitcherCss === null) {
        $__mwLangSwitcherCssPath = public_path('modules/multilanguage/css/lang-switcher.css');
        $__mwLangSwitcherCss = is_file($__mwLangSwitcherCssPath)
            ? (string) file_get_contents($__mwLangSwitcherCssPath)
            : '';
    }
    // The toggle's open/close is driven by lang-switcher.js (delegated click),
    // also injected via template_head() and therefore ALSO missing on templates
    // that don't flush it (Big2, Office, …) — so the dropdown wouldn't open at
    // all there. Inline it too; the script self-guards (window.__mwLangSwitcherWired)
    // so it wires exactly once even if the external copy also loads.
    static $__mwLangSwitcherJs = null;
    if ($__mwLangSwitcherJs === null) {
        $__mwLangSwitcherJsPath = public_path('modules/multilanguage/js/lang-switcher.js');
        $__mwLangSwitcherJs = is_file($__mwLangSwitcherJsPath)
            ? (string) file_get_contents($__mwLangSwitcherJsPath)
            : '';
    }
@endphp
<div class="mw-lang-switcher module-multilanguage">
    @if($__mwLangSwitcherCss !== '')
        <style>{!! $__mwLangSwitcherCss !!}</style>
    @else
        <link rel="stylesheet" href="{{ asset('modules/multilanguage/css/lang-switcher.css') }}">
    @endif
    @if($__mwLangSwitcherJs !== '')
        <script>{!! $__mwLangSwitcherJs !!}</script>
    @else
        <script defer src="{{ asset('modules/multilanguage/js/lang-switcher.js') }}"></script>
    @endif
    <button
        class="mw-lang-switcher__toggle"
        type="button"
        id="dropdownMenuButton"
        aria-expanded="false"
        aria-haspopup="listbox"
    >
        @if($current_language['display_icon'])
            <img src="{{ $current_language['display_icon'] }}" alt="{{ $current_language['display_name'] }}" class="mw-lang-switcher__flag">
        @else
            <span class="mw-flag-icon mw-flag-icon-{{ get_flag_icon($current_language['locale']) }} mw-lang-switcher__flag"></span>
        @endif
        <span class="mw-lang-switcher__current">{{ $current_language['display_name'] ?: strtoupper(substr($current_language['locale'], 0, 2)) }}</span>
        <svg class="mw-lang-switcher__caret" width="16" height="16" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
    </button>

    <div class="mw-lang-switcher__menu" role="listbox" aria-labelledby="dropdownMenuButton">
        <div class="mw-lang-switcher__label">{{ _e('Select Language', true) }}</div>
        @if(!empty($supported_languages))
            @foreach($supported_languages as $language)
                @if($language['is_active'])
                    <a class="mw-lang-switcher__item {{ $language['locale'] == $current_language['locale'] ? 'is-active' : '' }}"
                       href="?localeRedirect={{ $language['locale'] }}"
                       title="{{ $language['display_name'] ?: $language['language'] }}">
                        @if($language['display_icon'])
                            <img src="{{ $language['display_icon'] }}" alt="{{ $language['display_name'] }}" class="mw-lang-switcher__item-flag">
                        @else
                            <span class="mw-flag-icon mw-flag-icon-{{ get_flag_icon($language['locale']) }} mw-lang-switcher__item-flag"></span>
                        @endif
                        <span class="mw-lang-switcher__item-name">{{ $language['display_name'] ?: $language['language'] }}</span>
                        @if($language['locale'] == $current_language['locale'])
                            <svg class="mw-lang-switcher__check" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                                <polyline points="20,6 9,17 4,12"></polyline>
                            </svg>
                        @endif
                    </a>
                @endif
            @endforeach
        @endif
    </div>
</div>
