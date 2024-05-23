<div class="w-full">
    @php
    $state = $getState();

    if ($state instanceof \Illuminate\Support\Collection) {
        $state = $state->all();
    }

    $state = \Illuminate\Support\Arr::wrap($state);

    @endphp

    @if (isset($state[0]) && $state[0] != null)

        <div class="image-column-cropped" style="background-image:url('{!! $state[0] !!}');width: 100%;height: 180px;background-size: cover;background-position: top;">

        </div>
    @endif

</div>
