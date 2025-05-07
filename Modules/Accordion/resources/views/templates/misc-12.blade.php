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
    @php $accordion = $defaults @endphp
@endif

@include('modules.accordion::components.custom-css')


<style>
    .misc-skin-12 .tab-pane .element {
        display: flex;
        align-items: center;
    }

    @media (max-width: 550px) {
        .misc-skin-12 .tab-pane .element {
            flex-wrap: wrap;
        }
    }
</style>

<div class="row" id="mw-accordion-module-{{ $params['id'] }}">
    <div class="col-xl-2 col-lg-4">
        <ul class="nav flex-column" id="accordion-{{ $params['id'] }}" role="tablist">
            @foreach ($accordion as $key => $slide)
                <li class="nav-item">
                    <a class="nav-link {{ $key == 0 ? 'active' : '' }}" id="{{ $params['id'] . '-' . $key }}-tab" data-bs-toggle="tab" href="#tab-{{ $params['id'] . '-' . $key }}" role="tab" aria-controls="home" aria-selected="true">
                        {!! isset($slide['icon']) ? $slide['icon'] . ' ' : '' !!}{{ isset($slide['title']) ? $slide['title'] : '' }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="col-xl-10 col-lg-8">
        <div class="tab-content misc-skin-12" id="accordion-{{ $params['id'] }}-content">
            @foreach ($accordion as $key => $slide)
                @php
                    $edit_field_key = $key;
                    if (isset($slide['id'])) {
                        $edit_field_key = $slide['id'];
                    }
                @endphp

                <div class="tab-pane fade {{ $key == 0 ? 'show active' : '' }}" id="tab-{{ $params['id'] . '-' . $key }}" role="tabpanel" aria-labelledby="{{ $params['id'] . '-' . $key }}-tab">
                    <h4>{{ isset($slide['title']) ? $slide['title'] : '' }}</h4>
                    @include('modules.accordion::partials.render_accordion_item_content')

                </div>
            @endforeach
        </div>
    </div>
</div>
