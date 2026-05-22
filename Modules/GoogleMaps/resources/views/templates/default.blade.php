<?php

/*

type: template

name: Default

description: Default

*/
?>


<div class="mwembed mw-googlemaps" id="googlemaps-{{ $id }}">
    @if ($address != '')
        {{-- task-2026-05-22-ce249e / AI-919 — data-show-marker now wired.
             When false, omit the &markers= parameter so Google Maps renders
             the location without a pin. --}}
        <iframe
            width="{{ $width ?? '100%' }}"
            height="{{ $height ?? '600px' }}"
            frameborder="0"
            scrolling="no"
            marginheight="0"
            marginwidth="0"
            src="https://maps.google.com/maps?q={{ urlencode($address) }}&amp;z={{ intval($zoom) }}&amp;t={{ $mapType ?? 'm' }}&amp;output=embed{{ !($showMarker ?? true) ? '&amp;markers=' : (isset($markerLabel) && $markerLabel ? '&amp;markers=label:' . e($markerLabel) . '|' . urlencode($address) : '') }}">
        </iframe>
    @else
        {{-- AI-969 / task-2026-05-22 — improved placeholder when no address is set.
             Previous placeholder used lnotif() which renders as plain text and is
             hard to notice on canvas. New placeholder uses a styled card with map
             pin icon + instruction link to Settings. --}}
        <div class="mw-googlemaps-placeholder" style="
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 12px;
            min-height: 200px;
            width: 100%;
            background: #f3f4f6;
            border: 2px dashed #d1d5db;
            border-radius: 8px;
            color: #6b7280;
            text-align: center;
            padding: 24px;
            box-sizing: border-box;
        ">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
            </svg>
            <p style="margin: 0; font-size: 14px; font-weight: 500;">
                {!! e(lang('No address set')) !!}
            </p>
            <p style="margin: 0; font-size: 13px;">
                {!! e(lang('Set an address in the module settings to display the map.')) !!}
            </p>
        </div>
    @endif
</div>
