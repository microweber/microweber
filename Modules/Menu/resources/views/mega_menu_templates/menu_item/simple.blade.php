<?php

/*

type: layout

name: Mega Menu — Simple list

description: Compact single-column dropdown. Child items become a simple icon + title list (no descriptions or chips).

*/

?>

<script>mw.moduleCSS("{{ asset('modules/menu/mega-menu.css') }}?v={{ @filemtime(public_path('modules/menu/mega-menu.css')) }}", true);
(function(){ if (window.__mwMegaMenuJS) return; window.__mwMegaMenuJS = true; var s=document.createElement("script"); s.src="{{ asset('modules/menu/mega-menu.js') }}?v={{ @filemtime(public_path('modules/menu/mega-menu.js')) }}"; document.head.appendChild(s); })();</script>

@php($items = $mega_menu_items ?? [])

@if(!empty($items))
    <div class="mega-menu-panel">
        <div class="mega-menu-grid">
            @foreach($items as $entry)
                <a class="mega-menu-entry" href="{{ $entry['url'] ?: '#' }}">
                    @if(!empty($entry['icon']))
                        <span class="mega-menu-entry__icon">{!! icon_html($entry['icon']) !!}</span>
                    @endif
                    <span class="mega-menu-entry__body">
                        <span class="mega-menu-entry__title">{{ $entry['title'] }}</span>
                        @if(!empty($entry['description']))
                            <span class="mega-menu-entry__desc">{{ $entry['description'] }}</span>
                        @endif
                    </span>
                </a>
            @endforeach
        </div>
    </div>
@elseif(is_live_edit())
    <div class="mega-menu-empty">{{ _e('Add child menu items to this item to fill the mega menu.', true) }}</div>
@endif
