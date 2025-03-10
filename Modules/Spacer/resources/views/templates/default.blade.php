@php
/*
type: layout
name: Default
description: Default spacer template
*/
@endphp

<style>
    #spacer-{{ $module_id }}, .mw-spacer-disable-settings--{{ $module_id }} {
        pointer-events: none;
    }
    .mw-spacer-disable-settings--{{ $module_id }} > * {
        pointer-events: all;
    }
</style>

<div class="mw-le-spacer noelement nodrop inaccessibleModuleIfFirstParentIsLayout" 
     data-for-module-id="{{ $module_id }}" 
     contenteditable="false" 
     style="{{ $styles_attr }}" 
     id="spacer-item-{{ $module_id }}">
</div>
