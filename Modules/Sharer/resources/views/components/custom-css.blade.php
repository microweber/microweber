@php

    if (isset($iconSize)  and $iconSize) {
        $iconSize = (intval($iconSize)) . 'px!important;';
    }

    if (isset($iconColor) and $iconColor) {
        $iconColor = $iconColor . '!important;';
    }

    if (isset($iconSpacing) and $iconSpacing) {
        $iconSpacing = $iconSpacing . 'px!important;';
    }

@endphp

<style>
    #{{ $params['id'] }} .mw-social-share-links a svg {
        width: {{$iconSize}};
        height: {{$iconSize}};
        color: {{$iconColor}};
        margin-inline: {{$iconSpacing}};
    }

</style>
