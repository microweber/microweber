@php

    if (isset($accordionColor) and $accordionColor) {
        $accordionColor = $accordionColor . '!important;';
    }

    if (isset($accordionHoverColor) and $accordionHoverColor) {
            $accordionHoverColor = $accordionHoverColor . '!important;';
        }

    if (isset($accordionBorderColor) and $accordionBorderColor) {
            $accordionBorderColor = $accordionBorderColor . '!important;';
        }

@endphp

<style>
    #{{ $params['id'] }} .mw-accordion-module-button {
        background-color: {{$accordionColor}};
        border-color: {{$accordionBorderColor}};

        &:hover {
            background-color: {{$accordionHoverColor}};
        }
    }

</style>
