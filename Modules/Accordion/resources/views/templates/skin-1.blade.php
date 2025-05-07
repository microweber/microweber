<?php

/*

  type: layout

  name: skin-1

  description: Skin-1 template

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
    $(document).ready(function() {
        function toggleChevron(e) {
            $(e.target)
                .prev('.card-header')
                .find("i.mdi.arrow.sk1")
                .toggleClass('mdi-chevron-down mdi-chevron-up')
                .toggleClass('active')
        }
        $('#accordion-sk1').on('shown.bs.collapse', toggleChevron);
        $('#accordion-sk1').on('hidden.bs.collapse', toggleChevron);

        $(".card.sk1").click(function() {
            $(".card.sk1").removeClass("active");
            $(this).addClass("active");
        });
    })
</script>


@include('modules.accordion::components.custom-css')

<style>
    .card.sk1:hover,
    .card.active.sk1 {
        border: 1px var(--mw-primary-color) solid !important;
    }

    .card i.active.sk1 {
        background-color: var(--mw-primary-color) !important;
        color: white !important;
    }

    .card.sk1 {
        border: 1px solid #e6e6e6 !important;
        border-radius: 5px !important;
    }

    .mdi.arrow.sk1 {
        background-color: #f3f3f3;
        color: gray;
        line-height: 1 !important;
        font-size: 20px !important;
        width: 30px;
        height: 30px;
        display: flex;
        justify-content: center;
        align-items: center;
        margin: auto;
    }

    .card.card-collapse .card-header:after {
        border-top: none !important;
    }
</style>

<div id="mw-accordion-module-{{ $params['id'] }}">
    <div class="accordion" id="accordion-sk1">
        @foreach ($accordion as $key => $slide)
            @php
                $edit_field_key = $key;
                if (isset($slide['id'])) {
                    $edit_field_key = $slide['id'];
                }
            @endphp
            <div class="card sk1 card-collapse mb-3 {{ $key == 0 ? 'active_sk1' : '' }}">
                <div class="card-header p-0" id="header-item-{{ $edit_field_key }}">
                    <button class=" mw-accordion-module-button w-100 rounded-0 btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#collapse-accordion-item-{{ $edit_field_key . '-' . $key }}" aria-expanded="true" aria-controls="collapse-accordion-item-{{ $edit_field_key . '-' . $key }}">
                        {{ isset($slide['icon']) ? $slide['icon'] . ' ' : '' }}
                        <h4> {{ isset($slide['title']) ? $slide['title'] : '' }} </h4>
                        <i class="mdi arrow sk1 rounded-circle {{ $key == 0 ? 'mdi-chevron-down' : 'mdi-chevron-down' }}"></i>
                    </button>
                </div>

                <div id="collapse-accordion-item-{{ $edit_field_key . '-' . $key }}" class="collapse {{ $key == 0 ? 'show' : '' }}" aria-labelledby="header-item-{{ $edit_field_key }}" data-parent="#mw-accordion-module-{{ $params['id'] }}">
                    <div class="card-body p-5">
                        @include('modules.accordion::partials.render_accordion_item_content')
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
