<?php

/*

type: layout

name: Mega Menu — Columns

description: Three-column mega menu for larger navigations. Child items become entries (icon + title + description); grandchildren become tag chips.

*/

?>

<script>mw.moduleCSS("{{ asset('modules/menu/mega-menu.css') }}?v={{ @filemtime(public_path('modules/menu/mega-menu.css')) }}", true);
(function(){ if (window.__mwMegaMenuJS) return; window.__mwMegaMenuJS = true; var s=document.createElement("script"); s.src="{{ asset('modules/menu/mega-menu.js') }}?v={{ @filemtime(public_path('modules/menu/mega-menu.js')) }}"; document.head.appendChild(s); })();</script>

@php($items = $mega_menu_items ?? [])

@if(!empty($items))
    <div class="mega-menu-panel">
        <div class="mega-menu-grid">
            @foreach($items as $entry)
                <div class="mega-menu-entry">
                    @if(!empty($entry['icon']))
                        <span class="mega-menu-entry__icon">{!! icon_html($entry['icon']) !!}</span>
                    @endif
                    <div class="mega-menu-entry__body">
                        <a class="mega-menu-entry__title" href="{{ $entry['url'] ?: '#' }}">{{ $entry['title'] }}</a>
                        @if(!empty($entry['description']))
                            <div class="mega-menu-entry__desc">{{ $entry['description'] }}</div>
                        @endif
                        @if(!empty($entry['children']))
                            <div class="mega-menu-chips">
                                @foreach($entry['children'] as $chip)
                                    <a class="mega-menu-chip" href="{{ $chip['url'] ?: '#' }}">{{ $chip['title'] }}</a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@elseif(is_live_edit())
    <div class="mega-menu-empty">{{ _e('Add child menu items to this item to fill the mega menu.', true) }}</div>
@endif
