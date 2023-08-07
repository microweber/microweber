@props(['disabled' => false])

<div class="form-control-live-edit-label-wrapper">

    <div class="input-group">
        <span class="input-group-text">
            {{get_currency_symbol()}}
        </span>
        <input
            {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'form-control']) !!}
        />
    </div>
</div>
