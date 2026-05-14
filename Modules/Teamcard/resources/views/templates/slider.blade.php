@php
/*
type: layout
name: Slider
description slider
*/
@endphp
<script>
if (!window.SliderV2) {
    mw.require('{{ asset('modules/slider/js/slider-v2.js') }}',true)
}
</script>
<script>

    $(document).ready(function () {
        new SliderV2('#js-teamcard-slider-{{ $params['id'] }}', {
            loop: true,
            autoplay:true,
            direction: 'vertical',
            pagination: {
                element: '#js-teamcard-slider-pagination-{{ $params['id'] }}',
            },
            navigation: {},
        });
    });
</script>

<style>
    #js-teamcard-slider-{{ $params['id'] }}{
        max-height: 500px;
    }
</style>

<div id="js-teamcard-slider-{{ $params['id'] }}" class="slider_v2-default swiper">
    <div class="swiper-wrapper">
        @if ($teamcard->count() > 0)
            @foreach($teamcard as $i => $member)
            <div class="swiper-slide">
                <div class="row overflow-hidden text-start p-md-4 p-2 d-flex flex-wrap h-100">
                    <div class="col-md-6">
                        {{-- audit-test 2026-05-07 PM TICKET-AV bundle: migrated `<div bg-image>`
                             to real `<img>` (closes CSS-injection vector + adds alt + lazy).
                             object-fit:cover preserves the prior background-size:cover visual. --}}
                        @if ($member['file'])
                            <img class="m-auto h-100 w-450 d-block"
                                 src="{{ thumbnail($member['file'], 900) }}"
                                 alt="{{ $member['name'] ?? __('Team member') }}"
                                 loading="lazy"
                                 decoding="async"
                                 style="object-fit: cover;">
                        @else
                            <img class="m-auto h-100 w-450 d-block"
                                 src="{{ asset('modules/teamcard/default-content/default-image.svg') }}"
                                 alt=""
                                 loading="lazy"
                                 decoding="async"
                                 style="object-fit: cover;">
                        @endif
                    </div>

                    <div class="col-md-6 my-auto">
                        <h2 class="py-4 fs-1 font-weight-bold">
                            {{$member['name']}}
                        </h2>
                        <p class="pb-3">
                            {{$member['role']}}
                        </p>
                        {{-- task-2026-05-05-90021f — rel=noopener noreferrer for security --}}
                        @if(!empty($member['website']))
                        <a href="{{ $member['website'] }}" target="_blank" rel="noopener noreferrer">
                            {{$member['website']}}
                        </a>
                        @endif
                        <p class="pt-3 italic">
                            {{$member['bio']}}
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
        @else
            <p class="mw-pictures-clean">No team members added in the module. Please add your teammates</p>
        @endif
    </div>
    <div id="js-teamcard-slider-pagination-{{ $params['id'] }}" class="swiper-pagination"></div>
</div>
