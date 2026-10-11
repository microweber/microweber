@props(['type' => 'success'])



<div class="alert alert-{{$type}} {{ $attributes->merge([]) }}" role="alert">
    {{ $slot }}
</div>




