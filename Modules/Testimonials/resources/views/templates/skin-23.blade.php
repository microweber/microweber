@php
/*

type: layout

name: Testimonial Cards

description: Testimonials using x-testimonial-card component

*/
@endphp

@if (mw_asset_once('testimonials-skin-23'))
    {{-- Skin CSS moved to Modules/Testimonials/resources/assets/css/skin-23.css (served from
         public/modules/testimonials/css/skin-23.css); #id scope -> .mw-testimonials-skin-23. Emitted once per
         page via mw_asset_once instead of re-inlining for every instance. --}}
    <link rel="stylesheet" href="{{ asset('modules/testimonials/css/skin-23.css') }}">
@endif

<div id="testimonials-{{ $params['id'] }}" class="mw-testimonials-skin-23">
    @if($testimonials->isEmpty())
        <p class="mw-pictures-clean">No testimonials added to the module. Please add your testimonials to see the content.</p>
    @else
        @foreach($testimonials as $item)
            @php
                $pixel = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=';
                $src = isset($item->client_image) ? $item->client_image : $pixel;
            @endphp
            <div class="col">
                <x-testimonial-card
                    :name="$item->name ?? ''"
                    :content="$item->content ? \Illuminate\Support\Str::limit(strip_tags($item->content), 250) : ''"
                    :image="$src"
                    :company="$item->client_company ?? ''"
                    :role="$item->client_role ?? ''"
                    class="shadow-sm h-100"
                />
            </div>
        @endforeach
    @endif
</div>