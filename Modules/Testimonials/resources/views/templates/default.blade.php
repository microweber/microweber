<div>

    <div id="testimonials-{{ $params['id'] }}">
        @if($testimonials->isEmpty())
            <p>No testimonials available.</p>
        @else
            @foreach($testimonials as $item)

                <x-card>
                    @if($item->client_image)
                        <x-slot name="image">{{ $item->client_image }}</x-slot>
                    @endif
                    @if($item->name)
                        <x-slot name="header">

                            <h5 class="mb-0">{{ $item->name }}</h5>

                        </x-slot>
                    @endif
                    <x-slot name="content">
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
                    </x-slot>
                </x-card>

            @endforeach
        @endif
    </div>
</div>
