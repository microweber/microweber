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
            src="https://maps.google.com/maps?q={{ urlencode($address) }}&amp;z={{ intval($zoom) }}&amp;t={{ $mapType ?? 'm' }}&amp;output=embed{{ !($showMarker ?? true) ? '&amp;markers=' : '' }}">
        </iframe>
    @else
        {!! lnotif(lang('Set an address to display the map.')) !!}
    @endif
</div>
