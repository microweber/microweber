@props(['for'])

@error($for)
    <span {{ $attributes->merge(['class' => 'invalid-feedback']) }} role="alert">
        <strong>{{ $message }}</strong>
    </span>
@enderror
