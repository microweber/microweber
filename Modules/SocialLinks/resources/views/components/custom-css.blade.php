@php

    if (isset($iconSize)  and $iconSize) {
        $iconSize = (intval($iconSize)) . 'px!important;';
    }

    if (isset($iconColor) and $iconColor) {
        $iconColor = $iconColor . '!important;';
    }

    if (isset($iconHoverColor) and $iconHoverColor) {
            $iconHoverColor = $iconHoverColor . '!important;';
        }

    if (isset($iconSpacing) and $iconSpacing) {
        $iconSpacing = $iconSpacing . 'px!important;';
    }

    if (isset($iconFlex) and $iconFlex) {
        $iconFlex = $iconFlex . '!important;';
    }

@endphp

<style>
    #{{ $params['id'] }} .mw-socialLinks a svg {
        width: {{$iconSize}};
        height: {{$iconSize}};
        color: {{$iconColor}};

        &:hover {
            color: {{$iconHoverColor}};
        }
    }

    #{{ $params['id'] }} .mw-socialLinks {
        display: {{$iconFlex}};
        gap: {{$iconSpacing}};
        justify-content: {{$iconPosition}};
    }

</style>
