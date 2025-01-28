@php
/*

type: layout

name: Skin-15

description: Skin-15

*/
@endphp

@php
$rand = uniqid();
$limit = 40;
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
</style>

<div id="js-testimonials-slider-{{ $params['id'] }}" class="slider_v2-default swiper">
    <div class="swiper-wrapper pb-4">
        @if($testimonials->isEmpty())
            <p class="mw-pictures-clean">No testimonials added to the module. Please add your testimonials to see the content..</p>
        @else
            @foreach ($testimonials as $item)
                <div class="swiper-slide">
                    <div class="row text-center">
                        <div class="col-12 col-lg-10 col-lg-8 mx-auto my-2">
                            @if (isset($item['client_image']))
                                <div class="w-125 mx-auto my-4">
                                    <div class="img-as-background rounded-circle square">
                                        <img loading="lazy" src="{{ thumbnail($item['client_image'], 120) }}">
                                    </div>
                                </div>
                            @endif

                            <div class="my-3">
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

                            <p> {{ \Illuminate\Support\Str::limit($item['content'], 250) }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    <div id="js-testimonials-slider-pagination-{{ $params['id'] }}" class="swiper-pagination mt-4"></div>
</div>
