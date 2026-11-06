@php
/*

type: layout

name: Skin-16

description: Skin-16

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
                    breakpoint: 1300,
                    settings: {
                        slidesToShow: 2,
                    }
                },
                {
                    breakpoint: 975,
                    settings: {
                        slidesToShow: 1,
                    }
                }
            ]
        });
    });
</script>

@if (mw_asset_once('testimonials-skin-16'))
    {{-- Skin CSS moved to Modules/Testimonials/resources/assets/css/skin-16.css (served from
         public/modules/testimonials/css/skin-16.css); #id scope -> .mw-testimonials-skin-16. Emitted once per
         page via mw_asset_once instead of re-inlining for every instance. --}}
    <link rel="stylesheet" href="{{ asset('modules/testimonials/css/skin-16.css') }}">
@endif

<div class="slick-arrows-1 mw-testimonials-skin-16">
    <div class="slickslider" data-slick='{"slidesToShow": 3, "slidesToScroll": 1, "dots": false, "arrows": true}'>
        @if($testimonials->isEmpty())
            <p class="mw-pictures-clean">No testimonials added to the module. Please add your testimonials to see the content..</p>
        @else
            @foreach ($testimonials as $item)
                <div class="tony-template-testimonial testimonials-background-variable testimonialBorderVariable mx-3 p-5">
                    <div class="py-3 mb-4">
                        <img loading="lazy" height="20" width="auto" src="{{ asset('templates/big2/img/layouts/tony/testimonials-stars.png') }}" alt="{{ __('Rating stars') }}" class="img-fluid"/>
                    </div>
                    <p class="testimonials-tony-p mb-8"> {{ \Illuminate\Support\Str::limit($item['content'], 250) }}</p>

                    <div class="text-center text-sm-start mb-3">
                        <div class="d-block d-sm-flex align-items-center justify-content-between">
                            <div class="d-block d-sm-flex align-items-center">
                                @if (isset($item['client_image']))
                                    <div class="me-3">
                                        <div class="w-40 mx-auto">
                                            <div class="img-as-background rounded-circle square">
                                                {{-- audit-test 2026-05-08 PM TASK-012 / TICKET-CX (cycle-55): responsive_thumbnail helper. --}}
                                                {!! responsive_thumbnail($item['client_image'], 120, null, [
                                                    'alt' => $item['client_name'] ?? __('Testimonial author'),
                                                    'class' => 'd-block img-fluid',
                                                ]) !!}
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div>
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
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
