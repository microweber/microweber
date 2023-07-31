@props(['value'])

<label {{ $attributes->merge(['class'=>'live-edit-label']) }}>
    {{ $value ?? $slot }}
</label>
