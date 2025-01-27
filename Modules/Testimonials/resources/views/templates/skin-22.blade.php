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

        // Manually trigger the afterChange event for the first slide
        slider.trigger('afterChange', [slider, 0]);
    });
</script>

<style>
    #{{ $params['id'] }} .slick-track {
        display: flex !important;
    }

    #{{ $params['id'] }} .slick-dots {
        position: relative;
        bottom: -30px;
    }

    @media screen and (min-width: 768px) {
        #{{ $params['id'] }} .avatar-holder {
            margin-left: -40% !important;
        }
    }

    #{{ $params['id'] }} .slick-slide {
        height: inherit !important;
    }

    @media screen and (min-width: 1000px) {
        #{{ $params['id'] }} .slick-arrow.slick-prev {
            left: unset !important;
            top: 60% !important;
            right: -51px !important;
            transform: translate(-50%, -50%) !important;
            background-color: #fff !important;
            border: 1px solid #ececec;
            opacity: 1;
        }

        #{{ $params['id'] }} .slick-arrow.slick-next {
            left: unset !important;
            top: 45% !important;
            right: 19px !important;
            transform: translate(-50%, -50%) !important;
            background-color: #fff !important;
            border: 1px solid #ececec;
            opacity: 1;
        }
    }

    #{{ $params['id'] }} .slick-arrows-1 .slick-arrow:before {
        margin-bottom: 2px !important;
        opacity: 1;
    }

    #{{ $params['id'] }} .action-blog-quote {
        right: -13px;
        top: 211px;
    }
</style>

<div class="slick-arrows-1">
    <div class="slickslider">
        @if($testimonials->isEmpty())
            <p>No testimonials available.</p>
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

                                @if (isset($item['client_website']))
                                    <a class="my-1 d-block" href="{{ $item['client_website'] }}">{{ $item['client_website'] }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if (isset($item['client_image']))
                        <img class="testimonial-image-two" loading="lazy" src="{{ thumbnail($item['client_image'], 800) }}"/>
                    @endif
                </div>
            @endforeach
        @endif
    </div>
</div>
