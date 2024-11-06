<div>

    <div id="testimonials-{{ $params['id'] }}">
        @if($testimonials->isEmpty())
            <p>No testimonials available.</p>
        @else
            @foreach($testimonials as $item)
                <div class="card">
                    <div class="card-header" id="heading-{{ $params['id'] }}-{{ $item->id }}">
                        <h5 class="mb-0">
                            <button class="btn btn-link" data-toggle="collapse" data-target="#collapse-{{ $params['id'] }}-{{ $item->id }}" aria-expanded="true" aria-controls="collapse-{{ $params['id'] }}-{{ $item->id }}">
                                {{ $item->name }}
                            </button>
                        </h5>
                    </div>

                    <div id="collapse-{{ $params['id'] }}-{{ $item->id }}" class="collapse" aria-labelledby="heading-{{ $params['id'] }}-{{ $item->id }}" data-parent="#testimonials-{{ $params['id'] }}">
                        <div class="card-body">
                            {!! $item->content !!}
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
