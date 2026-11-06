@php
/*

type: layout

name: Skin-19

description: Skin-19

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

@if (mw_asset_once('testimonials-skin-19'))
    {{-- Skin CSS moved to Modules/Testimonials/resources/assets/css/skin-19.css (served from
         public/modules/testimonials/css/skin-19.css); #id scope -> .mw-testimonials-skin-19. Emitted once per
         page via mw_asset_once instead of re-inlining for every instance. --}}
    <link rel="stylesheet" href="{{ asset('modules/testimonials/css/skin-19.css') }}">
@endif

<div class="slick-arrows-1 mw-testimonials-skin-19 ziza-slick-testimonials mt-5">
    <div class="slickslider" data-slick='{"slidesToShow": 1, "slidesToScroll": 1, "dots": true, "arrows": false}'>
        @if($testimonials->isEmpty())
            <p class="mw-pictures-clean">No testimonials added to the module. Please add your testimonials to see the content..</p>
        @else
            @foreach ($testimonials as $item)
                <div class="row text-center text-lg-start d-flex align-items-center pt-7">
                    @if (isset($item['client_image']))

                        <div class="text-center mx-auto ziza-testimonials-image ziza-testimonials-image-holder">
                            <img class="ziza-testimonials-client-image"
                                 src="{{ thumbnail($item['client_image'], 450) }}"
                                 alt="{{ $item['client_name'] ?? '' }}"
                                 loading="lazy" decoding="async"/>
                            <div>
                                <div class="mw-ziza-testimonials-item-eclipse">
                                    <div></div>
                                </div>
                                <img loading="lazy" class="mw-ziza-testimonials-item-dots img-fluid" src="{{ asset('templates/big2/img/layouts/ziza/ziza-dots-94.png') }}" alt="{{ __('Decorative dots') }}"/>
                            </div>
                        </div>
                    @endif

                    <div class="col-xl-6 col-md-10 col-12 me-xl-auto mx-auto text-xl-start text-center ">
                        @if (isset($item['name']))
                            <h4 class="mb-1">{{ $item['name'] }}</h4>
                        @endif
                        <p>{{ isset($item['client_role']) ? $item['client_role'] : '' }}</p>
                        <p> {{ \Illuminate\Support\Str::limit($item['content'], 250) }}</p>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
