@php

    if (isset($iconSize)  and $iconSize) {
        $iconSize = (intval($iconSize)) . 'px!important;';
    }

    if (isset($iconColor) and $iconColor) {
        $iconColor = $iconColor . '!important;';
    }

@endphp

<style>
    .mw-socialLinks a svg {
        width: {{$iconSize}};
        height: {{$iconSize}};
        color: {{$iconColor}};
    }

</style>
