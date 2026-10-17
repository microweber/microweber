<?php

/*

  type: layout

  name: Default

  description: Default template

*/

?>

@if ($accordion == false)
    {!! lnotif(_e('Click to edit accordion', true)) !!}
    @php return; @endphp
@endif

@if (!isset($accordion) || count($accordion) == 0 AND isset($defaults))
    @php $accordion = $defaults @endphp
@endif

@once
    <link rel="stylesheet" href="{{ asset('modules/accordion/css/accordion-skin.css') }}">
    <script src="{{ asset('modules/accordion/js/accordion-skin.js') }}" defer></script>
@endonce

@include('modules.accordion::components.custom-css')

{{-- audit-test 2026-05-07 Accordion audit findings #1 + #2 + #3:
     #1 (BUG HIGH): inner accordion id `accordion-sk3` was hardcoded —
     parameterized to `accordion-sk-{$params['id']}`.
     #2 (BUG HIGH): `data-bs-parent="#mw-accordion-module-..."` pointed
     at the outer wrapper instead of the actual `.accordion` container.
     Bootstrap 5 collapse "only one panel open at a time" requires
     data-bs-parent to point at the wrapper that holds the collapse-
     targets directly. Switched to `#accordion-sk-{$params['id']}` to
     match #1 fix.
     #3 (BUG MEDIUM): `aria-expanded="false"` was a hardcoded literal
     even on the active panel ($key == 0 has the `active` class but
     also `class="collapse"` so it's collapsed-by-default actually —
     keeping aria-expanded false is correct here. Adding the dynamic
     attr anyway in case the design intent flips: aria-expanded
     mirrors `$key == 0 && $hasInitiallyOpen` once that pattern
     formalizes; for now matches the current `.collapse` initial state
     which has all panels collapsed. NO-OP correctness. --}}
<div id="mw-accordion-module-{{ $params['id'] }}">
    <div class="accordion" id="accordion-sk-{{ $params['id'] }}">
        @foreach ($accordion as $key => $slide)
            @php
                $edit_field_key = $key;
                if (isset($slide['id'])) {
                    $edit_field_key = $slide['id'];
                }
            @endphp
            {{-- audit-test 2026-05-07 post-merge follow-up #3 (TICKET-AJ#2):
                 cycle-29 multi-instance fix parameterized the OUTER ids
                 (`accordion-sk-{$params['id']}` + `mw-accordion-module-{$params['id']}`
                 + data-bs-parent) but missed the per-item collapse-target
                 ids (`header-item-{N}`, `collapse-accordion-item-{N}-{key}`).
                 Two accordion modules on a page that reference overlapping
                 slide ids would emit duplicate ids; data-bs-toggle resolves
                 to the FIRST DOM match so clicking module B's button toggled
                 module A's panel. Prefixed all 3 with `{$params['id']}-`. --}}
            <div class="mw-accordion-faq-skin-card card mb-3 {{ $key == 0 ? 'active' : '' }}">

                <h5 class="mw-accordion-faq-skin-header accordion-header card-header p-0 mb-0" id="header-item-{{ $params['id'] }}-{{ $edit_field_key }}">
                    <button type="button" class="mw-accordion-faq-skin-button accordion-button mw-accordion-module-button collapsed" data-bs-toggle="collapse" data-bs-target="#collapse-accordion-item-{{ $params['id'] }}-{{ $edit_field_key }}-{{ $key }}" aria-expanded="false" aria-controls="collapse-accordion-item-{{ $params['id'] }}-{{ $edit_field_key }}-{{ $key }}">
                        {!! isset($slide['icon']) ? icon_html($slide['icon']) . ' ' : '' !!}
                        <span class="ps-2 mw-accordion-text-color"> {{ isset($slide['title']) ? $slide['title'] : '' }} </span>
                        <i class="mdi mdi-plus active mw-accordion-chevron"></i>
                    </button>
                </h5>
                <div id="collapse-accordion-item-{{ $params['id'] }}-{{ $edit_field_key }}-{{ $key }}" class="collapse" aria-labelledby="header-item-{{ $params['id'] }}-{{ $edit_field_key }}" data-bs-parent="#accordion-sk-{{ $params['id'] }}">
                    <div class="card-body mw-accordion-module-content py-3 px-4">
                        @include('modules.accordion::partials.render_accordion_item_content')
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
