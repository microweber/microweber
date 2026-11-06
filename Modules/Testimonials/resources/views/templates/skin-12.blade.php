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

@if (mw_asset_once('testimonials-skin-12'))
    {{-- Skin CSS moved to Modules/Testimonials/resources/assets/css/skin-12.css (served from
         public/modules/testimonials/css/skin-12.css); #id scope -> .mw-testimonials-skin-12. Emitted once per
         page via mw_asset_once instead of re-inlining for every instance. --}}
    <link rel="stylesheet" href="{{ asset('modules/testimonials/css/skin-12.css') }}">
@endif

<div class="slick-arrows-1 mw-testimonials-skin-12">
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
                                    {{-- audit-test 2026-05-08 PM TASK-012 / TICKET-CX (cycle-55): responsive_thumbnail helper. --}}
                                    {!! responsive_thumbnail($item['client_image'], 120, null, [
                                        'alt' => $item['client_name'] ?? __('Testimonial author'),
                                        'class' => 'd-block img-fluid',
                                    ]) !!}
                                </div>
                            </div>
                        @endif

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

                        <i class="mdi mdi-format-quote-close icon-size-46px my-3 d-block safe-element"></i>

                        <p> {{ \Illuminate\Support\Str::limit($item['content'], 250) }}</p>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
