@php
    /*

    type: layout

    name: Skin-5

    description: Skin-5

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

<div id="js-testimonials-slider-{{ $params['id'] }}" class="slider_v2-default swiper">
<div class="swiper-wrapper">
    @if($testimonials->isEmpty())
        <p>No testimonials available.</p>
    @else
        @foreach ($testimonials as $item)
            <div class="swiper-slide">
                <div class="row text-center">
                    <div class="col-12 col-lg-10 col-lg-8 mx-auto">
                        <h5> {{ \Illuminate\Support\Str::limit($item['content'], 250) }}</h5>
                        <br/>
                        <br/>
                        <div class="text-center text-sm-start mb-5">
                            <div class="d-block d-sm-flex align-items-center mx-auto justify-content-center">
                                @if (isset($item['client_image']))
                                    <div class="me-sm-4 mb-5 mb-sm-0 mx-auto mx-sm-0">
                                        <div class="w-80 mx-auto">
                                            <div class="img-as-background rounded-circle square">
                                                <img loading="lazy" src="{{ thumbnail($item['client_image'], 120) }}">
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div>
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
        @endforeach
    @endif
</div>
<div id="js-testimonials-slider-pagination-{{ $params['id'] }}" class="swiper-pagination"></div>
<div id="js-testimonials-pagination-previous-{{ $params['id'] }}" class="mw-slider-v2-buttons-slide mw-slider-v2-button-prev"></div>
<div id="js-testimonials-pagination-next-{{ $params['id'] }}" class="mw-slider-v2-buttons-slide mw-slider-v2-button-next"></div>
</div>
