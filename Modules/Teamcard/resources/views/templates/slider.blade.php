@php
/*
type: layout
name: Slider
description slider
*/
@endphp

<script>
    <?php print get_asset('/Modules/Slider/resources/assets/js/slider-v2.js'); ?>

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
        background-color: #f5f5f5;
    }
</style>

<div id="js-teamcard-slider-{{ $params['id'] }}" class="slider_v2-default swiper">
    <div class="swiper-wrapper">
        @if ($teamcard->count() > 0)
            @foreach($teamcard as $i => $member)
            <div class="swiper-slide">
                <div class="row overflow-hidden text-start p-md-4 p-2 d-flex flex-wrap">
                    <div class="col-md-6">
                        @if ($member['file'])
                            <div class="m-auto h-100 w-100" style="background-image: url('{{ thumbnail($member['file'], 900) }}'); background-repeat: no-repeat; background-size: cover;"></div>
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
        @else
            <div>
                Add your teamcard.
            </div>
        @endif
    </div>
    <div id="js-teamcard-slider-pagination-{{ $params['id'] }}" class="swiper-pagination"></div>
</div>
