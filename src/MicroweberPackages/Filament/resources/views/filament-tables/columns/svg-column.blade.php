<div class="w-full">
    @php
    $state = $getState();

    if ($state instanceof \Illuminate\Support\Collection) {
        $state = $state->all();
    }

    $state = \Illuminate\Support\Arr::wrap($state);

    @endphp

    @if (isset($state[0]) && $state[0] != null)

      {!! $state[0] !!}

    @endif

</div>
