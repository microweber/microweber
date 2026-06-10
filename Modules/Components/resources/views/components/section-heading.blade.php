<div {{ $attributes->merge(['class' => 'text-' . $align . ' mb-4']) }}>
    <{{ $tag }} data-mwplaceholder="Enter title here">{{ $slot }}</{{ $tag }}>
    @if($subtitle)
        <p class="text-muted mt-2" data-mwplaceholder="Enter subtitle here">{{ $subtitle }}</p>
    @endif
</div>