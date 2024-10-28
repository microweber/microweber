@php
    $cssWrapper = '';
    $cssButton = '';
    $cssHoverButton = '';

    if (isset($backgroundColor) and $backgroundColor) {
        $cssButton .= 'background-color:' . $backgroundColor . '!important;';
    }
    if (isset($color) and $color) {
        $cssButton .= 'color:' . $color . '!important;';
    }

    if (isset($borderColor) and $borderColor) {
        $cssButton .= 'border-color:' . $borderColor . '!important;';
    }

    if (isset($borderWidth) and $borderWidth) {
        $cssButton .= 'border-width:' . $borderWidth . 'px!important;';
    }

    if (isset($borderRadius) and $borderRadius) {
        $cssButton .= 'border-radius:' . $borderRadius . 'px!important;';
    }

    if (isset($customSize)  and $customSize) {
        $cssButton .= 'font-size: ' . (intval($customSize)) . 'px!important;';
    }

    if (isset($shadow) and $shadow) {
        $cssButton .= 'box-shadow:' . $shadow . '!important;';
    }

    if (isset($align) and $align) {
        if (_lang_is_rtl()) {
            if ($align == 'left') {
                $align = 'right';
            } elseif ($align == 'right') {
                $align = 'left';
            }
        }
        $cssWrapper .= 'text-align:' . $align . ' !important;';
    }

    if (isset($hoverbackgroundColor) and $hoverbackgroundColor) {
        $cssHoverButton .= 'background-color:' . $hoverbackgroundColor . ' !important;';
    }

    if (isset($hovercolor) and $hovercolor) {
        $cssHoverButton .= 'color:' . $hovercolor . ' !important;';
    }

    if (isset($hoverborderColor) and $hoverborderColor) {
        $cssHoverButton .= 'border-color:' . $hoverborderColor . ' !important;';
    }
@endphp

<style>
@if($cssWrapper)
#{{ $params['id'] }} {
    {{ $cssWrapper }}
}
@endif
@if($cssButton)
#{{ $params['id'] }} > #{{ $btnId }}, #{{ $params['id'] }} > a, #{{ $params['id'] }} > button {
 {{ $cssButton }}
}
@endif

@if($cssHoverButton)
#{{ $params['id'] }} > #{{ $btnId }}:hover, #{{ $params['id'] }} > a:hover, #{{ $params['id'] }} > button:hover {
 {{ $cssHoverButton }}
}
@endif
</style>
