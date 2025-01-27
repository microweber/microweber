@php
/*
 
type: layout
 
name: Skin-7
 
description: Skin-7
 
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
            <p>No testimonials available.</p>
        @else
            @foreach ($testimonials as $item)
                <div class="border testimonials-background-variable testimonialBorderVariable mx-3 h-100 p-5">
                    <div class="text-center text-sm-start mb-3">
                        <div class="d-block d-sm-flex align-items-center justify-content-between">
                            <div class="d-block d-sm-flex align-items-center">
                                @if (isset($item['client_image']))
                                    <div class="me-3">
                                        <div class="w-40 mx-auto">
                                            <div class="img-as-background rounded-circle square">
                                                <img loading="lazy" src="{{ thumbnail($item['client_image'], 120) }}" class="d-block"/>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div>
                                    @if (isset($item['name']))
                                        <h6 class="mb-0">{{ $item['name'] }}</h6>
                                    @endif
                                    @if (isset($item['client_role']))
                                        <small class="mb-0 text-dark">{{ $item['client_role'] }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <p> {{ \Illuminate\Support\Str::limit($item['content'], 250) }}</p>
                </div>
            @endforeach
        @endif
    </div>
</div>
