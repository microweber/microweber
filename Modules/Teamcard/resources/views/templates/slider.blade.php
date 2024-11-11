@php
/*
type: layout
name: Slider
description slider
*/
@endphp

<script>
    mw.require('{{ modules_url() }}slider_v2/slider-v2.js');
    $(document).ready(function () {
        new SliderV2('#js-teamcard-slider-{{ $params['id'] }}', {
            loop: true,
            autoplay:true,
            direction: 'vertical', //horizontal or vertical
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
        background-color: #f5f5f5;
    }
</style>

<div id="js-teamcard-slider-{{ $params['id'] }}" class="slider_v2-default swiper">
    <div class="swiper-wrapper">
        @foreach($teamcard as $i => $member)
            <div class="swiper-slide">
                <div class="mb-3 overflow-hidden text-start px-md-4 my-5 d-flex flex-wrap">
                    <div class="col-md-6">
                        @if ($member['file'])
                            <div class="m-auto rounded-circle" style="width:150px;height:150px;background-image: url('{{ thumbnail($member['file'], 200) }}');"></div>
                        @else
                            <div class="m-auto rounded-circle">
                                <img width="185" height="185" src="{{ asset('modules/teamcard/default-content/default-image.svg') }}"/>
                            </div>
                        @endif
                    </div>

                    <div class="col-md-6">
                        <div class="py-4 fs-4">
                            {{$member['name']}}
                        </div>
                        <div class="pb-3">
                            {{$member['role']}}
                        </div>
                        <a href="{{ $member['website'] }}" target="_blank">
                            {{$member['website']}}
                        </a>
                        <div class="pt-3 italic">
                            {{$member['bio']}}
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div id="js-teamcard-slider-pagination-{{ $params['id'] }}" class="swiper-pagination"></div>
</div>
