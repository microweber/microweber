@props(['options'=>[], 'selectedOption'=>null])

@php
    $randomId = rand(1111,9999).time();
@endphp

<div
    x-data="{selectedOption: '{{$selectedOption}}'}"
    class="form-control-live-edit-label-wrapper d-flex mw-live-edit-resolutions-wrapper" style="max-height: unset; gap: 5px !important;">

    @if(!empty($options))
        @foreach($options as $key => $option)

            <label for="{{$randomId}}_{{$key}}" x-on:click="selectedOption = '{{$key}}'" :class="[selectedOption == '{{$key}}' ? 'active':'']" class="btn btn-icon tblr-body-color live-edit-toolbar-buttons w-100">

                <input style="display:none" id="{{$randomId}}_{{$key}}" type="radio" {!! $attributes->merge([]) !!} value="{{$key}}">

                {!! $option !!}
            </label>

        @endforeach
    @endif

</div>


