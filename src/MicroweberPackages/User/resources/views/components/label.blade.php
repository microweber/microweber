@props(['value'])

<label {{ $attributes }}>
    {{ $value ?? $slot }}
</label>
