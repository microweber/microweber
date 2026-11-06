@php
/*

type: layout

name: Skin-21

description: Skin-21

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

@if (mw_asset_once('testimonials-skin-21'))
    {{-- Skin CSS moved to Modules/Testimonials/resources/assets/css/skin-21.css (served from
         public/modules/testimonials/css/skin-21.css); #id scope -> .mw-testimonials-skin-21. Emitted once per
         page via mw_asset_once instead of re-inlining for every instance. --}}
    <link rel="stylesheet" href="{{ asset('modules/testimonials/css/skin-21.css') }}">
@endif

<div class="slick-arrows-1 mw-testimonials-skin-21">
    <div class="slickslider" data-slick='{"slidesToShow": 1, "slidesToScroll": 1, "dots": false, "arrows": true}'>
        @if($testimonials->isEmpty())
            <p class="mw-pictures-clean">No testimonials added to the module. Please add your testimonials to see the content..</p>
        @else
            @foreach ($testimonials as $item)
                <div class="testimonials-26-item p-5">
                    <p> {{ \Illuminate\Support\Str::limit($item['content'], 250) }}</p>

                    <div class="testimonials-26-author">
                        @if (isset($item['client_image']))
                            {{-- audit-test 2026-05-08 PM TASK-012 / TICKET-CX (cycle-55): responsive_thumbnail helper. --}}
                            {!! responsive_thumbnail($item['client_image'], 800, null, [
                                'alt' => $item['client_name'] ?? __('Testimonial author'),
                                'class' => 'img-fluid',
                            ]) !!}
                        @endif

                        @if (isset($item['client_role']))
                            <span>{{ $item['client_role'] }}</span>
                        @endif

                        @if (isset($item['name']))
                            <h4 class="mb-0">{{ $item['name'] }}</h4>
                        @endif

                        @if (isset($item['client_company']))
                            <p class="mb-0">{{ $item['client_company'] }}</p>
                        @endif

                        @if (isset($item['client_website']) && !empty($item['client_website']))
                            <a class="my-1 d-block" href="{{ $item['client_website'] }}">{{ $item['client_website'] }}</a>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
