@php
/*
Livewire component for locale switching
Usage: <livewire:multilanguage.locale-switcher />

Framework-agnostic skin: self-contained, --mw-* token-driven CSS (matches
.mw-currency-switcher and the multilanguage module template), so it renders on
Bootstrap-framework templates (Plain/Base/Big) as well as Tailwind surfaces.
*/
@endphp

@php
    // Self-contained skin: carry the switcher CSS WITH the component so it is
    // styled on EVERY template, not only where the header happens to flush the
    // template_head() <link> (it loads on Plain but was missing on Big2, leaving
    // the switcher unstyled: static menu, transparent panel, black-on-black
    // label). Read once per request (static cache) and inline it inside the
    // single Livewire root. Harmless if the external <link> also loads — the
    // rules are identical. Falls back to the asset URL when the file is absent.
    static $__mwLangSwitcherCss = null;
    if ($__mwLangSwitcherCss === null) {
        $__mwLangSwitcherCssPath = public_path('modules/multilanguage/css/lang-switcher.css');
        $__mwLangSwitcherCss = is_file($__mwLangSwitcherCssPath)
            ? (string) file_get_contents($__mwLangSwitcherCssPath)
            : '';
    }
@endphp
<div class="mw-lang-switcher" x-data="{ open: @entangle('isOpen') }">
    @if($__mwLangSwitcherCss !== '')
        <style>{!! $__mwLangSwitcherCss !!}</style>
    @else
        <link rel="stylesheet" href="{{ asset('modules/multilanguage/css/lang-switcher.css') }}">
    @endif
    <button
        type="button"
        class="mw-lang-switcher__toggle"
        @click="open = !open"
        :aria-expanded="open.toString()"
        aria-haspopup="listbox"
    >
        @if(isset($supportedLanguages['current']['iconUrl']) && $supportedLanguages['current']['iconUrl'])
            <img src="{{ $supportedLanguages['current']['iconUrl'] }}" alt="" class="mw-lang-switcher__flag">
        @else
            <span class="mw-lang-switcher__flag-abr">{{ $supportedLanguages['current']['abr'] ?? strtoupper(substr($currentLocale, 0, 2)) }}</span>
        @endif
        <span class="mw-lang-switcher__current">{{ $supportedLanguages['current']['display_name'] ?? $currentLocale }}</span>
        <svg class="mw-lang-switcher__caret" :class="{ 'is-open': open }" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
    </button>

    <div
        class="mw-lang-switcher__menu"
        role="listbox"
        x-show="open"
        @click.away="open = false"
        x-transition.origin.top
        style="display: none;"
    >
        <div class="mw-lang-switcher__label">{{ _e('Select Language', true) }}</div>

        @if(!empty($supportedLanguages['others']))
            @foreach($supportedLanguages['others'] as $language)
                <button
                    type="button"
                    class="mw-lang-switcher__item"
                    role="option"
                    wire:click="changeLocale('{{ $language['locale'] }}')"
                >
                    @if(isset($language['iconUrl']) && $language['iconUrl'])
                        <img src="{{ $language['iconUrl'] }}" alt="" class="mw-lang-switcher__item-flag">
                    @else
                        <span class="mw-lang-switcher__flag-abr">{{ $language['abr'] }}</span>
                    @endif
                    <span class="mw-lang-switcher__item-name">{{ $language['display_name'] }}</span>
                </button>
            @endforeach
        @else
            <div class="mw-lang-switcher__empty">{{ _e('No other languages available', true) }}</div>
        @endif
    </div>
</div>

@once
    {{-- Inlined (not @push'ed): Microweber frontend layouts render no @stack. --}}
    <style>
        .mw-lang-switcher { position: relative; display: inline-block; font-size: 14px; line-height: 1.4; }
        .mw-lang-switcher__toggle {
            display: inline-flex; align-items: center; gap: 8px;
            min-height: 44px; padding: 8px 14px;
            background: var(--mw-surface, #fff);
            color: var(--mw-text-color, #1f2933);
            border: 1px solid var(--mw-border-color, #d2d6dc);
            border-radius: var(--mw-border-radius, 8px);
            cursor: pointer; font: inherit;
            transition: background .15s ease, border-color .15s ease, box-shadow .15s ease;
        }
        .mw-lang-switcher__toggle:hover { background: var(--mw-surface-hover, #f6f7f9); }
        .mw-lang-switcher__toggle:focus-visible {
            outline: none;
            border-color: var(--mw-primary-color, #0d6efd);
            box-shadow: 0 0 0 3px var(--mw-primary-color-soft, rgba(13,110,253,.25));
        }
        .mw-lang-switcher__flag { width: 22px; height: 16px; border-radius: 4px; object-fit: cover; }
        .mw-lang-switcher__flag-abr { font-weight: 700; font-size: 13px; }
        .mw-lang-switcher__current { font-weight: 600; }
        .mw-lang-switcher__caret { width: 16px; height: 16px; opacity: .6; transition: transform .15s ease; }
        .mw-lang-switcher__caret.is-open { transform: rotate(180deg); opacity: 1; }

        .mw-lang-switcher__menu {
            position: absolute; right: 0; top: calc(100% + 6px);
            min-width: 224px; z-index: 1050;
            background: var(--mw-surface, #fff);
            border: 1px solid var(--mw-border-color, #e5e7eb);
            border-radius: var(--mw-border-radius, 8px);
            box-shadow: 0 10px 25px -5px rgba(0,0,0,.18), 0 4px 10px -4px rgba(0,0,0,.1);
            padding: 6px; overflow: hidden;
        }
        .mw-lang-switcher__label {
            padding: 8px 12px 6px; font-size: 11px; font-weight: 700;
            letter-spacing: .06em; text-transform: uppercase;
            color: var(--mw-text-muted, #6b7280);
        }
        .mw-lang-switcher__item {
            display: flex; align-items: center; gap: 12px; width: 100%;
            min-height: 44px; padding: 8px 12px; text-align: left; text-decoration: none;
            background: transparent; border: 0; border-left: 3px solid transparent;
            border-radius: 6px; cursor: pointer; font: inherit;
            color: var(--mw-text-color, #1f2933);
            transition: background .12s ease;
        }
        .mw-lang-switcher__item:hover { background: var(--mw-surface-hover, #f6f7f9); }
        .mw-lang-switcher__item-flag { width: 20px; height: 14px; border-radius: 3px; object-fit: cover; }
        .mw-lang-switcher__item-name { flex: 1 1 auto; }
        .mw-lang-switcher__empty { padding: 8px 12px; color: var(--mw-text-muted, #6b7280); }
    </style>

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('localeChanged', ({ locale }) => {
                const currentUrl = new URL(window.location.href);
                currentUrl.searchParams.set('localeRedirect', locale);
                window.location.href = currentUrl.toString();
            });
        });
    </script>
@endonce
