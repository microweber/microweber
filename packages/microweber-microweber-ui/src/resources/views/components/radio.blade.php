@props(['options'=>[]])

<div class="form-control-live-edit-label-wrapper">

    @if(!empty($options))
        @foreach($options as $key => $option)
            @php
                $optionRandId = md5($key).time();
            @endphp
            <div class="w-full">
                <label class="form-check form-check-inline" id="{{$optionRandId}}">
                    <input {!! $attributes->merge() !!}  class="form-check-input" type="radio" value="{{$key}}">
                    <span class="form-check-label">{{$option}}</span>
                </label>
            </div>
        @endforeach
    @endif

</div>
