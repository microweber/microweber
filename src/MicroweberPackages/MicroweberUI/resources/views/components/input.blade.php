@props(['disabled' => false])



<div>
    <label class="form-control-live-edit-label-wrapper">
        <input
            {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'form-control-live-edit-input']) !!}
        />
        <span class="form-control-live-edit-bottom-effect"></span>
    </label>
</div>
