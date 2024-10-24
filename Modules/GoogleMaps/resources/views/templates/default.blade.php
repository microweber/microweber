<div class="mwembed mw-googlemaps" id="googlemaps-{{ $id }}">
    @if ($address != '')
        <iframe 
            width="{{ $width ?? '100%' }}" 
            height="{{ $height ?? '400px' }}" 
            frameborder="0" 
            scrolling="no" 
            marginheight="0" 
            marginwidth="0"
            src="https://maps.google.com/maps?q={{ urlencode($address) }}&amp;z={{ intval($zoom) }}&amp;t={{ $mapType ?? 'm' }}&amp;output=embed">
        </iframe>
    @else
        {!! lnotif(_lang('Set an address to display the map.', "modules/googlemaps", true)) !!}
    @endif
</div>
