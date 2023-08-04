@props(['disabled' => false])

<div class="input-group">
    <span class="input-group-text">
        {{get_currency_symbol()}}
    </span>
    <input
        {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'form-control']) !!}
    />
</div>
