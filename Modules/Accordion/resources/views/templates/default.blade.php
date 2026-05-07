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

{{-- audit-test 2026-05-07 Accordion audit findings #1 + #7:
     #1 (BUG HIGH): `#accordion-sk3` was hardcoded — pages with 2+
     accordion instances had only the first one's chevron toggle
     wired (the others' `+` stayed `+` even when the panel opened).
     Parameterized to `accordion-sk-{$params['id']}` so each instance
     gets its own selector.
     #7 (UX): `.card.sk2` click handler targets a class that this
     skin never renders (sk2 is a different skin's class). Dead code
     AND unscoped — would have fired across all skins on a page;
     deleted. --}}
<script>
    $(document).ready(function() {

        function toggleChevron(e) {
            $(e.target)
                .prev('.mw-accordion-faq-skin-header')
                .find("i.mdi")
                .toggleClass('mdi-minus mdi-plus')
                .toggleClass('active')
        }
        $('#accordion-sk-{{ $params['id'] }}').on('hidden.bs.collapse', toggleChevron);
        $('#accordion-sk-{{ $params['id'] }}').on('shown.bs.collapse', toggleChevron);

    })
</script>

@include('modules.accordion::components.custom-css')

<style>
    .mw-accordion-faq-skin-button {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
        width: 100%;
        border: none;
        border-radius: 0;
        cursor: pointer;
        outline: none;
    }

    .mw-accordion-faq-skin-header:has(.active) {
        border-bottom: none !important;
    }

    .mw-accordion-faq-skin-card {
        border: none;
    }
</style>

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
                <div class="mw-accordion-faq-skin-header card-header p-0" id="header-item-{{ $params['id'] }}-{{ $edit_field_key }}">
                    <button class="mw-accordion-faq-skin-button  mw-accordion-module-button" data-bs-toggle="collapse" data-bs-target="#collapse-accordion-item-{{ $params['id'] }}-{{ $edit_field_key }}-{{ $key }}" aria-expanded="false" aria-controls="collapse-accordion-item-{{ $params['id'] }}-{{ $edit_field_key }}-{{ $key }}">
                        <h5 class="ps-2 mb-0 mw-accordion-text-color"> {!! isset($slide['icon']) ? icon_html( $slide['icon'] ) . ' ' : '' !!} {{ isset($slide['title']) ? $slide['title'] : '' }} </h5>
                        <i class="mdi mdi-plus active" style="font-size: 24px;"></i>
                    </button>
                </div>
                <div id="collapse-accordion-item-{{ $params['id'] }}-{{ $edit_field_key }}-{{ $key }}" class="collapse" aria-labelledby="header-item-{{ $params['id'] }}-{{ $edit_field_key }}" data-bs-parent="#accordion-sk-{{ $params['id'] }}">
                    <div class="card-body mw-accordion-module-content py-3 px-4">
                        @include('modules.accordion::partials.render_accordion_item_content')
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
