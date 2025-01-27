@php
/*
 
type: layout
 
name: Skin-21
 
description: Skin-21
 
*/
@endphp

@php
$rand = uniqid();
$limit = 40;
@endphp

<script>mw.lib.require('slick')</script>
<script>
    $(document).ready(function () {
        $('.slickslider', '#{{ $params['id'] }}').slick();
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
    <div class="slickslider" data-slick='{"slidesToShow": 1, "slidesToScroll": 1, "dots": false, "arrows": true}'>
        @if($testimonials->isEmpty())
            <p>No testimonials available.</p>
        @else
            @foreach ($testimonials as $item)
                <div class="testimonials-26-item p-5">
                    <p> {{ \Illuminate\Support\Str::limit($item['content'], 250) }}</p>

                    <div class="testimonials-26-author">
                        @if (isset($item['client_image']))
                            <img loading="lazy" src="{{ thumbnail($item['client_image'], 800) }}"/>
                        @endif

                        @if (isset($item['client_role']))
                            <span>{{ $item['client_role'] }}</span>
                        @endif

                        @if (isset($item['name']))
                            <h4 class="mb-0">{{ $item['name'] }}</h4>
                        @endif

                        @if (isset($item['client_company']))
                            <p class="mb-0">{{ $item['client_company'] }}</p>
                        @endif

                        @if (isset($item['client_website']))
                            <a class="my-1 d-block" href="{{ $item['client_website'] }}">{{ $item['client_website'] }}</a>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
