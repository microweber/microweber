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

    if (isset($accordionContentColor) and $accordionContentColor) {
            $accordionContentColor = $accordionContentColor . '!important;';
        }

     if (isset($accordionTextColor) and $accordionTextColor) {
            $accordionTextColor = $accordionTextColor . '!important;';
        }
@endphp

<style>
    #{{ $params['id'] }} .mw-accordion-module-button {
        background-color: {{$accordionColor}};
        border-color: {{$accordionBorderColor}};

        &:hover {
            background-color: {{$accordionHoverColor}};
        }

        i.mdi {
            color: {{$accordionTextColor}};
        }
    }

    #{{ $params['id'] }} .mw-accordion-text-color {
        color: {{$accordionTextColor}};
    }

    #{{ $params['id'] }} .mw-accordion-module-content {
        background-color: {{$accordionContentColor}};
    }

</style>
