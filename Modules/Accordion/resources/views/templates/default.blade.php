<div>

    <div id="accordion-{{ $params['id'] }}">
        @if($accordion->isEmpty())
            <p>No accordion items available.</p>
        @else
            @foreach($accordion as $item)
                <div class="card mb-3">
                    <button class="btn btn-link w-100 font-weight-bold text-start p-3" id="heading-{{ $params['id'] }}-{{ $item->id }}" data-toggle="collapse" data-target="#collapse-{{ $params['id'] }}-{{ $item->id }}" aria-expanded="true" aria-controls="collapse-{{ $params['id'] }}-{{ $item->id }}">
                        {{ $item->title }}
                    </button>

                    <div id="collapse-{{ $params['id'] }}-{{ $item->id }}" class="collapse" aria-labelledby="heading-{{ $params['id'] }}-{{ $item->id }}" data-parent="#accordion-{{ $params['id'] }}">
                        <div class="card-body">
                            {!! $item->content !!}
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
