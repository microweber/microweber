@php
/*

type: layout

name: Skin-20

description: Skin-20

*/
@endphp

<script>mw.lib.require('slick')</script>
<script>
    $(document).ready(function () {
        $('.slickslider', '#{{ $params['id'] }}').slick();
    });
</script>

@php
$rand = uniqid();
$limit = 40;
@endphp

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
        margin: 0 15px;
    }

    @media screen and (min-width: 1000px) {
        #{{ $params['id'] }} .slick-arrow.slick-prev {
            bottom: -104px;
            top: unset;
            left: 72px !important;
        }

        #{{ $params['id'] }} .slick-arrow.slick-next {
            bottom: -104px;
            top: unset;
            left: 70px;
        }
    }

    #{{ $params['id'] }} .slick-arrows-1 .slick-arrow:before {
        margin-bottom: 2px!important;
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
            <p class="mw-pictures-clean">No testimonials added to the module. Please add your testimonials to see the content..</p>
        @else
            @foreach ($testimonials as $item)
                <div class="row">
                    <div class="d-flex justify-content-between flex-wrap mx-auto">
                        <div class="col-lg-6 col-11 pe-3 position-relative">
                            @if (isset($item['client_image']))
                                <div class="img-as-background h-500">
                                    <img loading="lazy" src="{{ thumbnail($item['client_image'], 800) }}" class="position-relative"/>
                                </div>
                                <img loading="lazy" src="{{ asset('templates/big2/assets/img/layouts/action/action-blog-quote.png') }}" class="position-absolute action-blog-quote"/>
                            @endif
                        </div>

                        <div class="col-lg-4 col-12 mx-auto ps-3 mt-lg-0 mt-5">
                            <p class="py-3"> {{ \Illuminate\Support\Str::limit($item['content'], 250) }}</p>

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
            @endforeach
        @endif
    </div>
</div>
