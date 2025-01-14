<?php

/*

  type: layout

  name: skin-5

  description: Skin-5 template

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

<div class="accordion background-color-element element" id="mw-accordion-module-{{ $params['id'] }}">
    @foreach ($accordion as $key => $slide)
        @php
            $edit_field_key = $key;
            if (isset($slide['id'])) {
                $edit_field_key = $slide['id'];
            }
        @endphp

        <div class="accordion-item">
            <h2 class="accordion-header" id="header-item-{{ $edit_field_key }}">
                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapse-accordion-item-{{ $edit_field_key . '-' . $key }}"
                        aria-expanded="true"
                        aria-controls="collapse-accordion-item-{{ $edit_field_key . '-' . $key }}">
                    {{ isset($slide['icon']) ? $slide['icon'] . ' ' : '' }}{{ isset($slide['title']) ? $slide['title'] : '' }}
                </button>
            </h2>

            <div id="collapse-accordion-item-{{ $edit_field_key . '-' . $key }}"
                 class="accordion-collapse collapse {{ $key == 0 ? 'show' : '' }}"
                 aria-labelledby="header-item-{{ $edit_field_key }}"
                 data-parent="#mw-accordion-module-{{ $params['id'] }}">
                <div class="accordion-body">
                    @include('modules.accordion::partials.render_accordion_item_content')
                </div>
            </div>
        </div>
    @endforeach
</div>
