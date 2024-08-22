<div>
    <div  class="relative mw-prevent-interaction" id="google-map-{{ $mapId }}">
        <iframe width="{{ $width }}" height="{{ $height }}" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"
                src="https://maps.google.com/maps?f=q&amp;hl=en&amp;@if($pinEncoded)center={{ $pinEncoded }}&amp;@endif geocode=&amp;time=&amp;date=&amp;ttype=&amp;q={{ urlencode($address) }}&amp;ie=UTF8&amp;om=1&amp;s=AARTsJpG68j7ib5XkPnE95ZRHLMVsa8OWg&amp;spn=0.011588,0.023174&amp;z={{ intval($zoom) }}&amp;style={{ $map_style_param }}$maptype={{ $maptype }}&amp;output=embed" style="min-height: 400px;">
        </iframe>
        @if(in_live_edit())
        <div contentEditable="false" class="iframe_fix" style="display: block;"></div>
        @endif
    </div>
</div>
