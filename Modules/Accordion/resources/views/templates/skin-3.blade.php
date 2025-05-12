<?php

/*

  type: layout

  name: skin-3

  description: Skin-3 template

*/

?>
@if ($accordion == false)
    {!! lnotif(_e('Click to edit accordion', true)) !!}
    @php return; @endphp
@endif


@if (!isset($accordion) || count($accordion) == 0 AND isset($defaults))
    @php $accordion = $defaults @endphp
@endif

<script>
    $(document).ready(function () {
        var root = $("#mw-accordion-module-{{ $params['id'] }}");
        $('.accordion__title', root).on('click', function () {
            $('li.active', root).removeClass('active');
            $(this).parent().addClass('active');
        })
    })
</script>

@include('modules.accordion::components.custom-css')


<div id="mw-accordion-module-{{ $params['id'] }}">
    @foreach ($accordion as $key => $slide)
        @php
            $edit_field_key = $key;
            if (isset($slide['id'])) {
                $edit_field_key = $slide['id'];
            }
        @endphp

        <div class="card">
            <div class="card-header" id="header-item-{{ $edit_field_key }}">
                <h5 class="mb-0">
                    <button class="btn btn-link" data-bs-toggle="collapse" data-bs-target="#collapse-accordion-item-{{ $edit_field_key . '-' . $key }}" aria-expanded="true" aria-controls="collapse-accordion-item-{{ $edit_field_key . '-' . $key }}">
                        {{ isset($slide['icon']) ? $slide['icon'] . ' ' : '' }} <span class="mw-accordion-text-color"> {{ isset($slide['title']) ? $slide['title'] : '' }}</span>
                    </button>
                </h5>
            </div>

            <div id="collapse-accordion-item-{{ $edit_field_key . '-' . $key }}" class="collapse {{ $key == 0 ? 'show' : '' }}" aria-labelledby="header-item-{{ $edit_field_key }}" data-parent="#mw-accordion-module-{{ $params['id'] }}">
                <div class="card-body mw-accordion-module-content">
                    @include('modules.accordion::partials.render_accordion_item_content')

                </div>
            </div>
        </div>
    @endforeach
</div>
