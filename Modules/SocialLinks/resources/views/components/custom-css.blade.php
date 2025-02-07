@php

    if (isset($iconSize)  and $iconSize) {
        $iconSize = (intval($iconSize)) . 'px!important;';
    }

    if (isset($iconColor) and $iconColor) {
        $iconColor = $iconColor . '!important;';
    }

    if (isset($iconSpacing) and $iconSpacing) {
        $iconSpacing = $iconSpacing . '!important;';
    }

@endphp

<style>
    #{{ $params['id'] }} .mw-socialLinks a svg {
        width: {{$iconSize}};
        height: {{$iconSize}};
        color: {{$iconColor}};
        padding-inline: {{$iconSpacing}};
    }

</style>
