@php
/*

type: layout

name: Skin-6

description: Skin-6

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
    #{{ $params['id'] }} .slick-dots {
        position: relative;
    }

    @media screen and (min-width: 992px) {
        #{{ $params['id'] }} .slick-arrow.slick-prev {
            left: -60px;
        }

        #{{ $params['id'] }} .slick-arrow.slick-next {
            right: -60px;
        }
    }

    #{{ $params['id'] }} .slick-list {
        overflow: hidden;
    }
</style>

<div class="slick-arrows-1">
    <div class="slickslider" data-slick='{"slidesToShow": 3, "slidesToScroll": 1, "dots": true, "arrows": false}'>
        @if($testimonials->isEmpty())
            <p class="mw-pictures-clean">No testimonials added to the module. Please add your testimonials to see the content..</p>
        @else
            @foreach ($testimonials as $item)
                <div class="row text-center mb-5">
                    <div class="col-12 col-lg-11 mx-auto">
                        @if (isset($item['client_image']))
                            <div class="w-80 mx-auto mb-4">
                                <div class="img-as-background rounded-circle square">
                                    <img loading="lazy" src="{{ thumbnail($item['client_image'], 120) }}">
                                </div>
                            </div>
                        @endif

                        <p> {{ \Illuminate\Support\Str::limit($item['content'], 250) }}</p>
                        <br/>
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
            @endforeach
        @endif
    </div>
</div>
