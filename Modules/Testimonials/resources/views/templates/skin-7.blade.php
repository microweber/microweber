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

@if (mw_asset_once('testimonials-skin-7'))
    {{-- Skin CSS moved to Modules/Testimonials/resources/assets/css/skin-7.css (served from
         public/modules/testimonials/css/skin-7.css); #id scope -> .mw-testimonials-skin-7. Emitted once per
         page via mw_asset_once instead of re-inlining for every instance. --}}
    <link rel="stylesheet" href="{{ asset('modules/testimonials/css/skin-7.css') }}">
@endif

<div class="slick-arrows-1 mw-testimonials-skin-7">
    <div class="slickslider" data-slick='{"slidesToShow": 3, "slidesToScroll": 1, "dots": false, "arrows": true}'>
        @if($testimonials->isEmpty())
            <p class="mw-pictures-clean">No testimonials added to the module. Please add your testimonials to see the content..</p>
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
