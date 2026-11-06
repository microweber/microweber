@php
    /*

    type: layout

    name: Default

    description: Default

    */
@endphp

<div>

    @if (mw_asset_once('testimonials-default'))
    {{-- Skin CSS moved to Modules/Testimonials/resources/assets/css/default.css (served from
         public/modules/testimonials/css/default.css); #id scope -> .mw-testimonials-module-default-skin. Emitted once per
         page via mw_asset_once instead of re-inlining for every instance. --}}
    <link rel="stylesheet" href="{{ asset('modules/testimonials/css/default.css') }}">
@endif

    <div class="mw-testimonials-module-default-skin" id="testimonials-{{ $params['id'] }}">
        @if($testimonials->isEmpty())
            <p class="mw-pictures-clean">No testimonials added to the module. Please add your testimonials to see the content..</p>
        @else
            @foreach($testimonials as $item)

                <div class="card border-0">
                    <div id="collapse-{{ $params['id'] }}-{{ $item->id }}"
                         aria-labelledby="heading-{{ $params['id'] }}-{{ $item->id }}"
                         data-bs-parent="#testimonials-{{ $params['id'] }}">

                        @php

                            $pixel = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=';
                            $src =  isset($item->client_image) ? $item->client_image : $pixel;

                        @endphp

                        <img src="{{$src}}" class="card-img-top img-fluid" alt="{{ isset($item->name) ? $item->name : 'Client Image' }}">




                        <div class="card-body text-center">

                           <div class="mb-5">
                                <div id="heading-{{ $params['id'] }}-{{ $item->id }}">
                                    @if(isset($item->name))
                                        <h5 class="mb-0">
                                            {{ $item->name }}
                                        </h5>
                                    @endif
                                </div>

                                <div class="my-4">
                                    @if(isset($item->content))
                                        {!! $item->content !!}
                                    @endif
                                </div>

                            </div>


                             @if(isset($item->client_company))
                                 <div><span>{{ $item->client_company }}</span></div>
                             @endif
                             @if(isset($item->client_role))
                                 <div><span>{{ $item->client_role }}</span></div>
                             @endif
                             @if(isset($item->client_website))
                                 <div><a href="{{ $item->client_website }}"
           target="_blank" rel="noopener noreferrer">{{ $item->client_website }}</a></div>
                             @endif

                         </div>
                     </div>
                 </div>
             @endforeach
         @endif
     </div>
</div>
