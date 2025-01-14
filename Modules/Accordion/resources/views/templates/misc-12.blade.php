<?php

/*

  type: layout

  name: Misc-12

  description: Misc-12 template


*/

?>
@if ($accordion == false)
    {!! lnotif(_e('Click to edit accordion', true)) !!}
    @php return; @endphp
@endif

@if (!isset($accordion) || count($accordion) == 0 AND isset($defaults))
    @php $accordion = array(0 => $defaults); @endphp
@endif

<script>
    $(document).ready(function () {
        var root = $("#mw-accordion-module-{{ $params['id'] }}");
        $('.accordion__title', root).on('click', function () {
            $(this).parent().toggleClass('active');
            var isActive = $(this).parent().hasClass('active');
            if (isActive) {
                $('.plus-icon', this).addClass('rotate-icon');
            } else {
                $('.plus-icon', this).removeClass('rotate-icon');
            }
            $('li.active', root).not($(this).parent()).removeClass('active');
        });

        // Listen for the show.bs.collapse event
        $('.acordion-content-wrapper', root).on('show.bs.collapse', function () {
            $('.plus-icon', $(this).prev()).addClass('rotate-icon');
        });

        // Listen for the hide.bs.collapse event
        $('.acordion-content-wrapper', root).on('hide.bs.collapse', function () {
            $('.plus-icon', $(this).prev()).removeClass('rotate-icon');
        });
    });
</script>

<style>
    .accordion-section {
        display: flex;
        flex-direction: column;
    }

    .accordion-section {
        align-self: stretch;
        column-gap: 12px;
        row-gap: 12px;
    }

    .accordion-item, .accordion-title {
        background-color: #f4f3f1;
        border-radius: 10px;
    }

    .accordion-title {
        align-items: center;
        column-gap: 24px;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        padding: 18px;
        row-gap: 24px;
        transition: opacity .2s;
        transition-behavior: normal;
        border: none;
        width: 100%;
    }

    .accordion-content {
        padding: 9px 18px 18px;
    }

    .accordion-text {
        max-width: 671px;
    }

    .accordion-title:hover {
        opacity: .5;
    }

    @media screen and (max-width: 479px) {
        .accordion-item {
            border-radius: 6px;
        }

        .accordion-icon {
            max-height: 20px;
        }
    }

    .rotate-icon {
        transform: rotate(45deg);
        transition: transform 0.3s ease-in-out;
    }

    .accordion-icon {
        transition: transform 0.3s ease-in-out;
    }
</style>

<div class="accordion-section" id="mw-accordion-module-{{ $params['id'] }}">
    @foreach ($accordion as $key => $slide)
        @php
            $edit_field_key = $key;
            if (isset($slide['id'])) {
                $edit_field_key = $slide['id'];
            }
        @endphp

        <div class="accordion-item">
            <h2 class="accordion-header" id="header-item-{{ $edit_field_key }}">
                <button class="accordion-title" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapse-accordion-item-{{ $edit_field_key . '-' . $key }}"
                        aria-expanded="true"
                        aria-controls="collapse-accordion-item-{{ $edit_field_key . '-' . $key }}">
                    {{ isset($slide['icon']) ? $slide['icon'] . ' ' : '' }}<h6 class="font-weight-bold me-auto mb-0">{{ isset($slide['title']) ? $slide['title'] : '' }}</h6>
                </button>
            </h2>

            <div id="collapse-accordion-item-{{ $edit_field_key . '-' . $key }}" class="acordion-content-wrapper accordion-collapse collapse"
                 aria-labelledby="header-item-{{ $edit_field_key }}"
                 data-parent="#mw-accordion-module-{{ $params['id'] }}">
                <div class="accordion-content">
                    <div class="accordion-text">
                        @include('modules.accordion.templates.partials.render_accordion_item_content')
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
