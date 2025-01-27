@php
/*
 
type: layout
 
name: Skin-14
 
description: Skin-14
 
*/
@endphp

<script>
    @php print get_asset('/Modules/Slider/resources/assets/js/slider-v2.js'); ?>
</script>
<script>
    $(document).ready(function () {
        new SliderV2('#js-testimonials-slider-{{ $params['id'] }}', {
            loop: true,
            autoplay: true,
            direction: 'horizontal', //horizontal or vertical
            pagination: {
                element: '#js-testimonials-slider-pagination-{{ $params['id'] }}',
            },
            navigation: {
                nextElement: '#js-testimonials-pagination-next-{{ $params['id'] }}',
                previousElement: '#js-testimonials-pagination-previous-{{ $params['id'] }}',
            },
        });
    });
</script>

<style>
    .slider_v2-default.swiper .swiper-pagination-bullet {
        background-color: #000;
    }

    @media screen and (min-width: 768px) {
        #{{ $params['id'] }} .avatar-holder {
            margin-left: -40% !important;
        }
    }
</style>

<div id="js-testimonials-slider-{{ $params['id'] }}" class="slider_v2-default swiper">
    <div class="swiper-wrapper pb-4">
        @if($testimonials->isEmpty())
            <p>No testimonials available.</p>
        @else
            @foreach ($testimonials as $item)
                <div class="swiper-slide">
                    <div class="row">
                        <div class="col-12 col-md-9 offset-md-3 col-lg-7 offset-lg-4">
                            <div class="border testimonials-background-variable testimonialBorderVariable hover-border-color-primary p-md-5">
                                <div class="d-block d-md-flex text-center text-md-start">
                                    @if (isset($item['client_image']))
                                        <div class="mw-300 w-300 avatar-holder mx-auto me-md-5">
                                            <div class="img-as-background h-300">
                                                <img loading="lazy" src="{{ thumbnail($item['client_image'], 300) }}" class="d-block"/>
                                            </div>
                                        </div>
                                    @endif

                                    <div>
                                        <p> {{ \Illuminate\Support\Str::limit($item['content'], 250) }}</p>

                                        <div class="row d-flex align-items-center justify-content-between mt-3">
                                            <div class="col">
                                                @if (isset($item['name']))
                                                    <h5 class="mb-0">{{ $item['name'] }}</h5>
                                                @endif
                                                @if (isset($item['client_company']))
                                                    <p class="mb-0">{{ $item['client_company'] }}</p>
                                                @endif

                                                @if (isset($item['client_website']))
                                                    <a class="my-1 d-block" href="{{ $item['client_website'] }}">{{ $item['client_website'] }}</a>
                                                @endif

                                                @if (isset($item['client_role']))
                                                    <p>{{ $item['client_role'] }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    <div id="js-testimonials-slider-pagination-{{ $params['id'] }}" class="swiper-pagination mt-4"></div>
</div>
