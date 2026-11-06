@php
/*

type: layout

name: Skin-22

description: Skin-22

*/
@endphp

@php
$rand = uniqid();
$limit = 40;
@endphp

<script>mw.lib.require('slick')</script>
<script>
    $(document).ready(function () {
        var counter = 1; // Initialize counter to 1
        var slider = $('.slickslider', '#{{ $params['id'] }}');

        slider.slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            dots: true,
            arrows: false
        });

        slider.on('afterChange', function(event, slick, currentSlide) {
            var slide = slick.$slides.get(currentSlide);
            var testimonialContent = $(slide).find('.testimonial-content');
            var testimonialImage = $(slide).find('.testimonial-image-two');

            if (counter % 2 === 0) {
                testimonialContent.css('order', 1);
                testimonialImage.css('order', 2);
            } else {
                testimonialContent.css('order', 2);
                testimonialImage.css('order', 1);
            }
            counter++;
        });



        try {

            // Manually trigger the afterChange event for the first slide
            slider.trigger('afterChange', [slider, 0]);
        } catch (e) {

        }




    });
</script>

@if (mw_asset_once('testimonials-skin-22'))
    {{-- Skin CSS moved to Modules/Testimonials/resources/assets/css/skin-22.css (served from
         public/modules/testimonials/css/skin-22.css); #id scope -> .mw-testimonials-skin-22. Emitted once per
         page via mw_asset_once instead of re-inlining for every instance. --}}
    <link rel="stylesheet" href="{{ asset('modules/testimonials/css/skin-22.css') }}">
@endif

<div class="slick-arrows-1 mw-testimonials-skin-22">
    <div class="slickslider">
        @if($testimonials->isEmpty())
            <p class="mw-pictures-clean">No testimonials added to the module. Please add your testimonials to see the content..</p>
        @else
            @foreach ($testimonials as $item)
                <div class="testimonial-slide">
                    <div class="testimonial-content">
                        <div class="testimonial-quote"> {{ \Illuminate\Support\Str::limit($item['content'], 250) }}</div>

                        <div class="testimonial-info-two">
                            <div>
                                @if (isset($item['name']))
                                    <div class="testimonial-author-name">{{ $item['name'] }}</div>
                                @endif

                                @if (isset($item['client_role']))
                                    <div class="job-role">{{ $item['client_role'] }}</div>
                                @endif

                                @if (isset($item['client_company']))
                                    <small>{{ $item['client_company'] }}</small>
                                @endif

                                @if (isset($item['client_website']) && !empty($item['client_website']))
                                    <a class="my-1 d-block" href="{{ $item['client_website'] }}">{{ $item['client_website'] }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if (isset($item['client_image']))
                        {{-- audit-test 2026-05-08 PM TASK-012 / TICKET-CX (cycle-55): responsive_thumbnail helper. --}}
                        {!! responsive_thumbnail($item['client_image'], 800, null, [
                            'alt' => $item['client_name'] ?? __('Testimonial author'),
                            'class' => 'testimonial-image-two img-fluid',
                        ]) !!}
                    @endif
                </div>
            @endforeach
        @endif
    </div>
</div>
