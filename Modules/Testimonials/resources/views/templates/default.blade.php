<div>

    <style>
       .mw-testimonials-module-default-skin img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto;
            display: flex;
        }
    </style>

    <div class="mw-testimonials-module-default-skin d-flex align-items-center gap-3" id="testimonials-{{ $params['id'] }}">
        @if($testimonials->isEmpty())
            <p>No testimonials available.</p>
        @else
            @foreach($testimonials as $item)

                <div class="card border-0">
                    <div id="collapse-{{ $params['id'] }}-{{ $item->id }}"
                         aria-labelledby="heading-{{ $params['id'] }}-{{ $item->id }}"
                         data-parent="#testimonials-{{ $params['id'] }}">

                        @if($item->client_image)
                            <img src="{{ $item->client_image }}" class="card-img-top" alt="{{ $item->name }}'s image">
                        @endif



                        <div class="card-body text-center">

                           <div class="mb-5">
                               <div id="heading-{{ $params['id'] }}-{{ $item->id }}">
                                   @if($item->name)
                                       <h5 class="mb-0">
                                           {{ $item->name }}
                                       </h5>
                                   @endif
                               </div>

                               <div class="my-4">
                                   @if($item->content)
                                       {!! $item->content !!}
                                   @endif
                               </div>

                           </div>


                            @if($item->client_company)
                                <div><span>{{ $item->client_company }}</span></div>
                            @endif
                            @if($item->client_role)
                                <div><span>{{ $item->client_role }}</span></div>
                            @endif
                            @if($item->client_website)
                                <div><a href="{{ $item->client_website }}"
                                   target="_blank">{{ $item->client_website }}</a></div>
                            @endif

                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
