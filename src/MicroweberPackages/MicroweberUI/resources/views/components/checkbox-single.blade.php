@props(['name'=>'', 'value'=>''])
<div class="form-control-live-edit-label-wrapper">

    @php
        $optionRandId = md5(1).time();
    @endphp

    <label class="form-check" id="{{$optionRandId}}">
        <input class="form-check-input" :value="$value" type="checkbox" {!! $attributes->merge() !!}>
        <span class="form-check-label">
            {{ $name }}  
        </span>
    </label>

</div>
