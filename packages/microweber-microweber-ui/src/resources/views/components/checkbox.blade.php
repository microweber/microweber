@props(['options'=>[]])

<div class="form-control-live-edit-label-wrapper">
    @if(!empty($options))
        @foreach($options as $key => $option)
            @php
                $optionRandId = md5($key).time();
            @endphp
            <label class="form-check" id="{{$optionRandId}}">
                <input class="form-check-input" value="{{$key}}" type="checkbox" {!! $attributes->merge() !!}>
                <span class="form-check-label">{{$option}}</span>
            </label>
        @endforeach
    @endif
</div>
