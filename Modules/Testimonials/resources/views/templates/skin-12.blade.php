@php
/*

type: layout

name: Skin-12

description: Skin-12

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

    #{{ $params['id'] }} .slick-slide {
        height: inherit !important;
    }

    #{{ $params['id'] }} .slick-list {
        overflow: hidden;
    }
</style>

<div class="slick-arrows-1">
    <div class="slickslider" data-slick='{"slidesToShow": 1, "slidesToScroll": 1, "dots": false, "arrows": true}'>
        @if($testimonials->isEmpty())
            <p class="mw-pictures-clean">No testimonials added to the module. Please add your testimonials to see the content..</p>
        @else
            @foreach ($testimonials as $item)
                <div class="row text-center">
                    <div class="col-11 col-sm-10 col-lg-8 col-lg-5 mx-auto">
                        @if (isset($item['client_image']))
                            <div class="w-80 mx-auto my-4">
                                <div class="img-as-background rounded-circle square">
                                    <img loading="lazy" src="{{ thumbnail($item['client_image'], 120) }}" class="d-block"/>
                                </div>
                            </div>
                        @endif

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

                        <i class="mdi mdi-format-quote-close icon-size-46px my-3 d-block safe-element"></i>

                        <p> {{ \Illuminate\Support\Str::limit($item['content'], 250) }}</p>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
