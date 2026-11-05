{{--
    Currency switcher — framework-agnostic skin.

    Uses self-contained, scoped CSS (the Search-module pattern) driven by --mw-*
    design tokens with safe fallbacks, so it renders identically on Bootstrap
    framework templates (Plain / Base / Big — mw-framework.css) and on Tailwind
    admin surfaces alike. No Tailwind utility classes, no Bootstrap classes:
    everything the widget needs is in the <style> block below.
--}}
<div class="mw-currency-switcher" x-data="{ open: false }">
    @if(count($currencies) > 1)
        @php
            $current = collect($currencies)->firstWhere('code', $selectedCurrency);
        @endphp
        <div class="mw-currency-switcher__wrap">
            <button
                type="button"
                class="mw-currency-switcher__toggle"
                @click="open = !open"
                :aria-expanded="open.toString()"
                aria-haspopup="listbox"
            >
                <span class="mw-currency-switcher__current">
                    {{ $current['symbol'] ?? '$' }} {{ $selectedCurrency }}
                </span>
                <svg class="mw-currency-switcher__caret" :class="{ 'is-open': open }"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <div
                class="mw-currency-switcher__menu"
                role="listbox"
                x-show="open"
                @click.away="open = false"
                x-transition.origin.top
                style="display: none;"
            >
                <div class="mw-currency-switcher__label">{{ _e('Select Currency', true) }}</div>

                @foreach($currencies as $currency)
                    <button
                        type="button"
                        class="mw-currency-switcher__item {{ $selectedCurrency === $currency['code'] ? 'is-active' : '' }}"
                        role="option"
                        aria-selected="{{ $selectedCurrency === $currency['code'] ? 'true' : 'false' }}"
                        wire:click="switchCurrency('{{ $currency['code'] }}')"
                        wire:loading.attr="disabled"
                    >
                        <span class="mw-currency-switcher__item-symbol">{{ $currency['symbol'] }}</span>
                        <span class="mw-currency-switcher__item-text">
                            <span class="mw-currency-switcher__item-name">{{ $currency['name'] }}</span>
                            <span class="mw-currency-switcher__item-code">{{ $currency['code'] }}</span>
                        </span>

                        @if($currency['is_default'])
                            <span class="mw-currency-switcher__badge">{{ _e('Default', true) }}</span>
                        @endif

                        @if($selectedCurrency === $currency['code'])
                            <svg class="mw-currency-switcher__check" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        @endif
                    </button>
                @endforeach
            </div>
        </div>

        <div class="mw-currency-switcher__loading" wire:loading wire:target="switchCurrency">
            <svg class="mw-currency-switcher__spinner" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                <circle class="mw-currency-switcher__spinner-track" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="mw-currency-switcher__spinner-head" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            {{ _e('Updating currency...', true) }}
        </div>
    @endif
</div>

@once
    {{-- Inlined (not @push'ed): Microweber frontend layouts render no @stack,
         so pushed styles/scripts are dropped. @once still dedupes within the
         request when several switchers are on one page. --}}
    <style>
            /* Self-contained currency-switcher skin — no utility framework required.
               Colours follow the active template via --mw-* tokens, with neutral
               fallbacks so the widget is legible even when no tokens are defined. */
            .mw-currency-switcher { position: relative; display: inline-block; font-size: 14px; line-height: 1.4; }
            .mw-currency-switcher__wrap { position: relative; }
            .mw-currency-switcher__toggle {
                display: inline-flex; align-items: center; gap: 8px;
                min-height: 44px; padding: 8px 14px;
                background: var(--mw-surface, #fff);
                color: var(--mw-text-color, #1f2933);
                border: 1px solid var(--mw-border-color, #d2d6dc);
                border-radius: var(--mw-border-radius, 8px);
                cursor: pointer; font: inherit;
                transition: background .15s ease, border-color .15s ease, box-shadow .15s ease;
            }
            .mw-currency-switcher__toggle:hover { background: var(--mw-surface-hover, #f6f7f9); }
            .mw-currency-switcher__toggle:focus-visible {
                outline: none;
                border-color: var(--mw-primary-color, #0d6efd);
                box-shadow: 0 0 0 3px var(--mw-primary-color-soft, rgba(13,110,253,.25));
            }
            .mw-currency-switcher__current { font-weight: 600; }
            .mw-currency-switcher__caret { width: 16px; height: 16px; opacity: .6; transition: transform .15s ease; }
            .mw-currency-switcher__caret.is-open { transform: rotate(180deg); }

            .mw-currency-switcher__menu {
                position: absolute; right: 0; top: calc(100% + 6px);
                min-width: 224px; z-index: 1050;
                background: var(--mw-surface, #fff);
                border: 1px solid var(--mw-border-color, #e5e7eb);
                border-radius: var(--mw-border-radius, 8px);
                box-shadow: 0 10px 25px -5px rgba(0,0,0,.18), 0 4px 10px -4px rgba(0,0,0,.1);
                padding: 6px; overflow: hidden;
            }
            .mw-currency-switcher__label {
                padding: 8px 12px 6px; font-size: 11px; font-weight: 700;
                letter-spacing: .06em; text-transform: uppercase;
                color: var(--mw-text-muted, #6b7280);
            }
            .mw-currency-switcher__item {
                display: flex; align-items: center; gap: 12px; width: 100%;
                min-height: 44px; padding: 8px 12px; text-align: left;
                background: transparent; border: 0;
                border-left: 3px solid transparent;
                border-radius: 6px; cursor: pointer; font: inherit;
                color: var(--mw-text-color, #1f2933);
                transition: background .12s ease;
            }
            .mw-currency-switcher__item:hover { background: var(--mw-surface-hover, #f6f7f9); }
            .mw-currency-switcher__item.is-active {
                background: var(--mw-primary-color-soft, rgba(13,110,253,.08));
                border-left-color: var(--mw-primary-color, #0d6efd);
            }
            .mw-currency-switcher__item-symbol { font-size: 18px; min-width: 22px; text-align: center; }
            .mw-currency-switcher__item-text { display: flex; flex-direction: column; flex: 1 1 auto; }
            .mw-currency-switcher__item-name { font-weight: 600; }
            .mw-currency-switcher__item-code { font-size: 12px; color: var(--mw-text-muted, #6b7280); }
            .mw-currency-switcher__badge {
                font-size: 11px; font-weight: 600; padding: 2px 8px; border-radius: 999px;
                background: var(--mw-success-soft, #e6f4ea); color: var(--mw-success, #137333);
            }
            .mw-currency-switcher__check { width: 18px; height: 18px; color: var(--mw-primary-color, #0d6efd); }

            .mw-currency-switcher__loading {
                margin-top: 8px; display: flex; align-items: center; gap: 6px;
                font-size: 13px; color: var(--mw-text-muted, #6b7280);
            }
            .mw-currency-switcher__spinner { width: 16px; height: 16px; animation: mw-currency-spin 1s linear infinite; color: var(--mw-primary-color, #0d6efd); }
            .mw-currency-switcher__spinner-track { opacity: .25; }
            .mw-currency-switcher__spinner-head { opacity: .75; }
            @keyframes mw-currency-spin { to { transform: rotate(360deg); } }
        </style>

        <script>
            document.addEventListener('livewire:initialized', () => {
                Livewire.on('refresh-page', () => {
                    window.location.reload();
                });
            });
        </script>
@endonce
