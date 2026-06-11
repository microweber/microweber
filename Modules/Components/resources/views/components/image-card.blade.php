<div {{ $attributes->merge(['class' => $wrapperClass]) }}>
    <img @if($lazy) loading="lazy" @endif src="{{ $src }}" class="{{ $imgClass }}" alt="{{ $alt }}">
    @if($slot->isNotEmpty())
        {{ $slot }}
    @endif
</div>