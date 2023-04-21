@props(['for'])

@error($for)
    <div {{ $attributes->merge(['class' => 'alert alert-danger']) }} role="alert">
        <strong>{{ $message }}</strong>
    </div>
@enderror
