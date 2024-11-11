<div>

    <div id="testimonials-{{ $params['id'] }}">
        @if($testimonials->isEmpty())
            <p>No testimonials available.</p>
        @else
            @foreach($testimonials as $item)

                <div class="card">
                    <div class="card-header" id="heading-{{ $params['id'] }}-{{ $item->id }}">
                        @if($item->name)
                            <h5 class="mb-0">
                                {{ $item->name }}
                            </h5>
                        @endif
                    </div>

                    <div id="collapse-{{ $params['id'] }}-{{ $item->id }}"
                         aria-labelledby="heading-{{ $params['id'] }}-{{ $item->id }}"
                         data-parent="#testimonials-{{ $params['id'] }}">

                        @if($item->client_image)
                            <img src="{{ $item->client_image }}" class="card-img-top" alt="{{ $item->name }}'s image">
                        @endif

                        <div class="card-body">

                            @if($item->content)
                                {!! $item->content !!}
                            @endif

                            @if($item->client_company)
                                <p>{{ $item->client_company }}</p>
                            @endif
                            @if($item->client_role)
                                <p>{{ $item->client_role }}</p>
                            @endif
                            @if($item->client_website)
                                <p>
                                    <a href="{{ $item->client_website }}"
                                       target="_blank">{{ $item->client_website }}</a>
                                </p>
                            @endif

                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
