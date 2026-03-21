@props(['for'])

@error($for)
<div {{ $attributes->merge(['class' => 'invalid-feedback']) }} role="alert" aria-live="assertive">
    <span class="mw-error-icon"></span>
    <span class="mw-error-text">{{ $message }}</span>
</div>
@enderror
