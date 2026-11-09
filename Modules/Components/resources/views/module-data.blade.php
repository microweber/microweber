@if(isset($__data) and !empty($__data) and is_live_edit() and isset($params) and isset($params['id']))
@php
    // Only the module's OWN settings/params belong in this hydration blob (read
    // by modules.js in Live Edit). $__data is the full view scope, so it also
    // carries Blade/framework internals — most importantly `app` (the service
    // container, whose contextual-binding map serialises internal controller
    // class names into the page) and `__env`. Strip those: they are never read
    // by any consumer and leaking the container state into the DOM is both bloat
    // and information disclosure.
    $__moduleData = is_array($__data)
        ? array_diff_key($__data, array_flip([
            '__env', 'app', 'errors', '__data', '__path', 'obLevel',
            '__currentLoopData', 'loop', 'component', 'componentData',
            'attributes', 'slot', '__laravel_slots', '__sections',
            'componentName', '__sectionStack', '__componentOriginal',
        ]))
        : $__data;
@endphp

<script type="application/json" id="module-data--{{ $params['id'] }}" data-module-settings-id="{{ $params['id'] }}">
    {!! json_encode($__moduleData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!}
</script>

@endif
