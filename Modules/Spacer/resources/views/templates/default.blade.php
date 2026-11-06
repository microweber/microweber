@php
/*
type: layout
name: Default
description: Default spacer template
*/
@endphp

{{--
    Removed the per-instance <style> block that this template used to emit on
    every spacer render. Both its selectors were dead in every configuration:
      • #spacer-{{ '{id}' }} matched nothing — the module wrapper is #{{ '{id}' }}
        and the inner div is #spacer-item-{{ '{id}' }}; the "spacer-" prefix never
        lined up with either.
      • .mw-spacer-disable-settings--{{ '{id}' }} is applied nowhere in the
        codebase (no JS ever adds it), so both rules keyed on it never matched.
    Deleting it drops the duplicated inline <style> from every layout with zero
    behaviour change. Spacer non-interactivity in Live Edit is handled by the
    mw-le-spacer / noelement / nodrop classes + live-edit-spacer.js.
--}}
<div class="mw-le-spacer noelement nodrop inaccessibleModuleIfFirstParentIsLayout"
     data-for-module-id="{{ $module_id }}" 
     contenteditable="false" 
     style="{{ $styles_attr }}" 
     id="spacer-item-{{ $module_id }}">
</div>
