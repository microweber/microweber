@props(['disabled' => false])

<label class="form-control-live-edit-label-wrapper">

    <textarea rows="5" cols="50" {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class'=>'form-control-live-edit-input']) !!} ></textarea>

    <span class="form-control-live-edit-bottom-effect bottom-effect-textarea"></span>

</label>
