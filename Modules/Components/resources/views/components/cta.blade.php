<div {{ $attributes->merge(['class' => 'cta-block text-' . $align . ($layout === 'inline' ? ' d-md-flex align-items-center justify-content-between' : '')]) }}>
    @if(isset($heading))
        <div class="{{ $layout === 'inline' ? 'me-4' : 'mb-4' }}">
            {{ $heading }}
        </div>
    @endif

    @if($slot->isNotEmpty())
        <div class="{{ $layout === 'inline' ? '' : 'mt-3' }}">
            {{ $slot }}
        </div>
    @endif
</div>