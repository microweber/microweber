@php
/*

type: layout

name: Skin-9

description: Skin-9

*/
@endphp

@php
$rand = uniqid();
$limit = 40;
@endphp

<script>mw.lib.require('slick')</script>
<script>
    $(document).ready(function () {
        $('.slickslider', '#{{ $params['id'] }}').slick({
            rtl: document.documentElement.dir === 'rtl',
            responsive: [
                {
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 2,
                    }
                },
                {
                    breakpoint: 575,
                    settings: {
                        slidesToShow: 1,
                    }
                }
            ]
        });
    });
</script>

<style>
    #{{ $params['id'] }} .slick-track {
        display: flex !important;


    }

    #{{ $params['id'] }} .slick-list {
        overflow: hidden;
    }

    #{{ $params['id'] }} .slick-slide {
        height: inherit !important;
    }

    #{{ $params['id'] }} .list-inline {
        margin: 0;
    }

    #{{ $params['id'] }} .list-inline li {
        margin: 0 3px;
    }

    @media screen and (min-width: 992px) {
        #{{ $params['id'] }} .slick-arrow.slick-prev {
            left: -60px;
        }

        #{{ $params['id'] }} .slick-arrow.slick-next {
            right: -60px;
        }
    }
</style>

<div class="slick-arrows-1">
    <div class="slickslider" data-slick='{"slidesToShow": 3, "slidesToScroll": 1, "dots": false, "arrows": true}'>
        @if($testimonials->isEmpty())
            <p class="mw-pictures-clean">No testimonials added to the module. Please add your testimonials to see the content..</p>
        @else
            @foreach ($testimonials as $item)
                <div class="border testimonials-background-variable testimonialBorderVariable mx-3 h-100 p-5 gap-3">
                    @if (isset($item['client_image']))
                        <img loading="lazy" src="{{ thumbnail($item['client_image'], 130) }}" class="d-block mb-3"/>
                    @endif

                    @if (isset($item['name']))
                        <h5 class="mb-0">{{ $item['name'] }}</h5>
                    @endif

                    <p> {{ \Illuminate\Support\Str::limit($item['content'], 250) }}</p>
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
            @endforeach
        @endif
    </div>
</div>
