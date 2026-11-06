@php
/*

type: layout

name: Skin-14

description: Skin-14

*/
@endphp

<script>
if (!window.SliderV2) {
    mw.require('{{ asset('modules/slider/js/slider-v2.js') }}',true)
}
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

@if (mw_asset_once('testimonials-skin-14'))
    {{-- Skin CSS moved to Modules/Testimonials/resources/assets/css/skin-14.css (served from
         public/modules/testimonials/css/skin-14.css); #id scope -> .mw-testimonials-skin-14. Emitted once per
         page via mw_asset_once instead of re-inlining for every instance. --}}
    <link rel="stylesheet" href="{{ asset('modules/testimonials/css/skin-14.css') }}">
@endif

<div id="js-testimonials-slider-{{ $params['id'] }}" class="slider_v2-default mw-testimonials-skin-14 swiper">
    <div class="swiper-wrapper pb-4">
        @if($testimonials->isEmpty())
            <p class="mw-pictures-clean">No testimonials added to the module. Please add your testimonials to see the content..</p>
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
                                                {{-- audit-test 2026-05-08 PM TASK-012 / TICKET-CX (cycle-55): responsive_thumbnail helper. --}}
                                                {!! responsive_thumbnail($item['client_image'], 300, null, [
                                                    'alt' => $item['client_name'] ?? __('Testimonial author'),
                                                    'class' => 'd-block img-fluid',
                                                ]) !!}
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

                                                @if (isset($item['client_website']) && !empty($item['client_website']))
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
