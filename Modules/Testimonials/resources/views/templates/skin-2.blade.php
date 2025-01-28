@php
/*

type: layout

name: Skin-2

description: Skin-2

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
            <p class="mw-pictures-clean">No testimonials added to the module. Please add your testimonials to see the content..</p>
        @else
            @foreach ($testimonials as $item)
                <div class="swiper-slide">
                    <div class="row text-center">
                        <div class="col-12 col-lg-10 col-lg-8 mx-auto my-2">
                            <div class="img-as-background mx-auto my-3 rounded-circle" style="width:125px; height: 125px;">
                                @if (isset($item['client_image']))
                                    <img loading="lazy" src="{{ thumbnail($item['client_image'], 750) }}" class="d-inline"/>
                                @endif
                            </div>

                            @if (isset($item['name']))
                                <h6 class="mb-0">{{ $item['name'] }}</h6>
                            @endif

                            <p class="col-md-8 mx-auto"> {{ \Illuminate\Support\Str::limit($item['content'], 250) }}</p>

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
            @endforeach
        @endif
    </div>
    <div id="js-testimonials-pagination-previous-{{ $params['id'] }}" class="mw-slider-v2-buttons-slide mw-slider-v2-button-prev"></div>
    <div id="js-testimonials-pagination-next-{{ $params['id'] }}" class="mw-slider-v2-buttons-slide mw-slider-v2-button-next"></div>
</div>
