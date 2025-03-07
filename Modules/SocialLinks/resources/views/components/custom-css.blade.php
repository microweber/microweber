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

@endphp

<style>
    #{{ $params['id'] }} .mw-socialLinks a svg {
        width: {{$iconSize}};
        height: {{$iconSize}};
        color: {{$iconColor}};
        margin-inline: {{$iconSpacing}};

        &:hover {
            color: {{$iconHoverColor}};
        }
    }

    #{{ $params['id'] }} .mw-socialLinks {
        @apply flex items-center gap-2;
    }

</style>
