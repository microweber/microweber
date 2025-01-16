<?php

/*

type: layout

name: Default tabs

description: Default

*/
?>


<div>

    <div id="tabs-{{ $params['id'] }}">
        @if($tabs->isEmpty())
            <p>No tab items available.</p>
        @else
            @foreach($tabs as $item)
                <div class="card">
                    <div class="card-header" id="heading-{{ $params['id'] }}-{{ $item->id }}">
                        <h5 class="mb-0">
                            <button class="btn btn-link" data-toggle="collapse" data-target="#collapse-{{ $params['id'] }}-{{ $item->id }}" aria-expanded="true" aria-controls="collapse-{{ $params['id'] }}-{{ $item->id }}">
                                {{ $item->title }}
                            </button>
                        </h5>
                    </div>

                    <div id="collapse-{{ $params['id'] }}-{{ $item->id }}" class="collapse" aria-labelledby="heading-{{ $params['id'] }}-{{ $item->id }}" data-parent="#tabs-{{ $params['id'] }}">
                        <div class="card-body">
                            {!! $item->content !!}
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>

