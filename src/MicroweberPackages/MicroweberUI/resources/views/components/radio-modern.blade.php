@props(['options'=>[]])

@php
    $selectedOption = '';
    if (isset($options)) {
        $selectedOption = key($options);
    }
@endphp

<div
    x-data="{selectedOption: '{{$selectedOption}}'}"
    class="form-control-live-edit-label-wrapper d-flex mw-live-edit-resolutions-wrapper" style="max-height: unset; gap: 5px !important;">


    @if(!empty($options))
        @foreach($options as $key => $option)

            <button type="button" x-on:click="selectedOption = '{{$key}}'" :class="[selectedOption == '{{$key}}' ? 'active':'']" class="btn btn-icon live-edit-toolbar-buttons w-100">
              {{$option}}
            </button>

        @endforeach
    @endif

</div>


