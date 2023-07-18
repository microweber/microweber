@props(['disabled' => false])

<div>
    <textarea {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([

]) !!} ></textarea>
</div>
