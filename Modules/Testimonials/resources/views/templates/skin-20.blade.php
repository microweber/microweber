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

@if (mw_asset_once('testimonials-skin-20'))
    {{-- Skin CSS moved to Modules/Testimonials/resources/assets/css/skin-20.css (served from
         public/modules/testimonials/css/skin-20.css); #id scope -> .mw-testimonials-skin-20. Emitted once per
         page via mw_asset_once instead of re-inlining for every instance. --}}
    <link rel="stylesheet" href="{{ asset('modules/testimonials/css/skin-20.css') }}">
@endif

<div class="slick-arrows-1 mw-testimonials-skin-20">
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
                                    {{-- audit-test 2026-05-08 PM TASK-012 / TICKET-CX (cycle-55): responsive_thumbnail helper. --}}
                                    {!! responsive_thumbnail($item['client_image'], 800, null, [
                                        'alt' => $item['client_name'] ?? __('Testimonial author'),
                                        'class' => 'position-relative img-fluid',
                                    ]) !!}
                                </div>
                                <img loading="lazy" src="{{ asset('templates/big2/img/layouts/action/action-blog-quote.png') }}" class="position-absolute action-blog-quote img-fluid" alt="{{ __('Quote decoration') }}"/>
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

                            @if (isset($item['client_website']) && !empty($item['client_website']))
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
