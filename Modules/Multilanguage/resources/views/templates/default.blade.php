@php
    /*
    type: layout
    name: Default
    description: Default language switcher template
    */
@endphp

{{--
    Language switcher — matches the Currency switcher's token-driven skin so the
    two sit together cohesively in a storefront header. Keeps Bootstrap dropdown
    mechanics (data-bs-toggle + .dropdown-menu) because every mw-framework
    template ships the Bootstrap behaviour JS (mw-framework.js); styling is
    self-contained and driven by --mw-* tokens with neutral fallbacks, so it
    renders on any framework template (Plain / Base / Big).
--}}
<div class="mw-lang-switcher module-multilanguage">
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

<style>
    /* Self-contained skin, mirrors .mw-currency-switcher so the two match. */
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
    .mw-lang-switcher__current { font-weight: 600; }
    .mw-lang-switcher__caret { opacity: .6; transition: transform .15s ease; }
    .mw-lang-switcher__toggle[aria-expanded="true"] .mw-lang-switcher__caret { transform: rotate(180deg); opacity: 1; }

    /* Self-contained dropdown (no Bootstrap dependency): hidden until .show. */
    .mw-lang-switcher__menu {
        display: none;
        position: absolute; right: 0; top: calc(100% + 6px);
        min-width: 224px; z-index: 1050; padding: 6px;
        background: var(--mw-surface, #fff);
        border: 1px solid var(--mw-border-color, #e5e7eb);
        border-radius: var(--mw-border-radius, 8px);
        box-shadow: 0 10px 25px -5px rgba(0,0,0,.18), 0 4px 10px -4px rgba(0,0,0,.1);
    }
    .mw-lang-switcher__menu.show { display: block; }
    .mw-lang-switcher__label {
        padding: 8px 12px 6px; font-size: 11px; font-weight: 700;
        letter-spacing: .06em; text-transform: uppercase;
        color: var(--mw-text-muted, #6b7280);
    }
    .mw-lang-switcher__item {
        display: flex; align-items: center; gap: 12px; width: 100%;
        min-height: 44px; padding: 8px 12px; text-decoration: none;
        border-left: 3px solid transparent; border-radius: 6px;
        color: var(--mw-text-color, #1f2933);
        transition: background .12s ease;
    }
    .mw-lang-switcher__item:hover { background: var(--mw-surface-hover, #f6f7f9); color: var(--mw-text-color, #1f2933); }
    .mw-lang-switcher__item.is-active {
        background: var(--mw-primary-color-soft, rgba(13,110,253,.08));
        border-left-color: var(--mw-primary-color, #0d6efd);
        font-weight: 600;
    }
    .mw-lang-switcher__item-flag { width: 20px; height: 14px; border-radius: 3px; object-fit: cover; }
    .mw-lang-switcher__item-name { flex: 1 1 auto; }
    .mw-lang-switcher__check { color: var(--mw-primary-color, #0d6efd); margin-left: auto; }

    @media (max-width: 576px) {
        .mw-lang-switcher__toggle { padding: 6px 10px; }
        .mw-lang-switcher__current { display: none; } /* flag-only on phones */
    }
</style>

<script>
    (function () {
        try { if (window.mw && mw.lib && mw.lib.require) { mw.lib.require('flag_icons'); } } catch (e) {}

        // Self-contained toggle — no Bootstrap dropdown dependency, so the
        // switcher works on any template (Plain/Base/Big) and never throws when
        // Bootstrap's JS isn't present or the markup isn't its expected shape.
        if (window.__mwLangSwitcherWired) { return; }
        window.__mwLangSwitcherWired = true;

        function closeAll(except) {
            document.querySelectorAll('.mw-lang-switcher__menu.show').forEach(function (m) {
                if (m !== except) {
                    m.classList.remove('show');
                    var t = m.parentElement && m.parentElement.querySelector('.mw-lang-switcher__toggle');
                    if (t) { t.setAttribute('aria-expanded', 'false'); }
                }
            });
        }

        document.addEventListener('click', function (e) {
            var toggle = e.target.closest ? e.target.closest('.mw-lang-switcher__toggle') : null;
            if (toggle) {
                e.preventDefault();
                var menu = toggle.parentElement.querySelector('.mw-lang-switcher__menu');
                if (!menu) { return; }
                var willShow = !menu.classList.contains('show');
                closeAll(willShow ? menu : null);
                menu.classList.toggle('show', willShow);
                toggle.setAttribute('aria-expanded', willShow ? 'true' : 'false');
                return;
            }
            // click outside any switcher → close
            if (!(e.target.closest && e.target.closest('.mw-lang-switcher'))) {
                closeAll(null);
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') { closeAll(null); }
        });
    })();
</script>
