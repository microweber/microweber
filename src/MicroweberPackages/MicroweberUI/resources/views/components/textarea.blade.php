@props(['disabled' => false])

<label class="form-control-live-edit-label-wrapper">

    <textarea class=" form-control-live-edit-input" rows="5" cols="50" {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([]) !!} ></textarea>

    <span class="form-control-live-edit-bottom-effect bottom-effect-textarea"></span>

</label>
