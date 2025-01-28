@php
/*

type: layout

name: Skin-4

description: Skin-4

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
                    <div class="row">
                        <div class="col-12 col-lg-12 col-lg-10 mx-auto">
                            <i class="mdi mdi-format-quote-close icon-size-46px d-block mb-5"></i>
                            <div class="px-5">
                                <h4> {{ \Illuminate\Support\Str::limit($item['content'], 250) }}</h4>
                            </div>
                            <br/>
                            <div class="row">
                                <div class="col-12 col-lg-12 col-lg-11 mx-auto">
                                    <div class="row">
                                        <div class="col-6 text-start"></div>
                                        <div class="col-6 text-end text-right">
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
            @endforeach
        @endif
    </div>
    <div id="js-testimonials-slider-pagination-{{ $params['id'] }}" class="swiper-pagination me-auto text-start"></div>
</div>
