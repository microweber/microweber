@props(['options'=>[]])

@php
    $preformatedOptions = [];
    $selectedOption = [];
    if (isset($options) && !empty($options)) {
        foreach ($options as $key => $option) {
            $preformatedOptions[] = [
                'key' => $key,
                'value' => $option
            ];
        }
        $selectedOption = $preformatedOptions[0];
    }
@endphp

<div x-data="{selectedOption: {{json_encode($selectedOption)}}, openOptions:false}">

    <button type="button" class="btn btn-primary"
            x-on:click="openOptions = !openOptions" x-html="selectedOption.value">
    </button>

    <div class="dropdown-menu" :class="[openOptions ? 'show':'']">

        @if(!empty($preformatedOptions))
            @foreach($preformatedOptions as $option)

                <button type="button"
                        x-on:click="selectedOption = {{json_encode($option)}}; openOptions = false" :class="[selectedOption == '{{$option['key']}}' ? 'active':'']" class="dropdown-item">
                    {!! $option['value'] !!}
                </button>

            @endforeach
        @endif

    </div>
</div>
