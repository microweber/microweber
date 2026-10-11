@props(['disabled' => false])

@php
    $hasError = $errors->has($attributes->get('name')) || $errors->has($attributes->get('wire:model'));
    $baseClasses = 'form-control-live-edit-input';
    $errorClasses = $hasError ? ' is-invalid' : '';
@endphp

<div>
    <label class="form-control-live-edit-label-wrapper">

        <input
            {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => $baseClasses . $errorClasses]) !!}
        />
        <span class="form-control-live-edit-bottom-effect {{ $hasError ? 'border-danger-500' : '' }}"></span>
    </label>
</div>
